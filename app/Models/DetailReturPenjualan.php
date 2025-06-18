<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetailReturPenjualan extends Model
{
    use HasFactory;
    protected $table = 'detail_retur_penjualans';
    protected $fillable = [
        'uuid_retur_penjualan',
        'nama_barang',
        'qty',
        'harga',
        'satuan',
        'subtotal',
        'created_at',
    ];

    public function returPenjualan()
    {
        return $this->belongsTo(ReturPenjualan::class, 'uuid_retur_penjualan', 'uuid');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $model->uuid = (string) Str::uuid();
    });
}
}
