<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Models\Product;
use App\Orchid\Layouts\ProductListLayout;
use Illuminate\Support\Facades\Auth;

class ProductListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'products' => Product::filters()->defaultSort('id')->paginate()
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Liste des produits';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Gérer les produits qui pourront être commandés via l'application";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Create'))
                ->icon('pencil')
                ->canSee(Auth::user()->can('create', Product::class))
                ->route('platform.product.edit')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $this->authorize('viewAny', Product::class);

        return [
            ProductListLayout::class
        ];
    }
}
