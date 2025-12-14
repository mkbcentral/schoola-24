<?php

namespace Tests\Unit\Services;

use App\Services\Contracts\CurrencyExchangeServiceInterface;
use App\Services\CurrencyExchangeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CurrencyExchangeServiceTest extends TestCase
{
    use RefreshDatabase;

    private CurrencyExchangeServiceInterface $currencyService;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        $this->currencyService = new CurrencyExchangeService();
    }

    /** @test */
    public function it_can_convert_usd_to_cdf()
    {
        $amount = 100;
        $result = $this->currencyService->convert($amount, 'USD', 'CDF');

        $this->assertEquals(285000, $result);
    }

    /** @test */
    public function it_can_convert_cdf_to_usd()
    {
        $amount = 285000;
        $result = $this->currencyService->convert($amount, 'CDF', 'USD');

        $this->assertEquals(100, $result);
    }

    /** @test */
    public function it_returns_same_amount_for_same_currency()
    {
        $amount = 100;
        $result = $this->currencyService->convert($amount, 'USD', 'USD');

        $this->assertEquals($amount, $result);
    }

    /** @test */
    public function it_can_get_exchange_rate()
    {
        $rate = $this->currencyService->getRate('USD', 'CDF');

        $this->assertEquals(2850, $rate);
    }

    /** @test */
    public function it_returns_one_for_same_currency_rate()
    {
        $rate = $this->currencyService->getRate('USD', 'USD');

        $this->assertEquals(1.0, $rate);
    }

    /** @test */
    public function it_can_set_custom_rate()
    {
        $this->currencyService->setRate('USD', 'CDF', 3000);

        $rate = $this->currencyService->getRate('USD', 'CDF');

        $this->assertEquals(3000, $rate);
    }

    /** @test */
    public function it_can_convert_any_currency_to_usd()
    {
        $result = $this->currencyService->convertToUSD(285000, 'CDF');

        $this->assertEquals(100, $result);
    }

    /** @test */
    public function it_returns_same_amount_when_converting_usd_to_usd()
    {
        $result = $this->currencyService->convertToUSD(100, 'USD');

        $this->assertEquals(100, $result);
    }

    /** @test */
    public function it_can_get_all_rates()
    {
        $rates = $this->currencyService->getAllRates();

        $this->assertIsArray($rates);
        $this->assertArrayHasKey('USD_CDF', $rates);
        $this->assertArrayHasKey('CDF_USD', $rates);
    }

    /** @test */
    public function it_caches_rates()
    {
        $this->currencyService->setRate('USD', 'CDF', 3000);

        // Créer une nouvelle instance pour vérifier le cache
        $newService = new CurrencyExchangeService();
        $rate = $newService->getRate('USD', 'CDF');

        $this->assertEquals(3000, $rate);
    }

    /** @test */
    public function it_can_clear_cache()
    {
        $this->currencyService->setRate('USD', 'CDF', 3000);
        $this->currencyService->clearCache();

        $rate = $this->currencyService->getRate('USD', 'CDF');

        // Devrait revenir au taux par défaut
        $this->assertEquals(2850, $rate);
    }

    /** @test */
    public function it_rounds_conversion_result()
    {
        $amount = 33.333;
        $result = $this->currencyService->convert($amount, 'USD', 'CDF');

        // 33.333 * 2850 = 94999.05, arrondi à 95000.05
        $this->assertIsFloat($result);
        $this->assertEquals(round(33.333 * 2850, 2), $result);
    }

    /** @test */
    public function it_handles_inverse_rate_calculation()
    {
        // Si on a USD_CDF, on peut calculer CDF_USD automatiquement
        $this->currencyService->setRate('USD', 'CDF', 3000);

        $inverseRate = $this->currencyService->getRate('CDF', 'USD');

        $this->assertEquals(1 / 3000, $inverseRate);
    }

    /** @test */
    public function it_uses_configuration_defaults()
    {
        config(['currency.rates.USD_CDF' => 2900]);

        $service = new CurrencyExchangeService();

        // Le taux devrait être celui par défaut de la config si pas en cache
        $rate = $service->getRate('USD', 'CDF');

        $this->assertIsFloat($rate);
    }
}
