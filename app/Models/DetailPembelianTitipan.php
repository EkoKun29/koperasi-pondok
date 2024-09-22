<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPembelianTitipan extends Model
{
    use HasFactory;
    protected $fillable = ['uuid_pembeliantitipan', 'nama_barang', 'harga', 'qty', 'sisa_siang', 'sisa_sore', 'sisa_malam','sisa_akhir', 'subtotal', 'subtotal_sisa'];


    public function pembelianTitipan()
    {
        return $this->belongsTo(PembelianTitipan::class, 'uuid_pembeliantitipan', 'uuid');
    }
}
