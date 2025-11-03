<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $topProducts = [
            [
                'id' => 1,
                'name' => 'Nama Produk',
                'image' => 'images/products/product1.jpg',
                'price' => 5000,
            ],
            [
                'id' => 2,
                'name' => 'Nama Produk',
                'image' => 'images/products/product2.jpg',
                'price' => 10000
            ],
            [
                'id' => 3,
                'name' => 'Nama Produk',
                'image' => 'images/products/product3.jpg',
                'price' => 50000
            ],
            [
                'id' => 4,
                'name' => 'Nama Produk',
                'image' => 'images/products/product4.jpg',
                'price' => 150000
            ],
        ];

        $categories = [
            [
                'name' => 'Lampu Depan',
                'slug' => 'lampu-depan',
                'image' => 'images/categories/lampu-depan.jpg'
            ],
            [
                'name' => 'Spakbor',
                'slug' => 'spakbor',
                'image' => 'images/categories/spakbor.jpg'
            ],
            [
                'name' => 'Sein',
                'slug' => 'sein',
                'image' => 'images/categories/sein.jpg'
            ],
            [
                'name' => 'Other',
                'slug' => 'other',
                'image' => 'images/categories/other.jpg'
            ],
        ];

        return view('home', compact('topProducts', 'categories'));
    }
}