<?php namespace Tb\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class DeliveryMethods extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    protected $requiredPermissions = [
        'tb.catalog.deliverymethods.manage_all',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Tb.Catalog', 'catalog', 'deliverymethods');
    }
}
