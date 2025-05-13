<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PembelianPerKampus extends Model
{
    use HasFactory;
    protected $fillable = [
    'id_user',
    'tanggal',
    'nota',
    'nama_supplier',
    'pindah_barang',
    'total',
    'uuid',
];

    public function detailPembelianPerKampus()
    {
        return $this->hasMany(DetailPembelianPerKampus::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (PembelianPerKampus $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
