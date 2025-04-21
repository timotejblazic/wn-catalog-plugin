<?php namespace Tb\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Products extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\RelationController::class,
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
//    public $relationConfig = 'config_relation.yaml';

    protected $requiredPermissions = [
        'tb.catalog.products.manage_all',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Tb.Catalog', 'catalog', 'products');
    }
}
