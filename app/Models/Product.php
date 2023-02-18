<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $hidden = [
        'deleted_at',
        'description'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }

    public function line_items()
    {
        return $this->hasMany(LineItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'line_items');
    }
}
