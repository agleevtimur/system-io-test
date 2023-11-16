<?php

namespace App\Tests;

use App\Service\TaxNumber;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class TaxNumberTest extends TestCase
{
    #[DataProvider('validationProvider')]
    public function testValidate(string $taxNumber)
    {
        $result = TaxNumber::validate($taxNumber);

        $this->assertTrue($result);
    }

    #[DataProvider('validationFailProvider')]
    public function testValidateFail(string $taxNumber)
    {
        $result = TaxNumber::validate($taxNumber);

        $this->assertFalse($result);
    }

    #[DataProvider('taxRateProvider')]
    public function testTaxRate(string $taxNumber, int $expected)
    {
        $result = TaxNumber::getTaxRate($taxNumber);

        $this->assertSame($expected, $result);
    }

    public static function validationProvider(): array
    {
        return [
            ['DE123456789'],
            ['IT12345678900'],
            ['FRAB123456789']
        ];
    }

    public static function validationFailProvider(): array
    {
        return [
            ['DV123456789'],
            ['12345678910'],
            ['FR12345678910']
        ];
    }

    public static function taxRateProvider(): array
    {
        return [
            ['DE123456789', 19],
            ['IT12345678900', 22],
            ['GR', 24],
            ['FRAB111111111', 20]
        ];
    }
}