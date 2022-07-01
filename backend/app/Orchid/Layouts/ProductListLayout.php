<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $columns = [
            TD::make('name', __('Name'))
                ->sort()
                ->render(function (Product $product) {
                    return Link::make($product->name)
                        ->route('platform.product.edit', $product);
                }),
            TD::make('price', 'Prix')->sort(),
            TD::make('status', 'Statut')->sort()
        ];

        $columns[] = TD::make('', 'Actions')->render(function (Product $product) {
            if (Auth::user()->can('update', $product)) {
                return Link::make(__('Edit'))
                    ->icon('pencil')
                    ->class('btn btn-warning btn-block px-3 py-2')
                    ->route('platform.product.edit', $product->id);
            }
        });

        return $columns;
    }
}
