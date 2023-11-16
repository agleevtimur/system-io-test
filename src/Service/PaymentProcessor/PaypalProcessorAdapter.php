<?php
declare(strict_types=1);
namespace App\Service\PaymentProcessor;

use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use TypeError;

class PaypalProcessorAdapter implements PaymentProcessorAdapterInterface
{

    public function __construct(private PaypalPaymentProcessor $paypalPaymentProcessor) {}

    public function pay(float $price): bool
    {
        try {
            //окгруляем float до целого числа в большую сторону
            $this->paypalPaymentProcessor->pay(intval(ceil($price)));
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }
}