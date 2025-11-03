<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'tgl_transaksi',
        'metode_pembayaran',
        'status_pembayaran',
        'ongkir',
        'total_harga'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(pelanggan::class, 'id_pelanggan');
    }

    public function detail()
    {
        return $this->hasMany(detail_transaksi::class, 'id_transaksi');
    }
}

