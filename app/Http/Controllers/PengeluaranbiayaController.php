<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranBiaya;
use App\Helpers\Errormsg;
use App\Models\Kas;
use App\Models\Biaya;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranbiayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pengeluaran_biaya as a')
            ->leftJoin('kas as b', 'a.id_kas', '=', 'b.id_kas')
            ->leftJoin('biaya as c', 'a.id_biaya', '=', 'c.id_biaya')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pengeluaran biaya') as d"), 'a.id_pengeluaran_biaya', '=', 'd.id_transaksi')
            ->select('a.*', 'b.*', 'c.nama_biaya', 'd.no_jurnal', 'd.id_jurnal_header')
            ->get();
        return view('pengeluaranbiaya/index', ['data' => $data, 'title' => "Pengeluaran Biaya"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kas = Kas::all();
        $biaya = Biaya::all();
        return view('pengeluaranbiaya.create', ['kas' => $kas, 'biaya' => $biaya, 'title' => 'Input Pengeluaran Biaya']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pengeluaran_biaya' => 'required',
            'keterangan' => 'required',
            'id_biaya' => 'required',
            'id_kas' => 'required',
            'nominal' => 'required',
        ]);

        $tgl = date_create($request->tgl_pengeluaran_biaya);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pengeluaran_biaya = DB::select("SELECT * FROM no_pengeluaran('$tgl_format')");
        foreach ($get_no_pengeluaran_biaya as $p) {
            $no_pengeluaran = $p->no_pengeluaran;
        }

        try {
            $store = PengeluaranBiaya::create(['no_pengeluaran_biaya' => $no_pengeluaran, 'tgl_pengeluaran_biaya' => $request->tgl_pengeluaran_biaya, 'keterangan' => $request->keterangan, 'id_biaya' => $request->id_biaya, 'id_kas' => $request->id_kas, 'nominal' => $request->nominal, 'user_id_created' => Auth::user()->id]);
            $id = $store->id_pengeluaran_biaya;
            return redirect('/pengeluaranbiaya')->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pengeluaranbiaya')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $header = DB::table('pengeluaran_biaya as a')
            ->where('id_pengeluaran_biaya', '=', $id)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pengeluaran_biaya = $p->id_pengeluaran_biaya;
                $tgl_pengeluaran_biaya = $p->tgl_pengeluaran_biaya;
                $id_biaya = $p->id_biaya;
                $id_kas = $p->id_kas;
                $nominal = $p->nominal;
                $keterangan = $p->keterangan;
            }
        }

        $biaya = Biaya::all();
        $kas = Kas::all();
        return view('pengeluaranbiaya.edit', ['biaya' => $biaya, 'kas' => $kas, 'id_pengeluaran_biaya' => $id_pengeluaran_biaya, 'tgl_pengeluaran_biaya' => $tgl_pengeluaran_biaya, 'keterangan' => $keterangan, 'id_biaya' => $id_biaya, 'id_kas' => $id_kas, 'nominal' => $nominal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_pengeluaran_biaya' => 'required',
            'tgl_pengeluaran_biaya' => 'required',
            'keterangan' => 'required',
            'id_biaya' => 'required',
            'id_kas' => 'required',
            'nominal' => 'required',
        ]);

        try {
            $update = PengeluaranBiaya::where('id_pengeluaran_biaya', $request->id_pengeluaran_biaya)->update(['tgl_pengeluaran_biaya' => $request->tgl_pengeluaran_biaya, 'keterangan' => $request->keterangan, 'id_biaya' => $request->id_biaya, 'id_kas' => $request->id_kas, 'nominal' => $request->nominal, 'user_id_updated' => Auth::user()->id]);
            return redirect('/pengeluaranbiaya')->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pengeluaranbiaya')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pengeluaranbiaya = PengeluaranBiaya::findOrFail($id);
            $pengeluaranbiaya->delete();
            return redirect()->route('pengeluaranbiaya')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pengeluaranbiaya')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_pengeluaran_biaya)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pengeluaran_biaya($id_pengeluaran_biaya)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_pengeluaran_biaya;
            }
            return redirect('pengeluaranbiaya')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pengeluaranbiaya')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnaldetail($id_jurnal_header)
    {
        $header = DB::table('jurnal_header as a')
            ->where('id_jurnal_header', '=', $id_jurnal_header)
            ->select('a.*')
            ->get();

        $data = DB::table('jurnal_detail as a')
            ->leftjoin('akun as b', 'a.id_akun', '=', 'b.id_akun')
            ->where('id_jurnal_header', '=', $id_jurnal_header)
            ->select('a.*', 'b.*')
            ->orderBy('a.id_jurnal_detail')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $no_jurnal = $p->no_jurnal;
                $tgl_jurnal = $p->tgl_jurnal;
                $keterangan = $p->keterangan;
                $jenis_transaksi = $p->jenis_transaksi;
            }
        }
        return view('pengeluaranbiaya.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}