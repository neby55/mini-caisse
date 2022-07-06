<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Alert;

class CartEditScreen extends Screen
{
    /**
     * @var Cart
     */
    public $cart;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Cart $cart): iterable
    {
        return [
            'cart' => $cart
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->cart->exists ? __('Edit cart line') : __('Create cart line');
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return __('Manage cart lines');
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
                ->canSee(
                    $this->cart->exists
                    && Auth::user()->can('update', $this->cart->order)
                )
                ->route('platform.cart.list', $this->cart->order),

            Button::make(__('Delete'))
                ->icon('trash')
                ->method('remove')
                ->canSee(
                    $this->cart->exists
                    && Auth::user()->can('delete', $this->cart)
                )
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
        // Forgot those lines, but Feature tests does not
        if ($this->cart->exists) {
            $this->authorize('update', $this->cart);
        } else {
            $this->authorize('create', Cart::class);
        }

        return [
            Layout::rows([

                Relation::make('order_id')
                    ->fromModel(Order::class, 'number')
                    ->title(__('Order'))
                    ->value($this->cart->exists ? $this->cart->order_id : ''),

                Relation::make('product_id')
                        ->fromModel(Product::class, 'name')
                        ->title(__('Product'))
                        ->value($this->cart->exists ? $this->cart->product_id : ''),
                
                Input::make('quantity')
                    ->title(__('Quantity'))
                    ->help('ID provided to the customer')
                    ->required()
                    ->value($this->cart->exists ? $this->cart->quantity : ''),

                Input::make('price')
                    ->title(__('Price'))
                    ->placeholder(__('Price'))
                    ->help(__('Amount in € and decimal separator is .'))
                    ->mask([
                        'alias' => 'currency',
                        'prefix' => '',
                        'groupSeparator' => ' ',
                        'digitsOptional' => true
                    ])
                    ->required()
                    ->value($this->cart->exists ? $this->cart->price : ''),
    
                Button::make(__('Save'))
                    ->icon('note')
                    ->method('createOrUpdate')
                    ->class('btn btn-success btn-block')
                    ->canSee($this->cart->exists)
            ])
        ];
    }

    /**
     * @param Cart $cart
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Cart $cart, Request $request)
    {   
        // Update
        if (!empty($cart)) {
            $this->authorize('update', $cart);

            $validated = $request->validate([
                'order' => 'required|int',
                'product' => 'required|int',
                'quantity' => 'required|int|gt:0',
                'price' => 'required|numeric|gt:0',
            ]);

            if ($validated) {
                $cart->fill($request->all())->save();
                Alert::info('La ligne de panier a bien été modifié');
            } else {
                Alert::warning('Erreur dans la modification de la ligne de panier');
            }
            return redirect()->route('platform.cart.list', $cart->order);
        } else { // Add
            $this->authorize('create', Cart::class);

            $validated = $request->validate([
                'order' => 'required|int',
                'product' => 'required|int',
                'quantity' => 'required|int|gt:0',
                'price' => 'required|numeric|gt:0',
            ]);

            if ($validated) {
                $cart->fill($request->all())->save();
                Alert::info('La ligne de panier a bien été créé');
            } else {
                Alert::warning('Erreur dans la création de la ligne de panier');
            }
            return redirect()->route('platform.order.list');
        }
    }

    /**
     * @param Product $cart
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Product $cart)
    {
        // Check Auth
        $this->authorize('delete', $cart);

        $cart->delete();

        Alert::info('Produit supprimé');

        return redirect()->route('platform.product.list');
    }
}
