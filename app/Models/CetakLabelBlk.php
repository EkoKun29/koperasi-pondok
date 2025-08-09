<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CetakLabelBlk extends Model
{
    use HasFactory;
    protected $table = 'cetak_label_blks';
    protected $fillable = [
        'tanggal',
        'label',
    ];
}
