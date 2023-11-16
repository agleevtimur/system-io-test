<?php

namespace App\DTO;

use App\Service\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseRequestDTO
{
    const PAYMENT_PROCESSOR_TYPES = ['paypal', 'stripe'];
    #[Assert\Type('integer')]
    private int $productId;
    #[Assert\Callback([TaxNumber::class, 'validate'])]
    private string $taxNumber;
    private ?string $couponCode;
    #[Assert\Choice(choices: self::PAYMENT_PROCESSOR_TYPES, message: 'Неизвестный способ оплаты')]
    private string $paymentProcessor;

    private function __construct() {}

    static function fromJSON(string $json): static
    {
        $data = json_decode($json, true);
        $dto = new static();

        $dto->productId = $data['product'];
        $dto->couponCode = $data['couponCode'];
        $dto->taxNumber = $data['taxNumber'];
        $dto->paymentProcessor = $data['paymentProcessor'];

        return $dto;
    }

    public function getCouponCode(): string
    {
        return $this->couponCode;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }
}