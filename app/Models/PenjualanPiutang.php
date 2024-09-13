<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanPiutang extends Model
{
    use HasFactory;
    public function detailPenjualanPiutang()
    {
        return $this->hasMany(DetailPenjualanPiutang::class);
    }

    public function User(){
        return $this->belongsTo(User::class);
    }
}
