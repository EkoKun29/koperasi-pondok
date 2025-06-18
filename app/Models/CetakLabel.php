<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CetakLabel extends Model
{
    use HasFactory;
    protected $table = 'cetak_labels';
    protected $fillable = [
        'tanggal',
        'label',
    ];
}
