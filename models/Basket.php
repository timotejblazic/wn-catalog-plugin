<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use \Winter\Storm\Database\Traits\Validation;

class Basket extends Model
{
    use Validation;

    public $table = 'tb_catalog_baskets';

    protected $fillable = ['user_id', 'session_id'];

    public $rules = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $hasMany = [
        'items' => [
            BasketItem::class,
            'key' => 'basket_id'
        ]
    ];
}
