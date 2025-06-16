<?php namespace Tb\Catalog\Models;

use Tb\Catalog\Classes\AbstractPaymentGateway;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Traits\Validation;

class PaymentMethod extends Model
{
    use Validation;

    public $table = 'tb_catalog_payment_methods';

    protected $fillable = ['type', 'name', 'public_key', 'secret_key'];

    public $rules = [
        'type' => 'required',
        'name' => 'required',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public static function getTypeOptions()
    {
        return AbstractPaymentGateway::paymentMethods();
    }
}
