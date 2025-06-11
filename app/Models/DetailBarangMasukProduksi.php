<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangMasukProduksi extends Model
{
    use HasFactory;
    protected $table = 'detail_barang_masuk_produksis';
    protected $fillable = [
        'uuid_masukproduksi',
        'nama_barang',
        'qty',
        'satuan',
        'uuid',
    ];
    public function barangMasukProduksi()
    {
        return $this->belongsTo(BarangMasukProduksi::class, 'uuid_masukproduksi', 'uuid');
    }
    public static function boot() {
        parent::boot();
        static::creating(function (DetailBarangMasukProduksi $item) {
            $item->uuid = \Illuminate\Support\Str::uuid()->toString();
        });
    }
}
