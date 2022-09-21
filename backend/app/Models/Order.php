<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\Enums\OrderStatus;

class Order extends Model
{
    use HasFactory, SoftDeletes, Filterable, AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['number', 'amount', 'user_id', 'payment_date', 'payment_mode', 'firstname', 'lastname', 'email', 'status'];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = ['number', 'amount', 'user_id', 'payment_date', 'payment_mode', 'firstname', 'lastname', 'email'];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = ['number', 'amount', 'user_id', 'payment_date', 'payment_mode', 'firstname', 'lastname', 'email', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['user_id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_mode' => \App\Enums\PaymentMode::class,
        'status' => \App\Enums\OrderStatus::class,
    ];

    /**
     * Get the user related to order payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Related carts
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Check if an order number is already in use
     * 
     * @param int $orderNumber
     * @return boolean
     */
    public static function isNumberAvailable(int $orderNumber): bool
    {
        return self::where('number', $orderNumber)
            ->whereIn('status', [OrderStatus::CREATED, OrderStatus::PAID])
            ->doesntExist();
    }

    /**
     * Create an order with default data
     * 
     * @param int $orderNumber
     * @return Order
     */
    public static function createDefaultOrder(int $orderNumber): Order
    {
        // creating order
        $order = new Order();
        $order->number = $orderNumber;
        $order->amount = 0;
        // by default, we setup user as "caisse"
        $order->user_id = 4;
        $order->status = OrderStatus::CREATED;
        $order->save();

        return $order;
    }

    /**
     * Create a cart item in this order
     * 
     * @param Product $product
     * @param int $quantity
     * @return Order
     */
    public function addCart(Product $product, int $quantity): bool
    {
        $cart = new Cart();
        $cart->order_id = $this->id;
        $cart->product_id = $product->id;
        $cart->quantity = $quantity;
        $cart->price = $product->price;
        return $cart->save();
    }

    /**
     * Sum up all cart items amounts
     * 
     * @return boolean
     */
    public function sumCartAmounts(): bool
    {
        $amount = 0;
        foreach ($this->carts as $currentCart) {
            $amount += $currentCart->price * $currentCart->quantity;
        }
        $this->amount = $amount;
        return $this->save();
    }

    /**
     * Check if order payment can be set
     * 
     * @return boolean
     */
    public function canSetPayment(): bool
    {
        return $this->status === OrderStatus::CREATED;
    }

    /**
     * Set payment infos
     * 
     * @return boolean
     */
    public function setPayment(): bool
    {
        $this->payment_date = \Carbon\Carbon::now()->toDateTimeString();
        $this->status = OrderStatus::PAID;
        return $this->save();
    }

    /**
     * Check if order completion can be defined
     * 
     * @return boolean
     */
    public function canSetCompleted(): bool
    {
        return $this->status === OrderStatus::PAID;
    }

    /**
     * Define order as completed
     * 
     * @return boolean
     */
    public function setCompleted(): bool
    {
        $this->status = OrderStatus::COMPLETED;
        return $this->save();
    }

}
