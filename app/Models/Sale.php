<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'store_id',
        'cashier_id',
        'total_price',
        'payment',
        'change'
    ];

    /* ======================
     |  RELATIONSHIPS
     ====================== */

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
