<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangTerjual extends Model
{
    use HasFactory;
    protected $fillable = ['uuid_terjual', 'nama_barang', 'harga', 'qty', 'keterangan', 'subtotal'];

    public function barangTerjual()
    {
        return $this->belongsTo(barangTerjual::class, 'uuid_terjual', 'uuid');
    }
}
