<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaBarang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_barang',
        'nama_personil',
        'nama_penitip',
    ];
}
