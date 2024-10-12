<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Kas;
use App\Models\Pelunasanpembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelunasanpembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pelunasan_pembelian as a')
            ->leftjoin('penerimaan_header as b', 'a.id_penerimaan_header', '=', 'b.id_penerimaan_header')
            ->leftjoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->leftjoin('pemesanan_header as d', 'b.id_pemesanan_header', '=', 'd.id_pemesanan_header')
            ->leftjoin('supplier as e', 'd.id_supplier', '=', 'e.id_supplier')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pelunasan pembelian') as f"), 'a.id_pelunasan', '=', 'f.id_transaksi')
            ->select('a.*', 'b.no_penerimaan', 'c.*', 'e.*', 'f.no_jurnal', 'f.id_jurnal_header')
            ->get();

        return view('pelunasanpembelian.index', ['data' => $data, 'title' => 'Pelunasan Pembelian']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $penerimaan = DB::table('penerimaan_header as a')
            ->leftjoin('penerimaan_detail as b', 'a.id_penerimaan_header', '=', 'b.id_penerimaan_header')
            ->leftjoin('pemesanan_header as c', 'a.id_pemesanan_header', '=', 'c.id_pemesanan_header')
            ->leftjoin('supplier as d', 'c.id_supplier', '=', 'd.id_supplier')
            ->groupBy('a.id_penerimaan_header', 'a.no_penerimaan', 'a.tgl_penerimaan', 'd.nama_supplier','c.nominal_uang_muka')
            ->select('a.id_penerimaan_header', 'a.no_penerimaan', 'a.no_faktur_pembelian', DB::raw('COALESCE(SUM(b.kuantitas*(base_price + ppn)), 0) as total_penerimaan'),DB::raw('COALESCE(SUM(b.kuantitas*(base_price + ppn)), 0) - c.nominal_uang_muka as total_tagihan'), 'a.tgl_penerimaan', 'd.nama_supplier')
            ->get();
        $kas = Kas::all();
        return view('pelunasanpembelian.create', ['kas' => $kas, 'penerimaan' => $penerimaan, 'title' => 'Input Pelunasan Pembelian']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pelunasan' => 'required',
            'keterangan' => 'required',
            'id_penerimaan_header' => 'required',
            'id_kas' => 'required',
            'nominal_pembayaran' => 'required',
        ]);

        $tgl = date_create($request->tgl_pelunasan);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pelunasan = DB::select("SELECT * FROM no_pelunasan('$tgl_format')");
        foreach ($get_no_pelunasan as $p) {
            $no_pelunasan = $p->no_pelunasan;
        }

        try {
            $store = Pelunasanpembelian::create(['no_pelunasan' => $no_pelunasan, 'tgl_pelunasan' => $request->tgl_pelunasan, 'keterangan' => $request->keterangan, 'id_penerimaan_header' => $request->id_penerimaan_header, 'id_kas' => $request->id_kas, 'nominal_pembayaran' => $request->nominal_pembayaran, 'user_id_created' => Auth::user()->id]);
            $id = $store->id_pelunasan;
            return redirect('/pelunasanpembelian')->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pelunasanpembelian')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelunasanpembelian $pelunasanpembelian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $header = DB::table('pelunasan_pembelian as a')
            ->where('id_pelunasan', '=', $id)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pelunasan = $p->id_pelunasan;
                $tgl_pelunasan = $p->tgl_pelunasan;
                $id_penerimaan_header = $p->id_penerimaan_header;
                $id_kas = $p->id_kas;
                $nominal_pembayaran = $p->nominal_pembayaran;
                $keterangan = $p->keterangan;
            }
        }

        $penerimaan = DB::table('penerimaan_header as a')
            ->leftjoin('penerimaan_detail as b', 'a.id_penerimaan_header', '=', 'b.id_penerimaan_header')
            ->leftjoin('pemesanan_header as c', 'a.id_pemesanan_header', '=', 'c.id_pemesanan_header')
            ->leftjoin('supplier as d', 'c.id_supplier', '=', 'd.id_supplier')
            ->groupBy('a.id_penerimaan_header', 'a.no_penerimaan', 'a.tgl_penerimaan', 'd.nama_supplier','c.nominal_uang_muka')
            ->select('a.id_penerimaan_header', 'a.no_penerimaan', 'a.no_faktur_pembelian', DB::raw('COALESCE(SUM(b.kuantitas*(base_price + ppn)), 0) as total_penerimaan'),DB::raw('COALESCE(SUM(b.kuantitas*(base_price + ppn)), 0) - c.nominal_uang_muka as total_tagihan'), 'a.tgl_penerimaan', 'd.nama_supplier')
            ->get();

        $kas = Kas::all();
        return view('pelunasanpembelian.edit', ['penerimaan' => $penerimaan, 'kas' => $kas, 'id_pelunasan' => $id_pelunasan, 'tgl_pelunasan' => $tgl_pelunasan, 'keterangan' => $keterangan, 'id_penerimaan_header' => $id_penerimaan_header, 'id_kas' => $id_kas, 'nominal_pembayaran' => $nominal_pembayaran]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_pelunasan' => 'required',
            'tgl_pelunasan' => 'required',
            'keterangan' => 'required',
            'id_penerimaan_header' => 'required',
            'id_kas' => 'required',
            'nominal_pembayaran' => 'required',
            
        ]);

        try {
            $update = Pelunasanpembelian::where('id_pelunasan', $request->id_pelunasan)->update(['tgl_pelunasan' => $request->tgl_pelunasan, 'keterangan' => $request->keterangan, 'id_penerimaan_header' => $request->id_penerimaan_header, 'id_kas' => $request->id_kas, 'nominal_pembayaran' => $request->nominal_pembayaran, 'user_id_updated' => Auth::user()->id]);
            return redirect('/pelunasanpembelian')->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pelunasanpembelian')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pelunasanpembelian = Pelunasanpembelian::findOrFail($id);
            $pelunasanpembelian->delete();
            return redirect()->route('pelunasanpembelian.index')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pelunasanpembelian.index')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_pelunasan)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pelunasan_pembelian($id_pelunasan)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_pelunasan_pembelian;
            }
            return redirect('pelunasanpembelian')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pelunasanpembelian')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('pelunasanpembelian.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}