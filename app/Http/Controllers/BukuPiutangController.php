<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\BukuPiutang;
use GuzzleHttp\Client;
use Carbon\Carbon;

class BukuPiutangController extends Controller
{
    public function index()
{
    // Ambil URL berdasarkan peran pengguna
    $url = $this->getKampusLinkByRole(Auth::user()->role);

    // Pastikan URL tidak null
    if (!$url) {
        return redirect()->back()->with('error', 'Link API tidak ditemukan untuk peran ini.');
    }

    // Ambil data dari API
    $barangs = [];

    try {
        $client = new Client();
        $response = $client->request('GET', $url, [
            'verify' => false,
        ]);

        // Decode JSON dari response
        $data = json_decode($response->getBody());

        // Ubah ke koleksi jika berhasil
        if ($data) {
            $barangs = collect($data);
        }
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Gagal mengambil data dari API: ' . $th->getMessage());
    }


    // Kirimkan data ke view
    return view('database.buku_piutang.index', compact('barangs'));
}

public function sync()
{
    // Hapus semua data dari tabel BukuPiutang
    BukuPiutang::truncate();

    // Ambil URL berdasarkan peran pengguna
    $url = $this->getKampusLinkByRole(Auth::user()->role);

    // Pastikan URL tidak null
    if (!$url) {
        return redirect()->back()->with('error', 'Link API tidak ditemukan untuk peran ini.');
    }

    // HIT API
    try {
        $client = new Client();
        
        $response = $client->request('GET', $url, [
            'verify' => false,
        ]);

        // Decode JSON dari response
        $data = json_decode($response->getBody());
        $barangs = collect($data); // Ubah ke koleksi
    } catch (\Throwable $th) {
        return redirect()->back()->with('delete', 'Data Barang Gagal di Syncronize!');
    }

    // INSERT KE DATABASE
    foreach ($barangs as $barang) {
        // Pastikan tanggal tidak kosong
        if (!$barang->tanggal) {
            continue; // Skip jika tanggal kosong
        }

        // CEK APAKAH DATA TELAH ADA
        $itemInBarang = BukuPiutang::where('no_nota', $barang->no_nota)->first();

        if (!$itemInBarang && $barang->no_nota != '') {
            // Validasi format tanggal DD/MM/YYYY
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $barang->tanggal)) {
                try {
                    // Tanggal sudah dalam format DD/MM/YYYY, jadi kita simpan langsung tanpa konversi
                    $tanggal = $barang->tanggal;

                    // Simpan data ke dalam database
                    BukuPiutang::create([
                        'konsumen' => $barang->konsumen,
                        'tanggal' => $tanggal, // Simpan langsung dalam format DD/MM/YYYY
                        'no_nota' => $barang->no_nota,
                        'sisa_piutang' => $barang->sisa_piutang
                    ]);
                } catch (\Exception $e) {
                    // Tangani kesalahan
                    return redirect()->back()->with('error', 'Kesalahan saat menyimpan data untuk no_nota: ' . $barang->no_nota);
                }
            } else {
                // Jika tanggal tidak sesuai format DD/MM/YYYY
                return redirect()->back()->with('error', 'Tanggal tidak valid untuk no_nota: ' . $barang->no_nota . ' dengan tanggal: ' . $barang->tanggal);
            }
        }
    }

    return redirect()->back()->with('success', 'Data Barang Berhasil di Syncronize!');
}


private function getKampusLinkByRole($role)
{
    switch ($role) {
        case '1':
            return 'https://script.google.com/macros/s/AKfycbxaubxopcaIFmXmGoz9fWNcrMJrR-0z7s9-kvBIOJS8T12Df3K63SetEJIz4-y0Umjj/exec';
        case '2':
            return 'https://script.google.com/macros/s/AKfycbyf_qgAGRxQsuASDLmCHIPXP_dpt3c-dCn-x_Op88Ib9XeHGDnPJWIr8pJ1vjl6ow/exec';
        case '3':
            return 'https://script.google.com/macros/s/AKfycbxDCr2UVkjWbGtVR4O20S0zTAdHzBJ91qSWxp_Oaj8NP8kk0E_JVF_8NbBSclLJYv899g/exec';
        case '4':
            return 'https://script.google.com/macros/s/AKfycbw85bgFJw_1FHFVP6KT61_5G_w8AMMcMXx29perD4DhhYrCMq15GMyzDvljLTELfPouqw/exec';
        default:
            return null; // Kembalikan null jika tidak ada role yang sesuai
    }
}


}
