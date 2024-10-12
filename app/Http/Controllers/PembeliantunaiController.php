<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\PembelianTunaiHeader;
use App\Models\PembelianTunaiDetail;
use App\Models\Supplier;
use App\Models\Kas;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembeliantunaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pembelian_tunai_header as a')
            ->leftJoin('supplier as b', 'a.id_supplier', '=', 'b.id_supplier')
            ->leftJoin(DB::raw('(select id_pembelian_tunai_header, sum(kuantitas*(base_price+ppn)) as subtotal_pembelian_tunai from pembelian_tunai_detail group by id_pembelian_tunai_header) as c'), 'a.id_pembelian_tunai_header', '=', 'c.id_pembelian_tunai_header')
            ->leftJoin('kas as d', 'a.id_kas', '=', 'd.id_kas')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pembelian tunai') as e"), 'a.id_pembelian_tunai_header', '=', 'e.id_transaksi')
            ->select('a.*', 'b.*', 'c.subtotal_pembelian_tunai', 'd.*', 'e.no_jurnal', 'e.id_jurnal_header')
            ->get();
        return view('pembeliantunai/index', ['data' => $data, 'title' => "Pembelian Tunai"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplier = Supplier::all();
        $kas = Kas::all();
        return view('pembeliantunai.create', ['supplier' => $supplier, 'kas' => $kas]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pembelian_tunai' => 'required',
            'keterangan' => 'required',
            'id_supplier' => 'required',
            'kas' => 'required',
            'no_faktur_pembelian_tunai' => 'required',
        ]);

        $tgl = date_create($request->tanggal_pembelian_tunai);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pembelian = DB::select("SELECT * FROM no_pembelian('$tgl_format')");
        foreach ($get_no_pembelian as $p) {
            $no_pembelian = $p->no_pembelian;
        }

        try {
            $store = PembelianTunaiHeader::create([
                'no_pembelian_tunai' => $no_pembelian,
                'tanggal_pembelian_tunai' => $request->tanggal_pembelian_tunai,
                'keterangan' => $request->keterangan,
                'id_supplier' => $request->id_supplier,
                'id_kas' => $request->kas,
                'no_faktur_pembelian_tunai' => $request->no_faktur_pembelian_tunai,
                'user_id_created' => Auth::user()->id
            ]);
            $id = $store->id_pembelian_tunai_header;
            return redirect('/pembeliantunai/detail/' . $id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pembeliantunai/create/')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_pembelian_tunai)
    {
        $header = DB::table('pembelian_tunai_header as a')
            ->where('id_pembelian_tunai_header', '=', $id_pembelian_tunai)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pembelian_tunai_header = $p->id_pembelian_tunai_header;
                $tanggal_pembelian_tunai = $p->tanggal_pembelian_tunai;
                $keterangan = $p->keterangan;
                $id_kas = $p->id_kas;
                $id_supplier = $p->id_supplier;
                $no_faktur_pembelian_tunai = $p->no_faktur_pembelian_tunai;
            }
        }
        $supplier = Supplier::all();
        $kas = Kas::all();
        return view('pembeliantunai.edit', [
            'supplier' => $supplier,
            'kas' => $kas,
            'id_pembelian_tunai_header' => $id_pembelian_tunai_header,
            'tanggal_pembelian_tunai' => $tanggal_pembelian_tunai,
            'keterangan' => $keterangan,
            'id_kas' => $id_kas,
            'id_supplier' => $id_supplier,
            'no_faktur_pembelian_tunai'=> $no_faktur_pembelian_tunai,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_pembelian_tunai_header' => 'required',
            'tanggal_pembelian_tunai' => 'required',
            'keterangan' => 'required',
            'id_supplier' => 'required',
            'kas' => 'required',
            'no_faktur_pembelian_tunai' => 'required',
        ]);

        try {
            $update = PembelianTunaiHeader::where('id_pembelian_tunai_header', $request->id_pembelian_tunai_header)->update([
                'tanggal_pembelian_tunai' => $request->tanggal_pembelian_tunai,
                'keterangan' => $request->keterangan,
                'id_supplier' => $request->id_supplier,
                'id_kas' => $request->kas,
                'no_faktur_pembelian_tunai' => $request->no_faktur_pembelian_tunai,
                'user_id_updated' => Auth::user()->id
            ]);
            return redirect('/pembeliantunai/detail/' . $request->id_pembelian_tunai_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pembeliantunai')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pembelian_tunai)
    {
        try {
            $pembelian_tunai = PembelianTunaiHeader::findOrFail($id_pembelian_tunai);
            $pembelian_tunai->delete();
            return redirect()->route('pembeliantunai')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pembeliantunai')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id_pembelian_tunai_header)
    {
        $header = DB::table('pembelian_tunai_header as a')
            ->leftJoin('supplier as b', 'a.id_supplier', '=', 'b.id_supplier')
            ->leftJoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->where('id_pembelian_tunai_header', '=', $id_pembelian_tunai_header)
            ->select('a.*', 'b.*', 'c.*')
            ->get();

        $data = DB::table('pembelian_tunai_detail as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin('item_satuan as c', 'a.id_item_satuan','=','c.id_item_satuan')
            ->where('id_pembelian_tunai_header', '=', $id_pembelian_tunai_header)
            ->select('a.*', 'b.nama_item',
            DB::raw("
            CASE
                when a.id_item_satuan ISNULL then b.satuan
                else c.satuan
            END as satuan
            ")
            )
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $no_pembelian_tunai = $p->no_pembelian_tunai;
                $tanggal_pembelian_tunai = $p->tanggal_pembelian_tunai;
                $keterangan = $p->keterangan;
                $no_faktur_pembelian_tunai = $p->no_faktur_pembelian_tunai;
                $nama_kas = $p->nama_kas;
                $nama_supplier = $p->nama_supplier;
                $jenis_supplier = $p->jenis_supplier;
            }
        }

        $array_jenis_supplier= explode(" dan ",$jenis_supplier);

        $query=DB::table('item');
        foreach($array_jenis_supplier as $s){
            $query=$query->orWhere('jenis_item','=',trim($s));
        }
        $data_item=$query->get();

        return view('pembeliantunai.detail', [
            'title' => "Detail Pembelian Tunai No " . $no_pembelian_tunai,
            'id_pembelian_tunai_header' => $id_pembelian_tunai_header,
            'data_item' => $data_item,
            'no_pembelian_tunai' => $no_pembelian_tunai,
            'tanggal_pembelian_tunai' => $tanggal_pembelian_tunai,
            'keterangan' => $keterangan,
            'nama_kas' => $nama_kas,
            'nama_supplier' => $nama_supplier,
            'no_faktur_pembelian_tunai' => $no_faktur_pembelian_tunai,
            'data' => $data
        ]);
    }
    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_pembelian_tunai_header' => 'required',
            'id_item' => 'required',
            'id_item_satuan' => 'required',
            'kuantitas' => 'required',
            'ppn' => 'required',
            'harga_satuan' => 'required',
        ]);

        if($request->ppn == 't'){
            $ppn = $request->harga_satuan * 11/100;
            $base = $request->harga_satuan;
        }else{
            $base = $request->harga_satuan;
            $ppn = 0;
        }


        try {
            if ($request->id_item_satuan == 0){

                $store = PembelianTunaiDetail::create([
                    'id_pembelian_tunai_header' => $request->id_pembelian_tunai_header,
                    'id_item' => $request->id_item,
                    'kuantitas' => $request->kuantitas,
                    'base_price' => $base,
                    'ppn' => $ppn,
                    'user_id_created' => Auth::user()->id
                ]);
                $stock = Persediaan::create([
                    'user_id_created' => Auth::user()->id, 'tgl_persediaan' => $request->tanggal_pembelian_tunai,
                    'keterangan' => 'pembelian tunai no ' . $request->no_pembelian_tunai,
                    'id_item' => $request->id_item, 'kuantitas' => $request->kuantitas,
                    'harga_satuan' => $request->harga_satuan, 'id_pembelian_tunai_detail' => $store->id_pembelian_tunai_detail
                ]);

            }
            else {
                $store = PembelianTunaiDetail::create(['id_pembelian_tunai_header' => $request->id_pembelian_tunai_header, 'id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'base_price' => $base, 'ppn' => $ppn, 'id_item_satuan' =>$request->id_item_satuan, 'user_id_created' => Auth::user()->id]);
                $get_satuan = DB::table('item_satuan')->where('id_item_satuan','=',$request->id_item_satuan)->get();
                foreach ($get_satuan as $data_satuan){
                    $konversi = $data_satuan->konversi;
                    $operator = $data_satuan->operator;
                }
                if ($operator == '*'){
                    $kuantitas = $request->kuantitas * $konversi;
                    $base_price = $base / $konversi;
                    $nom_ppn = $ppn / $konversi;
                }
                else{
                    $kuantitas = $request->kuantitas / $konversi;
                    $base_price = $base * $konversi;
                    $nom_ppn = $ppn * $konversi;
                }
                $stock = Persediaan::create([
                    'user_id_created' => Auth::user()->id, 'tgl_persediaan' => $request->tanggal_pembelian_tunai,
                    'keterangan' => 'pembelian tunai no ' . $request->no_pembelian_tunai, 'id_item' => $request->id_item, 'kuantitas' => $kuantitas,
                    'harga_satuan' => $base_price+$nom_ppn, 'id_pembelian_tunai_detail' => $store->id_pembelian_tunai_detail
                ]);
            }
            return redirect('/pembeliantunai/detail/' . $request->id_pembelian_tunai_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pembeliantunai/detail/' . $request->id_pembelian_tunai_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
    public function destroydetail($id_pembelian_tunai_detail)
{
    try {
        $pembelian_detail = PembelianTunaiDetail::findOrFail($id_pembelian_tunai_detail);
        $id_pembelian_tunai_header = $pembelian_detail->id_pembelian_tunai_header;
        $pembelian_detail->delete();
        return redirect('/pembeliantunai/detail/' . $id_pembelian_tunai_header)->with('success', 'Data Berhasil di Hapus');
    } catch (\Exception $e) {
        return redirect('/pembeliantunai')->with('warning', Errormsg::errordb($e->getCode()));
    }
}


    public function jurnal($id_pembelian_tunai_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pembelian_tunai($id_pembelian_tunai_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_pembelian_tunai;
            }
            return redirect('pembeliantunai')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pembeliantunai')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('pembeliantunai.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}