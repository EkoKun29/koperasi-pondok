<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setoran extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'tanggal',
        'nama_koperasi',
        'penyetor',
        'penerima',
        'nominal',
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
        static::creating(function (Setoran $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }


}
