<?php
use App\Models\User;
return [
    'banner' => [
        // ['title' => 'iphone12美版64G', 'goods_id' => 2, 'image' => 'iphone.jpeg', 'store_id' => 1],
        // ['title' => 'ipad mini3 64G', 'goods_id' => 5, 'image' => 'ipad.jpeg', 'store_id' => 1],
    ],
    'devices' => [
        'ggz17zg9nAJ' => ['Graphene_G0001', 'Graphene_G0002', 'Graphene_G0003'],
        'ggz1JU0WNFH' => ['JQR_0003', 'JQR_0004', 'JQR_0005']
    ],
    'resources' => [
        "Address","Banner","BaseModel","Cart","Category","City","Clerk",
        "Customer","Device","District","Example","Expert","Goods","Logistic","Order",
        "Province","Revenue","Salesman","Setting","Store","Supplier","User",        
    ],
    'roles' => [
        User::SALESMAN => [
            "View Store", "View Device", "View Order"
        ],
        User::MANAGER => [
            "View Customer", "View Clerk", "View Order", "View Review"
        ],
        User::CLERK => [
            "View Customer", "View Order"
        ],
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
        ['pid' => null, 'id' => 1, 'name' => '营养', 'status' => 'on_shelf'],
        ['pid' => null, 'id' => 2, 'name' => '保健', 'status' => 'on_shelf'],
    ],
    'goods' => [
        ['id' => 1, 'category_id' => 1, 'name' => '澳洲进口牛奶', 'price' => 68, 'status' => 'on_shelf'],
        ['id' => 2, 'category_id' => 2, 'name' => '拉力器', 'price' => 88, 'status' => 'on_shelf'],
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
            'mobile' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 3,
            'contact' => '小张',
            'mobile' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 4,
            'contact' => '小张',
            'mobile' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        
    ]
];