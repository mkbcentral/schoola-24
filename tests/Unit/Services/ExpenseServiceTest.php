<?php

namespace Tests\Unit\Services;

use App\DTOs\ExpenseDTO;
use App\DTOs\ExpenseFilterDTO;
use App\Models\CategoryExpense;
use App\Models\CategoryFee;
use App\Models\ExpenseFee;
use App\Models\School;
use App\Models\SchoolYear;
use App\Services\Contracts\CurrencyExchangeServiceInterface;
use App\Services\Contracts\ExpenseServiceInterface;
use App\Services\ExpenseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseServiceTest extends TestCase
{
    use RefreshDatabase;

    private ExpenseServiceInterface $expenseService;
    private CurrencyExchangeServiceInterface $currencyService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expenseService = app(ExpenseServiceInterface::class);
        $this->currencyService = app(CurrencyExchangeServiceInterface::class);

        // Créer les données de test
        $this->createTestData();
    }

    private function createTestData(): void
    {
        // Créer une école
        School::factory()->create(['id' => 1]);

        // Créer une année scolaire
        SchoolYear::factory()->create(['id' => 1]);

        // Créer des catégories
        CategoryExpense::factory()->create(['id' => 1, 'school_id' => 1]);
        CategoryFee::factory()->create(['id' => 1, 'school_id' => 1]);
    }

    /** @test */
    public function it_can_create_expense()
    {
        $dto = ExpenseDTO::fromArray([
            'description' => 'Test expense',
            'month' => '11',
            'amount' => 500,
            'currency' => 'USD',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'created_at' => '2025-11-26',
        ]);

        $created = $this->expenseService->create($dto);

        $this->assertInstanceOf(ExpenseDTO::class, $created);
        $this->assertNotNull($created->id);
        $this->assertEquals('Test expense', $created->description);
        $this->assertEquals(500, $created->amount);

        $this->assertDatabaseHas('expense_fees', [
            'description' => 'Test expense',
            'amount' => 500,
            'currency' => 'USD',
        ]);
    }

    /** @test */
    public function it_can_update_expense()
    {
        $expense = ExpenseFee::factory()->create([
            'description' => 'Original',
            'amount' => 100,
            'currency' => 'USD',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $dto = ExpenseDTO::fromArray([
            'description' => 'Updated',
            'month' => '11',
            'amount' => 200,
            'currency' => 'CDF',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
        ]);

        $updated = $this->expenseService->update($expense->id, $dto);

        $this->assertEquals('Updated', $updated->description);
        $this->assertEquals(200, $updated->amount);
        $this->assertEquals('CDF', $updated->currency);
    }

    /** @test */
    public function it_can_delete_expense()
    {
        $expense = ExpenseFee::factory()->create([
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $result = $this->expenseService->delete($expense->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('expense_fees', ['id' => $expense->id]);
    }

    /** @test */
    public function it_can_find_expense_by_id()
    {
        $expense = ExpenseFee::factory()->create([
            'description' => 'Find me',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $found = $this->expenseService->findById($expense->id);

        $this->assertNotNull($found);
        $this->assertEquals('Find me', $found->description);
    }

    /** @test */
    public function it_returns_null_for_non_existent_expense()
    {
        $found = $this->expenseService->findById(9999);

        $this->assertNull($found);
    }

    /** @test */
    public function it_can_get_all_expenses_with_pagination()
    {
        ExpenseFee::factory()->count(15)->create([
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO(perPage: 10);
        $paginated = $this->expenseService->getAll($filters);

        $this->assertEquals(10, $paginated->perPage());
        $this->assertEquals(15, $paginated->total());
        $this->assertCount(10, $paginated->items());
    }

    /** @test */
    public function it_can_filter_expenses_by_currency()
    {
        ExpenseFee::factory()->create([
            'currency' => 'USD',
            'amount' => 100,
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'currency' => 'CDF',
            'amount' => 285000,
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO(currency: 'USD');
        $expenses = $this->expenseService->getAll($filters);

        $this->assertEquals(1, $expenses->total());
        $this->assertEquals('USD', $expenses->first()->currency);
    }

    /** @test */
    public function it_can_filter_expenses_by_month()
    {
        ExpenseFee::factory()->create([
            'month' => '11',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'month' => '12',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO(month: '11');
        $expenses = $this->expenseService->getAll($filters);

        $this->assertEquals(1, $expenses->total());
    }

    /** @test */
    public function it_can_filter_expenses_by_period()
    {
        ExpenseFee::factory()->create([
            'created_at' => now(),
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'created_at' => now()->subMonths(6),
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO(period: 'this_month');
        $expenses = $this->expenseService->getAll($filters);

        $this->assertEquals(1, $expenses->total());
    }

    /** @test */
    public function it_can_calculate_total_amount_by_currency()
    {
        ExpenseFee::factory()->create([
            'currency' => 'USD',
            'amount' => 100,
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'currency' => 'USD',
            'amount' => 200,
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'currency' => 'CDF',
            'amount' => 285000,
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO();
        $totals = $this->expenseService->getTotalAmountByCurrency($filters);

        $this->assertEquals(300, $totals['USD']);
        $this->assertEquals(285000, $totals['CDF']);
    }

    /** @test */
    public function it_can_calculate_total_amount_converted_to_usd()
    {
        ExpenseFee::factory()->create([
            'currency' => 'USD',
            'amount' => 100,
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'currency' => 'CDF',
            'amount' => 285000, // = 100 USD au taux de 2850
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO();
        $total = $this->expenseService->getTotalAmount($filters);

        // 100 USD + (285000 CDF / 2850) = 100 + 100 = 200 USD
        $this->assertEquals(200, $total);
    }

    /** @test */
    public function it_can_get_expenses_grouped_by_month()
    {
        ExpenseFee::factory()->create([
            'month' => '11',
            'amount' => 100,
            'currency' => 'USD',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'month' => '11',
            'amount' => 200,
            'currency' => 'USD',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        ExpenseFee::factory()->create([
            'month' => '12',
            'amount' => 300,
            'currency' => 'USD',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO();
        $byMonth = $this->expenseService->getByMonth($filters);

        $this->assertCount(2, $byMonth);

        $november = $byMonth->firstWhere('month', '11');
        $this->assertEquals(300, $november->total_usd);
        $this->assertEquals(2, $november->count);
    }

    /** @test */
    public function it_can_get_statistics()
    {
        ExpenseFee::factory()->count(5)->create([
            'amount' => 100,
            'currency' => 'USD',
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $filters = new ExpenseFilterDTO();
        $stats = $this->expenseService->getStatistics($filters);

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_usd', $stats);
        $this->assertArrayHasKey('total_cdf', $stats);
        $this->assertArrayHasKey('count', $stats);
        $this->assertArrayHasKey('average', $stats);
        $this->assertArrayHasKey('by_month', $stats);
        $this->assertArrayHasKey('by_category', $stats);

        $this->assertEquals(500, $stats['total_usd']);
        $this->assertEquals(5, $stats['count']);
        $this->assertEquals(100, $stats['average']);
    }

    /** @test */
    public function it_checks_if_expense_exists()
    {
        $expense = ExpenseFee::factory()->create([
            'category_expense_id' => 1,
            'category_fee_id' => 1,
            'school_year_id' => 1,
        ]);

        $this->assertTrue($this->expenseService->exists($expense->id));
        $this->assertFalse($this->expenseService->exists(9999));
    }

    /** @test */
    public function it_validates_expense_dto()
    {
        $dto = new ExpenseDTO(
            description: '',
            amount: 0,
            currency: 'INVALID',
        );

        $errors = $dto->validate();

        $this->assertArrayHasKey('description', $errors);
        $this->assertArrayHasKey('amount', $errors);
        $this->assertArrayHasKey('currency', $errors);
        $this->assertFalse($dto->isValid());
    }
}
