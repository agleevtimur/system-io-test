<?php

namespace App\Service;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TaxNumber
{
    private static array $taxRates = [
        'DE' => 19,
        'IT' => 22,
        'GR' => 24,
        'FR' => 20
    ];

    private static array $taxNumberRules = [
        'DE' => '/^DE\d{9}$/',
        'IT' => '/^IT\d{11}$/',
        'GR' => '/^GR\d{9}$/',
        'FR' => '/^FR[A-Z]{2}\d{9}$/'
    ];

    /**
     *  Метод возвращает налоговую ставку страны по первым двум символам налогового номера.
     *  Метод не отвечает за валидацию налогового номера
     *
     * @param string $taxNumber
     * @return int|null
     */
    public static function getTaxRate(string $taxNumber): ?int
    {
        foreach (self::$taxRates as $prefix => $rate) {
            if (str_starts_with($taxNumber, $prefix) === true) {
                return $rate;
            }
        }

        return null;
    }

    /**
     * Валидация налогового номера
     *
     * @param mixed $taxNumber
     * @param ExecutionContextInterface|null $context
     * @return bool
     */
    public static function validate(mixed $taxNumber, ExecutionContextInterface $context = null): bool
    {
        foreach (self::$taxNumberRules as $rule) {
            if (preg_match($rule, $taxNumber)) {
                return true;
            }
        }

        $context
            ?->buildViolation('Неизвестный налоговый номер')
            ->atPath('taxNumber')
            ->addViolation();

        return false;
    }
}