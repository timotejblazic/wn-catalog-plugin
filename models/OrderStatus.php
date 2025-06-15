<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class OrderStatus extends Model
{
    use Validation;

    public $table = 'tb_catalog_order_statuses';

    protected $fillable = ['name'];

    public $rules = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public const PENDING = 'pending';
    public const PROCESSING = 'processing';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    public $hasMany = [
        'orders' => [
            Order::class,
            'key' => 'status_id'
        ]
    ];

    public static function findByCode(string $code): self
    {
        return self::where('code', $code)->firstOrFail();
    }
}
