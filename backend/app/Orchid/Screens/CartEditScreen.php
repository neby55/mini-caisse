<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Illuminate\Support\Facades\Auth;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Relation;

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
}
