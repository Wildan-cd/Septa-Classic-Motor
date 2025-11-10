<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $timestamps = false;
    
    protected $fillable = [
        'id_transaksi',
        'status_pengiriman',
        'tgl_pengiriman'
    ];
    
    protected $casts = [
        'tgl_pengiriman' => 'date'
    ];
    
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }
}