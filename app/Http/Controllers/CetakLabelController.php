<?php

namespace App\Http\Controllers;

use App\Models\CetakLabel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class CetakLabelController extends Controller
{
    public function index(){
        $labels = CetakLabel::get();
        return view('cetak_label.index', compact('labels'));
    }

    public function sync(){

        CetakLabel::truncate();

        // HIT API
        try {
            $client = new Client();

            $url = "https://script.google.com/macros/s/AKfycbz9V5nVOfO22OToQSusVcsCMu9wflDDx3LoD_lOf0ZlGByN_LHb5gKdBbTPALfSWgwl/exec";

            $response = $client->request('GET', $url, [
                'verify'  => false,
            ]);

            // Decode JSON dari response
            $data = json_decode($response->getBody());
            $pakets = collect($data); // Ubah ke koleksi
        } catch (\Throwable $th) {
            return redirect()->back()->with('delete', 'Data Barang Gagal di Syncronize!');
        }

        // INSERT KE DATABASE
        foreach ($pakets as $paket) {
            // Lewati jika data kosong atau tidak sesuai
            if (!$paket->label || !$paket->tanggal) {
                continue;
            }

            $itemInBarang = CetakLabel::where('label', $paket->label)->first();

            if (!$itemInBarang) {
                try {
                    // Konversi tanggal dari d-m-Y ke Y-m-d
                    $tanggalFormatted = Carbon::createFromFormat('d-m-Y', $paket->tanggal)->format('Y-m-d');

                    CetakLabel::create([
                        'tanggal' => $tanggalFormatted,
                        'label' => $paket->label,
                    ]);
                } catch (\Exception $e) {
                    // Lewati jika tanggal tidak valid
                    continue;
                }
            }
        }        
        return redirect()->back()->with('success', 'Data Barang Berhasil di Syncronize!');

    }
}
