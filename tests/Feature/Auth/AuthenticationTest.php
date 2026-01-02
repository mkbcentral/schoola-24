<?php

namespace Tests\Feature\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private AuthenticationService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur de test
        $this->user = User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $this->authService = app(AuthenticationService::class);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'password123',
            'remember' => false,
        ]);

        $result = $this->authService->login($loginDTO);

        $this->assertTrue($result['success']);
        $this->assertNotNull($result['user']);
        $this->assertEquals('Connexion réussie.', $result['message']);
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function user_can_login_with_email()
    {
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'test@example.com',
            'password' => 'password123',
            'remember' => false,
        ]);

        $result = $this->authService->login($loginDTO);

        $this->assertTrue($result['success']);
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'wrongpassword',
            'remember' => false,
        ]);

        $result = $this->authService->login($loginDTO);

        $this->assertFalse($result['success']);
        $this->assertNull($result['user']);
        $this->assertStringContainsString('Identifiants incorrects', $result['message']);
        $this->assertEquals(2, $result['remainingAttempts']); // 3 - 1 = 2
    }

    /** @test */
    public function user_is_locked_after_three_failed_attempts()
    {
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'wrongpassword',
            'remember' => false,
        ]);

        // Première tentative
        $result1 = $this->authService->login($loginDTO);
        $this->assertEquals(2, $result1['remainingAttempts']);

        // Deuxième tentative
        $result2 = $this->authService->login($loginDTO);
        $this->assertEquals(1, $result2['remainingAttempts']);

        // Troisième tentative
        $result3 = $this->authService->login($loginDTO);
        $this->assertEquals(0, $result3['remainingAttempts']);
        $this->assertNotNull($result3['lockoutTime']);

        // Quatrième tentative (bloquée)
        $result4 = $this->authService->login($loginDTO);
        $this->assertFalse($result4['success']);
        $this->assertStringContainsString('Trop de tentatives', $result4['message']);
        $this->assertEquals(0, $result4['remainingAttempts']);
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        $this->user->update(['is_active' => false]);

        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'password123',
            'remember' => false,
        ]);

        $result = $this->authService->login($loginDTO);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('désactivé', $result['message']);
    }

    /** @test */
    public function user_is_locked_returns_true_after_max_attempts()
    {
        // Simuler 3 tentatives échouées
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'wrongpassword',
            'remember' => false,
        ]);

        for ($i = 0; $i < 3; $i++) {
            $this->authService->login($loginDTO);
        }

        $isLocked = $this->authService->isLocked('testuser');
        $this->assertTrue($isLocked);
    }

    /** @test */
    public function successful_login_clears_failed_attempts()
    {
        // Première tentative échouée
        $failedDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'wrongpassword',
            'remember' => false,
        ]);
        $this->authService->login($failedDTO);

        // Vérifier qu'il y a une tentative enregistrée
        $remainingAttempts = $this->authService->getRemainingAttempts('testuser');
        $this->assertEquals(2, $remainingAttempts);

        // Connexion réussie
        $successDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'password123',
            'remember' => false,
        ]);
        $result = $this->authService->login($successDTO);

        $this->assertTrue($result['success']);

        // Vérifier que les tentatives ont été réinitialisées
        $remainingAttempts = $this->authService->getRemainingAttempts('testuser');
        $this->assertEquals(3, $remainingAttempts);
    }

    /** @test */
    public function logout_invalidates_session()
    {
        // Se connecter d'abord
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'password123',
            'remember' => false,
        ]);
        $this->authService->login($loginDTO);

        $this->assertAuthenticatedAs($this->user);

        // Se déconnecter
        $this->authService->logout();

        $this->assertGuest();
    }

    /** @test */
    public function last_login_fields_are_updated_on_successful_login()
    {
        $loginDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'password123',
            'remember' => false,
            'ip_address' => '127.0.0.1',
        ]);

        $result = $this->authService->login($loginDTO);

        $this->assertTrue($result['success']);

        $user = $this->user->fresh();
        $this->assertNotNull($user->last_login_at);
        $this->assertEquals('127.0.0.1', $user->last_login_ip);
    }

    /** @test */
    public function login_dto_detects_email_vs_username()
    {
        $emailDTO = LoginDTO::fromArray([
            'identifier' => 'test@example.com',
            'password' => 'password',
        ]);

        $usernameDTO = LoginDTO::fromArray([
            'identifier' => 'testuser',
            'password' => 'password',
        ]);

        $emailCredentials = $emailDTO->toCredentials();
        $usernameCredentials = $usernameDTO->toCredentials();

        $this->assertArrayHasKey('email', $emailCredentials);
        $this->assertArrayHasKey('username', $usernameCredentials);
    }
}
