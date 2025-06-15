<?php namespace Tb\Catalog\Models;

use Illuminate\Support\Carbon;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class Coupon extends Model
{
    use Validation;

    public $table = 'tb_catalog_coupons';

    protected $fillable = ['code', 'type', 'value', 'usage_limit', 'times_used', 'expires_at'];

    public $rules = [];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function getTypeOptions()
    {
        return ['fixed' => 'Fixed amount', 'percent' => 'Percentage'];
    }

    public function isValid()
    {
        if ($this->expires_at && Carbon::now()->gt(new Carbon($this->expires_at))) {
            return false;
        }

        if ($this->usage_limit !== null && $this->times_used >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function markUsed()
    {
        $this->increment('times_used');
    }
}
