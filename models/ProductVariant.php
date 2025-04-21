<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;

class ProductVariant extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'tb_catalog_product_variants';

    protected $fillable = ['title', 'sku', 'price', 'stock', 'weight', 'weight_type', 'product_id'];

    public $rules = [
        'title' => 'required',
        'price' => 'required',
        'stock' => 'required',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'product' => [Product::class],
    ];
}
