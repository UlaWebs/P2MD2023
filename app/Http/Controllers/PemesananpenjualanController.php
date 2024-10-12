<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Pelanggan;
use App\Models\PemesananPenjualanDetail;
use App\Models\PemesananPenjualanHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemesananpenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pemesanan_penjualan_header as a')
            ->leftJoin('pelanggan as b', 'a.id_pelanggan', '=', 'b.id_pelanggan')
            ->leftJoin(DB::raw('(select id_pemesanan_penjualan_header, sum(kuantitas*(harga_satuan)) as subtotal_pemesanan from pemesanan_penjualan_detail group by id_pemesanan_penjualan_header) as c'), 'a.id_pemesanan_penjualan_header', '=', 'c.id_pemesanan_penjualan_header')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pemesanan penjualan') as d"), 'a.id_pemesanan_penjualan_header', '=', 'd.id_transaksi')
            ->select('a.*', 'b.*', 'c.subtotal_pemesanan', 'd.no_jurnal', 'd.id_jurnal_header')
            ->get();
        return view('pemesananpenjualan.index', ['data' => $data, 'title' => "Pemesanan Penjualan"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = DB::table('pelanggan')->get();
        return view('pemesananpenjualan/create', ['data' => $data, 'title' => "Pemesanan Penjualan"]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pemesanan' => 'required',
            'keterangan' => 'required',
            'id_pelanggan' => 'required',
            'tgl_jatuh_tempo' => 'required',
        ]);

        $tgl = date_create($request->tgl_pemesanan);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_so = DB::select("SELECT * FROM no_so('$tgl_format')");
        foreach ($get_no_so as $p) {
            $no_so = $p->no_so;
        }

        try {
            $store = PemesananPenjualanHeader::create(['no_pemesanan_penjualan_header' => $no_so, 'tgl_pemesanan' => $request->tgl_pemesanan, 'keterangan' => $request->keterangan, 'status' => "Pending", 'id_pelanggan' => $request->id_pelanggan, 'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo, 'user_id_created' => Auth::user()->id]);
            $id = $store->id_pemesanan_penjualan_header;
            return redirect('/pemesananpenjualan/detail/' . $id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pemesananpenjualan/detail/' . $id)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $header = DB::table('pemesanan_penjualan_header as a')
            ->where('id_pemesanan_penjualan_header', '=', $id)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pemesanan_penjualan_header = $p->id_pemesanan_penjualan_header;
                $tgl_pemesanan = $p->tgl_pemesanan;
                $keterangan = $p->keterangan;
                $tgl_jatuh_tempo = $p->tgl_jatuh_tempo;
                $id_pelanggan = $p->id_pelanggan;
            }
        }

        $data = Pelanggan::all();
        return view('pemesananpenjualan.edit', ['data' => $data, 'id_pemesanan_penjualan_header' => $id_pemesanan_penjualan_header, 'tgl_pemesanan' => $tgl_pemesanan, 'keterangan' => $keterangan, 'tgl_jatuh_tempo' => $tgl_jatuh_tempo, 'id_pelanggan' => $id_pelanggan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_pemesanan_penjualan_header' => 'required',
            'tgl_pemesanan' => 'required',
            'keterangan' => 'required',
            'id_pelanggan' => 'required',
            'tgl_jatuh_tempo' => 'required',
        ]);

        try {
            $update = PemesananPenjualanHeader::where('id_pemesanan_penjualan_header', $request->id_pemesanan_penjualan_header)->update(['tgl_pemesanan' => $request->tgl_pemesanan, 'keterangan' => $request->keterangan, 'status' => "Pending", 'id_pelanggan' => $request->id_pelanggan, 'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo, 'user_id_created' => Auth::user()->id]);
            return redirect('/pemesananpenjualan/detail/' . $request->id_pemesanan_penjualan_header)->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pemesananpenjualan/detail/' . $request->id_pemesanan_penjualan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pemesanan_penjualan_header)
    {
        try {
            $pemesanan_penjualan_header = PemesananPenjualanHeader::findOrFail($id_pemesanan_penjualan_header);
            $pemesanan_penjualan_header->delete();
            return redirect()->route('pemesananpenjualan')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pemesananpenjualan')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id)
    {
        $header = DB::table('pemesanan_penjualan_header as a')
            ->leftJoin('pelanggan as b', 'a.id_pelanggan', '=', 'b.id_pelanggan')
            ->where('id_pemesanan_penjualan_header', '=', $id)
            ->select('a.*', 'b.*')
            ->get();

        $data = DB::table('pemesanan_penjualan_detail as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->where('id_pemesanan_penjualan_header', '=', $id)
            ->select('a.*', 'b.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $no_pemesanan_penjualan_header = $p->no_pemesanan_penjualan_header;
                $tgl_pemesanan = $p->tgl_pemesanan;
                $keterangan = $p->keterangan;
                $status = $p->status;
                $tgl_jatuh_tempo = $p->tgl_jatuh_tempo;
                $nama_pelanggan = $p->nama_pelanggan;
            }
        }
        $data_item = DB::table('persediaan as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin(DB::raw('(select id_persediaan_header, sum(kuantitas) as total_pengambilan from pengambilan group by id_persediaan_header) as c'), 'a.id_persediaan_header', '=', 'c.id_persediaan_header')
            ->groupBy('a.id_item', 'b.satuan', 'b.nama_item', 'b.jenis_item')
            ->select(
                'a.id_item',
                'b.nama_item',
                'b.satuan',
                'b.jenis_item',
                DB::raw('sum(a.kuantitas) as total_persediaan'),
                DB::raw('sum(c.total_pengambilan) as total_pengambilan'),
                DB::raw('sum(a.kuantitas*a.harga_satuan) - sum(COALESCE(c.total_pengambilan, 0)*a.harga_satuan) as nilai_persediaan'),
                DB::raw('sum(a.kuantitas) - sum(COALESCE(c.total_pengambilan, 0)) as stok')
            )
            ->where('b.jenis_item', '=', 'Barang Jadi')
            ->get();

        return view('pemesananpenjualan.detail', ['title' => "Detail Pemesanan No " . $no_pemesanan_penjualan_header, 'data_item' => $data_item, 'id_pemesanan_penjualan_header' => $id, 'no_pemesanan_penjualan_header' => $no_pemesanan_penjualan_header, 'tgl_pemesanan' => $tgl_pemesanan, 'keterangan' => $keterangan, 'status' => $status, 'tgl_jatuh_tempo' => $tgl_jatuh_tempo, 'nama_pelanggan' => $nama_pelanggan, 'data' => $data]);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_pemesanan_penjualan_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
            'harga_satuan' => 'required',
        ]);

        try {
            $store = PemesananPenjualanDetail::create(['id_pemesanan_penjualan_header' => $request->id_pemesanan_penjualan_header, 'id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'harga_satuan' => $request->harga_satuan, 'user_id_created' => Auth::user()->id]);
            return redirect('/pemesananpenjualan/detail/' . $request->id_pemesanan_penjualan_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pemesananpenjualan/detail/' . $request->id_pemesanan_penjualan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail($id)
    {
        try {
            $pemesanan_penjualan_detail = PemesananPenjualanDetail::findOrFail($id);
            $get = $pemesanan_penjualan_detail->get();
            $id_pemesanan_penjualan_header = 0;
            foreach ($get as $data) {
                $id_pemesanan_penjualan_header = $data->id_pemesanan_penjualan_header;
            }

            $pemesanan_penjualan_detail->delete();
            return redirect('/pemesananpenjualan/detail/' . $id_pemesanan_penjualan_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/pemesananpenjualan/detail/' . $id_pemesanan_penjualan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function editdetail($id_pemesanan)
    {
        $header = DB::table('pemesanan_penjualan_detail as a')
            ->where('id_pemesanan_penjualan_detail', '=', $id_pemesanan)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pemesanan_penjualan_detail = $p->id_pemesanan_penjualan_detail;
                $id_pemesanan_penjualan_header = $p->id_pemesanan_penjualan_header;
                $id_item = $p->id_item;
                $kuantitas = $p->kuantitas;
                $harga_satuan = $p->harga_satuan;
            }
        }

        $data_item = DB::table('item')->get();
        return view('pemesananpenjualan.editdetail', ['title' => "Edit Detail", 'id_pemesanan_penjualan_header' => $id_pemesanan_penjualan_header, 'data_item' => $data_item, 'id_pemesanan_penjualan_detail' => $id_pemesanan_penjualan_detail, 'id_item' => $id_item, 'kuantitas' => $kuantitas, 'harga_satuan' => $harga_satuan]);
    }

    public function updatedetail(Request $request)
    {
        $validated = $request->validate([
            'id_pemesanan_penjualan_detail' => 'required',
            'id_pemesanan_penjualan_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
            'harga_satuan' => 'required',
        ]);

        try {
            $store = PemesananPenjualanDetail::where('id_pemesanan_penjualan_detail', $request->id_pemesanan_penjualan_detail)->update(['id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'harga_satuan' => $request->harga_satuan, 'user_id_created' => Auth::user()->id]);
            return redirect('/pemesananpenjualan/detail/' . $request->id_pemesanan_penjualan_header)->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pemesananpenjualan/detail/' . $request->id_pemesanan_penjualan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_pemesanan_penjualan_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pemesanan_penjualan($id_pemesanan_penjualan_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_pemesanan_penjualan;
            }
            return redirect('pemesananpenjualan')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pemesananpenjualan')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('pemesananpenjualan.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}