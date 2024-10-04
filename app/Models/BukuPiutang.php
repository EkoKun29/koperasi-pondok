<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuPiutang extends Model
{
    use HasFactory;
    protected $fillable = [
        'konsumen',
        'tanggal',
        'no_nota',
        'sisa_piutang',
    ];
}
