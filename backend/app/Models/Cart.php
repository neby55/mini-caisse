<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Cart extends Model
{
    use HasFactory, SoftDeletes, Filterable, AsSource;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = ['order_id', 'product_id', 'quantity', 'price'];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = ['order_id', 'product_id', 'quantity', 'price'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['order_id', 'product_id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Get the related order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the related product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
