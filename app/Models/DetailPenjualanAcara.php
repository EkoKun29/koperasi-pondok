<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetailPenjualanAcara extends Model
{
    use HasFactory;
    protected $primaryKey = 'uuid';   // <- Tambahkan ini

    public $incrementing = false;     // <- Karena UUID bukan auto increment
    protected $keyType = 'string';
    protected $fillable = [
        'nama_barang',
        'harga',
        'qty',
        'keterangan',
        'subtotal',
        'uuid_penjualan_acara',
        'uuid',
    ];

    public function penjualanAcara()
    {
        return $this->belongsTo(PenjualanAcara::class, 'uuid_penjualan_acara', 'uuid');
    }

    public static function boot() {
        parent::boot();
        static::creating(function (DetailPenjualanAcara $item) {
            $item->uuid = Str::uuid()->toString();
        });
    }

    protected static function booted()
{
    static::deleting(function ($detailPenjualanAcara) {
        $penjualanAcara = $detailPenjualanAcara->penjualanAcara;

        // Hitung jumlah detail yang tersisa selain yang akan dihapus
        $sisaDetail = $penjualanAcara->details()
                        ->where('id', '!=', $detailPenjualanAcara->id)
                        ->count();

        // Jika tidak ada detail lain, hapus induk
        if ($sisaDetail == 0) {
            $penjualanAcara->delete();
        }
    });
}

}
