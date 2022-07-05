<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

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

}
