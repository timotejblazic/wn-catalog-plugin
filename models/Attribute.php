<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;

class Attribute extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'tb_catalog_attributes';

    protected $fillable = ['name', 'code'];

    public $rules = [
        'name' => 'required',
        'code' => [
            'required',
            'unique:tb_catalog_attributes,code',
        ]
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $hasMany = [
        'items' => AttributeItem::class,
    ];
}
