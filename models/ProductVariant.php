<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use \Winter\Storm\Database\Traits\Validation;

class ProductVariant extends Model
{
    use Validation;

    public $table = 'tb_catalog_product_variants';

    protected $fillable = ['title', 'sku', 'price', 'stock', 'weight', 'weight_type', 'product_id'];

    protected $appends = ['discount_price','discount_label'];

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

    public function getDiscountPriceAttribute()
    {
        $price = $this->price;
        $product = $this->product;

        if (!$product->discount_type || !$product->discount_value) {
            return null;
        }

        if ($product->discount_type === 'fixed') {
            return max(0, $price - $product->discount_value);
        }

        return round($price * (1 - $product->discount_value/100), 2);
    }

    public function getDiscountLabelAttribute()
    {
        return $this->product->discount_label;
    }
}
