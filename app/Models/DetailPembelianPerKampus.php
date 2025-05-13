<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetailPembelianPerKampus extends Model
{
    use HasFactory;
    protected $table = 'detail_pembelian_per_kampus';
    protected $fillable = [
        'uuid_pembelian',
        'nama_barang',
        'harga',
        'qty',
        'satuan',
        'subtotal',
        'uuid',
    ];
    public function pembelianPerKampus()
    {
        return $this->belongsTo(PembelianPerKampus::class, 'uuid_pembelian', 'uuid');
    }

    public static function boot() {
        parent::boot();
        static::creating(function (DetailPembelianPerKampus $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }
}
