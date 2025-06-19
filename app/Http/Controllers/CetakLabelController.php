<?php

namespace App\Http\Controllers;

use App\Models\CetakLabel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

class CetakLabelController extends Controller
{
    public function index(){

        session()->flash('syncing', true);
        $this->sync();

        $labels = CetakLabel::get();
        $unique = CetakLabel::select('tanggal')->distinct()->get();
        return view('cetak_label.index', compact('labels', 'unique'));
    }

    private function sync()
{
    CetakLabel::truncate();

    try {
        $client = new Client();
        $url = "https://script.google.com/macros/s/AKfycbz9V5nVOfO22OToQSusVcsCMu9wflDDx3LoD_lOf0ZlGByN_LHb5gKdBbTPALfSWgwl/exec";

        $response = $client->request('GET', $url, ['verify' => false]);
        $data = json_decode($response->getBody());
        $pakets = collect($data);
    } catch (\Throwable $th) {
        // Bisa log error, tapi jangan redirect
        return;
    }

    foreach ($pakets as $paket) {
        if (!$paket->label || !$paket->tanggal) {
            continue;
        }

        $itemInBarang = CetakLabel::where('label', $paket->label)->first();

        if (!$itemInBarang) {
            try {
                $tanggalFormatted = Carbon::createFromFormat('d-m-Y', $paket->tanggal)->format('Y-m-d');

                CetakLabel::create([
                    'tanggal' => $tanggalFormatted,
                    'label' => $paket->label,
                ]);
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}


    public function print(Request $request)
{
    $tanggal = $request->tanggal;

    if (!$tanggal) {
        return redirect()->back()->with('error', 'Tanggal harus dipilih');
    }

    $labels = CetakLabel::whereDate('tanggal', $tanggal)->get();

    if ($labels->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada data label untuk tanggal tersebut.');
    }

    return view('cetak_label.print', compact('labels', 'tanggal'));
}

}
