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
        'tanggal_transaksi',
        'total_harga',
        'status_pembayaran',
        'metode_pembayaran',
        'catatan',
        'ongkir'
    ];
    
    protected $casts = [
        'total_harga' => 'decimal:2',
        'ongkir' => 'decimal:2',
        'tanggal_transaksi' => 'date'
    ];
    
    /**
     * Relationship to Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
    
    /**
     * Relationship to DetailTransaksi
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_transaksi');
    }

}