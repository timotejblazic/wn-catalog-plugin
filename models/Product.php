<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use \Winter\Storm\Database\Traits\Validation;

class Product extends Model
{
    use Validation;

    public $category;

    public $table = 'tb_catalog_products';

    protected $fillable = ['title', 'slug', 'description', 'base_price', 'pictures'];

    protected $jsonable = ['pictures'];

    public $rules = [
        'title' => 'required',
        'slug'  => 'required|unique:tb_catalog_products,slug',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $hasMany = [
        'variants'   => [ProductVariant::class],
        'productattributes' => [ProductAttribute::class],
    ];

    public $belongsTo = [
        'brand' => [Brand::class],
    ];

    public $belongsToMany = [
        'categories' => [
            Category::class,
            'table' => 'tb_catalog_product_categories',
        ],
    ];
}
