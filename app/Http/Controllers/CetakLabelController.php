<?php

namespace App\Http\Controllers;

use App\Models\CetakLabel;
use App\Models\CetakLabelBlk;
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

    public function indexBlk(){

        session()->flash('syncing', true);
        $this->syncBlk();

        $labels = CetakLabelBlk::get();
        $unique = CetakLabelBlk::select('tanggal')->distinct()->get();
        return view('cetak_label_blk.index', compact('labels', 'unique'));
    }

    private function sync()
    {
        CetakLabel::truncate();

        try {
            $client = new Client();
            $url = "https://script.google.com/macros/s/AKfycbxvIThNMLMlSzihEAwNQFxXBt1eExZWjsWZK8KrQxYjV0_vQW1XAPxMpAVVQrnEVf8r/exec";

            $response = $client->request('GET', $url, ['verify' => false]);
            $data = json_decode($response->getBody());
            $pakets = collect($data);
        } catch (\Throwable $th) {
            return;
        }

        $insertData = [];

        foreach ($pakets as $paket) {
            if (empty($paket->label) || empty($paket->tanggal)) {
                continue;
            }

            try {
                $tanggalFormatted = Carbon::createFromFormat('d-m-Y', $paket->tanggal)->format('Y-m-d');

                $insertData[] = [
                    'tanggal' => $tanggalFormatted,
                    'label'   => $paket->label,
                ];
            } catch (\Exception $e) {
                continue;
            }
        }

        if (!empty($insertData)) {
            CetakLabel::insert($insertData); // Sekali insert, jauh lebih cepat
        }
    }


    public function syncBlk(){
        CetakLabelBlk::truncate();

        try {
            $client = new Client();
            $url = "https://script.google.com/macros/s/AKfycbz7paBTBvFUOB9iFSz4qJOxmeRbdvNo2muRnrHdD-g1xK1zj_jmTnJXAWvEDy6xsvrQ/exec";

            $response = $client->request('GET', $url, ['verify' => false]);
            $data = json_decode($response->getBody());
            $pakets = collect($data);
        } catch (\Throwable $th) {
            return;
        }

        $insertData = [];

        foreach ($pakets as $paket) {
            if (empty($paket->label) || empty($paket->tanggal)) {
                continue;
            }

            try {
                $tanggalFormatted = Carbon::createFromFormat('d-m-Y', $paket->tanggal)->format('Y-m-d');

                $insertData[] = [
                    'tanggal' => $tanggalFormatted,
                    'label'   => $paket->label,
                ];
            } catch (\Exception $e) {
                continue;
            }
        }

        if (!empty($insertData)) {
            CetakLabelBlk::insert($insertData); // Sekali insert, jauh lebih cepat
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

    public function printBlk(Request $request)
    {
        $tanggal = $request->tanggal;

        if (!$tanggal) {
            return redirect()->back()->with('error', 'Tanggal harus dipilih');
        }

        $labels = CetakLabelBlk::whereDate('tanggal', $tanggal)->get();

        if ($labels->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data label untuk tanggal tersebut.');
        }

        return view('cetak_label_blk.print', compact('labels', 'tanggal'));
    }
}
