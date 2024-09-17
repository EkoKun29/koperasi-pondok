<?php

namespace App\Http\Controllers;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NamaBarangController extends Controller
{
    public function index(){
        $barangs = NamaBarang::query()->get();

        return view('database.barang.index', compact('barangs'));
    }

    public function sync()
    {
        // Hapus semua data dari tabel NamaBarang
        NamaBarang::truncate();
    
        // HIT API
        try {
            $client = new Client();
    
            $url = "https://script.googleusercontent.com/macros/echo?user_content_key=DFItjr8_8zNRavN-4Uw1imVi5Bu3X5IIqsCXoBr9Ukdbstevr-zFwCB4VUGATbRUdr9CR3QNyTM-3FDy_A8Y5CstKaogLVjWm5_BxDlH2jW0nuo2oDemN9CCS2h10ox_1xSncGQajx_ryfhECjZEnBFDIDxGvGkRhFazt1joEoD_U2XbRfk8FVhF0qFDJeCXBlrbG_zQ9863O0ENQLiKiuv0KRKprsowA-tFy0LDacjF0O0HYaff9Nz9Jw9Md8uu&lib=M8TIx8mSjzavGZGekIQnKSEKYLEpX0pV5";
    
            $response = $client->request('GET', $url, [
                'verify'  => false,
            ]);
    
            // Decode JSON dari response
            $data = json_decode($response->getBody());
            $barangs = collect($data); // Ubah ke koleksi
        } catch (\Throwable $th) {
            return redirect()->back()->with('delete', 'Data Barang Gagal di Syncronize!');
        }
    
        // INSERT KE DATABASE
        foreach ($barangs as $barang) {
            // CEK APAKAH DATA TELAH ADA
            // Menggunakan 'nama_barang' bukan 'barang'
            $itemInBarang = NamaBarang::where('nama_barang', $barang->nama_barang)->first();
    
            // Pastikan nama_barang tidak kosong dan belum ada di database
            if (!$itemInBarang && $barang->nama_barang != '') {
                NamaBarang::create([
                    'nama_barang' => $barang->nama_barang,
                    'nama_personil' => $barang->nama_personil,
                    'nama_penitip' => $barang->nama_penitip
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Data Barang Berhasil di Syncronize!');
    }
    
}
