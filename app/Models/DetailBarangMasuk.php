<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetailBarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'detail_barang_masuks';
    protected $fillable = [
        'uuid_barangmasuk',
        'nama_barang',
        'qty',
        'satuan',
        'uuid',
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'uuid_barangmasuk', 'uuid');
    }

    public static function boot() {
        parent::boot();
        static::creating(function (DetailBarangMasuk $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }
}
