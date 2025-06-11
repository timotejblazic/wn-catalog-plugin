<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use \Winter\Storm\Database\Traits\Validation;

class BasketItem extends Model
{
    use Validation;

    public $table = 'tb_catalog_basket_items';

    protected $fillable = ['basket_id', 'product_variant_id', 'quantity', 'unit_price'];

    public $rules = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'basket'  => [
            Basket::class,
            'key' => 'basket_id'
        ],
        'variant' => [
            ProductVariant::class,
            'key' => 'product_variant_id'
        ]
    ];
}
