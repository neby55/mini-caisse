<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Product;

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
        return [
            TD::make('name', 'Nom')
                ->sort()
                ->render(function (Product $product) {
                    return Link::make($product->name)
                        ->route('platform.product.edit', $product);
                }),
            TD::make('price', 'Prix')->sort(),
            TD::make('created_at', 'Crée le'),
            TD::make('updated_at', 'Dernière modification'),
        ];
    }
}
