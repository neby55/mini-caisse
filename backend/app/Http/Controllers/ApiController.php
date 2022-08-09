<?php
 
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Send all orders data
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrders()
    {
        return response()->json(Order::all());
    }

    /**
     * Send created orders data
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreatedOrders()
    {
        return response()->json(Order::where('status', OrderStatus::CREATED)->get());
    }

    /**
     * Send paid orders data
     *
     * @return \Illuminate\Http\Response
     */
    public function getPaidOrders()
    {
        return response()->json(Order::where('status', OrderStatus::PAID)->get());
    }

    /**
     * Send order data
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function getOrder(int $orderId)
    {
        $order = Order::find($orderId);

        if (!empty($order)) {
            return response()->json([
                'id' => $order->id,
                'number' => $order->number,
                'amount' => $order->amount,
                'payment_date' => $order->payment_date,
                'status' => $order->status,
                'items' => $order->carts
            ]);
        } else {
            return response('', 404);
        }
    }

    /**
     * Add an order
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addOrder(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|numeric|gt:0',
            'items' => 'required|array'
        ]);

        if ($validated) {
            $orderNumber = (int) $request->input('number');
            // First checking number is not already used
            $activeOrdersWithNumber = Order::where('number', $orderNumber)
                ->whereIn('status', [OrderStatus::CREATED, OrderStatus::PAID])
                ->get();
            // If no active order with same number
            if ($activeOrdersWithNumber->isEmpty()) {
                // creating order
                $order = new Order();
                $order->number = $orderNumber;
                $order->amount = 0;
                // by default, we setup user as "caisse"
                $order->user_id = 4;
                $order->status = OrderStatus::CREATED;
                $order->save();

                // creating cart items
                $items = $request->input('items');
                foreach ($items as $currentItem) {
                    $currentProduct = Product::find($currentItem['id']);

                    if (!empty($currentProduct)) {
                        $currentCart = new Cart();
                        $currentCart->order_id = $order->id;
                        $currentCart->product_id = $currentProduct->id;
                        $currentCart->quantity = $currentItem['qty'];
                        $currentCart->price = $currentProduct->price;
                        $currentCart->save();

                        // Adding to order amount
                        $order->amount += $currentCart->price * $currentCart->quantity;
                    }
                }

                // Amount updated
                $order->save();

                return response()->json([
                    'id' => $order->id,
                    'number' => $order->number,
                    'amount' => $order->amount,
                    'payment_date' => $order->payment_date,
                    'status' => $order->status,
                    'items' => $order->carts
                ], 201);
            } else {
                return response('Order number already in use', 400);
            }
        } else {
            return response('Order data incorrect', 400);
        }
    }

    /**
     * Set order payment date
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function setOrderPayment(int $orderId)
    {
        $order = Order::find($orderId);

        if (!empty($order)) {
            if ($order->status === OrderStatus::CREATED) {
                if (empty($order->payment_date)) {
                    $order->payment_date = \Carbon\Carbon::now()->toDateTimeString();
                    $order->status = OrderStatus::PAID;
                    $order->save();

                    return response('Updated', 200);
                } else {
                    return response('Order already have a payment', 409);
                }
            } else {
                return response('Order status incorrect', 409);
            }
        } else {
            return response('', 404);
        }
    }

    /**
     * Set order completed status
     *
     * @param  int  $order
     * @return \Illuminate\Http\Response
     */
    public function setOrderCompleted(int $orderId)
    {
        $order = Order::find($orderId);

        if (!empty($order)) {
            if ($order->status === OrderStatus::PAID) {
                if (!empty($order->payment_date)) {
                    $order->status = OrderStatus::COMPLETED;
                    $order->save();

                    return response('Updated', 200);
                } else {
                    return response('Order does not have any payment', 409);
                }
            } else {
                return response('Order status incorrect ('. $order->status->value . ' expecting ' . OrderStatus::PAID->value . ')', 409);
            }
        } else {
            return response('', 404);
        }
    }

    /**
     * Send all products data
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts()
    {
        return response()->json(Product::all());
    }

    /**
     * Send products to prepare
     *
     * @return \Illuminate\Http\Response
     */
    public function getPreparationsByOrders()
    {
        // Retrieve all paid orders
        $orders = Order::where('status', OrderStatus::PAID)->with('carts')->with('carts.product')->get();

        return response()->json($orders);
    }

    /**
     * Send products to prepare
     *
     * @return \Illuminate\Http\Response
     */
    public function getPreparationsByProducts()
    {
        // Retrieve all products
        $products = Product::where('status', ProductStatus::ENABLED)->get();

        // Retrieve all paid orders
        $orders = Order::where('status', OrderStatus::PAID)->with('carts')->get();

        $data = [];
        foreach ($products as $currentProduct) {
            $item = $currentProduct->toArray();
            $item['quantity'] = 0;
            $item['orders_number'] = [];

            foreach ($orders as $currentOrder) {
                foreach ($currentOrder->carts as $currentCartLine) {
                    if ($currentCartLine->product_id === $currentProduct->id) {
                        $item['quantity'] += $currentCartLine->quantity;
                        $item['orders_number'][] = $currentOrder->number;
                    }
                }
            }
            $data[] = $item;
        }
        return response()->json($data);
    }
}