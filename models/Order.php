<?php namespace Tb\Catalog\Models;

use Winter\User\Models\User;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class Order extends Model
{
    use Validation;

    public $table = 'tb_catalog_orders';

    protected $fillable = [
        'basket_id',
        'user_id',
        'status_id',
        'payment_status_id',
        'coupon_id',
        'discount_amount',
        'total_amount',
        'shipping_address',
        'billing_address',
        'delivery_method_id',
        'shipping_cost',
    ];

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
        'user' => [
            User::class,
            'key' => 'user_id'
        ],
        'status' => [
            OrderStatus::class,
            'key' => 'status_id'
        ],
        'paymentStatus' => [
            PaymentStatus::class,
            'key' => 'payment_status_id'
        ],
        'coupon' => [
            Coupon::class,
            'key' => 'coupon_id'
        ],
        'deliveryMethod' => [
            DeliveryMethod::class,
            'key'=>'delivery_method_id'
        ],
    ];

    public $hasMany = [
        'items' => [
            OrderItem::class,
            'key' => 'order_id'
        ]
    ];

    public function getOrderProductTitles()
    {
        return $this->items->map(function ($item) {
            return $item->variant->product->title . ' (' . $item->variant->title . ')';
        });
    }
}
