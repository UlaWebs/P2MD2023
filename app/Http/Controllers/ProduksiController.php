<?php

namespace App\Http\Controllers;

use App\Models\ProduksiHeader;
use App\Helpers\Errormsg;
use App\Models\ProduksiDetailBahanBaku;
use App\Models\ProduksiDetailOutput;
use App\Models\ProduksiDetailBop;
use App\Models\ProduksiDetailBtk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('produksi_header as a')
            ->leftJoin('produksi_detail_output as b', 'a.id_produksi_header', '=', 'b.id_produksi_header')
            ->leftJoin('item as c', 'b.id_item', '=', 'c.id_item')
            ->leftjoin(DB::raw('(SELECT id_produksi_header, sum(x.kuantitas*y.harga_satuan) as total_bbb FROM pengambilan as x left join persediaan as y on x.id_persediaan_header = y.id_persediaan_header left join produksi_detail_bhn_baku as z on x.id_produksi_detail_bhn_baku = z.id_produksi_detail_bhn_baku group by z.id_produksi_header)as d'), 'a.id_produksi_header', '=', 'd.id_produksi_header')
            ->leftjoin(DB::raw('(SELECT id_produksi_header, sum(x.kuantitas*y.harga_satuan) as total_bop FROM pengambilan as x left join persediaan as y on x.id_persediaan_header = y.id_persediaan_header left join produksi_detail_bop as z on x.id_produksi_detail_bop = z.id_produksi_detail_bop group by z.id_produksi_header)as e'), 'a.id_produksi_header', '=', 'e.id_produksi_header')
            ->leftjoin(DB::raw('(SELECT id_produksi_header, sum(biaya_tenaga_kerja)as total_btk FROM produksi_detail_tenaga_kerja group by id_produksi_header)as f'), 'a.id_produksi_header', '=', 'f.id_produksi_header')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'produksi') as g"), 'a.id_produksi_header', '=', 'g.id_transaksi')
            ->select('a.*', 'b.id_item', 'b.kuantitas', 'c.*', DB::raw('coalesce(d.total_bbb,0.00) as total_bbb'), DB::raw('coalesce(e.total_bop, 0.00) as total_bop'), DB::raw('coalesce(f.total_btk, 0.00) as total_btk'), DB::raw('coalesce(d.total_bbb, 0.00) + coalesce(e.total_bop, 0.00) + coalesce(f.total_btk, 0.00) as biaya_produksi'), DB::raw('(coalesce(d.total_bbb, 0.00) + coalesce(e.total_bop, 0.00) + coalesce(f.total_btk, 0.00))/b.kuantitas as harga_satuan'), 'g.no_jurnal', 'g.id_jurnal_header')
            ->get();
        return view('produksi/index', ['data' => $data, 'title' => "Produksi"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data_produk_jadi = DB::table('item')->where('jenis_item', '=', 'Barang Jadi')->orWhere('jenis_item', '=', 'Barang Dalam Proses')->get();
        return view('produksi.create', ['title' => "Input Produksi", 'data_produk_jadi' => $data_produk_jadi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_produksi' => 'required',
            'keterangan' => 'required',
            'id_item_output' => 'required',
            'kuantitas' => 'required',
        ]);

        $tgl = date_create($request->tgl_produksi);
        $tgl_format = date_format($tgl, "Y-m-d");

        try {
            $user_id = Auth::user()->id;
            $store = DB::select("SELECT * FROM produksi_bom ('$tgl_format', $request->id_item_output, $request->kuantitas, '$request->keterangan', $user_id)");
            $result = "";
            foreach($store as $data){
                $result=$data->produksi_bom;
            }
            $hasil = explode("|",$result);
            if($hasil[0]=='F'){
                return redirect('/produksi/create')->with('warning',$hasil[1]);
            }else{
                $id=$hasil[1];
                return redirect('/produksi/detail/' . $id)->with('success', 'Data Berhasil di Input');
            }


        } catch (\Exception $e) {
            $id = $hasil[1];
            return redirect('/produksi/detail/' . $id)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_produksi_header)
    {
        $header = DB::table('produksi_header as a')
            ->where('id_produksi_header', '=', $id_produksi_header)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_produksi_header = $p->id_produksi_header;
                $tgl_produksi = $p->tgl_produksi;
                $keterangan = $p->keterangan;
            }
        }

        return view('produksi.edit', ['id_produksi_header' => $id_produksi_header, 'tgl_produksi' => $tgl_produksi, 'keterangan' => $keterangan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_produksi_header' => 'required',
            'tgl_produksi' => 'required',
            'keterangan' => 'required'
        ]);

        try {
            $update = ProduksiHeader::where('id_produksi_header', $request->id_produksi_header)->update(['tgl_produksi' => $request->tgl_produksi, 'keterangan' => $request->keterangan, 'status' => "Pending", 'user_id_updated' => Auth::user()->id]);
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('success', 'Data Berhasil di Update');
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_produksi_header)
    {
        try {
            $produksi = ProduksiHeader::findOrFail($id_produksi_header);
            $produksi->delete();
            return redirect()->route('produksi')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('produksi')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    // Detail
    public function detail($id_produksi_header)
    {
        $header = DB::table('produksi_header as a')
            ->where('id_produksi_header', '=', $id_produksi_header)
            ->select('a.*')
            ->get();

        $data_output = DB::table('produksi_detail_output as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftjoin('persediaan as c', 'a.id_produksi_detail_output', '=', 'c.id_produksi_detail_output')
            ->where('id_produksi_header', '=', $id_produksi_header)
            ->select('a.*', 'b.*', 'c.harga_satuan')
            ->get();

        $data_bbb = DB::table('produksi_detail_bhn_baku as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftjoin(
                DB::raw('(SELECT x.id_produksi_detail_bhn_baku,
                sum(x.kuantitas*y.harga_satuan) as harga_pengambilan
                FROM pengambilan as x LEFT JOIN persediaan as y ON x.id_persediaan_header = y.id_persediaan_header 
                GROUP BY id_produksi_detail_bhn_baku) as c'),
                'a.id_produksi_detail_bhn_baku',
                '=',
                'c.id_produksi_detail_bhn_baku'
            )
            ->where('id_produksi_header', '=', $id_produksi_header)
            ->select('a.*', 'b.*', 'c.harga_pengambilan')
            ->get();

        $data_bop = DB::table('produksi_detail_bop as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftjoin(
                DB::raw('(SELECT x.id_produksi_detail_bop,
                    sum(x.kuantitas*y.harga_satuan) as harga_pengambilan
                    FROM pengambilan as x LEFT JOIN persediaan as y ON x.id_persediaan_header = y.id_persediaan_header GROUP BY id_produksi_detail_bop) as c'),
                'a.id_produksi_detail_bop',
                '=',
                'c.id_produksi_detail_bop'
            )
            ->where('id_produksi_header', '=', $id_produksi_header)
            ->select('a.*', 'b.*', 'c.harga_pengambilan')
            ->get();

        $data_detail_pekerja = DB::table('produksi_detail_tenaga_kerja as a')
            ->leftjoin('pekerja as b', 'a.id_pekerja', '=', 'b.id_pekerja')
            ->where('id_produksi_header', '=', $id_produksi_header)
            ->select('a.*', 'b.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $no_produksi = $p->no_produksi;
                $tgl_produksi = $p->tgl_produksi;
                $keterangan = $p->keterangan;
                $status = $p->status;
                $tgl_selesai = $p->tgl_selesai;
            }
        }
        $data_item_produk_jadi = DB::table('item')->where('jenis_item', '=', 'Barang Jadi')->orWhere('jenis_item', '=', 'Barang Dalam Proses')->get();

        $data_pekerja = DB::table('pekerja')->get();

        $data_item_bbb = DB::table('persediaan as a')
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
            ->where('b.jenis_item', '=', 'Bahan Baku')->orWhere('b.jenis_item', '=', 'Barang Dalam Proses')
            ->get();

        $data_item_bop = DB::table('persediaan as a')
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
            ->where('b.jenis_item', '=', 'Bahan Penolong')
            ->get();

        return view('produksi.detail', ['title' => "Detail Produksi", 'data_item_produk_jadi' => $data_item_produk_jadi, 'data_pekerja' => $data_pekerja, 'data_detail_pekerja' => $data_detail_pekerja, 'id_produksi_header' => $id_produksi_header, 'no_produksi' => $no_produksi, 'tgl_produksi' => $tgl_produksi, 'keterangan' => $keterangan, 'status' => $status, 'tgl_selesai' => $tgl_selesai, 'data_output' => $data_output, 'data_bbb' => $data_bbb, 'data_bop' => $data_bop, 'data_item_bbb' => $data_item_bbb, 'data_item_bop' => $data_item_bop]);
    }

    public function storedetailoutput(Request $request)
    {
        $validated = $request->validate([
            'id_produksi_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
        ]);

        try {
            $store = ProduksiDetailOutput::create(['id_produksi_header' => $request->id_produksi_header, 'id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'user_id_created' => Auth::user()->id]);
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function storedetailbbb(Request $request)
    {
        $validated = $request->validate([
            'id_produksi_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
        ]);

        try {
            $user_id = Auth::user()->id;
            $store = DB::select("SELECT * FROM pengambilan_produksi_bhn_baku($request->id_produksi_header, $request->id_item, $request->kuantitas, $user_id)");
            foreach ($store as $p) {
                $message = $p->pengambilan_produksi_bhn_baku;
            }
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));;
        }
    }

    public function storedetailbop(Request $request)
    {
        $validated = $request->validate([
            'id_produksi_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
        ]);

        try {
            $user_id = Auth::user()->id;
            $store = DB::select("SELECT * FROM pengambilan_produksi_bop($request->id_produksi_header, $request->id_item, $request->kuantitas, $user_id)");
            foreach ($store as $p) {
                $message = $p->pengambilan_produksi_bop;
            }
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function storedetailbtk(Request $request)
    {
        $validated = $request->validate([
            'id_produksi_header' => 'required',
            'id_pekerja' => 'required',
            'biaya_tenaga_kerja' => 'required',
        ]);

        try {
            $store = ProduksiDetailBtk::create(['id_produksi_header' => $request->id_produksi_header, 'id_pekerja' => $request->id_pekerja, 'biaya_tenaga_kerja' => $request->biaya_tenaga_kerja, 'user_id_created' => Auth::user()->id]);
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $request->id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetailoutput($id_produksi_detail)
    {
        try {
            $produksi_detail = ProduksiDetailOutput::where('id_produksi_detail_output', '=' , $id_produksi_detail);
            $get = $produksi_detail->get();
            $id_produksi_header = 0;
            foreach ($get as $data) {
                $id_produksi_header = $data->id_produksi_header;
            }

            $produksi_detail->delete();
            return redirect('/produksi/detail/' . $id_produksi_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetailbbb($id_produksi_detail)
    {
        try {
            $produksi_detail = ProduksiDetailBahanBaku::where('id_produksi_detail_bhn_baku', '=' ,$id_produksi_detail);
            $get = $produksi_detail->get();
            $id_produksi_header = 0;
            foreach ($get as $data) {
                $id_produksi_header = $data->id_produksi_header;
            }

            $produksi_detail->delete();
            return redirect('/produksi/detail/' . $id_produksi_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetailbop($id_produksi_detail)
    {
        try {
            $produksi_detail = ProduksiDetailBop::where('id_produksi_detail_bop', '=' ,$id_produksi_detail);
            $get = $produksi_detail->get();
            $id_produksi_header = 0;
            foreach ($get as $data) {
                $id_produksi_header = $data->id_produksi_header;
            }

            $produksi_detail->delete();
            return redirect('/produksi/detail/' . $id_produksi_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetailbtk($id_produksi_detail)
    {
        try {
            $produksi_detail = ProduksiDetailBtk::where('id_produksi_detail_tenaga_kerja', '=' ,$id_produksi_detail);
            $get = $produksi_detail->get();
            $id_produksi_header = 0;
            foreach ($get as $data) {
                $id_produksi_header = $data->id_produksi_header;
            }

            $produksi_detail->delete();
            return redirect('/produksi/detail/' . $id_produksi_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $id_produksi_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
    public function finishproduksi($id)
    {
        try {
            $store = DB::select("SELECT * FROM produksi_output($id)");
            foreach ($store as $p) {
                $message = $p->produksi_output;
            }
            return redirect('/produksi/detail/' . $id)->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/produksi/detail/' . $id)->with('warning', Errormsg::errordb($e->getCode()));;
        }
    }

    public function jurnal($id_produksi_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_produksi($id_produksi_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_produksi;
            }
            return redirect('produksi')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('produksi')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('produksi.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}
