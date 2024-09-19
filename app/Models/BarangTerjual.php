<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BarangTerjual extends Model
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
        return $this->hasMany(DetailBarangTerjual::class, 'uuid_terjual', 'uuid');
    }

    public function User(){
        return $this->belongsTo(User::class);
    }

    public static function boot() {
        parent::boot();
        static::creating(function (BarangTerjual $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

    protected static function booted()
    {
        static::deleting(function ($barangTerjual) {
            // Menghapus semua detail yang terkait
            $barangTerjual->details()->delete();
        });
    }
}
