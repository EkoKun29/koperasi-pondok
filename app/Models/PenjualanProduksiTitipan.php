<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualanProduksiTitipan extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'id_user',
        'nama_koperasi',
        'nama_personil',
        'shift',
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
        return $this->hasMany(DetailPenjualanProduksiTitipan::class, 'uuid_titipan', 'uuid');
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (PenjualanProduksiTitipan $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

    protected static function booted()
    {
        static::deleting(function ($penjualanTitipan) {
            // Menghapus semua detail yang terkait
            $penjualanTitipan->details()->delete();
        });
    }

}
