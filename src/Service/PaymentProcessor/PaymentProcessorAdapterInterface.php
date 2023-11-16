<?php

namespace App\Service\PaymentProcessor;

/**
 * Задача адаптера - привести способы оплаты к единому интерфейсу.
 * Единый интерфейс нужен для простого добавления новых способов оплат.
 */
interface PaymentProcessorAdapterInterface
{
    public function pay(float $price): bool;
}