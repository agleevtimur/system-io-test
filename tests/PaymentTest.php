<?php
declare(strict_types=1);
namespace App\Tests;

use App\Service\PaymentService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentTest extends KernelTestCase
{
    #[DataProvider('makePaymentProvider')]
    public function testMakePayment(float $price, string $paymentProcessorType)
    {
        $container = static::getContainer();
        $paymentService = $container->get(PaymentService::class);

        $result = $paymentService->makePayment($price, $paymentProcessorType);

        $this->assertTrue($result);
    }

    #[DataProvider('makePaymentFailProvider')]
    public function testMakePaymentFail(float $price, string $paymentProcessorType)
    {
        $container = static::getContainer();
        $paymentService = $container->get(PaymentService::class);

        $result = $paymentService->makePayment($price, $paymentProcessorType);

        $this->assertFalse($result);
    }

    public static function makePaymentProvider(): array
    {
        return [
            [1110.5, 'stripe'],
            [200, 'paypal'],
        ];
    }

    public static function makePaymentFailProvider(): array
    {
        return [
            'stripe small price' => [10.5, 'stripe'],
            'paypal huge price' => [10000000, 'paypal'],
        ];
    }
}