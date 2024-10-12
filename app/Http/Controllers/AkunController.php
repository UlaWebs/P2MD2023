<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Akun;
use App\Models\JenisAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('akun as a')->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')->orderBy('a.kode_akun')->get();
        return view('akun.index', ['data' => $data, 'title' => 'Akun']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = JenisAkun::all();
        return view('akun.create', ['data' => $data, 'title' => 'Akun']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_akun' => 'required|unique:akun,kode_akun',
            'nama_akun' => 'required',
            'id_jenis_akun' => 'required',
        ]);

        $store = Akun::create(['kode_akun' => $request->kode_akun, 'nama_akun' => $request->nama_akun, 'id_jenis_akun' => $request->id_jenis_akun, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('akun')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(akun $akun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_akun)
    {
        $data = JenisAkun::all();
        $get = DB::table('akun')->where('id_akun', $id_akun)->get();
        foreach ($get as $p) {
            $id_akun = $p->id_akun;
            $kode_akun = $p->kode_akun;
            $nama_akun = $p->nama_akun;
            $id_jenis_akun = $p->id_jenis_akun;
        }
        return view('akun.edit', ['data' => $data, 'id_akun' => $id_akun, 'nama_akun' => $nama_akun, 'kode_akun' => $kode_akun, 'id_jenis_akun' => $id_jenis_akun, 'title' => 'Akun']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, akun $akun)
    {
        $validated = $request->validate([
            'kode_akun' => 'required|unique:akun,kode_akun,' . $request->id_akun . ',id_akun',
            'nama_akun' => 'required',
            'id_jenis_akun' => 'required',
        ]);

        $store = Akun::where('id_akun', $request->id_akun)
            ->update([
                'kode_akun' => $request->kode_akun,
                'nama_akun' => $request->nama_akun,
                'id_jenis_akun' => $request->id_jenis_akun,
                'user_id_updated' => Auth::user()->id
            ]);

        return redirect()->route('akun')->with('success', 'Data Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_akun)
    {
        try {
            $akun = Akun::findOrFail($id_akun);
            $akun->delete();
            return redirect()->route('akun')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('akun')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
}
