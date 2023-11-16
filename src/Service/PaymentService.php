<?php

namespace App\Service;


use App\Service\PaymentProcessor\PaymentProcessorFactory;

/**
 * Сервис оплаты
 */
class PaymentService
{
    public function __construct(private PaymentProcessorFactory $paymentProcessorFactory) {}

    /**
     * @param float $price Итоговая цена для полаты
     * @param string $paymentProcessorType Способ оплаты
     * @return bool
     */
    public function makePayment(float $price, string $paymentProcessorType): bool
    {
        $paymentProcessor = $this->paymentProcessorFactory->create($paymentProcessorType);

        return $paymentProcessor->pay($price);
    }
}