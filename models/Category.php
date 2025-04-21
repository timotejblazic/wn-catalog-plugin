<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\NestedTree;
use Winter\Storm\Database\Traits\Validation;

class Category extends Model
{
    use Validation;
    use NestedTree;

    public $table = 'tb_catalog_categories';

    protected $fillable = ['title', 'slug', 'description', 'parent_id'];

    public $rules = [
        'title' => 'required',
        'slug' => 'required|unique:tb_catalog_categories,slug',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsToMany = [
        'products' => [
            Product::class,
            'table' => 'tb_catalog_product_categories',
        ]
    ];
}
