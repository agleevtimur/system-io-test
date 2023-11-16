<?php

namespace App\Service\PaymentProcessor;

class PaymentProcessorFactory
{

    public function __construct(
        private PaypalProcessorAdapter $paypalProcessorAdapter,
        private StripeProcessorAdapter $stripeProcessorAdapter
    ) {}

    public function create(string $processorType): PaymentProcessorAdapterInterface
    {
        return match ($processorType) {
            'paypal' => $this->paypalProcessorAdapter,
            'stripe' => $this->stripeProcessorAdapter
        };
    }
}