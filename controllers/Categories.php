<?php namespace Tb\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Categories extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    protected $requiredPermissions = [
        'tb.catalog.categories.manage_all',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Tb.Catalog', 'catalog', 'categories');
    }

    public function formExtendModel($model)
    {
        if(!$model->exists && get('subcategory')) {
            $model->parent_id = get('subcategory');
        }
    }
}
