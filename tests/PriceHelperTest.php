<?php

use App\Entity\DBAL\CouponType;
use App\Service\PriceHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PriceHelperTest extends TestCase
{
    #[DataProvider('calculationTotalPriceProvider')]
    public function testCalculateTotalPrice(float $basePrice, string $taxNumber, ?string $couponType, ?int $discount, float $expected)
    {
        $priceHelper = new PriceHelper();
        $result = $priceHelper->calculateTotalPrice($basePrice, $taxNumber, $couponType, $discount);

        $this->assertSame($expected, $result);
    }

    #[DataProvider('calculationDiscountPriceProvider')]
    public function testCalculateDiscountPrice(float $basePrice, string $couponType, int $discount, float $expected)
    {
        $priceHelper = new PriceHelper();
        $result = $priceHelper->calculateDiscountPrice($basePrice, $couponType, $discount);

        $this->assertSame($expected, $result);
    }

    #[DataProvider('calculationTaxPriceProvider')]
    public function testCalculateTaxPrice(float $basePrice, string $taxNumber, float $expected)
    {
        $priceHelper = new PriceHelper();
        $result = $priceHelper->calculateTaxPrice($basePrice, $taxNumber);

        $this->assertSame($expected, $result);
    }

    public static function calculationTotalPriceProvider(): array
    {
        return [
            [100, 'GR123456789', CouponType::FIX_TYPE, 50, 62],
            [100, 'GR123456789', CouponType::PERCENTAGE_TYPE, 6, 116.56],
            [1000, 'IT12345678910', CouponType::FIX_TYPE, 100, 1098],
            [100, 'GR123456789', null, null, 124]
        ];
    }

    public static function calculationDiscountPriceProvider(): array
    {
        return [
            [100, CouponType::FIX_TYPE, 50, 50],
            [100, CouponType::PERCENTAGE_TYPE, 10, 90]
        ];
    }

    public static function calculationTaxPriceProvider(): array
    {
        return [
            [100, 'GR123456789', 124]
        ];
    }
}