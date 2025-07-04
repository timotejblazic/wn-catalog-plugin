<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class DeliveryMethod extends Model
{
    use Validation;

    public $table = 'tb_catalog_delivery_methods';

    protected $fillable = ['name', 'cost', 'free_over_amount'];

    public $rules = [
        'name' => 'required',
        'cost' => 'required',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public $hasMany = [
        'orders' => [
            Order::class,
            'key' => 'delivery_method_id'
        ]
    ];

    public function calculateCost($orderTotalAfterDiscount)
    {
        if ($this->free_over_amount !== null && $this->free_over_amount > 0 && $orderTotalAfterDiscount >= $this->free_over_amount) {
            return 0.0;
        }

        return $this->cost;
    }
}
