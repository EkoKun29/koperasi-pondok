<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuanPo extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = ['uuid_po', 'nama_barang', 'harga', 'qty', 'keterangan', 'total'];


    public function pengajuanPO()
    {
        return $this->belongsTo(PengajuanPo::class, 'uuid_po', 'uuid');
    }
}
