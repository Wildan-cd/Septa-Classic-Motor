<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = 'stok';
    protected $primaryKey = 'id_stok';
    public $timestamps = false;
    
    protected $fillable = [
        'id_produk',
        'jumlah_stok',
        'tgl_update'
    ];
    
    protected $casts = [
        'tgl_update' => 'date'
    ];
    
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}