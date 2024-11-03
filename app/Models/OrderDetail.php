<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasApiTokens;
    
    protected $fillable = ['order_id', 'item_id', 'price', 'qty'];

    /**
     * Get the user that owns the OrderDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}