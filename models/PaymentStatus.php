<?php namespace Tb\Catalog\Models;

use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class PaymentStatus extends Model
{
    use Validation;

    public $table = 'tb_catalog_payment_statuses';

    protected $fillable = ['name', 'code'];

    public $rules = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const FAILED = 'failed';

    public static function findByCode(string $code)
    {
        return self::where('code', $code)->firstOrFail();
    }
}
