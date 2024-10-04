<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelunasan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'no_nota',
        'nama_konsumen',
        'penyetor',
        'nota_penjualan_piutang',
        'tanggal_penjualan_piutang',
        'sisa_piutang_sebelumnya',
        'transfer',
        'tunai',
        'bank',
        'sisa_piutang_akhir',
        'uuid',
        'nama_koperasi',
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
        static::creating(function (Pelunasan $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

}
