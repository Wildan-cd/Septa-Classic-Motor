<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_produk',
        'jenis',
        'harga',
        'stok',
        'keterangan'
    ];
    
    protected $casts = [
        'harga' => 'decimal:2'
    ];
    
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }
    
    public function stok()
    {
        return $this->hasMany(Stok::class, 'id_produk', 'id_produk');
    }
}
