<?php namespace Tb\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Brands extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
    ];

    protected $requiredPermissions = [
        'tb.catalog.brands.manage_all',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Tb.Catalog', 'catalog', 'brands');
    }
}
