<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id', 'user_id', 'type', 'score', 'fine',
        'description', 'status', 'settled_by', 'settled_at'
    ];

    protected $casts = [
        'settled_at' => 'datetime',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function settler()
    {
        return $this->belongsTo(User::class, 'settled_by');
    }
}