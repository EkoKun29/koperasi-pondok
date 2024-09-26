<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelianCash extends Model
{
    use HasFactory;

    protected $fillable = ['uuid_cash', 'nama_barang', 'harga', 'qty', 'cek_barang', 'subtotal','keterangan'];


    public function pembelianCash()
    {
        return $this->belongsTo(PembelianCash::class, 'uuid_cash', 'uuid');
    }
}
