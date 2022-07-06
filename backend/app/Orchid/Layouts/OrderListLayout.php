<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\TD;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

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
            TD::make('payment_mode', __('Payment mode'))
                ->sort()
                ->render(function (Order $order) {
                    return !empty($order->payment_mode) ? $order->payment_mode->label() : '-';
                }),
            TD::make('user_id', 'Paiement pris par')->sort()->render(function (Order $order) {
                return $order->user->name;
            }),
            TD::make('firstname', __('Firstname'))->sort(),
            TD::make('lastname', __('Lastname'))->sort(),
            TD::make('status', __('Status'))
                ->sort()
                ->render(function (Order $order) {
                    return $order->status->label();
                }),
            TD::make('', 'Actions')->render(function (Order $order) {
                $columns = [];
                if (Auth::user()->can('update', $order)) {
                    $columns[] = Link::make(__('Edit'))
                        ->icon('pencil')
                        ->class('btn btn-warning btn-block')
                        ->route('platform.order.edit', $order->id);
                }
                if (Auth::user()->can('viewAny', $order)) {
                    $columns[] = Link::make(__('Cart'))
                        ->icon('eye')
                        ->class('btn btn-info btn-block')
                        ->route('platform.cart.edit', $order->id);
                }
                return Group::make($columns);
            })
        ];
    }
}
