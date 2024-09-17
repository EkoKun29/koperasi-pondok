<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PenjualanPiutang extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_nota',
        'id_user',
        'nama_pembeli',
        'nama_koperasi',
        'nama_personil',
        'shift',
        'total',
    ];
    
    public function detailPenjualanPiutang()
    {
        return $this->hasMany(DetailPenjualanPiutang::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (PenjualanPiutang $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }
}
