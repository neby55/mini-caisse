<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Dashboard;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard)
    {
        // Products permissions
        $permissions = ItemPermission::group('Products')
            ->addPermission('products.add', 'Add products')
            ->addPermission('products.edit', 'Update products')
            ->addPermission('products.delete', 'Delete products')
            ->addPermission('products.view', 'List products');

        $dashboard->registerPermissions($permissions);

        // Orders permissions
        $permissions = ItemPermission::group('Orders')
            ->addPermission('orders.add', 'Add orders')
            ->addPermission('orders.edit', 'Update orders')
            ->addPermission('orders.delete', 'Delete orders')
            ->addPermission('orders.view', 'List orders');

        $dashboard->registerPermissions($permissions);
    }
}
