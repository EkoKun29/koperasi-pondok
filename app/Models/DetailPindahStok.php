<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPindahStok extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_pindah_stok',
        'produk',
        'qty',
        'satuan',
        'subtotal',
        'keterangan_produksi',
    ];
}
