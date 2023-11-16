<?php

use App\Entity\DBAL\CouponType;

return [
    'product' => [
        ['name' => 'Iphone', 'price' => 100],
        ['name' => 'Наушники', 'price' => 20],
        ['name' => 'Чехол', 'price' => 10],
    ],
    'coupon' => [
        ['code' => 'D15', 'type' => CouponType::FIX_TYPE, 'discount' => 5],
        ['code' => 'D16', 'type' => CouponType::PERCENTAGE_TYPE, 'discount' => 50],
    ]
];