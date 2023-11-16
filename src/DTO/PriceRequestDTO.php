<?php

namespace App\DTO;

use App\Service\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

class PriceRequestDTO
{
    #[Assert\Type('integer')]
    private int $productId;
    #[Assert\Callback([TaxNumber::class, 'validate'])]
    private string $taxNumber;
    private ?string $couponCode;

    private function __construct() {}

    static function fromJSON(string $json): static
    {
        $data = json_decode($json, true);
        $dto = new static();

        $dto->productId = $data['product'];
        $dto->couponCode = $data['couponCode'] ?? null;
        $dto->taxNumber = $data['taxNumber'];

        return $dto;
    }

    public function getCouponCode(): ?string
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
}