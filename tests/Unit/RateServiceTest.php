<?php

namespace Tests\Unit;

use App\Adapters\BlockchainInfoRateAdapter;
use App\Domain\Converting;
use App\Domain\Rate;
use App\Exceptions\NoRateFoundException;
use App\Services\RateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use ReflectionClass;
use Tests\TestCase;

/**
 * @coversDefaultClass \App\Services\RateService
 */
class RateServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers ::convertMoney
     */
    public function testConvertMoney()
    {
        $this->mock(BlockchainInfoRateAdapter::class, function (MockInterface $mock) {
            $mock->shouldReceive('gateRates')->andReturn($this->ratesCollectionProvider());
        });

        $currency_from = 'BTC';
        $currency_to = 'USD';
        $value = 2.00;
        $converted_value = 98000;
        $rate = 49000;

        $converting = app(RateService::class)->convertMoney($currency_from, $currency_to, $value);

        $this->assertInstanceOf(Converting::class, $converting);
        $this->assertDatabaseHas(
            'convertings',
            compact('currency_from', 'currency_to', 'value', 'converted_value', 'rate')
        );
    }

    /**
     * @covers ::convertMoney
     */
    public function testConvertMoneyException()
    {
        $this->expectException(NoRateFoundException::class);

        $this->mock(BlockchainInfoRateAdapter::class, function (MockInterface $mock) {
            $mock->shouldReceive('gateRates')->andReturn($this->ratesCollectionProvider());
        });

        app(RateService::class)->convertMoney('BTC', 'FAKE', 2.00);
    }

    /**
     * @dataProvider getRateDataProvider
     * @covers ::getRate
     * @param  string  $from
     * @param  string  $to
     * @param  float  $rate
     * @param  bool  $exist
     * @throws \ReflectionException
     */
    public function testGetRate(string $from, string $to, float $rate, bool $exist)
    {
        $this->mock(BlockchainInfoRateAdapter::class, function (MockInterface $mock) {
            $mock->shouldReceive('gateRates')->andReturn($this->ratesCollectionProvider());
        });

        $service = app(RateService::class);
        $class = new ReflectionClass(RateService::class);
        $method = $class->getMethod('getRate');
        $method->setAccessible(true);
        $resultRate = $method->invoke($service, $from, $to);

        if ($exist) {
            $this->assertInstanceOf(Rate::class, $resultRate);
            $this->assertEquals($from, $resultRate->from);
            $this->assertEquals($to, $resultRate->to);
            $this->assertEquals($rate, $resultRate->rate);
        } else {
            $this->assertNull($resultRate);
        }
    }

    public function getRateDataProvider(): array
    {
        return [
            ['from' => 'BTC', 'to' => 'USD', 'rate' => 50000, 'exist' => true],
            ['from' => 'USD', 'to' => 'BTC', 'rate' => 0.00002, 'exist' => true],
            ['from' => 'BTC', 'to' => 'AUD', 'rate' => 52000, 'exist' => true],
            ['from' => 'BTC', 'to' => 'FAKE', 'rate' => 0, 'exist' => false],
        ];
    }

    protected function ratesCollectionProvider(): Collection
    {
        return collect([
            Rate::make('BTC', 'EUR', 48000, 2),
            Rate::make('BTC', 'USD', 50000, 2),
            Rate::make('BTC', 'AUD', 52000, 2),
        ]);
    }


    /**
     * @dataProvider applyFeeDataProvider
     * @covers ::applyFee
     * @param  Rate  $rate
     * @param  Rate  $expectedRate
     * @throws \ReflectionException
     */
    public function testApplyFee(Rate $rate, Rate $expectedRate)
    {
        $service = app(RateService::class);

        $class = new ReflectionClass(RateService::class);
        $method = $class->getMethod('applyFee');
        $method->setAccessible(true);
        $resultRate = $method->invoke($service, $rate);

        $this->assertEquals($expectedRate, $resultRate);
    }

    public function applyFeeDataProvider(): array
    {
        return [
            [
                'rate' => Rate::make('BTC', 'USD', 50000.55, 2),
                'expectedRate' => Rate::make('BTC', 'USD', 49000.53, 2),
            ],
            [
                'rate' => Rate::make('USD', 'BTC', 0.0010203971, 10),
                'expectedRate' => Rate::make('USD', 'BTC', 0.0009999891, 10),
            ],
        ];
    }
}
