<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'price', 'status'];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = ['name', 'price'];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = ['name', 'price'];

}
