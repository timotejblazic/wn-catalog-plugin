<?php namespace Tb\Catalog;

use Event;
use Backend;
use Backend\Models\UserRole;
use System\Classes\PluginBase;
use Tb\Catalog\Services\CartManager;

class Plugin extends PluginBase
{
    public $require = ['Winter.User'];

    public function pluginDetails(): array
    {
        return [
            'name'        => 'tb.catalog::lang.plugin.name',
            'description' => 'tb.catalog::lang.plugin.description',
            'author'      => 'Tb',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {
        $this->registerConsoleCommand('tb.catalog.seed', \Tb\Catalog\Console\SeedCommand::class);
    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot(): void
    {
        Event::listen('winter.user.login', function () {
            (new CartManager())->mergeGuestCartIntoUser();
        });
    }

    public function registerComponents(): array
    {
        return [
            \Tb\Catalog\Components\ProductList::class     => 'productList',
            \Tb\Catalog\Components\ProductSingle::class   => 'productSingle',
            \Tb\Catalog\Components\AddToCart::class       => 'addToCart',
            \Tb\Catalog\Components\CartSummary::class     => 'cartSummary',
            \Tb\Catalog\Components\CartPage::class        => 'cartPage',
            \Tb\Catalog\Components\CheckoutPayment::class => 'checkoutPayment',
            \Tb\Catalog\Components\ThankYou::class        => 'thankYou',
            \Tb\Catalog\Components\UserOrders::class      => 'userOrders',
            \Tb\Catalog\Components\OrderDetail::class     => 'orderDetail',
        ];
    }

    public function registerPermissions(): array
    {
        return []; // Remove this line to activate

        return [
            'tb.catalog.some_permission' => [
                'tab'   => 'tb.catalog::lang.plugin.name',
                'label' => 'tb.catalog::lang.permissions.some_permission',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
        ];
    }

    public function registerReportWidgets()
    {
        return [
            \Tb\Catalog\ReportWidgets\DailySales::class => [
                'label'       => 'Daily Sales',
                'context'     => 'dashboard',
                'permissions' => ['tb.catalog.*']
            ],
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'catalog' => [
                'label'       => 'tb.catalog::lang.plugin.name',
                'url'         => Backend::url('tb/catalog/products'),
                'icon'        => 'icon-cubes',
                'permissions' => ['tb.catalog.*'],
                'order'       => 500,
                'sideMenu'    => [
                    'products'        => [
                        'label'       => 'tb.catalog::lang.models.product.label_plural',
                        'url'         => Backend::url('tb/catalog/products'),
                        'icon'        => 'icon-cubes',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 500,
                    ],
                    'brands'          => [
                        'label'       => 'tb.catalog::lang.models.brand.label_plural',
                        'url'         => Backend::url('tb/catalog/brands'),
                        'icon'        => 'icon-copyright',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 600,
                    ],
                    'categories'      => [
                        'label'       => 'tb.catalog::lang.models.category.label_plural',
                        'url'         => Backend::url('tb/catalog/categories'),
                        'icon'        => 'icon-sitemap',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 700,
                    ],
                    'attributes'      => [
                        'label'       => 'tb.catalog::lang.models.attribute.label_plural',
                        'url'         => Backend::url('tb/catalog/attributes'),
                        'icon'        => 'icon-info-circle',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 800,
                    ],
                    'orders'          => [
                        'label'       => 'tb.catalog::lang.models.order.label_plural',
                        'url'         => Backend::url('tb/catalog/orders'),
                        'icon'        => 'icon-cart-arrow-down',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 900,
                    ],
                    'coupons'         => [
                        'label'       => 'tb.catalog::lang.models.coupon.label_plural',
                        'url'         => Backend::url('tb/catalog/coupons'),
                        'icon'        => 'icon-ticket',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 1000,
                    ],
                    'paymentmethods'  => [
                        'label'       => 'tb.catalog::lang.models.paymentmethod.label_plural',
                        'url'         => Backend::url('tb/catalog/paymentmethods'),
                        'icon'        => 'icon-credit-card',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 1100,
                    ],
                    'deliverymethods' => [
                        'label'       => 'tb.catalog::lang.models.deliverymethod.label_plural',
                        'url'         => Backend::url('tb/catalog/deliverymethods'),
                        'icon'        => 'icon-truck',
                        'permissions' => ['tb.catalog.*'],
                        'order'       => 1200,
                    ],
                ]
            ],
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'dd' => function () {
                    dd(...func_get_args());
                },
            ]
        ];
    }
}
