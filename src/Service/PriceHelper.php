<?php

namespace App\Service;

use App\Entity\DBAL\CouponType;

/**
 * Калькулятор цен
 */
class PriceHelper
{
    /**
     * Вычисляет итоговую цену, включая скидку и налог
     *
     * @param float $basePrice
     * @param string $taxNumber
     * @param string|null $couponType
     * @param int|null $discount
     * @return float
     */
    public function calculateTotalPrice(float $basePrice, string $taxNumber, ?string $couponType, ?int $discount): float
    {
        if ($couponType !== null) {
            $basePrice = $this->calculateDiscountPrice($basePrice, $couponType, $discount);
        }

        return $this->calculateTaxPrice($basePrice, $taxNumber);
    }

    /**
     * Вычисляет цену со скидкой
     *
     * @param float $basePrice
     * @param string $couponType
     * @param int $discount
     * @return float
     */
    public function calculateDiscountPrice(float $basePrice, string $couponType, int $discount): float
    {
        return $couponType === CouponType::FIX_TYPE ?
            $basePrice - $discount :
            $basePrice - $basePrice * $discount / 100;
    }

    /**
     * Вычисляет цену с налогом страны
     *
     * @param float $basePrice
     * @param string $taxNumber
     * @return float
     */
    public function calculateTaxPrice(float $basePrice, string $taxNumber): float
    {
        return $basePrice + $basePrice * TaxNumber::getTaxRate($taxNumber) / 100;
    }
}