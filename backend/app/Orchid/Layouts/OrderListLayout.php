<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Order;

class OrderListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('number', __('Number'))
                ->sort()
                ->render(function (Order $order) {
                    return Link::make($order->number)
                        ->route('platform.order.edit', $order);
                }),
            TD::make('amount', __('Total'))->sort(),
            TD::make('payment_date', __('Payment date'))->sort(),
            TD::make('user_id', 'Paiement pris par')->sort()->render(function (Order $order) {
                return $order->user->name;
            }),
            TD::make('status', __('Status'))->sort(),
            TD::make('', 'Actions')->render(function (Order $order) {
                return Link::make(__('Edit'))
                    ->icon('pencil')
                    ->class('btn btn-warning btn-block px-3 py-2')
                    ->route('platform.order.edit', $order->id);
            })
        ];
    }
}
