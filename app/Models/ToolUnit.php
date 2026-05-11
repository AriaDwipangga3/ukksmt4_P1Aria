<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolUnit extends Model
{
    use HasFactory;
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'tool_id', 'status', 'notes'];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'unit_code', 'code');
    }
}