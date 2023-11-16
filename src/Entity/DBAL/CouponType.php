<?php

namespace App\Entity\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class CouponType extends Type
{
    const NAME = 'enum_coupon_type';
    const FIX_TYPE = 'fix';
    const PERCENTAGE_TYPE = 'percentage';
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::NAME;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, array(self::FIX_TYPE, self::PERCENTAGE_TYPE))) {
            throw new InvalidArgumentException("Invalid type");
        }

        return $value;
    }
}
