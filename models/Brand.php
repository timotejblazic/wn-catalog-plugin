<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;

class Brand extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'tb_catalog_brands';

    protected $fillable = ['title', 'slug', 'description'];

    public $rules = [
        'title' => 'required',
        'slug' => 'required|unique:tb_catalog_brands,slug',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $hasMany = [
        'products' => [Product::class],
    ];
}
