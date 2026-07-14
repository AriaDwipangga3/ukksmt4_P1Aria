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

    // Cek apakah unit tersedia
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    // Ubah status menjadi dipinjam
    public function setLent()
    {
        $this->status = 'borrowed';
        $this->save();
    }

    // Ubah status menjadi tersedia
    public function setAvailable()
    {
        $this->status = 'available';
        $this->save();
    }
}