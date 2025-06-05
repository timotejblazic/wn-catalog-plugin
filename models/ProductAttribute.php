<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use \Winter\Storm\Database\Traits\Validation;

class ProductAttribute extends Model
{
    use Validation;

    public $table = 'tb_catalog_product_attributes';

    protected $fillable = ['product_id', 'attribute_id', 'attribute_item_id', 'value'];

    public $rules = [
        'product_id'        => 'required',
        'attribute_id'      => 'required',
        'attribute_item_id' => 'required',
    ];

    public $timestamps = false;

    public $belongsTo = [
        'product'       => [Product::class],
        'attribute'     => [Attribute::class],
        'attributeItem' => [AttributeItem::class],
    ];

    public function getAttributeIdOptions()
    {
        return Attribute::pluck('name', 'id')->toArray();
    }

    public function getAttributeItemIdOptions()
    {
        $currentAttributeItems = AttributeItem::where('attribute_id', $this->attribute_id)->get();

        return $currentAttributeItems->pluck('name', 'id')->toArray();
    }
}
