<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailHutangNonProduksi extends Model
{
    use HasFactory;
    protected $fillable = ['uuid_hutangnonproduksi', 'nama_barang', 'harga', 'qty', 'subtotal', 'check_barang'];


    public function pembelianHutangNonProduksi()
    {
        return $this->belongsTo(PembelianHutangNonProduksi::class, 'uuid_hutangnonproduksi', 'uuid');
    }
}
