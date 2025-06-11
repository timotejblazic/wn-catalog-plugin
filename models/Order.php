<?php namespace Tb\Catalog\Models;

use Winter\User\Models\User;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class Order extends Model
{
    use Validation;

    public $table = 'tb_catalog_orders';

    protected $fillable = ['basket_id', 'user_id', 'status_id', 'total_amount', 'shipping_address', 'billing_address'];

    public $rules = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'basket' => [
            Basket::class,
            'key' => 'basket_id'
        ],
        'user'   => [
            User::class,
            'key' => 'user_id'
        ],
        'status' => [
            OrderStatus::class,
            'key' => 'status_id'
        ]
    ];

    public $hasMany = [
        'items' => [
            OrderItem::class,
            'key' => 'order_id'
        ]
    ];
}
