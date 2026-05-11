<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'loan_id', 'return_date', 'proof_photo', 'condition', 
        'notes', 'fine', 'status', 'processed_by'
    ];

    // Relasi ke tabel loans
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    // Relasi ke user yang memproses
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    
    
}