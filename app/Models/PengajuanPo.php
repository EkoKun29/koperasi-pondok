<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengajuanPo extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'id_user',
        'nama_pengaju',
        'total',
        'uuid',
        'tanggal',
        'nama_koperasi',
    ];

    protected $primaryKey = 'uuid';

    // Jika UUID adalah tipe string
    protected $keyType = 'string';
    
    // Jika UUID tidak increment
    public $incrementing = false;
    public function details()
    {
        return $this->hasMany(DetailPengajuanPo::class, 'uuid_po', 'uuid');
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (PengajuanPo $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

    protected static function booted()
    {
        static::deleting(function ($pengajuanPO) {
            // Menghapus semua detail yang terkait
            $pengajuanPO->details()->delete();
        });
    }
}
