<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function PenjualanPiutang(){
        return $this->hasMany(PenjualanPiutang::class);
    }

    public function PenjualanProduksiTitipan(){
        return $this->hasMany(PenjualanProduksiTitipan::class);
    }

    public function PenjualanNonProduksi(){
        return $this->hasMany(PenjualanNonProduksi::class);
    }
    
    public function BarangTerjual(){
        return $this->hasMany(BarangTerjual::class);
    }

    public function PembelianTitipan(){
        return $this->hasMany(PembelianTitipan::class);
    }

    public function PembelianCash(){ 
    return $this->hasMany(PembelianCash::class); }

    public function PembelianHutangNonProduksi(){
        return $this->hasMany(PembelianHutangNonProduksi::class);
    }

    public function Setoran(){
        return $this->hasMany(Setoran::class);
    }

    public function Pelunasan(){
        return $this->hasMany(Pelunasan::class);
    }

    public function PengajuanPo(){
        return $this->hasMany(PengajuanPo::class);
    }

    public function bukuPiutang(){
        return $this->hasMany(BukuPiutang::class);
    }

    public function pembelianPerKampus(){
        return $this->hasMany(PembelianPerKampus::class);
    }
}
