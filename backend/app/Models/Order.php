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
    protected $fillable = ['number', 'amount', 'user_id', 'payment_date', 'status'];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = ['number', 'amount', 'user_id', 'payment_date'];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = ['number', 'amount', 'user_id', 'payment_date', 'status'];

    /**
     * List of allowed status
     * 
     * @var array
     */
    public static $status = ['created', 'paid', 'completed', 'canceled'];

    /**
     * Get the user related to order payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
