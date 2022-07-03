<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Cart;
use App\Models\Order;

class CartListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'carts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('order_id', __('Order'))
                ->sort()
                ->render(function (Cart $cart) {
                    return Link::make($cart->order->number)
                        ->route('platform.order.edit', $cart->order);
                }),
            TD::make('product_id', __('Product'))
                ->sort()
                ->render(function (Cart $cart) {
                    return Link::make($cart->product->name)
                        ->route('platform.product.edit', $cart->product);
                }),
            TD::make('quantity', __('Quantity'))->sort(),
            TD::make('price', __('Price'))->sort(),
            TD::make('', 'Actions')->render(function (Cart $cart) {
                return Link::make(__('Edit'))
                    ->icon('pencil')
                    ->class('btn btn-warning btn-block px-3 py-2')
                    ->route('platform.cart.edit', [$cart->order, $cart]);
            })
        ];
    }
}
