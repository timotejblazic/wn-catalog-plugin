<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class OrderItem extends Model
{
    use Validation;

    public $table = 'tb_catalog_order_items';

    protected $fillable = ['order_id', 'product_variant_id', 'quantity', 'unit_price'];

    public $rules = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'order'   => [
            Order::class,
            'key' => 'order_id'
        ],
        'variant' => [ProductVariant::class,
            'key' => 'product_variant_id'
        ]
    ];
}
