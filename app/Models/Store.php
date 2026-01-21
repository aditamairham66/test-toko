<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'level',
        'parent_store_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Store::class, 'parent_store_id');
    }

    public function children()
    {
        return $this->hasMany(Store::class, 'parent_store_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
