<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Akun;
use App\Models\Kas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('kas as a')->leftJoin('akun as b', 'a.id_akun_kas', '=', 'b.id_akun')->get();
        return view('kas/index', ['data' => $data, 'title' => "Kas"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Akun::all();
        return view('kas.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kas' => 'required',
            'id_akun_kas' => 'required',
        ]);

        $store = Kas::create(['nama_kas' => $request->nama_kas, 'id_akun_kas' => $request->id_akun_kas, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('kas')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(kas $kas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_kas)
    {
        $data = Akun::all();
        $get = DB::table('kas')->where('id_kas', $id_kas)->get();
        foreach ($get as $p) {
            $id_kas = $p->id_kas;
            $nama_kas = $p->nama_kas;
            $id_akun_kas = $p->id_akun_kas;
        }
        return view('kas.edit', ['data' => $data, 'id_kas' => $id_kas, 'nama_kas' => $nama_kas, 'id_akun_kas' => $id_akun_kas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, kas $kas)
    {
        $validated = $request->validate([
            'nama_kas' => 'required',
            'id_akun_kas' => 'required',
        ]);

        $store = Kas::where('id_kas', $request->id_kas)->update(['nama_kas' => $request->nama_kas, 'id_akun_kas' => $request->id_akun_kas, 'user_id_updated' => Auth::user()->id]);
        return redirect()->route('kas')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_kas)
    {
        try {
            $kas = Kas::findOrFail($id_kas);
            $kas->delete();

            return redirect()->route('kas')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('kas')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
}