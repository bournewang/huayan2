<?php
use App\Models\User;
return [
    'banner' => [
        // ['title' => 'iphone12美版64G', 'goods_id' => 2, 'image' => 'iphone.jpeg', 'store_id' => 1],
        // ['title' => 'ipad mini3 64G', 'goods_id' => 5, 'image' => 'ipad.jpeg', 'store_id' => 1],
    ],
    'devices' => [
        'ggz17zg9nAJ' => ['QYB_D0001', 'Graphene_G0002', 'Graphene_G0003'],
        'ggz1JU0WNFH' => ['JQR_0003', 'JQR_0004', 'JQR_0005']
    ],
    // resource to build permissions of View/Create/Update/Delete/ForceDelete
    // new resource must put here
    'resources' => [
        "Address","Banner","BaseModel","Cart","Category","City","Clerk",
        "Customer","Device","District","Example","Expert","Goods","Logistic","Order",
        "Province","Revenue","Salesman","Setting","Store","Supplier","User",
        "Review", "PurchaseOrder","SalesOrder","Stock", "StockItem"
    ],
    // special permissions
    'permissions' => [
        'Delivery', 'StockImport'
    ],
    'roles' => [
        User::SALESMAN => [
            "Index Store", "View Store", 
            "Index Device", "View Device", 
            "Index Order", "View Order",
            "View"
        ],
        // User::WAREHOUSE_KEEPER => [
        // 
        // ],
        // User::FINANCIAL => [
        // 
        // ],
        User::MANAGER => [
            "Index Customer", "View Customer", 
            "Index Clerk", "View Clerk", 
            "Index Order", "View Order", 
            "Index ServiceOrder", "View ServiceOrder", 
            "View Review", 
            "Index Goods", "View Goods",
            'Index PurchaseOrder',  'View PurchaseOrder',   'Delete PurchaseOrder', 'StockImport',
            'Index SalesOrder',     'View SalesOrder',      'Delete SalesOrder',
            'View Stock',
            'Index StockItem', 'View StockItem',
            "View Cart"
        ],
        User::CLERK => [
            "Index Customer", "View Customer", 
            "Index Order", "View Order", 
            "Index Goods", "View Goods",
            'Index PurchaseOrder',  'Create PurchaseOrder', 'View PurchaseOrder', 'Update PurchaseOrder',
            'Index SalesOrder',     'Create SalesOrder',    'View SalesOrder', 'Update SalesOrder', 
            'Index Stock', 'View Stock',
            'Index StockItem', 'View StockItem',
            "View Cart"
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
        ['id' => 1, 'name' => 'Admin', 'email' =>'admin@test.com',    'mobile' =>  '13811111110', 'password' => '111111', 'openid' => '111110', 'unionid' => '111110'],
        ['id' => 2, 'name' => '王业务员', 'email' => 'sales@test.com',   'mobile' => '13811111111',  'password' => '111111', 'senior_id' => null, 'openid' => '111111', 'unionid' => '111111', 'type' => User::SALESMAN],
        ['id' => 3, 'name' => '张店长', 'email' => 'zhang@test.com',   'mobile' => '13811111112',  'password' => '111111', 'store_id' => 1, 'senior_id' => null, 'openid' => '111112', 'unionid' => '111112', 'type' => User::MANAGER],
        ['id' => 4, 'name' => '小刘/店员', 'email' => 'liu@test.com',   'mobile' => '13811111113',    'password' => '111111', 'store_id' => 1, 'senior_id' => 2,    'openid' => '111113', 'unionid' => '111113', 'type' => User::CLERK],
        ['id' => 5, 'name' => '老赵', 'email' => 'zhao@test.com',       'mobile' => '13811111114',  'password' => '111111', 'store_id' => 1, 'senior_id' => 3,    'openid' => '111114', 'unionid' => '111114', 'type' => User::CUSTOMER],
    ],
    'address' => [
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
        [
            'user_id' => 5,
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