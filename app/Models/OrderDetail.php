<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class OrderDetail extends Model
{
    use HasApiTokens;
    
    protected $fillable = ['order_id', 'item_id', 'price'];
}