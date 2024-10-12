<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\KategoriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Errormsg;
use App\Models\Akun;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('aset as a')
            ->leftJoin('akun as b', 'a.id_akun_aktiva_tetap', '=', 'b.id_akun')
            ->leftJoin('akun as c', 'a.id_akun_beban_penyusutan', '=', 'c.id_akun')
            ->leftJoin('akun as d', 'a.id_akun_akumulasi_penyusutan', '=', 'd.id_akun')
            ->orderBy('kode_aset')
            ->get();

        $data2 = DB::table('aset as a')
            ->leftJoin('kategori_aset as e', 'a.id_kategori_aset', '=', 'e.id_kategori_aset')
            ->orderBy('a.kode_aset')
            ->get();

        return view('aset.index', ['data' => $data, 'data2' => $data2, 'title' => 'Aset']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = KategoriAset::all();
        $data2 = Akun::orderBy('kode_akun')->get();

        $data_aktiva = DB::table('akun as a')
            ->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')
            ->where('b.kode_tipe_akun', '=', '12A')
            ->select('a.*', 'b.*')
            ->get();

        $data_beban = DB::table('akun as a')
            ->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')
            ->where('b.kode_tipe_akun', '=', '61C')
            ->select('a.*', 'b.*')
            ->get();

        $data_akumulasi = DB::table('akun as a')
            ->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')
            ->where('b.kode_tipe_akun', '=', '12B')
            ->select('a.*', 'b.*')
            ->get();

        return view('aset.create', ['data' => $data, 'data2' => $data2, 'data_aktiva' => $data_aktiva, 'data_beban' =>
        $data_beban, 'data_akumulasi' => $data_akumulasi,'title' => 'Aset']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_aset' => 'required|unique:aset,kode_aset',
            'nama_aset' => 'required',
            'tingkat_penyusutan' => 'required',
            'id_kategori_aset' => 'required',
            'id_akun_aktiva_tetap' => 'required',
            'id_akun_beban_penyusutan' => 'required',
            'id_akun_akumulasi_penyusutan' => 'required',
        ]);

        $store = Aset::create([
            'kode_aset' => $request->kode_aset,
            'nama_aset' => $request->nama_aset,
            'tingkat_penyusutan' => $request->tingkat_penyusutan,
            'id_kategori_aset' => $request->id_kategori_aset,
            'id_akun_aktiva_tetap' => $request->id_akun_aktiva_tetap,
            'id_akun_beban_penyusutan' => $request->id_akun_beban_penyusutan,
            'id_akun_akumulasi_penyusutan' => $request->id_akun_akumulasi_penyusutan,
            'user_id_created' => Auth::user()->id
        ]);
        return redirect()->route('aset')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $aset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_aset)
    {
        $data = KategoriAset::all();
        $data2 = Akun::orderBy('kode_akun')->get();
        $get = DB::table('aset')->where('id_aset', $id_aset)->get();
        foreach ($get as $p) {
            $id_aset = $p->id_aset;
            $kode_aset = $p->kode_aset;
            $nama_aset = $p->nama_aset;
            $tingkat_penyusutan = $p->tingkat_penyusutan;
            $id_kategori_aset = $p->id_kategori_aset;
            $id_akun_aktiva_tetap = $p->id_akun_aktiva_tetap;
            $id_akun_beban_penyusutan = $p->id_akun_beban_penyusutan;
            $id_akun_akumulasi_penyusutan = $p->id_akun_akumulasi_penyusutan;
        }

        return view('aset.edit', [
            'data' => $data,
            'data2' => $data2,
            'id_aset' => $id_aset,
            'nama_aset' => $nama_aset,
            'tingkat_penyusutan' => $tingkat_penyusutan,
            'kode_aset' => $kode_aset,
            'id_kategori_aset' => $id_kategori_aset,
            'id_akun_aktiva_tetap' => $id_akun_aktiva_tetap,
            'id_akun_beban_penyusutan' => $id_akun_beban_penyusutan,
            'id_akun_akumulasi_penyusutan' => $id_akun_akumulasi_penyusutan,
            'title' => 'Aset'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'kode_aset' => 'required|unique:aset,kode_aset,' . $request->id_aset . ',id_aset',
            'nama_aset' => 'required',
            'id_kategori_aset' => 'required',
            'tingkat_penyusutan' => 'required',
        ]);

        $store = Aset::where('id_aset', $request->id_aset)
            ->update([
                'kode_aset' => $request->kode_aset,
                'nama_aset' => $request->nama_aset,
                'tingkat_penyusutan' => $request->tingkat_penyusutan,
                'id_kategori_aset' => $request->id_kategori_aset,
                'id_akun_aktiva_tetap' => $request->id_akun_aktiva_tetap,
                'id_akun_beban_penyusutan' => $request->id_akun_beban_penyusutan,
                'id_akun_akumulasi_penyusutan' => $request->id_akun_akumulasi_penyusutan,
                'user_id_updated' => Auth::user()->id,
            ]);
        return redirect()->route('aset')->with('success', 'Data Berhasil di Ubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_aset)
    {
        try {
            $aset = Aset::findOrFail($id_aset);
            $aset->delete();
            return redirect()->route('aset')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('aset')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
}
