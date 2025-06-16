<?php

return [
    'plugin' => [
        'name' => 'Catalog',
        'description' => 'Manage a catalog of products',
    ],
    'permissions' => [
        'some_permission' => 'Some permission',
    ],
    'models' => [
        'general' => [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'pictures' => 'Pictures',
            'picture' => 'Picture',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'actions' => 'Actions',
            'name' => 'Name',
            'code' => 'Code',
        ],
        'product' => [
            'label' => 'Product',
            'label_plural' => 'Products',
            'base_price' => 'Base price',
            'discount_type' => 'Discount type',
            'discount_value' => 'Discount value',
        ],
        'product_variant' => [
            'label' => 'Product variant',
            'label_plural' => 'Product variants',
        ],
        'product_attribute' => [
            'label' => 'Product attribute',
            'label_plural' => 'Product attributes',
        ],
        'brand' => [
            'label' => 'Brand',
            'label_plural' => 'Brands',
        ],
        'category' => [
            'label' => 'Category',
            'label_plural' => 'Categories',
            'add_subcategory' => 'Add subcategory',
        ],
        'attribute' => [
            'label' => 'Attribute',
            'label_plural' => 'Attributes',
        ],
        'order' => [
            'label' => 'Order',
            'label_plural' => 'Orders',
        ],
        'coupon' => [
            'label' => 'Coupon',
            'label_plural' => 'Coupons',
            'type' => 'Type',
            'value' => 'Value',
            'usage_limit' => 'Usage limit',
            'times_used' => 'Times used',
            'expires_at' => 'Expires at',
        ],
        'deliverymethod' => [
            'label' => 'Delivery Method',
            'label_plural' => 'Delivery Methods',
            'cost' => 'Base cost',
            'free_over' => 'Free over (€)',
        ],
        'paymentmethod' => [
            'label' => 'Payment Method',
            'label_plural' => 'Payment Methods',
            'type' => 'Payment type',
            'public_key' => 'Public key',
            'secret_key' => 'Secret key',
        ],
    ],
];
