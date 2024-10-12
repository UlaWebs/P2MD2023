<?php

namespace App\Http\Controllers;

use App\Models\Pekerja;
use App\Models\Akun;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PekerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pekerja as a')
            ->leftJoin('akun as b', 'a.id_akun_biaya_upah', '=', 'b.id_akun')
            ->leftJoin('akun as c', 'a.id_akun_utang_upah', '=', 'c.id_akun')
            ->get();
        return view('pekerja/index', ['data' => $data, 'title' => "Jenis Upah"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Akun::all();
        return view('pekerja.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pekerja' => 'required'
        ]);

        $store = Pekerja::create(['nama_pekerja' => $request->nama_pekerja, 'id_akun_biaya_upah' => $request->id_akun_biaya_upah, 'id_akun_utang_upah' => $request->id_akun_utang_upah, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('pekerja')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(pekerja $pekerja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_pekerja)
    {
        $data = Akun::all();
        $get = DB::table('pekerja')->where('id_pekerja', $id_pekerja)->get();
        foreach ($get as $p) {
            $id_pekerja = $p->id_pekerja;
            $nama_pekerja = $p->nama_pekerja;
            $alamat_pekerja = $p->alamat_pekerja;
            $no_telp_pekerja = $p->no_telp_pekerja;
            $id_akun_biaya_upah = $p->id_akun_biaya_upah;
            $id_akun_utang_upah = $p->id_akun_utang_upah;
        }
        return view('pekerja.edit', ['data' => $data, 'id_pekerja' => $id_pekerja, 'nama_pekerja' => $nama_pekerja, 'alamat_pekerja' => $alamat_pekerja, 'no_telp_pekerja' => $no_telp_pekerja,  'id_akun_biaya_upah' => $id_akun_biaya_upah, 'id_akun_utang_upah' => $id_akun_utang_upah]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pekerja $pekerja)
    {
        $validated = $request->validate([
            'nama_pekerja' => 'required'
        ]);

        $store = Pekerja::where('id_pekerja', $request->id_pekerja)->update(['nama_pekerja' => $request->nama_pekerja, 'alamat_pekerja' => $request->alamat_pekerja, 'no_telp_pekerja' => $request->no_telp_pekerja, 'id_akun_biaya_upah' => $request->id_akun_biaya_upah, 'id_akun_utang_upah' => $request->id_akun_utang_upah, 'user_id_updated' => Auth::user()->id]);
        return redirect()->route('pekerja')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pekerja)
    {
        $pekerja = Pekerja::findOrFail($id_pekerja);
        $pekerja->delete();

        return redirect()->route('pekerja')->with('success', 'Data Berhasil di Hapus');
    }
}