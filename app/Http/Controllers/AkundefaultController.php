<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Akundefault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AkundefaultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('akun_default as a')->leftJoin('akun as b', 'a.id_akun', '=', 'b.id_akun')->orderBy('a.id_akun_default')->get();
        return view('akundefault.index', ['data' => $data, 'title' => 'Akun Default']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_akun_default)
    {
        $data = DB::table('akun as a')->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')->where('b.is_group', 'f')->orderBy('a.kode_akun')->get();
        $get = DB::table('akun_default')->where('id_akun_default', $id_akun_default)->get();
        foreach ($get as $p) {
            $id_akun_default = $p->id_akun_default;
            $id_akun = $p->id_akun;
            $keterangan = $p->keterangan;
        }
        return view('akundefault.edit', ['data' => $data, 'id_akun_default' => $id_akun_default, 'id_akun' => $id_akun, 'keterangan' => $keterangan, 'title' => 'Akun Default']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, akun $akun)
    {
        $validated = $request->validate([
            'id_akun_default' => 'required',
            'id_akun' => 'required',
        ]);

        $store = Akundefault::where('id_akun_default', $request->id_akun_default)->update(['id_akun' => $request->id_akun, 'user_id_updated' => Auth::user()->id]);
        return redirect()->route('akundefault')->with('success', 'Data Berhasil di Input');
    }
}
