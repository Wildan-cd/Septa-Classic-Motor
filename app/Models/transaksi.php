<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;
    
    protected $fillable = [
        'id_pelanggan',
        'tgl_transaksi',
        'total_harga',
        'status_pembayaran',
        'ongkir'
    ];
    
    protected $casts = [
        'total_harga' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'tgl_transaksi' => 'date'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'id_pelanggan', 'id');
    }
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_transaksi');
    }

}