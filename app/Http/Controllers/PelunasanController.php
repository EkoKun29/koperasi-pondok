<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NamaBarang; // Pastikan ini adalah model yang benar untuk nomor nota
use Illuminate\Http\Request;
use App\Models\Pelunasan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PelunasanController extends Controller
{
    public function index()
    {
        // Ambil URL berdasarkan peran pengguna
        $kampusLink = $this->getKampusLinkByRole(Auth::user()->role);
    
        // Jika $kampusLink memiliki URL, ambil data dari API
        $dataKonsumen = [];
        if ($kampusLink) {
            try {
                // Menggunakan Http::get dengan menonaktifkan verifikasi SSL
                $response = Http::withoutVerifying()->get($kampusLink);
    
                // Pastikan response berhasil dan parse hasilnya
                if ($response->successful()) {
                    $rawData = $response->json(); // simpan dulu di variabel $rawData
                    $dataKonsumen = collect($rawData)->map(function ($item) {
                        $tanggal = $item['tanggal'] ?? null;

                        try {
                            // Asumsikan API kirim format d-m-Y
                            \Carbon\Carbon::createFromFormat('d-m-Y', $tanggal);
                            $item['tanggal_valid'] = true;
                        } catch (\Exception $e) {
                            $item['tanggal_valid'] = false;
                        }

                        return $item;
                    })->toArray();

                } else {
                    // Tangani jika response tidak berhasil (misal status bukan 200)
                    return redirect()->back()->with('error', 'Gagal mengambil data dari API');
                }
            } catch (\Throwable $th) {
                // Jika terjadi kesalahan saat request ke API
                return redirect()->back()->with('error', 'Data Barang Gagal disinkronkan! Error: ' . $th->getMessage());
            }
        }

        if (Auth::user()->role == 'admin') {
            $pelunasan = Pelunasan::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }
    
        // Ambil data barang
        $data = NamaBarang::all()->unique();
    
        // Periksa jika data barang kosong
        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
        }
    
        // Kirimkan data ke view
        return view('pelunasan.index', compact('pelunasan', 'data', 'dataKonsumen'));
    }
    

    private function getKampusLinkByRole($role)
    {
        switch ($role) {
            case '1':
                return 'https://script.google.com/macros/s/AKfycbyuuJmZgA4qxIvnX0hQVVNoqhDzAmnYn97d5LwWOdV6UIs3OVZVh8vTz8UAqs1Ct64w/exec';
            case '2':
                return 'https://script.google.com/macros/s/AKfycbzJIMPCCRXfn92CFfu73tCe1Dh4KDaXYxzPdO46uZceWGSaDsKvZj0yZ3ICHeJ2iXQd/exec';
            case '3':
                return 'https://script.google.com/macros/s/AKfycbzQqgEC7Md7ZC9iwY2xvOWZGpB8rAwWpAFbzA3CMFm42IMr7ehMSsyQa1k6HH1qpUPx_A/exec';
            case '4':
                return 'https://script.google.com/macros/s/AKfycbx_XMHS-JYMmlUgonvZtFs6ufw7DfxcDQZoJ4v_AMuefmDf66Ad9I7Uu6B4SWD7IwMKfA/exec';
            default:
                return null; // Kembalikan null jika tidak ada role yang sesuai
        }
    }

    public function show($uuid)
    {
        $pelunasan = Pelunasan::where('uuid', $uuid)->firstOrFail();// Ambil data berdasarkan ID
        return view('pelunasan.show', compact('pelunasan')); // Kirim ke view
    }

    public function getSisaPiutang(Request $request)
{
    $konsumen = $request->query('konsumen');
    $tanggal = $request->query('tanggal');
    $no_nota = $request->query('no_nota');

    $kampusLink = $this->getKampusLinkByRole(Auth::user()->role);

    if ($kampusLink) {
        try {
            $response = Http::withoutVerifying()->get($kampusLink);

            if ($response->successful()) {
                $dataKonsumen = $response->json();

                // Filter data berdasarkan konsumen, tanggal, dan no_nota
                $filteredData = array_filter($dataKonsumen, function ($item) use ($konsumen, $tanggal, $no_nota) {
                    return $item['konsumen'] == urldecode($konsumen) &&
                            $item['tanggal'] == urldecode($tanggal) &&
                            $item['no_nota'] == urldecode($no_nota);
                });

                // Ambil sisa piutang dari data yang sudah difilter
                $sisaPiutang = !empty($filteredData) ? reset($filteredData)['sisa_piutang'] : null;

                return response()->json(['sisa_piutang' => $sisaPiutang]);
            } else {
                return response()->json(['error' => 'Gagal mengambil data dari API.'], 500);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data: ' . $th->getMessage()], 500);
        }
    }

    return response()->json(['error' => 'Link API tidak ditemukan.'], 404);
}

public function store(Request $request)
{
    // Hitung sisa_piutang_akhir
    $sisa_piutang_akhir = $request->sisa_piutang_sebelumnya - ($request->transfer ?? 0) - ($request->tunai ?? 0);

    // Buat data pelunasan
    $pelunasan = new Pelunasan();
    $pelunasan->id_user = Auth::user()->id;
    $pelunasan->no_nota = $this->generateNota();
    $pelunasan->tanggal_penjualan_piutang = Carbon::createFromFormat('d-m-Y', $request->tanggal_penjualan_piutang)->format('Y-m-d');
    $pelunasan->nama_koperasi = 'KAMPUS ' . Auth::user()->role;
    $pelunasan->penyetor = $request->nama_personil;
    $pelunasan->nama_konsumen = $request->nama_konsumen;
    $pelunasan->nota_penjualan_piutang = $request->nota_penjualan_piutang;
    $pelunasan->sisa_piutang_sebelumnya = $request->sisa_piutang_sebelumnya;
    $pelunasan->transfer = $request->transfer;
    $pelunasan->tunai = $request->tunai;
    $pelunasan->bank = $request->bank;
    $pelunasan->sisa_piutang_akhir = $sisa_piutang_akhir;

    // Simpan data
    try {
        $pelunasan->save();
        return redirect()->route('pelunasan.index')->with('success', 'Setoran berhasil ditambahkan');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

public function edit($uuid)
{
    $pelunasan = Pelunasan::where('uuid', $uuid)->firstOrFail();
   return response()->json([
        'penyetor' => $pelunasan->penyetor,
        'nama_konsumen' => $pelunasan->nama_konsumen,
        'nota_penjualan_piutang' => $pelunasan->nota_penjualan_piutang,
        'tanggal_penjualan_piutang' => Carbon::parse($pelunasan->tanggal_penjualan_piutang)->format('Y-m-d'),
        'sisa_piutang_sebelumnya' => $pelunasan->sisa_piutang_sebelumnya, 
        'tunai' => $pelunasan->tunai,
        'transfer' => $pelunasan->transfer,
        'bank' => $pelunasan->bank,
    ]);
}

public function update(Request $request, $uuid)
{
    $request->validate([
        'penyetor' => 'required',
        'nama_konsumen' => 'required',
        'nota_penjualan_piutang' => 'required',
        'tanggal_penjualan_piutang' => 'required|date_format:Y-m-d',
        'tunai' => 'nullable|numeric',
        'transfer' => 'nullable|numeric',
        'bank' => 'nullable|string'
    ]);

    $pelunasan = Pelunasan::where('uuid', $uuid)->firstOrFail();
    $sisa_piutang_akhir = $request->sisa_piutang_sebelumnya - ($request->transfer ?? 0) - ($request->tunai ?? 0);

    // Update pelunasan data
    $pelunasan->id_user = Auth::user()->id;
    $pelunasan->no_nota = $pelunasan->no_nota; 
    $pelunasan->tanggal_penjualan_piutang = Carbon::createFromFormat('Y-m-d', $request->tanggal_penjualan_piutang)->format('Y-m-d');
    $pelunasan->nama_koperasi = 'KAMPUS ' . Auth::user()->role;
    $pelunasan->penyetor = $request->penyetor;
    $pelunasan->nama_konsumen = $request->nama_konsumen;
    $pelunasan->nota_penjualan_piutang = $request->nota_penjualan_piutang;
    $pelunasan->sisa_piutang_sebelumnya = $request->sisa_piutang_sebelumnya;
    $pelunasan->transfer = $request->transfer;
    $pelunasan->tunai = $request->tunai;
    $pelunasan->bank = $request->bank;
    $pelunasan->sisa_piutang_akhir = $sisa_piutang_akhir;

    // Simpan data
    try {
        $pelunasan->save();
        return redirect()->route('pelunasan.index')->with('success', 'Pelunasan berhasil diperbarui');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
    }
}

private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = Pelunasan::where('no_nota', 'like', 'PLK' . $inisial . '%')
                                    ->orderBy('id', 'desc')
                                    ->first();
    
        if ($lastNote) {
            // Ekstrak bagian numerik dari no_nota
            $parts = explode('-', $lastNote->no_nota);
            $numericPart = (int)end($parts);
            $numericPart++; // Increment bagian numerik
        } else {
            $numericPart = 1; // Mulai dari 1 jika tidak ada record sebelumnya
        }
    
        return 'PLK' . $inisial . '-' . $numericPart;
    }


    public function Delete($uuid)
    {
        $pelunasan = Pelunasan::where('uuid', $uuid)->first();
        if ($pelunasan) {
            $pelunasan->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }


}
