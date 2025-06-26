<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\DetailReturPenjualan;
use App\Models\User;

class ReturPenjualan extends Model
{
    use HasFactory;
    protected $table = 'retur_penjualans';
    protected $fillable = [
        'id_user',
        'tanggal',
        'nota_retur',
        'nota_barang_masuk',
        'tgl_barang_masuk',
        'nama_kampus',
        'nama_personil',
        'nama_supplier',
    ];

    public function detailReturPenjualans()
    {
        return $this->hasMany(DetailReturPenjualan::class, 'uuid_retur_penjualan', 'uuid');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public static function boot() {
        parent::boot();
        static::creating(function (ReturPenjualan $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }


}
