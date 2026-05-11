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

    // App\Models\Loan.php


public function toolUnit()
{
    return $this->belongsTo(ToolUnit::class, 'unit_code', 'code');
}

public function violation()
{
    return $this->hasOne(\App\Models\Violation::class, 'loan_id');
}


}