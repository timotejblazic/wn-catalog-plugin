<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use \Winter\Storm\Database\Traits\Validation;

class Product extends Model
{
    use Validation;

    public $category;

    public $table = 'tb_catalog_products';

    protected $fillable = ['title', 'slug', 'description', 'base_price', 'discount_type', 'discount_value', 'pictures', 'brand_id'];

    protected $appends = ['discount_price','discount_label'];

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
        'variants'          => [ProductVariant::class],
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

    public function afterSave()
    {
        $variants = $this->variants();
        if ($variants->count() === 0) {
            $variants->create([
                'title'     => $this->title,
                'price'     => $this->base_price,
                'stock'     => 0,
            ]);
        }
    }

    public function getDiscountTypeOptions()
    {
        return ['fixed' => 'Fixed amount', 'percent' => 'Percent'];
    }

    public function getDiscountPriceAttribute()
    {
        $price = $this->base_price;

        if (!$this->discount_type || !$this->discount_value) {
            return null;
        }

        if ($this->discount_type === 'fixed') {
            return max(0, $price - $this->discount_value);
        }

        return round($price * (1 - $this->discount_value / 100), 2);
    }

    public function getDiscountLabelAttribute()
    {
        if (!$this->discount_type || !$this->discount_value) {
            return null;
        }

        if ($this->discount_type === 'fixed') {
            return number_format($this->discount_value, 2) . '€ OFF';
        }

        return intval($this->discount_value) . '% OFF';
    }
}
