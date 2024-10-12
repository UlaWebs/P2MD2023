<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\Akun;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('biaya as a')
            ->leftJoin('akun as b', 'a.id_akun_biaya', '=', 'b.id_akun')
            ->get();
        return view('biaya/index', ['data' => $data, 'title' => "Biaya"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Akun::all();
        return view('biaya.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_biaya' => 'required'
        ]);

        $store = Biaya::create(['nama_biaya' => $request->nama_biaya, 'id_akun_biaya' => $request->id_akun_biaya, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('biaya')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(biaya $biaya)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_biaya)
    {
        $data = Akun::all();
        $get = DB::table('biaya')->where('id_biaya', $id_biaya)->get();
        foreach ($get as $p) {
            $id_biaya = $p->id_biaya;
            $nama_biaya = $p->nama_biaya;
            $id_akun_biaya = $p->id_akun_biaya;
        }
        return view('biaya.edit', ['data' => $data, 'id_biaya' => $id_biaya, 'nama_biaya' => $nama_biaya, 'id_akun_biaya' => $id_akun_biaya]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, biaya $biaya)
    {
        $validated = $request->validate([
            'nama_biaya' => 'required'
        ]);

        $store = Biaya::where('id_biaya', $request->id_biaya)->update(['nama_biaya' => $request->nama_biaya, 'id_akun_biaya' => $request->id_akun_biaya, 'user_id_updated' => Auth::user()->id]);
        return redirect()->route('biaya')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_biaya)
    {
        $biaya = Biaya::findOrFail($id_biaya);
        $biaya->delete();

        return redirect()->route('biaya')->with('success', 'Data Berhasil di Hapus');
    }
}