<?php namespace Tb\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class PaymentMethods extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    protected $requiredPermissions = [
        'tb.catalog.paymentmethods.manage_all',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Tb.Catalog', 'catalog', 'paymentmethods');
    }
}
