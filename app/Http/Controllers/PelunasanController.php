<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelunasan; // Assuming your model is Pelunasan

class PelunasanController extends Controller
{   
    public function index()
    {
        $data = NamaBarang::all();
        if (Auth::user()->role == 'admin') {
            $pelunasan = Pelunasan::orderBy('uuid', 'desc')->paginate(10);
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->paginate(10);

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $pelunasan = Pelunasan::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->paginate(10);

        }else{
            abort(403, 'Unauthorized action.');
        }
        return view('pelunasan.index',compact('pelunasan', 'data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = Pelunasan::where('no_nota', 'like', 'PlK' . $inisial . '%')
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
    
        return 'PPK' . $inisial . '-' . $numericPart;
    }
    // Function to store Pelunasan data
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'nama_konsumen' => 'required|string|max:255',
            'penyetor' => 'required|string|max:255',

        ]);

        // Create a new Pelunasan record
        Pelunasan::create([
            'no_nota' => $request->input('no_nota'),
            'nama_konsumen' => $request->input('nama_konsumen'),
            'penyetor' => $request->input('penyetor'),
            // Add other fields as necessary
        ]);

        return redirect()->back()->with('success', 'Pelunasan data has been added successfully.');
    }

    // Function to delete Pelunasan data
    public function destroy($id)
    {
        $pelunasan = Pelunasan::find($id);

        if ($pelunasan) {
            $pelunasan->delete();
            return response()->json(['success' => 'Pelunasan record deleted successfully.']);
        } else {
            return response()->json(['error' => 'Pelunasan record not found.'], 404);
        }
    }

    // Function to handle printing Pelunasan data
    public function print($id)
    {
        // Retrieve the specific Pelunasan data
        $pelunasan = Pelunasan::find($id);

        if (!$pelunasan) {
            return redirect()->back()->with('error', 'Pelunasan not found.');
        }

        // Logic for printing could involve returning a view to display a print-ready format
        return view('pelunasan.print', compact('pelunasan'));
    }
}
