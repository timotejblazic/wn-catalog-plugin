<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class DeliveryMethod extends Model
{
    use Validation;

    public $table = 'tb_catalog_delivery_methods';

    protected $fillable = ['name', 'cost', 'min_weight', 'free_over_amount'];

    public $rules = [];

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
        if ($this->free_over_amount !== null && $orderTotalAfterDiscount >= $this->free_over_amount) {
            return 0.0;
        }

        return $this->cost;
    }
}
