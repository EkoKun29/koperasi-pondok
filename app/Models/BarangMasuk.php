<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\DetailBarangMasuk;
use App\Models\User;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'barang_masuks';
    protected $fillable = [
        'id_user',
        'tanggal',
        'nota',
        'nama_personil',
        'nama_kampus',
        'masuk_ke',
        'uuid'
    ];
    public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'uuid_barangmasuk', 'uuid');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public static function boot() {
        parent::boot();
        static::creating(function (BarangMasuk $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }
}
