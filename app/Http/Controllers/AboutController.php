<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $aboutData = [
            'title' => 'Your Reliable Partner for Quality Classic Motorcycle Parts and Restoration',
            'description' => [
                'Didirikan pada tahun 2015, Septa Classic Motor berawal dari kecintaan terhadap motor klasik dan semangat untuk menjaga warisan otomotif tetap hidup di jalanan. Kami memulai dari bengkel kecil yang fokus pada perawatan dan restorasi motor lawas, hingga kini berkembang menjadi penyedia suku cadang dan layanan perbaikan yang dipercaya banyak penggemar motor klasik.',
                
                'Selama bertahun-tahun, kami terus berkomitmen memberikan produk berkualitas, pelayanan yang jujur, serta hasil kerja yang memuaskan. Setiap motor yang kami tangani memiliki cerita dan kami hadir untuk membantu pemiliknya menjaga nilai dan keasliannya.',
                
                'Bagi kami, Septa Classic Motor bukan sekadar bengkel, tapi rumah bagi para pencinta motor klasik yang ingin mempertahankan pesona masa lalu dengan kualitas masa kini.'
            ]
        ];

        $services = [
            [
                'title' => 'Spare Part Store',
                'description' => 'Tersedia berbagai suku cadang dan aksesori motor klasik dengan kualitas terbaik.',
                'type' => 'black'
            ],
            [
                'title' => 'Installation Service',
                'description' => 'Layanan pemasangan profesional untuk memastikan performa motor tetap optimal.',
                'type' => 'gray'
            ],
            [
                'title' => 'Motorcycle Maintenance',
                'description' => 'Perawatan rutin dan perbaikan ringan untuk menjaga kondisi motor tetap prima.',
                'type' => 'gray'
            ],
            [
                'title' => 'Consultation & Recommendations',
                'description' => 'Bantuan dalam memilih suku cadang dan layanan sesuai kebutuhan motormu.',
                'type' => 'black'
            ]
        ];

        $contact = [
            'phone' => '+6282234322320',
            'email' => 'kwbmotorclassic@gmail.com',
            'address' => 'Jl. Bukit Berbunga No. 115, Sidomulyo, Kec. Batu, Kota Batu, Jawa Timur',
            'coordinates' => [
                'lat' => -7.8753,
                'lng' => 112.5273
            ]
        ];

        return view('about', compact('aboutData', 'services', 'contact'));
    }
}