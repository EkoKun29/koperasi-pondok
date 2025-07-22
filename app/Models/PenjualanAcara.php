<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class PenjualanAcara extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'tanggal',
        'nama_personil',
        'shift',
        'total',
        'id_user',
        'uuid'
    ];

    public function details()
    {
        return $this->hasMany(DetailPenjualanAcara::class, 'uuid_penjualan_acara', 'uuid');
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (PenjualanAcara $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

    protected static function booted()
    {
        static::deleting(function ($penjualanAcara) {
            // Menghapus semua detail yang terkait
            $penjualanAcara->details()->delete();
        });
    }
}
