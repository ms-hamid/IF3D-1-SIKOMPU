<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prodi::query();

        // Search berdasarkan nama atau kode prodi
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_prodi', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_prodi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan jenjang
        if ($request->has('jenjang') && $request->jenjang != '') {
            $query->where('jenjang', $request->jenjang);
        }

        $prodis = $query->orderBy('nama_prodi', 'asc')->paginate(15);
        
        return view('pages.manajemen-prodi', compact('prodis'));
    }

    public function create()
    {
        return view('pages.manajemen-prodi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodi,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,D4,S1,S2', // ✅ Sudah ada S1 dan S2
        ]);

        Prodi::create($request->all());

        return redirect()->route('prodi.index')
            ->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('pages.edit-prodi', compact('prodi'));
    }

    public function update(Request $request, $id)
    {
        $prodi = Prodi::findOrFail($id);
        
        $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:prodi,kode_prodi,' . $id,
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,D4,S1,S2',
        ]);

        $prodi->update($request->all());

        return redirect()->route('prodi.index')
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('prodi.index')
            ->with('success', 'Program Studi berhasil dihapus.');
    }   
}