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
            $order->load('carts');
            return response()->json($order);
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
            if (Order::isNumberAvailable($orderNumber)) {
                $order = Order::createDefaultOrder($orderNumber);

                // creating cart items
                $items = $request->input('items');
                foreach ($items as $currentItem) {
                    $currentProduct = Product::find($currentItem['id']);

                    if (!empty($currentProduct)) {
                        $order->addCart($currentProduct, $currentItem['qty']);
                    }
                }

                // Amount updated
                $order->sumCartAmounts();

                return response()->json($order, 201);
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
            if ($order->canSetPayment()) {
                if (empty($order->payment_date)) {
                    $order->setPayment();

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
            if ($order->canSetCompleted()) {
                if (!empty($order->payment_date)) {
                    $order->setCompleted();

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