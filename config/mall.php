<?php

return [
    'base_url' => env('ZIMAOGO_URL'),
    'user_id' => env('ZIMAOGO_USERID'),
    'shop_id' => env('ZIMAOGO_SHOPID'),
    'key' => env('ZIMAOGO_KEY'),
    'per_page' => 20,
    'image_resize' => [
        'thumb' => 100,
        'medium' => 500,
        'large' => 1000
    ],
    'store' => [
        'width_bonus' => [
            'bonus' => 2,
            'pgpv' => 25000,
            'dd_qty' => 3
        ], 
        'depth_bonus' => [
            'bonus' => 1,
            'dd_qty' => 3
        ], 
        'leader_bonus' => [
            'bonus' => 6,
            'pgpv' => 4000
        ],
        'tier_bonus' => [
            250 => 3,
            750 => 6,
            1250 => 9,
            2500 => 12,
            5000 => 15,
            8750 => 18,
            12500 => 21
        ],
        'bonus_title' => [
            3 => '营销经理（翡翠）',
            6 => '高级营销经理（钻石）',
            9 => '执行专才直系直销商',
            12 => '双钻石直系直销商',
            15 => '三钻石直系直销商',
            18 => '皇冠直系直销商',
        ]
    ]
];