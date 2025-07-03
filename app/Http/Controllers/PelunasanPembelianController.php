<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PelunasanPembelian;
use App\Models\NamaBarang;


class PelunasanPembelianController extends Controller
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
            $pelunasan = PelunasanPembelian::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == 'pembeli'){
            $usersWithRole1 = User::where('role', 'pembeli')->pluck('id');
            $pelunasan = PelunasanPembelian::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }else{
            abort(403, 'Unauthorized action.');
        }
    
        // Ambil data barang
        $data = NamaBarang::all();
    
        // Periksa jika data barang kosong
        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
        }
    
        // Kirimkan data ke view
        return view('pelunasan_pembelian.index', compact('pelunasan', 'data', 'dataKonsumen'));
    }
    

    private function getKampusLinkByRole($role)
    {
        switch ($role) {
            case 'pembeli':
                return 'https://script.google.com/macros/s/AKfycbykO5TodCL2T8a102SfEpjyw4gF7hJeZMTtY8EDMBb7W5mHmJX6wgEKNnMWX3TYCLKt/exec';
                return null; // Kembalikan null jika tidak ada role yang sesuai
        }
    }

    public function show($uuid)
    {
        $pelunasan = PelunasanPembelian::where('uuid', $uuid)->firstOrFail();// Ambil data berdasarkan ID
        return view('pelunasan_pembelian.show', compact('pelunasan')); // Kirim ke view
    }

    public function getSisaPiutang(Request $request)
{
    $supplier = $request->query('supplier');
    $tanggal = $request->query('tanggal');
    $no_nota = $request->query('no_nota');

    $kampusLink = $this->getKampusLinkByRole(Auth::user()->role);

    if ($kampusLink) {
        try {
            $response = Http::withoutVerifying()->get($kampusLink);

            if ($response->successful()) {
                $dataKonsumen = $response->json();

                // Filter data berdasarkan konsumen, tanggal, dan no_nota
                $filteredData = array_filter($dataKonsumen, function ($item) use ($supplier, $tanggal, $no_nota) {
                    return $item['supplier'] == urldecode($supplier) &&
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
    $pelunasan = new PelunasanPembelian();
    $pelunasan->id_user = Auth::user()->id;
    $pelunasan->no_nota = $this->generateNota();
    $pelunasan->tanggal_pembelian = Carbon::createFromFormat('d-m-Y', $request->tanggal_pembelian)->format('Y-m-d');
    $pelunasan->pelunas = $request->nama_personil;
    $pelunasan->nama_supplier = $request->nama_supplier;
    $pelunasan->nota_pembelian = $request->nota_pembelian;
    $pelunasan->sisa_piutang_sebelumnya = $request->sisa_piutang_sebelumnya;
    $pelunasan->transfer = $request->transfer;
    $pelunasan->tunai = $request->tunai;
    $pelunasan->bank = $request->bank;
    $pelunasan->sisa_piutang_akhir = $sisa_piutang_akhir;

    // Simpan data
    try {
        $pelunasan->save();
        return redirect()->route('pelunasan-pembelian.index')->with('success', 'Setoran berhasil ditambahkan');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

public function edit($uuid)
{
    $pelunasan = PelunasanPembelian::where('uuid', $uuid)->firstOrFail();
    $pelunasan->tanggal_pembelian = Carbon::parse($pelunasan->tanggal_pembelian)->format('d-m-Y');
    return response()->json($pelunasan); // Send pelunasan data as JSON
}

public function update(Request $request, $uuid)
{
    // $request->validate([
    //     'pelunas' => 'required',
    //     'nama_supplier' => 'required',
    //     'nota_pembelian' => 'required',
    //     'tanggal_pembelian' => 'required|date_format:Y-m-d',
    //     'tunai' => 'nullable|numeric',
    //     'transfer' => 'nullable|numeric',
    //     'bank' => 'nullable|string'
    // ]);

    $pelunasan = PelunasanPembelian::where('uuid', $uuid)->firstOrFail();
    $sisa_piutang_akhir = $request->sisa_piutang_sebelumnya - ($request->transfer ?? 0) - ($request->tunai ?? 0);

    // Update pelunasan data
    $pelunasan->id_user = Auth::user()->id;
    $pelunasan->no_nota = $pelunasan->no_nota; 
    $pelunasan->tanggal_pembelian = Carbon::createFromFormat('d-m-Y', $request->tanggal_pembelian)->format('Y-m-d');
    $pelunasan->pelunas = $request->nama_personil;
    $pelunasan->nama_supplier = $request->nama_supplier;
    $pelunasan->nota_pembelian = $request->nota_pembelian;
    $pelunasan->sisa_piutang_sebelumnya = $request->sisa_piutang_sebelumnya;
    $pelunasan->transfer = $request->transfer;
    $pelunasan->tunai = $request->tunai;
    $pelunasan->bank = $request->bank;
    $pelunasan->sisa_piutang_akhir = $sisa_piutang_akhir;

    // Simpan data
    try {
        $pelunasan->save();
        return redirect()->route('pelunasan-pembelian.index')->with('success', 'Pelunasan berhasil diperbarui');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
    }
}

private function generateNota()
    {
        // $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PelunasanPembelian::where('no_nota', 'like', 'PLH' . '%')
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
    
        return 'PLH' . '-' . $numericPart;
    }


    public function Delete($uuid)
    {
        $pelunasan = PelunasanPembelian::where('uuid', $uuid)->first();
        if ($pelunasan) {
            $pelunasan->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
