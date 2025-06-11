<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasukProduksi extends Model
{
    use HasFactory;
    protected $table = 'barang_masuk_produksis';
    protected $fillable = [
        'id_user',
        'tanggal',
        'nota',
        'nama_personil',
        'masuk_ke',
        'keterangan',
        'created_at',
        'uuid'
    ];
    public function detailBarangMasukProduksi()
    {
        return $this->hasMany(DetailBarangMasukProduksi::class, 'uuid_masukproduksi', 'uuid');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    public static function boot() {
        parent::boot();
        static::creating(function (BarangMasukProduksi $item) {
            $item->uuid = \Illuminate\Support\Str::uuid()->toString();
        });
    }
}
