<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;

class AttributeItem extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'tb_catalog_attribute_items';

    protected $fillable = ['code', 'name'];

    public $rules = [
        'code' => 'required',
        'name' => 'required',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $belongsTo = [
        'attribute' => Attribute::class,
    ];
}
