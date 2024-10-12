<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\PelunasanAset;
use App\Models\PerolehanAsetHeader;
use Illuminate\Http\Request;
use App\Helpers\Errormsg;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelunasanasetController extends Controller
{
    public function index()
    {
        $data = DB::table('pelunasan_aset as a')
        ->leftjoin('perolehan_aset_header as b', 'a.id_perolehan_aset', '=', 'b.id_perolehan_aset_header')
        ->leftJoin('kas as c', 'a.id_kas','=','c.id_kas')
        ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pelunasan aset') as d"), 'a.id_pelunasan_aset', '=', 'd.id_transaksi')
        ->select('a.*', 'b.no_perolehan_aset', 'c.nama_kas', 'd.no_jurnal', 'd.id_jurnal_header')
        ->get();

        return view("pelunasanaset.index", [
            'data' => $data,
            'title' => 'Pelunasan Aset',
        ]);
    }

    public function create(){

        $data = Kas::all();

        $aset = DB::table('perolehan_aset_header as a')
         ->leftJoin(DB::raw('(select y.id_perolehan_aset_header, sum(kuantitas*harga_perolehan) as
         subtotal_perolehan from perolehan_aset_detail as x
         left join perolehan_aset_header as y on x.id_perolehan_aset_header=y.id_perolehan_aset_header
         group by y.id_perolehan_aset_header) as b'),
         'a.id_perolehan_aset_header','=','b.id_perolehan_aset_header')
         ->leftjoin(DB::raw('(select z.id_perolehan_aset, sum(z.nominal_pelunasan) as total_pelunasan
         from pelunasan_aset as z group by z.id_perolehan_aset) as c'), 'a.id_perolehan_aset_header', '=', 'c.id_perolehan_aset')
        ->select('a.no_perolehan_aset', 'a.id_perolehan_aset_header' ,DB::raw('COALESCE(b.subtotal_perolehan, 0) - COALESCE(c.total_pelunasan, 0) - a.nominal_kas as saldo_utang'))
        ->where(DB::raw('COALESCE(b.subtotal_perolehan, 0) - COALESCE(c.total_pelunasan, 0) - a.nominal_kas'), '>','0')
        ->get();

        return view("pelunasanaset.create", [
            'data' => $data,
            'aset' => $aset,
            'title' => 'Tambah Data Pelunasan Aset',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pelunasan_aset' => 'required',
            'id_perolehan_aset'=> 'required',
            'id_kas' => 'required',
            'nominal_pelunasan' => 'required',
        ]);

        $tgl = date_create($request->tgl_pelunasan_aset);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pa = DB::select("SELECT * FROM no_pela('$tgl_format')");
        foreach ($get_no_pa as $p) {
            $no_pela = $p->no_pela;
        }

        $get_perolehan = DB::table('perolehan_aset_header as a')
                            ->leftJoin('perolehan_aset_detail as b', 'a.id_perolehan_aset_header', '=', 'b.id_perolehan_aset_header')
                            ->where('a.id_perolehan_aset_header', '=', $request->id_perolehan_aset)
                            ->groupBy('a.id_perolehan_aset_header', 'a.nominal_kas')
                            ->select('a.id_perolehan_aset_header', DB::raw('sum(b.kuantitas * b.harga_perolehan) - a.nominal_kas as saldo_utang'))
                            ->get();
        $saldo_utang = 0;

        foreach ( $get_perolehan as $p ) {
            $saldo_utang += $p->saldo_utang;
        }

        try {
            if($request->nominal_pelunasan > $saldo_utang) {
                return redirect('/pelunasanaset/create')->with('warning', 'Nominal Pelunasan tidak boleh lebih besar dari saldo utang : ' . $saldo_utang);
            } else {
                $store = PelunasanAset::create(['no_pelunasan_aset' => $no_pela, 'tgl_pelunasan_aset' => $request->tgl_pelunasan_aset, 'id_perolehan_aset' => $request->id_perolehan_aset ,'id_kas' => $request->id_kas, 'nominal_pelunasan' => $request->nominal_pelunasan,'user_id_created' => Auth::user()->id]);
                return redirect('/pelunasanaset/')->with('success', 'Data Berhasil di Input');
            }
        } catch (\Exception $e) {
            return redirect('/pelunasanaset/create/')->with('warning', $e->getMessage());
        }
    }

    public function edit($id_pelunasan_aset){

        $header = DB::table('pelunasan_aset as a')
            ->leftJoin('perolehan_aset_header as b' ,'a.id_perolehan_aset','=','b.id_perolehan_aset_header')
            ->where('id_pelunasan_aset', '=', $id_pelunasan_aset)
            ->select('a.*', 'b.id_perolehan_aset_header' ,'b.no_perolehan_aset')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pelunasan_aset = $p->id_pelunasan_aset;
                $tgl_pelunasan_aset = $p->tgl_pelunasan_aset;
                $id_kas = $p->id_kas;
                $no_perolehan_aset = $p->no_perolehan_aset;
                $nominal_pelunasan = $p->nominal_pelunasan;
                $id_perolehan_aset_header = $p->id_perolehan_aset_header;
            }
        }
        
        $data_kas = Kas::all();
        return view('pelunasanaset.edit', [
            'data_kas' => $data_kas,
            'id_pelunasan_aset' => $id_pelunasan_aset,
            'tgl_pelunasan_aset' => $tgl_pelunasan_aset,
            'no_perolehan_aset' => $no_perolehan_aset,
            'id_kas' => $id_kas,
            'id_perolehan_aset_header' => $id_perolehan_aset_header,
            'nominal_pelunasan' => $nominal_pelunasan
        ]);
    }

    public function update(Request $request){
        $validated = $request->validate([
            'id_pelunasan_aset' => 'required',
            'tgl_pelunasan_aset' => 'required',
            'id_kas' => 'required',
            'nominal_pelunasan' => 'required',
        ]);

        $get_perolehan = DB::table('perolehan_aset_header as a')
                            ->leftJoin('perolehan_aset_detail as b', 'a.id_perolehan_aset_header', '=', 'b.id_perolehan_aset_header')
                            ->where('a.id_perolehan_aset_header', '=', $request->id_perolehan_aset_header)
                            ->groupBy('a.id_perolehan_aset_header', 'a.nominal_kas')
                            ->select('a.id_perolehan_aset_header', DB::raw('sum(b.kuantitas * b.harga_perolehan) - a.nominal_kas as saldo_utang'))
                            ->get();
        $saldo_utang = 0;

        foreach ( $get_perolehan as $p ) {
            $saldo_utang += $p->saldo_utang;
        }

        try {
            if($request->nominal_pelunasan > $saldo_utang) {
                return redirect('/pelunasanaset/edit/' . $request->id_pelunasan_aset)->with('warning', 'Nominal Pelunasan tidak boleh lebih besar dari saldo utang : ' . $saldo_utang);
            } else {
                $update = PelunasanAset::where('id_pelunasan_aset', $request->id_pelunasan_aset)
            ->update([
                'tgl_pelunasan_aset' => $request->tgl_pelunasan_aset,
                'id_kas' => $request->id_kas,
                'nominal_pelunasan' => $request->nominal_pelunasan,
                'user_id_created' => Auth::user()->id
            ]);
            return redirect('pelunasanaset')->with('success', 'Data Berhasil di Edit');
            }
        } catch (\Exception $e) {
            return redirect('pelunasanaset')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
    public function destroy($id_pelunasan_aset){
        $lunas = PelunasanAset::findOrFail($id_pelunasan_aset);
        $lunas->delete();

        return redirect()->route('pelunasanaset')->with('success', 'Data Berhasil di Hapus');
    }

    public function jurnal($id_pelunasan_aset)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pelunasan_aset($id_pelunasan_aset)");
            $msg = "";
            foreach ($jurnal as $data_jurnal){
                $msg = $data_jurnal->jurnal_pelunasan_aset;
            }
            return redirect ('pelunasanaset')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pelunasanaset')->with('warning', $e->getMessage());
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
        return view('pelunasanaset.jurnaldetail', [
            'title' => "Jurnal No " . $no_jurnal,
            'no_jurnal' => $no_jurnal,
            'tgl_jurnal' => $tgl_jurnal,
            'keterangan' => $keterangan,
            'jenis_transaksi' => $jenis_transaksi,
            'data' => $data
        ]);
    }
}