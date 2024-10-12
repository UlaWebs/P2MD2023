<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Akun;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pelanggan as a')->leftJoin('akun as b', 'a.id_akun_piutang', '=', 'b.id_akun')->get();
        return view('pelanggan/index', ['data' => $data, 'title' => "Pelanggan"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Akun::all();
        return view('pelanggan.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required',
            'id_akun_piutang' => 'required'
        ]);
        $get_kode_pelanggan = DB::select("SELECT * FROM kode_pelanggan()");
        foreach ($get_kode_pelanggan as $p) {
            $kode_pelanggan = $p->kode_pelanggan;
        }

        $store = Pelanggan::create(['kode_pelanggan'=> $kode_pelanggan, 'nama_pelanggan' => $request->nama_pelanggan, 'alamat_pelanggan' => $request->alamat_pelanggan, 'no_telp_pelanggan' => $request->no_telp_pelanggan, 'id_akun_piutang' => $request->id_akun_piutang, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('pelanggan')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(pelanggan $pelanggan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_pelanggan)
    {
        $data = Akun::all();
        $get = DB::table('pelanggan')->where('id_pelanggan', $id_pelanggan)->get();
        foreach ($get as $p) {
            $id_pelanggan = $p->id_pelanggan;
            $nama_pelanggan = $p->nama_pelanggan;
            $alamat_pelanggan = $p->alamat_pelanggan;
            $no_telp_pelanggan = $p->no_telp_pelanggan;
            $id_akun_piutang = $p->id_akun_piutang;
        }
        return view('pelanggan.edit', ['data' => $data, 'id_pelanggan' => $id_pelanggan, 'nama_pelanggan' => $nama_pelanggan, 'alamat_pelanggan' => $alamat_pelanggan, 'no_telp_pelanggan' => $no_telp_pelanggan,  'id_akun_piutang' => $id_akun_piutang]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required',
            'id_akun_piutang' => 'required',
        ]);

        $store = Pelanggan::where('id_pelanggan', $request->id_pelanggan)->update(['nama_pelanggan' => $request->nama_pelanggan, 'alamat_pelanggan' => $request->alamat_pelanggan, 'no_telp_pelanggan' => $request->no_telp_pelanggan, 'id_akun_piutang' => $request->id_akun_piutang, 'user_id_updated' => Auth::user()->id]);
        return redirect()->route('pelanggan')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pelanggan)
    {
        $pelanggan = Pelanggan::findOrFail($id_pelanggan);
        $pelanggan->delete();

        return redirect()->route('pelanggan')->with('success', 'Data Berhasil di Hapus');
    }
}