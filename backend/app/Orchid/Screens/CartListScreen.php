<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use App\Models\Cart;
use App\Models\Order;
use App\Orchid\Layouts\CartListLayout;
use Illuminate\Support\Facades\Auth;

class CartListScreen extends Screen
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
        $this->order = $order;
        return [
            'carts' => $order->exists ? Cart::where('order_id', $order->id)->filters()->defaultSort('id')->paginate() : [],
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Contenu de la commande #' . $this->order->id;
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
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $this->authorize('viewAny', Cart::class);

        return [
            CartListLayout::class
        ];
    }
}
