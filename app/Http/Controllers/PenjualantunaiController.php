<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Kas;
use App\Models\Pelanggan;
use App\Models\PenjualanTunaiDetail;
use App\Models\PenjualanTunaiHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualantunaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('penjualan_tunai_header as a')
            ->leftjoin('pelanggan as b', 'a.id_pelanggan', '=', 'b.id_pelanggan')
            ->leftjoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->leftjoin(DB::raw('(SELECT x.id_penjualan_tunai_header, sum(x.kuantitas * x.harga_jual) as total_penjualan, string_agg(y.nama_item, \', \') as nama_item
            FROM penjualan_tunai_detail x LEFT JOIN item y ON x.id_item = y.id_item GROUP BY x.id_penjualan_tunai_header) as d'), 'a.id_penjualan_tunai_header', '=', 'd.id_penjualan_tunai_header')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'penjualan tunai') as e"), 'a.id_penjualan_tunai_header', '=', 'e.id_transaksi')
            ->select('a.*', 'b.*', 'c.*', DB::raw('coalesce(d.total_penjualan, 0.00) as total_penjualan'), 'd.nama_item', 'e.no_jurnal', 'e.id_jurnal_header')
            ->get();
        return view('penjualantunai/index', ['data' => $data, 'title' => "Penjualan Tunai"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pelanggan = Pelanggan::all();
        $kas = Kas::all();
        return view('penjualantunai.create', ['pelanggan' => $pelanggan, 'kas' => $kas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_penjualan' => 'required',
            'keterangan' => 'required',
            'id_pelanggan' => 'required',
            'kas' => 'required',
        ]);

        $tgl = date_create($request->tgl_penjualan);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_penjualan = DB::select("SELECT * FROM no_penjualan('$tgl_format')");
        foreach ($get_no_penjualan as $p) {
            $no_penjualan = $p->no_penjualan;
        }

        try {
            $store = PenjualanTunaiHeader::create([
                'no_penjualan_header' => $no_penjualan,
                'tgl_penjualan' => $request->tgl_penjualan,
                'keterangan' => $request->keterangan,
                'id_pelanggan' => $request->id_pelanggan,
                'id_kas' => $request->kas,
                'user_id_created' => Auth::user()->id
            ]);
            $id = $store->id_penjualan_tunai_header;
            return redirect('/penjualantunai/detail/' . $id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/penjualantunai/create/')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_penjualan)
    {
        $header = DB::table('penjualan_tunai_header as a')
            ->where('id_penjualan_tunai_header', '=', $id_penjualan)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_penjualan_tunai_header = $p->id_penjualan_tunai_header;
                $tgl_penjualan = $p->tgl_penjualan;
                $keterangan = $p->keterangan;
                $id_kas = $p->id_kas;
                $id_pelanggan = $p->id_pelanggan;
            }
        }
        $pelanggan = Pelanggan::all();
        $kas = Kas::all();
        return view('penjualantunai.edit', ['pelanggan' => $pelanggan, 'kas' => $kas, 'id_penjualan_tunai_header' => $id_penjualan_tunai_header, 'tgl_penjualan' => $tgl_penjualan, 'keterangan' => $keterangan, 'id_kas' => $id_kas, 'id_pelanggan' => $id_pelanggan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    { {
            $validated = $request->validate([
                'id_penjualan_tunai_header' => 'required',
                'tgl_penjualan' => 'required',
                'keterangan' => 'required',
                'id_pelanggan' => 'required',
                'kas' => 'required',
            ]);

            try {
                $update = PenjualanTunaiHeader::where('id_penjualan_tunai_header', $request->id_penjualan_tunai_header)->update([
                    'tgl_penjualan' => $request->tgl_penjualan,
                    'keterangan' => $request->keterangan,
                    'id_pelanggan' => $request->id_pelanggan,
                    'id_kas' => $request->kas,
                    'user_id_updated' => Auth::user()->id
                ]);
                return redirect('/penjualantunai/detail/' . $request->id_penjualan_tunai_header)->with('success', 'Data Berhasil di Input');
            } catch (\Exception $e) {
                return redirect('/penjualantunai')->with('warning', Errormsg::errordb($e->getCode()));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_penjualan)
    {
        try {
            $penjualan_tunai = PenjualanTunaiHeader::findOrFail($id_penjualan);
            $penjualan_tunai->delete();
            return redirect()->route('penjualantunai')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('penjualantunai')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
    public function detail($id_penjualan_tunai_header)
    {
        $header = DB::table('penjualan_tunai_header as a')
            ->leftJoin('pelanggan as b', 'a.id_pelanggan', '=', 'b.id_pelanggan')
            ->leftJoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->where('id_penjualan_tunai_header', '=', $id_penjualan_tunai_header)
            ->select('a.*', 'b.*', 'c.*')
            ->get();

        $data = DB::table('penjualan_tunai_detail as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->where('id_penjualan_tunai_header', '=', $id_penjualan_tunai_header)
            ->select('a.*', 'b.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $no_penjualan_header = $p->no_penjualan_header;
                $tgl_penjualan = $p->tgl_penjualan;
                $keterangan = $p->keterangan;
                $nama_kas = $p->nama_kas;
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

        return view('penjualantunai.detail', [
            'title' => "Detail Penjualan Tunai No " . $no_penjualan_header,
            'id_penjualan_tunai_header' => $id_penjualan_tunai_header,
            'data_item' => $data_item,
            'no_penjualan_header' => $no_penjualan_header,
            'tgl_penjualan' => $tgl_penjualan,
            'keterangan' => $keterangan,
            'nama_kas' => $nama_kas,
            'nama_pelanggan' => $nama_pelanggan,
            'data' => $data
        ]);
    }
    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_penjualan_tunai_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
            'harga_jual' => 'required',
            'no_penjualan_header' => 'required',
            'tgl_penjualan' => 'required',
        ]);

        try {
            $user_id = Auth::user()->id;
            $store = DB::select("SELECT * FROM pengambilan_penjualan($request->id_penjualan_tunai_header, $request->id_item, $request->kuantitas, $request->harga_jual, $user_id)");
            foreach ($store as $p) {
                $message = $p->pengambilan_penjualan;
            }

            return redirect('/penjualantunai/detail/' . $request->id_penjualan_tunai_header)->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/penjualantunai/detail/' . $request->id_penjualan_tunai_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail($id_penjualan_tunai_detail)
    {
        try {
            $penjualan_tunai = PenjualanTunaiDetail::where($id_penjualan_tunai_detail);
            $get = $penjualan_tunai->get();
            $id_penjualan_tunai_header = 0;
            foreach ($get as $data) {
                $id_penjualan_tunai_header = $data->id_penjualan_tunai_header;
            }

            $penjualan_tunai->delete();
            return redirect('penjualantunai/detail/' . $id_penjualan_tunai_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('penjualantunai/detail' . $id_penjualan_tunai_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_penjualan_tunai_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_penjualan_tunai($id_penjualan_tunai_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_penjualan_tunai;
            }
            return redirect('penjualantunai')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('penjualantunai')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('penjualantunai.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}