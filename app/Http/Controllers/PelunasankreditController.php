<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Helpers\Errormsg;
use App\Models\Pelunasankredit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelunasankreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pelunasan_kredit as a')
            ->leftjoin('pengiriman_header as b', 'a.id_pengiriman_header', '=', 'b.id_pengiriman_header')
            ->leftjoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->leftjoin('pemesanan_penjualan_header as d', 'b.id_pemesanan_penjualan_header', '=', 'd.id_pemesanan_penjualan_header')
            ->leftjoin('pelanggan as e', 'd.id_pelanggan', '=', 'e.id_pelanggan')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pelunasan kredit') as f"), 'a.id_pelunasan_kredit', '=', 'f.id_transaksi')
            ->select('a.*', 'b.no_pengiriman', 'c.*', 'e.*', 'f.no_jurnal', 'f.id_jurnal_header')
            ->get();

        return view('pelunasankredit.index', ['data' => $data, 'title' => 'Pelunasan Penjualan']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengiriman = DB::table('pengiriman_header as a')
            ->leftjoin('pengiriman_detail as b', 'a.id_pengiriman_header', '=', 'b.id_pengiriman_header')
            ->leftjoin('pemesanan_penjualan_header as c', 'a.id_pemesanan_penjualan_header', '=', 'c.id_pemesanan_penjualan_header')
            ->leftjoin('pelanggan as d', 'c.id_pelanggan', '=', 'd.id_pelanggan')
            ->groupBy('a.id_pengiriman_header', 'a.no_pengiriman', 'a.tgl_pengiriman', 'd.nama_pelanggan')
            ->select('a.id_pengiriman_header', 'a.no_pengiriman', DB::raw('COALESCE(SUM(b.kuantitas*harga_satuan), 0) as total_pengiriman'), 'a.tgl_pengiriman', 'd.nama_pelanggan')
            ->get();
        $kas = Kas::all();
        return view('pelunasankredit.create', ['kas' => $kas, 'pengiriman' => $pengiriman, 'title' => 'Input Pelunasan Penjualan']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pelunasan' => 'required',
            'keterangan' => 'required',
            'id_pengiriman_header' => 'required',
            'id_kas' => 'required',
            'nominal' => 'required',
        ]);

        $tgl = date_create($request->tgl_pelunasan);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pelunasanpe = DB::select("SELECT * FROM no_pelunasanpe('$tgl_format')");
        foreach ($get_no_pelunasanpe as $p) {
            $no_pelunasan = $p->no_pelunasanpe;
        }

        try {
            $store = PelunasanKredit::create(['no_pelunasan_kredit' => $no_pelunasan, 'tgl_pelunasan' => $request->tgl_pelunasan, 'keterangan' => $request->keterangan, 'id_pengiriman_header' => $request->id_pengiriman_header, 'id_kas' => $request->id_kas, 'nominal' => $request->nominal, 'user_id_created' => Auth::user()->id]);
            $id = $store->id_pelunasan_kredit_kredit;
            return redirect('/pelunasankredit')->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pelunasankredit')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $header = DB::table('pelunasan_kredit as a')
            ->where('id_pelunasan_kredit', '=', $id)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pelunasan_kredit = $p->id_pelunasan_kredit;
                $tgl_pelunasan = $p->tgl_pelunasan;
                $id_pengiriman_header = $p->id_pengiriman_header;
                $id_kas = $p->id_kas;
                $nominal = $p->nominal;
                $keterangan = $p->keterangan;
            }
        }

        $pengiriman = DB::table('pengiriman_header as a')
            ->leftjoin('pengiriman_detail as b', 'a.id_pengiriman_header', '=', 'b.id_pengiriman_header')
            ->leftjoin('pemesanan_penjualan_header as c', 'a.id_pemesanan_penjualan_header', '=', 'c.id_pemesanan_penjualan_header')
            ->leftjoin('pelanggan as d', 'c.id_pelanggan', '=', 'd.id_pelanggan')
            ->groupBy('a.id_pengiriman_header', 'a.no_pengiriman', 'a.tgl_pengiriman', 'd.nama_pelanggan')
            ->select('a.id_pengiriman_header', 'a.no_pengiriman', DB::raw('COALESCE(SUM(b.kuantitas*harga_satuan), 0) as total_pengiriman'), 'a.tgl_pengiriman', 'd.nama_pelanggan')
            ->get();

        $kas = Kas::all();
        return view('pelunasankredit.edit', ['pengiriman' => $pengiriman, 'kas' => $kas, 'id_pelunasan_kredit' => $id_pelunasan_kredit, 'tgl_pelunasan' => $tgl_pelunasan, 'keterangan' => $keterangan, 'id_pengiriman_header' => $id_pengiriman_header, 'id_kas' => $id_kas, 'nominal' => $nominal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_pelunasan_kredit' => 'required',
            'tgl_pelunasan' => 'required',
            'keterangan' => 'required',
            'id_pengiriman_header' => 'required',
            'id_kas' => 'required',
            'nominal' => 'required',
        ]);

        try {
            $update = PelunasanKredit::where('id_pelunasan_kredit', $request->id_pelunasan_kredit)->update(['tgl_pelunasan' => $request->tgl_pelunasan, 'keterangan' => $request->keterangan, 'id_pengiriman_header' => $request->id_pengiriman_header, 'id_kas' => $request->id_kas, 'nominal' => $request->nominal, 'user_id_updated' => Auth::user()->id]);
            return redirect('/pelunasankredit')->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pelunasankredit')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pelunasankredit = PelunasanKredit::findOrFail($id);
            $pelunasankredit->delete();
            return redirect()->route('pelunasankredit')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pelunasankredit')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_pelunasan_kredit)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pelunasan_kredit($id_pelunasan_kredit)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_pelunasan_kredit;
            }
            return redirect('pelunasankredit')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pelunasankredit')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('pelunasankredit.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}