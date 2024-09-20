<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailNonProduksi extends Model
{
    use HasFactory;
    protected $fillable = ['uuid_nonproduksi', 'nama_barang', 'harga', 'qty', 'keterangan', 'subtotal'];


    public function penjualanNonProduksi()
    {
        return $this->belongsTo(PenjualanNonProduksi::class, 'uuid_nonproduksi', 'uuid');
    }
}
