<?php

namespace App\Http\Controllers;

use App\Models\PembayaranUpah;
use App\Helpers\Errormsg;
use App\Models\Kas;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranupahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pembayaran_upah as a')
            ->leftJoin('kas as b', 'a.id_kas', '=', 'b.id_kas')
            ->leftJoin('produksi_detail_tenaga_kerja as c', 'a.id_produksi_detail_tenaga_kerja', '=', 'c.id_produksi_detail_tenaga_kerja')
            ->leftJoin('pekerja as d', 'c.id_pekerja', '=', 'd.id_pekerja')
            ->leftJoin('produksi_header as e', 'c.id_produksi_header', '=', 'e.id_produksi_header')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pembayaran upah') as f"), 'a.id_pembayaran_upah', '=', 'f.id_transaksi')
            ->select('a.*', 'b.*', 'c.biaya_tenaga_kerja', 'd.nama_pekerja', 'e.no_produksi', 'f.no_jurnal', 'f.id_jurnal_header')
            ->get();
        return view('pembayaranupah/index', ['data' => $data, 'title' => "Pembayaran Upah"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produksi_detail_tenaga_kerja = DB::table('produksi_detail_tenaga_kerja as a')
            ->leftjoin('pekerja as b', 'a.id_pekerja', '=', 'b.id_pekerja')
            ->leftjoin('produksi_header as c', 'a.id_produksi_header', '=', 'c.id_produksi_header')
            ->select('a.id_produksi_detail_tenaga_kerja', 'b.nama_pekerja', 'a.biaya_tenaga_kerja', 'c.no_produksi', 'c.tgl_produksi')
            ->get();
        $kas = Kas::all();
        return view('pembayaranupah.create', [
            'kas' => $kas,
            'produksi_detail_tenaga_kerja' => $produksi_detail_tenaga_kerja,
            'title' => 'Input Pelunasan Pembelian'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pembayaran' => 'required',
            'keterangan' => 'required',
            'id_produksi_detail_tenaga_kerja' => 'required',
            'id_kas' => 'required',
            'nominal' => 'required',
        ]);

        $tgl = date_create($request->tgl_pembayaran);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pembayaran = DB::select("SELECT * FROM no_pembayaran_upah('$tgl_format')");
        foreach ($get_no_pembayaran as $p) {
            $no_pembayaran = $p->no_pembayaran_upah;
        }

        try {
            $store = PembayaranUpah::create(['no_pembayaran' => $no_pembayaran, 'tgl_pembayaran' => $request->tgl_pembayaran, 'keterangan' => $request->keterangan, 'id_produksi_detail_tenaga_kerja' => $request->id_produksi_detail_tenaga_kerja, 'id_kas' => $request->id_kas, 'nominal' => $request->nominal, 'user_id_created' => Auth::user()->id]);
            $id = $store->id_pembayaran_upah;
            return redirect('/pembayaranupah')->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pembayaranupah')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $header = DB::table('pembayaran_upah as a')
            ->where('id_pembayaran_upah', '=', $id)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pembayaran_upah = $p->id_pembayaran_upah;
                $tgl_pembayaran = $p->tgl_pembayaran;
                $id_produksi_detail_tenaga_kerja = $p->id_produksi_detail_tenaga_kerja;
                $id_kas = $p->id_kas;
                $nominal = $p->nominal;
                $keterangan = $p->keterangan;
            }
        }

        $produksi_detail_tenaga_kerja = DB::table('produksi_detail_tenaga_kerja as a')
            ->leftjoin('pekerja as b', 'a.id_pekerja', '=', 'b.id_pekerja')
            ->leftjoin('produksi_header as c', 'a.id_produksi_header', '=', 'c.id_produksi_header')
            ->select('a.id_produksi_detail_tenaga_kerja', 'b.nama_pekerja', 'a.biaya_tenaga_kerja', 'c.no_produksi', 'c.tgl_produksi')
            ->get();

        $kas = Kas::all();
        return view('pembayaranupah.edit', ['produksi_detail_tenaga_kerja' => $produksi_detail_tenaga_kerja, 'kas' => $kas, 'id_pembayaran_upah' => $id_pembayaran_upah, 'tgl_pembayaran' => $tgl_pembayaran, 'keterangan' => $keterangan, 'id_produksi_detail_tenaga_kerja' => $id_produksi_detail_tenaga_kerja, 'id_kas' => $id_kas, 'nominal' => $nominal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_pembayaran_upah' => 'required',
            'tgl_pembayaran' => 'required',
            'keterangan' => 'required',
            'id_produksi_detail_tenaga_kerja' => 'required',
            'id_kas' => 'required',
            'nominal' => 'required',
        ]);

        try {
            $update = PembayaranUpah::where('id_pembayaran_upah', $request->id_pembayaran_upah)->update(['tgl_pembayaran' => $request->tgl_pembayaran, 'keterangan' => $request->keterangan, 'id_produksi_detail_tenaga_kerja' => $request->id_produksi_detail_tenaga_kerja, 'id_kas' => $request->id_kas, 'nominal' => $request->nominal, 'user_id_updated' => Auth::user()->id]);
            return redirect('/pembayaranupah')->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pembayaranupah')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pembayaranupah = PembayaranUpah::findOrFail($id);
            $pembayaranupah->delete();
            return redirect()->route('pembayaranupah')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pembayaranupah')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_pembayaran_upah)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pembayaran_upah($id_pembayaran_upah)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_pembayaran_upah;
            }
            return redirect('pembayaranupah')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pembayaranupah')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('pembayaranupah.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}
