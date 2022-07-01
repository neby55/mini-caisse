<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Product;
use App\Enums\ProductStatus;
use Illuminate\Validation\Rules\Enum;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class ProductEditScreen extends Screen
{
    /**
     * @var Product
     */
    public $product;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Product $product): iterable
    {
        return [
            'product' => $product
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->product->exists ? __('Edit product') : __('Create product');
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return __('Manage products');
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Back to list'))
                ->icon('arrow-left')
                ->route('platform.product.list'),

            Button::make(__('Delete'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->product->exists && Auth::user()->can('delete', $this->product))
                ->confirm(__('Do you confirm you want to delete this item ?')),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        if ($this->product->exists) {
            $this->authorize('update', $this->product);
        } else {
            $this->authorize('create', Product::class);
        }

        return [
            Layout::rows([
                Input::make('name')
                    ->title(__('Name'))
                    ->placeholder(__('product name'))
                    ->help('Rensigner un nom court et compréhensible rapidement')
                    ->required()
                    ->value($this->product->exists ? $this->product->name : ''),

                Input::make('price')
                    ->title(__('Price'))
                    ->placeholder(__('product price'))
                    ->help('Le montant est exprimé en € (euros), et le séparateur des décimales est le . (point)')
                    ->mask([
                        'alias' => 'currency',
                        'prefix' => '',
                        'groupSeparator' => ' ',
                        'digitsOptional' => true
                    ])
                    ->required()
                    ->value($this->product->exists ? $this->product->price : ''),

                Select::make('status')
                    ->title(__('Status'))
                    ->options([
                        'created' => __('Created'),
                        'enabled' => __('Enabled'),
                        'disabled' => __('Disabled'),
                    ])
                    ->required()
                    ->value($this->product->exists ? $this->product->status : ''),
    
                Button::make(__('SAve'))
                    ->icon('note')
                    ->method('createOrUpdate')
                    ->class('btn btn-success btn-block')
            ])
        ];
    }

    /**
     * @param Product $product
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Product $product, Request $request)
    {   
        // Update
        if (!empty($product)) {
            $this->authorize('update', $product);

            $validated = $request->validate([
                'name' => 'required|max:255',
                'price' => 'required|numeric|gt:0',
                'status' => [new Enum(ProductStatus::class)]
            ]);

            if ($validated) {
                $product->fill($request->all())->save();
                Alert::info('Le produit a bien été modifié');
            } else {
                Alert::warning('Erreur dans la modification du produit');
            }
            return redirect()->route('platform.product.edit', $product);
        } else { // Add
            $this->authorize('create', Product::class);

            $validated = $request->validate([
                'name' => 'required|unique:products|max:255',
                'price' => 'required|numeric|gt:0',
                'status' => [new Enum(ProductStatus::class)]
            ]);

            if ($validated) {
                $product->fill($request->all())->save();
                Alert::info('Le produit a bien été créé');
            } else {
                Alert::warning('Erreur dans la création du produit');
            }
            return redirect()->route('platform.product.list');
        }
    }

    /**
     * @param Product $product
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Product $product)
    {
        // Check Auth
        $this->authorize('delete', $product);

        $product->delete();

        Alert::info('Produit supprimé');

        return redirect()->route('platform.product.list');
    }
}
