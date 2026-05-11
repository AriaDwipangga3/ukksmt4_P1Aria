<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'item_type', 'price', 
        'min_credit_score', 'description', 'code_slug', 'photo_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // di app/Models/Tool.php
public function units()
{
    return $this->hasMany(ToolUnit::class);
}

public function availableUnits()
{
    return $this->units()->where('status', 'available');
}
}