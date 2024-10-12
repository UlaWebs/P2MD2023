<?php

namespace App\Http\Controllers;

use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Errormsg;

class KategoriAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = KategoriAset::all();
        return view('kategoriaset.index', ['data' => $data, 'title' => "Kategori Aset"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategoriaset.create', ['title' => 'Aset']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori_aset' => 'required',
            'umur_ekonomis' => 'required',
            'metode_penyusutan' => 'required',
        ]);

        $store = KategoriAset::create(['nama_kategori_aset' => $request->nama_kategori_aset, 'umur_ekonomis' => $request->umur_ekonomis, 'metode_penyusutan' => $request->metode_penyusutan, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('kategori-aset')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriAset $kategoriAset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_kategori_aset)
    {
        $get = DB::table('kategori_aset')->where('id_kategori_aset', $id_kategori_aset)->get();
        foreach ($get as $p) {
            $id_kategori_aset = $p->id_kategori_aset;
            $nama_kategori_aset = $p->nama_kategori_aset;
            $umur_ekonomis = $p->umur_ekonomis;
            $metode_penyusutan = $p->metode_penyusutan;
        }
        return view('kategoriaset.edit', ['id_kategori_aset' => $id_kategori_aset, 'umur_ekonomis' => $umur_ekonomis, 'nama_kategori_aset' => $nama_kategori_aset, 'metode_penyusutan' => $metode_penyusutan, 'title' => 'Kategori Aset']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriAset $kategoriAset)
    {
        $validated = $request->validate([
            'nama_kategori_aset' => 'required|unique:kategori_aset,nama_kategori_aset,' . $request->id_kategori_aset . ',id_kategori_aset',
            'umur_ekonomis' => 'required',
            'metode_penyusutan' => 'required',
        ]);

        $store = KategoriAset::where('id_kategori_aset', $request->id_kategori_aset)
            ->update([
                'nama_kategori_aset' => $request->nama_kategori_aset,
                'umur_ekonomis' => $request->umur_ekonomis,
                'metode_penyusutan' => $request->metode_penyusutan,
                'user_id_updated' => Auth::user()->id
            ]);
        return redirect()->route('kategori-aset')->with('success', 'Data Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_kategori_aset)
    {
        try {
            $kategori = KategoriAset::findOrFail($id_kategori_aset);
            $kategori->delete();
            return redirect()->route('kategori-aset')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('kategori-aset')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
}
