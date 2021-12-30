<?php
return [
    'banner' => [
        // ['title' => 'iphone12美版64G', 'goods_id' => 2, 'image' => 'iphone.jpeg', 'store_id' => 1],
        // ['title' => 'ipad mini3 64G', 'goods_id' => 5, 'image' => 'ipad.jpeg', 'store_id' => 1],
    ],
    'devices' => [
        'ggz17zg9nAJ' => ['Graphene_G0001', 'Graphene_G0002', 'Graphene_G0003'],
        'ggz1JU0WNFH' => ['JQR_0003', 'JQR_0004', 'JQR_0005']
    ],
    'setting' => [
        'banks' => [
            "ICBC" 	=> "中国工商银行",
            "CCB" 	=> "中国建设银行",
            "HSBC" 	=> "汇丰银行",
            "BC" 	=> "中国银行",
            "ABC" 	=> "中国农业银行",
            "BC" 	=> "交通银行",
            "CMB" 	=> "招商银行",
            "CMB" 	=> "中国民生银行",
            "SPDB" 	=> "上海浦东发展银行",
            "CITIC" => "中信银行",
            "CEB" 	=> "中国光大银行",
            "HB" 	=> "华夏银行",
            "GDB" 	=> "广东发展银行",
            "SDB" 	=> "深圳发展银行",
            "CIB" 	=> "兴业银行",
            "CDB" 	=> "国家开发银行",
            "EIBC" 	=> "中国进出口银行",
            "ADBC" 	=> "中国农业发展银行",
            "OTHER" => "其他"            
        ],
        'device_types' => [
            'ggz1JU0WNFH' => '强筋机器人',
            'ggz17zg9nAJ' => '石墨烯能量房',
            'ggz1Vk6qGPr' => '超长波治疗仪'
        ]
    ],
    'category' => [
        ['pid' => null, 'id' => 1, 'name' => '电子产品'],
            ['pid' => 1,    'id' => 11, 'name' => '手机', 'status' => 'on_shelf'],
            ['pid' => 1,    'id' => 12, 'name' => '平板', 'status' => 'off_shelf'],
            ['pid' => 1,    'id' => 13, 'name' => '电脑', 'status' => 'on_shelf'],
        ['pid' => null, 'id' => 2, 'name' => '居家'],    
            ['pid' => 2,    'id' => 21, 'name' => '枕头', 'status' => 'recommend'],
            ['pid' => 2,    'id' => 22, 'name' => '床单', 'status' => 'off_shelf'],
            ['pid' => 2,    'id' => 23, 'name' => '被罩', 'status' => 'on_shelf'],
        ['pid' => null, 'id' => 3, 'name' => '运动'],    
            ['pid' => 3,    'id' => 31, 'name' => '头盔', 'status' => 'off_shelf'],
            ['pid' => 3,    'id' => 32, 'name' => '单车', 'status' => 'recommend'],
            ['pid' => 3,    'id' => 33, 'name' => '护具', 'status' => 'on_shelf'],
    ],
    'goods' => [
        ['id' => 1, 'category_id' => 11, 'name' => 'iPhone 11美版', 'price' => 3680, 'status' => 'on_shelf'],
        ['id' => 2, 'category_id' => 11, 'name' => 'iPhone 12美版64G', 'price' => 3680, 'status' => 'recommend'],
        ['id' => 3, 'category_id' => 11, 'name' => 'iPhone 8美版', 'price' => 3680, 'status' => 'on_shelf'],
        
        
        ['id' => 4, 'category_id' => 12, 'name' => '10寸ipad 3', 'price' => 5800, 'status' => 'on_shelf'],
        ['id' => 5, 'category_id' => 12, 'name' => '10寸ipad3 mini', 'price' => 5800, 'status' => 'recommend'],
        ['id' => 6, 'category_id' => 13, 'name' => 'MacPro2020 4核8G', 'price' => 9800, 'status' => 'on_shelf'],
        ['id' => 7, 'category_id' => 13, 'name' => 'MacPro2020 4核4G', 'price' => 9800, 'status' => 'off_shelf'],
        
        
        ['id' => 8, 'category_id' => 21, 'name' => '羽绒枕', 'price' => 120, 'status' => 'off_shelf'],
        ['id' => 9, 'category_id' => 21, 'name' => '棉花枕', 'price' => 128, 'status' => 'on_shelf'],
        
        
        ['id' => 10, 'category_id' => 32, 'name' => 'GIANT 2021 山地车', 'price' => 2300],
        ['id' => 11, 'category_id' => 32, 'name' => '2021 公路车', 'price' => 2400],
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
            'contact' => '小张',
            'telephone' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 3,
            'contact' => '小张',
            'telephone' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 4,
            'contact' => '小张',
            'telephone' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        
    ]
];