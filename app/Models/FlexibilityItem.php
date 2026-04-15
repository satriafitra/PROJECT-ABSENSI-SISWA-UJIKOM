<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlexibilityItem extends Model
{
    protected $fillable = [
        'item_name', 
        'category', 
        'description', 
        'point_cost', 
        'stock_limit', 
        'icon', 
        'is_active'
    ];
}