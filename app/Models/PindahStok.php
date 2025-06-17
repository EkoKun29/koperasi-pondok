<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PindahStok extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_surat',
        'yang_memindah',
        'dari',
        'ke',
        'tanggal',
    ];
}
