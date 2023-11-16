<?php

namespace App\Service\PaymentProcessor;

use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripeProcessorAdapter implements PaymentProcessorAdapterInterface
{

    public function __construct(private StripePaymentProcessor $stripePaymentProcessor) {}

    public function pay(float $price): bool
    {
        return $this->stripePaymentProcessor->processPayment($price);
    }
}