<?php

declare(strict_types=1);

use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\ProductEditScreen;
use App\Orchid\Screens\ProductListScreen;
use App\Orchid\Screens\OrderEditScreen;
use App\Orchid\Screens\OrderListScreen;
use App\Orchid\Screens\CartListScreen;
use App\Orchid\Screens\CartEditScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

//Route::screen('idea', Idea::class, 'platform.screens.idea');
Route::screen('product/{product?}', ProductEditScreen::class)
    ->name('platform.product.edit')
    ->breadcrumbs(function (Trail $trail, Product $product) {
        if ($product->exists) {
            return $trail
                ->parent('platform.index')
                ->push(__('Products'), route('platform.product.list'))
                ->push(__('Product') . ' #' . $product->id, route('platform.product.edit', $product->id));
        }
    });
Route::screen('products', ProductListScreen::class)
    ->name('platform.product.list')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Products'), route('platform.product.list'));
    });
Route::screen('order/{order?}', OrderEditScreen::class)
    ->name('platform.order.edit')
    ->breadcrumbs(function (Trail $trail, Order $order) {
        if ($order->exists()) {
            return $trail
                ->parent('platform.index')
                ->push(__('Orders'), route('platform.order.list'))
                ->push(__('Order'). ' #' . $order->id, route('platform.order.edit', $order->id));
        }
    });
Route::screen('order/{order}/carts', CartListScreen::class)
    ->name('platform.cart.list')
    ->breadcrumbs(function (Trail $trail, Order $order) {
        if ($order->exists()) {
            return $trail
                ->parent('platform.index')
                ->push(__('Orders'), route('platform.order.list'))
                ->push(__('Order'). ' #' . $order->id, route('platform.order.edit', $order->id))
                ->push(__('Cart'), route('platform.cart.list', $order->id));
        }
    });
Route::screen('order/{order}/carts/{cart?}', CartEditScreen::class)
    ->name('platform.cart.edit')
    ->breadcrumbs(function (Trail $trail, int $orderId, Cart $cart) {
        if ($cart->exists) {
            return $trail
                ->parent('platform.index')
                ->push(__('Orders'), route('platform.order.list'))
                ->push(__('Order'). ' #' . $orderId, route('platform.order.edit', $orderId))
                ->push(__('Cart'), route('platform.cart.list', $orderId))
                ->push(__('Cart'). ' #' . $cart->id, route('platform.cart.edit', [$orderId, $cart->id]));
        }
    });
Route::screen('orders', OrderListScreen::class)
    ->name('platform.order.list')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
        ->parent('platform.index')
        ->push(__('Orders'), route('platform.order.list'));
    });