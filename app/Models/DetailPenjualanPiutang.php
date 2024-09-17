<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualanPiutang extends Model
{
    use HasFactory;
    protected $fillable = ['uuid_penjualan', 'nama_barang', 'harga', 'qty', 'keterangan', 'subtotal'];


    public function penjualan_piutang()
    {
        return $this->belongsTo(PenjualanPiutang::class);
    }
}
