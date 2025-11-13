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
    
    protected $casts = [
        'tgl_transaksi' => 'date',
        'ongkir' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];
    
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
    
    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_transaksi', 'id_transaksi');
    }
}
