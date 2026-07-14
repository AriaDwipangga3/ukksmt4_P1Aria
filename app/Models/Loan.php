<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'tool_id', 'unit_code', 'employee_id', 'status',
        'loan_date', 'due_date', 'purpose', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function unit()
    {
        return $this->belongsTo(ToolUnit::class, 'unit_code', 'code');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Relasi ke violation (satu loan bisa punya satu violation)
    public function violation()
    {
        return $this->hasOne(Violation::class);
    }

    // Helper method untuk cek apakah loan aktif (dipinjam / disetujui)
    public function isActive()
    {
        return in_array($this->status, ['approved', 'borrowed']);
    }

}
