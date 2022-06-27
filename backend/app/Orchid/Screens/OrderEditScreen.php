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
        return $this->order->exists ? 'Editer la commande' : 'Créer un commande';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Gérer les commandes qui ont été faites via l'application";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Retour à la liste')
                ->icon('arrow-left')
                ->route('platform.order.list'),

            Button::make('Supprimer')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->order->exists && $this->order->status === 'created')
                ->confirm('Êtes-vous certain de vouloir supprimer cette élément ?'),
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
                    ->title('Numéro')
                    ->placeholder('Numéro de la commande')
                    ->help('correspond au numméro sur le ticket fourni au client')
                    ->required()
                    ->value($this->order->exists ? $this->order->number : ''),

                Input::make('amount')
                    ->title('Montant')
                    ->placeholder('Montant total de la commande')
                    ->help('Le montant est exprimé en € (euros), et le séparateur des décimales est le . (point)')
                    ->mask([
                        'alias' => 'currency',
                        'prefix' => '',
                        'groupSeparator' => ' ',
                        'digitsOptional' => true
                    ])
                    ->required()
                    ->value($this->order->exists ? $this->order->amount : ''),
                
                DateTimer::make('payment_date')
                    ->title('Date de paiement')
                    ->allowInput()
                    ->format('Y-m-d H:i')
                    ->value($this->order->exists ? $this->order->payment_date : ''),

                Relation::make('user')
                    ->fromModel(User::class, 'name')
                    ->title('Paiement pris par')
                    ->value($this->order->exists ? $this->order->user_id : ''),

                Select::make('status')
                    ->title('Statut')
                    ->options([
                        'created' => 'Crée',
                        'paid' => 'Payée',
                        'completed' => 'Terminée',
                        'canceled' => 'Annulée'
                    ])
                    ->required()
                    ->value($this->order->exists ? $this->order->status : ''),
    
                Button::make('Valider')
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
            $validated = $request->validate([
                'number' => 'required|max:255',
                'amount' => 'required|numeric|gt:0',
                'status' => [new Enum(OrderStatus::class)]
            ]);

            if ($validated) {
                $order->fill($request->all())->save();
                Alert::info('La commande a bien été modifié');
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
        $order->delete();

        Alert::info('Commande supprimée');

        return redirect()->route('platform.order.list');
    }
}
