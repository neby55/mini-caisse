<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Order;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Validation\Rules\Enum;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Relation;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class OrderEditScreen extends Screen
{
    /**
     * @var Order
     */
    public $order;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Order $order): iterable
    {
        return [
            'order' => $order
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->order->exists ? __('Edit order') : __('Create order');
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return __('Manage orders');
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
                ->route('platform.order.list'),

            Button::make(__('Delete'))
                ->icon('trash')
                ->method('remove')
                ->canSee(
                    $this->order->exists
                    && $this->order->status === 'created'
                    && Auth::user()->can('delete', $this->order)
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
                Input::make('number')
                    ->title(__('Number'))
                    ->placeholder(__('Order ID'))
                    ->help('ID provided to the customer')
                    ->required()
                    ->value($this->order->exists ? $this->order->number : ''),

                Input::make('amount')
                    ->title(__('Amount'))
                    ->placeholder(__('Order total'))
                    ->help(__('Amount in € and decimal separator is .'))
                    ->mask([
                        'alias' => 'currency',
                        'prefix' => '',
                        'groupSeparator' => ' ',
                        'digitsOptional' => true
                    ])
                    ->required()
                    ->value($this->order->exists ? $this->order->amount : ''),
                
                DateTimer::make('payment_date')
                    ->title(__('Payment date'))
                    ->allowInput()
                    ->format('Y-m-d H:i')
                    ->value($this->order->exists ? $this->order->payment_date : ''),

                Relation::make('user')
                    ->fromModel(User::class, 'name')
                    ->title(__('Payment registered by'))
                    ->value($this->order->exists ? $this->order->user_id : ''),

                Select::make('status')
                    ->title(__('Status'))
                    ->options([
                        'created' => __('Created'),
                        'paid' => __('Paid'),
                        'completed' => __('Completed'),
                        'canceled' => __('Canceled')
                    ])
                    ->required()
                    ->value($this->order->exists ? $this->order->status : ''),
    
                Button::make(__('Save'))
                    ->icon('note')
                    ->method('createOrUpdate')
                    ->class('btn btn-success btn-block')
                    ->canSee($this->order->exists)
            ])
        ];
    }

    /**
     * @param Order $order
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Order $order, Request $request)
    {
        // Update
        if (!empty($order)) {
            $this->authorize('update', $order);

            $validated = $request->validate([
                'number' => 'required|max:255',
                'amount' => 'required|numeric|gt:0',
                'status' => [new Enum(OrderStatus::class)]
            ]);

            if ($validated) {
                $order->fill($request->all())->save();
                Alert::info('La commande a bien été modifié'); // J'en peux plus de traduire :(
            } else {
                Alert::warning('Erreur dans la modification de la commande');
            }
            return redirect()->route('platform.order.edit', $order);
        } else { // Add
            Alert::danger('La création de commande manuelle est interdite dans ce backOffice');

            return redirect()->route('platform.order.list');
        }
    }

    /**
     * @param Order $order
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Order $order)
    {
        // Check Auth
        $this->authorize('delete', $order);

        $order->delete();

        Alert::info('Commande supprimée');

        return redirect()->route('platform.order.list');
    }
}
