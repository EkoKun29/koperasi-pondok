<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PelunasanPembelian extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'no_nota',
        'nama_supplier',
        'pelunas',
        'nota_pembelian',
        'tanggal_pembelian',
        'sisa_piutang_sebelumnya',
        'cicilan',
        'transfer',
        'tunai',
        'bank',
        'sisa_piutang_akhir',
        'uuid',
    ];

    protected $primaryKey = 'uuid';

    // Jika UUID adalah tipe string
    protected $keyType = 'string';
    
    // Jika UUID tidak increment
    public $incrementing = false;

    public function User(){
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (PelunasanPembelian $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }
}
