<?php
return [
    'banner' => [
        // ['title' => 'iphone12美版64G', 'goods_id' => 2, 'image' => 'iphone.jpeg', 'store_id' => 1],
        // ['title' => 'ipad mini3 64G', 'goods_id' => 5, 'image' => 'ipad.jpeg', 'store_id' => 1],
    ],
    'category' => [
        ['pid' => null, 'id' => 1, 'name' => '电子产品', 'image' => 'images/'],
            ['pid' => 1,    'id' => 11, 'name' => '手机', 'image' => 'images/phone.png'],
            ['pid' => 1,    'id' => 12, 'name' => '平板', 'image' => 'images/pad.png', 'commission' => 40],
            ['pid' => 1,    'id' => 13, 'name' => '电脑', 'image' => 'images/computer.png'],
        ['pid' => null, 'id' => 2, 'name' => '居家'],    
            ['pid' => 2,    'id' => 21, 'name' => '枕头'],
            ['pid' => 2,    'id' => 22, 'name' => '床单'],
            ['pid' => 2,    'id' => 23, 'name' => '被罩'],
        ['pid' => null, 'id' => 3, 'name' => '运动'],    
            ['pid' => 3,    'id' => 31, 'name' => '头盔'],
            ['pid' => 3,    'id' => 32, 'name' => '单车', 'commission' => 30],
            ['pid' => 3,    'id' => 33, 'name' => '护具'],
    ],
    'goods' => [
        ['shopId' => 1, 'id' => 1, 'category_id' => 11, 'name' => 'iPhone 11美版', 'price' => 3680, 'commission' => 20],
        ['shopId' => 1, 'id' => 2, 'category_id' => 11, 'name' => 'iPhone 12美版64G', 'price' => 3680, 'commission' => 25],
        ['shopId' => 1, 'id' => 3, 'category_id' => 11, 'name' => 'iPhone 8美版', 'price' => 3680, 'commission' => 24],
        
        
        ['shopId' => 1, 'id' => 4, 'category_id' => 12, 'name' => '10寸ipad 3', 'price' => 5800],
        ['shopId' => 1, 'id' => 5, 'category_id' => 12, 'name' => '10寸ipad3 mini', 'price' => 5800],
        ['shopId' => 1, 'id' => 6, 'category_id' => 13, 'name' => 'MacPro2020 4核8G', 'price' => 9800],
        ['shopId' => 1, 'id' => 7, 'category_id' => 13, 'name' => 'MacPro2020 4核4G', 'price' => 9800],
        
        
        ['shopId' => 1, 'id' => 8, 'category_id' => 21, 'name' => '羽绒枕', 'price' => 120, 'commission' => 40],
        ['shopId' => 1, 'id' => 9, 'category_id' => 21, 'name' => '棉花枕', 'price' => 128, 'commission' => 50],
        
        
        ['shopId' => 1, 'id' => 10, 'category_id' => 32, 'name' => 'GIANT 2021 山地车', 'price' => 2300],
        ['shopId' => 1, 'id' => 11, 'category_id' => 32, 'name' => '2021 公路车', 'price' => 2400],
    ],
    'user' => [
        ['id' => 1, 'name' => 'Admin', 'email' =>'admin@test.com', 'password' => '111111'],
        ['id' => 2, 'name' => '小张', 'email' => 'zhang@test.com',  'password' => '111111', 'store_id' => 1, 'senior_id' => null, ],
        ['id' => 3, 'name' => '小刘', 'email' => 'liu@test.com',    'password' => '111111', 'store_id' => 1, 'senior_id' => 2,    ],
        ['id' => 4, 'name' => '小红', 'email' => 'hong@test.com',   'password' => '111111', 'store_id' => 1, 'senior_id' => 3,    ],
    ],
    'address' => [
        [
            'user_id' => 2,
            'consignee' => '小张',
            'telephone' => '13322223333',
            'province_id' => 4,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 3,
            'consignee' => '小张',
            'telephone' => '13322223333',
            'province_id' => 4,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 4,
            'consignee' => '小张',
            'telephone' => '13322223333',
            'province_id' => 4,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        
    ]
];