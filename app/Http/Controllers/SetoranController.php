<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NamaBarang;

class SetoranController extends Controller
{
    
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $setoran = Setoran::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }
        $data = NamaBarang::all();
        return view('setoran.index',compact('setoran','data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }



    public function store(Request $request)
{
    $request->validate([
        'penyetor' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'nominal' => 'required|numeric|min:0',
    ]);

    $setoran = Setoran::create([
        'id_user' => Auth::user()->id,
        'tanggal' => $request->tanggal,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'penyetor' => $request->penyetor,
        'penerima' => $request->penerima,
        'nominal' => $request->nominal,
    ]);

    // Kembalikan UUID untuk redirect
    return redirect()->route('setoran.print', ['uuid' => $setoran->uuid])->with('success', 'Setoran berhasil ditambahkan');
}

public function edit($uuid)
{
    // Retrieve the entry using the UUID
    $setoran = Setoran::where('uuid', $uuid)->firstOrFail(); // Replace with your actual model name and logic if needed

    return response()->json([
        'tanggal' => $setoran->tanggal,
        'penyetor' => $setoran->penyetor,
        'penerima' => $setoran->penerima,
        'nominal' => $setoran->nominal, // If you want to send the personil list back for dropdown (if used elsewhere)
    ]);
}
public function update(Request $request, $uuid)
{
    // Validate the incoming request data
    $request->validate([
        'tanggal' => 'required|date',
        'penyetor' => 'required|string|max:255',
        'penerima' => 'required|string|max:255',
        'nominal' => 'required|numeric',
    ]);

    // Find the entry to update
    $setoran = Setoran::where('uuid', $uuid)->firstOrFail();

    // Update the entry with validated data
    $setoran->update([
        'tanggal' => $request->tanggal, 
        'penyetor' => $request->penyetor,
        'penerima' => $request->penerima,
        'nominal' => $request->nominal,
    ]);
    return redirect()->route('setoran.index')->with('success', 'Data updated successfully!');
}

    public function DeleteSetoran($uuid)
    {
        $setoran = Setoran::where('uuid', $uuid)->first();
        if ($setoran) {
            $setoran->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }



    public function print($uuid)
    {
        $setoran = Setoran::where('uuid', $uuid)->firstOrFail();
        return view('setoran.print', compact('setoran'));
    }
}
