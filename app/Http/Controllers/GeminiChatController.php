<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Produk; 

class GeminiChatController extends Controller
{
    public function chat(Request $request)
    {
        // 1. Validasi API Key
        $apiKey = env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return response()->json(['reply' => 'Error: API Key belum disetting.']);
        }

        // 2. SIAPKAN KONTEKS
        $context = "";
        try {
            $products = Produk::limit(20)->get(); 
            
            if ($products->isNotEmpty()) {
                $context = "DAFTAR STOK BENGKEL SEPTA CLASSIC MOTOR:\n";
                foreach($products as $p) {
                    $stok = ($p->stok ?? 0) > 0 ? "Ready" : "Habis";
                    $nama = $p->nama_produk ?? 'Barang';
                    $harga = number_format($p->harga ?? 0, 0, ',', '.');
                    
                    $context .= "- {$nama} (Harga: Rp {$harga}) [Status: {$stok}]\n";
                }
            } else {
                $context = "Info: Saat ini data produk sedang kosong di sistem.";
            }
        } catch (\Throwable $e) {
            $context = "Info: Gagal terhubung ke database stok. Jawab pertanyaan umum saja.";
            Log::error("Chatbot DB Error: " . $e->getMessage());
        }

        // 3. DEFINISI SYSTEM PROMPT
        $systemInstruction = "
            PERAN: Kamu adalah Customer Service (CS) untuk bengkel 'Septa Classic Motor'.
            GAYA BAHASA: Ramah, membantu, santai tapi sopan (Boleh pakai bahasa gaul sedikit).
            TUGAS: Jawab pertanyaan pelanggan seputar motor dan sparepart.
            
            ATURAN PENTING:
            1. Gunakan data stok di bawah ini untuk menjawab pertanyaan ketersediaan barang.
            2. Jika barang tidak ada di daftar, katakan 'Maaf, barang itu belum tersedia'.
            3. Jika ditanya harga, sebutkan sesuai data.
            4. Jawablah dengan singkat dan padat (maksimal 3-4 kalimat).
            
            DATA STOK TERBARU:
            {$context}
        ";

        // 4. REQUEST KE GEMINI
        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

            $response = Http::withOptions([
                'verify' => false, 
            ])->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $systemInstruction . "\n\nPERTANYAAN USER: " . $request->message]
                        ]
                    ]
                ]
            ]);

            // Cek Error dari Google
            if ($response->failed()) {
                return response()->json(['reply' => "Maaf, server AI sedang sibuk. (Error: " . $response->status() . ")"]);
            }

            // Ambil Jawaban
            $data = $response->json();
            $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya kurang mengerti.';
            
            // Format Teks
            $botReply = str_replace("**", "<b>", $botReply);
            $botReply = str_replace("*", "</b>", $botReply);
            $botReply = nl2br($botReply); // Enter jadi baris baru

            return response()->json(['reply' => $botReply]);

        } catch (\Throwable $e) {
            return response()->json(['reply' => 'System Error: ' . $e->getMessage()]);
        }
    }
}