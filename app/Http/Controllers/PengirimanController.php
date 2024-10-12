<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\PemesananPenjualanHeader;
use App\Models\PengirimanDetail;
use App\Models\PengirimanHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pengiriman_header as a')
            ->leftJoin('pemesanan_penjualan_header as b', 'a.id_pemesanan_penjualan_header', '=', 'b.id_pemesanan_penjualan_header')
            ->leftJoin('pelanggan as c', 'b.id_pelanggan', '=', 'c.id_pelanggan')
            ->leftJoin(DB::raw('(select id_pengiriman_header, sum(kuantitas*harga_satuan) as subtotal_pengiriman from pengiriman_detail group by id_pengiriman_header) as d'), 'a.id_pengiriman_header', '=', 'd.id_pengiriman_header')
            ->leftJoin(DB::raw('(select id_pemesanan_penjualan_header, sum(kuantitas*harga_satuan) as subtotal_pemesanan_penjualan from pemesanan_penjualan_detail group by id_pemesanan_penjualan_header) as e'), 'a.id_pemesanan_penjualan_header', '=', 'e.id_pemesanan_penjualan_header')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pengiriman') as f"), 'a.id_pengiriman_header', '=', 'f.id_transaksi')
            ->select('a.*', 'b.*', 'c.*', 'd.subtotal_pengiriman', 'e.subtotal_pemesanan_penjualan', 'f.no_jurnal', 'f.id_jurnal_header')
            ->get();
        return view('pengiriman/index', ['data' => $data, 'title' => "Pengiriman"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = PemesananPenjualanHeader::all();
        return view('pengiriman.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pengiriman' => 'required',
            'id_pemesanan_penjualan_header' => 'required',
            'no_invoice' => 'required',

        ]);

        $tgl = date_create($request->tgl_pengiriman);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pengiriman = DB::select("SELECT * FROM no_pengiriman('$tgl_format')");
        foreach ($get_no_pengiriman as $p) {
            $no_pengiriman = $p->no_pengiriman;
        }

        try {
            $store = PengirimanHeader::create(['no_pengiriman' => $no_pengiriman, 'tgl_pengiriman' => $request->tgl_pengiriman, 'keterangan' => $request->keterangan, 'no_invoice' => $request->no_invoice, 'id_pemesanan_penjualan_header' => $request->id_pemesanan_penjualan_header, 'user_id_created' => Auth::user()->id]);
            $id = $store->id_pengiriman_header;
            return redirect('/pengiriman/detail/' . $id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pengiriman/detail/' . $id)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_pengiriman_header)
    {
        $data = PemesananPenjualanHeader::all();
        $get = DB::table('pengiriman_header')->where('id_pengiriman_header', $id_pengiriman_header)->get();
        foreach ($get as $p) {
            $id_pengiriman_header = $p->id_pengiriman_header;
            $no_pengiriman = $p->no_pengiriman;
            $tgl_pengiriman = $p->tgl_pengiriman;
            $keterangan = $p->keterangan;
            $no_invoice = $p->no_invoice;
            $id_pemesanan_penjualan_header = $p->id_pemesanan_penjualan_header;
        }
        return view('pengiriman.edit', ['data' => $data, 'id_pengiriman_header' => $id_pengiriman_header, 'no_pengiriman' => $no_pengiriman, 'tgl_pengiriman' => $tgl_pengiriman, 'keterangan' => $keterangan,'no_invoice' => $no_invoice, 'id_pemesanan_penjualan_header' => $id_pemesanan_penjualan_header]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'no_pengiriman' => 'required',
            'tgl_pengiriman' => 'required',
            'id_pemesanan_penjualan_header' => 'required',
            'no_invoice' => 'required',
        ]);

        try {
            $update = PengirimanHeader::where('id_pengiriman_header', $request->id_pengiriman_header)->update(['no_pengiriman' => $request->no_pengiriman, 'tgl_pengiriman' => $request->tgl_pengiriman, 'keterangan' => $request->keterangan, 'no_invoice' => $request->no_invoice, 'id_pemesanan_penjualan_header' => $request->id_pemesanan_penjualan_header]);
            return redirect()->route('pengiriman')->with('success', 'Data Berhasil di Update');
        } catch (\Exception $e) {
            return redirect('/pengiriman/edit/' . $request->id_pengiriman_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pengiriman_header)
    {
        try {
            $pengiriman_header = PengirimanHeader::findOrFail($id_pengiriman_header);
            $pengiriman_header->delete();
            return redirect()->route('pengiriman')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pengiriman')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id_pengiriman_header)
    {
        $header = DB::table('pengiriman_header as a')
            ->leftJoin('pemesanan_penjualan_header as b', 'a.id_pemesanan_penjualan_header', '=', 'b.id_pemesanan_penjualan_header')
            ->where('a.id_pengiriman_header', '=', $id_pengiriman_header)
            ->select('a.*', 'b.*', 'a.keterangan')
            ->get();

        $data = DB::table('pengiriman_header as a')
            ->leftjoin('pengiriman_detail as b', 'b.id_pengiriman_header', '=', 'a.id_pengiriman_header')
            ->leftjoin('item as c', 'b.id_item', '=', 'c.id_item')
            ->where('b.id_pengiriman_header', '=', $id_pengiriman_header)
            ->select('a.*', 'b.*', 'c.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pemesanan_penjualan_header = $p->id_pemesanan_penjualan_header;
                $no_pengiriman = $p->no_pengiriman;
                $tgl_pengiriman = $p->tgl_pengiriman;
                $no_pemesanan_penjualan_header = $p->no_pemesanan_penjualan_header;
                $keterangan = $p->keterangan;
            }
            $data_detail_so = DB::table('pemesanan_penjualan_detail as a')
                ->leftjoin('item as i', 'a.id_item', '=', 'i.id_item')
                ->where('a.id_pemesanan_penjualan_header', '=', $id_pemesanan_penjualan_header)
                ->select('a.*', 'i.*')
                ->get();
        }
        return view('pengiriman.detail', ['title' => "Detail Pengiriman No " . $no_pengiriman, 'data_detail_so' => $data_detail_so, 'id_pengiriman_header' => $id_pengiriman_header, 'no_pengiriman' => $no_pengiriman, 'no_pemesanan_penjualan_header' => $no_pemesanan_penjualan_header, 'tgl_pengiriman' => $tgl_pengiriman, 'keterangan' => $keterangan, 'data' => $data]);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_pengiriman_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
            'harga_satuan' => 'required',
            'no_pengiriman' => 'required',
            'tgl_pengiriman' => 'required',
        ]);

        try {
            $user_id = Auth::user()->id;
            $store = DB::select("SELECT * FROM pengambilan_pengiriman($request->id_pengiriman_header, $request->id_item, $request->kuantitas, $request->harga_satuan, $user_id)");
            foreach ($store as $p) {
                $message = $p->pengambilan_pengiriman;
            }

            return redirect('/pengiriman/detail/' . $request->id_pengiriman_header)->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/pengiriman/detail/' . $request->id_pengiriman_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail($id)
    {
        try {
            $pengiriman_detail = PengirimanDetail::findOrFail($id);
            $get = $pengiriman_detail->get();
            $id_pengiriman_header = 0;
            foreach ($get as $data) {
                $id_pengiriman_header = $data->id_pengiriman_header;
            }

            $pengiriman_detail->delete();
            return redirect('pengiriman/detail/' . $id_pengiriman_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('pengiriman/detail' . $id_pengiriman_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_pengiriman_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pengiriman($id_pengiriman_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_pengiriman;
            }
            return redirect('pengiriman')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pengiriman')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('pengiriman.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}