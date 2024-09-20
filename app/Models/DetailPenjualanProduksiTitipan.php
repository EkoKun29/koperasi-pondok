<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualanProduksiTitipan extends Model
{
    use HasFactory;
    protected $fillable = ['uuid_titipan', 'nama_barang', 'harga', 'qty', 'subtotal'];


    public function titipan()
    {
        return $this->belongsTo(PenjualanProduksiTitipan::class, 'uuid_titipan', 'uuid');
    }
}
