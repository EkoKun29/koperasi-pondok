<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianHutangNonProduksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'id_user',
        'nama_koperasi',
        'nama_supplier',
        'tanggal_jatuh_tempo',
        'total',
        'uuid',
    ];
    
    protected $primaryKey = 'uuid';

    // Jika UUID adalah tipe string
    protected $keyType = 'string';
    
    // Jika UUID tidak increment
    public $incrementing = false;
    public function details()
    {
        return $this->hasMany(DetailHutangNonProduksi::class, 'uuid_hutangnonproduksi', 'uuid');
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (PembelianHutangNonProduksi $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

    protected static function booted()
    {
        static::deleting(function ($pembelianHutangNonProduksi) {
            // Menghapus semua detail yang terkait
            $pembelianHutangNonProduksi->details()->delete();
        });
    }
}
