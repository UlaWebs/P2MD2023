<?php

namespace App\Http\Controllers;

use App\Models\DepresiasiAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Errormsg;

class DepresiasiasetController extends Controller
{
    public function index()
    {
        $data = DB::table("depresiasi_aset as a")
        ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'depresiasi aset') as d"), 'a.id_depresiasi_aset', '=', 'd.id_transaksi')
        ->select('a.tgl_depresiasi_aset', 
            DB::raw('sum(a.kuantitas) as total_kuantitas, sum(a.kuantitas * a.nominal_depresiasi) as total_depresiasi, count(d.no_jurnal) as n_jurnal'))
        ->groupBy('a.tgl_depresiasi_aset')
        ->get();

        return view("depresiasiaset.index", [
            'data' => $data,
            'title' => 'Depresiasi Aset',
        ]);
    }

    public function create()
    {
        return view('depresiasiaset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_depresiasi' => 'required',
        ]);

         try {

            $tgl = date_create($request->tgl_depresiasi);
            $tgl_format = date_format($tgl, "Y-m-d");

            $deresiasi = DB::select("select * from depresiasi_aset('$tgl_format')");
            $msg = "";
            
            foreach ($deresiasi as $data_depresiasi){
                $msg = $data_depresiasi->depresiasi_aset;
            }



            return redirect ('depresiasiaset')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('depresiasiaset')->with('warning', $e->getMessage());
        }
    }

    public function detail($tgl_depresiasi){
        $data = DB::table('depresiasi_aset as a')
            ->leftjoin('inventaris as b', 'a.id_inventaris', '=', 'b.id_inventaris')
            ->leftjoin('aset as c', 'b.id_aset', '=', 'c.id_aset')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'depresiasi aset') as d"), 'a.id_depresiasi_aset', '=', 'd.id_transaksi')
            ->where('a.tgl_depresiasi_aset', '=', $tgl_depresiasi)
            ->orderBy('a.tgl_depresiasi_aset', 'asc')
            ->get();

        $n_jurnal = 0;
        foreach($data as $dt){
            if($dt->no_jurnal != ''){
                $n_jurnal = $n_jurnal+1;
            }
        }

        if($n_jurnal == count($data)){
            $btnJurnal = 'F';
        } else{
            $btnJurnal = 'T';
        }
        
        return view('depresiasiaset.detail', [
            'data' => $data, 
            'title' => 'Detail Depresiasi Aset', 
            'tgl_depresiasi_aset' => $tgl_depresiasi,
            'btnJurnal' => $btnJurnal,
        ]);
    }

    public function edit(DepresiasiAset $depresiasiAset)
    {
        //
    }

    public function update(Request $request, DepresiasiAset $depresiasiAset)
    {
        //
    }

    public function destroy($tgl_depresiasi_aset)
    {
        try {
            $depresiasi = DepresiasiAset::where('tgl_depresiasi_aset','=', $tgl_depresiasi_aset);
            $depresiasi->delete();
            return redirect()->route('depresiasiaset')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('depresiasiaset')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($tgl_depresiasi)
    {
        try {
            $depresiasi = DB::select("select * from jurnal_depresiasi_aset('$tgl_depresiasi')");
            $msg = "";
            foreach ($depresiasi as $data_jurnal){
                $msg = $data_jurnal->jurnal_depresiasi_aset;
            }
            return redirect ('/depresiasiaset/detail/' . $tgl_depresiasi)->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('/depresiasiaset/detail/' . $tgl_depresiasi)->with('warning', $e->getMessage());
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
        return view('depresiasiaset.jurnaldetail', [
            'title' => "Jurnal No " . $no_jurnal,
            'no_jurnal' => $no_jurnal,
            'tgl_jurnal' => $tgl_jurnal,
            'keterangan' => $keterangan,
            'jenis_transaksi' => $jenis_transaksi,
            'data' => $data
        ]);
    }
}