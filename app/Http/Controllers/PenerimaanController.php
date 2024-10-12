<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\PemesananHeader;
use App\Models\PenerimaanDetail;
use App\Models\PenerimaanHeader;
use App\Models\Persediaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('penerimaan_header as a')
            ->leftJoin('pemesanan_header as b', 'a.id_pemesanan_header', '=', 'b.id_pemesanan_header')
            ->leftJoin('supplier as c', 'b.id_supplier', '=', 'c.id_supplier')
            ->leftJoin(DB::raw('(select id_penerimaan_header, sum(kuantitas*(base_price+ppn)) as subtotal_penerimaan from penerimaan_detail group by id_penerimaan_header) as d'), 'a.id_penerimaan_header', '=', 'd.id_penerimaan_header')
            ->leftJoin(DB::raw('(select id_pemesanan_header, sum(kuantitas*(base_price+ppn)) as subtotal_pemesanan from pemesanan_detail group by id_pemesanan_header) as e'), 'a.id_pemesanan_header', '=', 'e.id_pemesanan_header')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'penerimaan') as f"), 'a.id_penerimaan_header', '=', 'f.id_transaksi')
            ->leftJoin(DB::raw('(select xx.id_penerimaan_header, sum(xx.nominal_pembayaran) as total_pembayaran from
            pelunasan_pembelian as xx group by xx.id_penerimaan_header) as
            g'),'a.id_penerimaan_header','=','g.id_penerimaan_header')
            ->select('a.*', 'b.*', 'c.*', 'd.subtotal_penerimaan', 'e.subtotal_pemesanan', 'f.no_jurnal', 'f.id_jurnal_header', 'g.total_pembayaran')
            ->orderBy('a.tgl_penerimaan')
            ->get();
        return view('penerimaan/index', ['data' => $data, 'title' => "Penerimaan"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = PemesananHeader::where('status','=','Pending')->get();
        return view('penerimaan.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_penerimaan' => 'required',
            'id_pemesanan_header' => 'required',
            'keterangan' => 'required',
            'no_faktur_pembelian' => 'required',
        ]);

        $tgl = date_create($request->tgl_penerimaan);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pe = DB::select("SELECT * FROM no_pe('$tgl_format')");
        foreach ($get_no_pe as $p) {
            $no_pe = $p->no_pe;
        }

        try {
            $store = PenerimaanHeader::create(['no_penerimaan' => $no_pe, 'tgl_penerimaan' => $request->tgl_penerimaan, 'keterangan' => $request->keterangan, 'id_pemesanan_header' => $request->id_pemesanan_header, 'no_faktur_pembelian' => $request->no_faktur_pembelian, 'user_id_created' => Auth::user()->id]);
            $id = $store->id_penerimaan_header;
            return redirect('/penerimaan/detail/' . $id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/penerimaan/detail/' . $id)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_penerimaan_header)
    {
        $data = PemesananHeader::all();
        $get = DB::table('penerimaan_header')->where('id_penerimaan_header', $id_penerimaan_header)->get();
        foreach ($get as $p) {
            $id_penerimaan_header = $p->id_penerimaan_header;
            $no_penerimaan = $p->no_penerimaan;
            $tgl_penerimaan = $p->tgl_penerimaan;
            $keterangan = $p->keterangan;
            $id_pemesanan_header = $p->id_pemesanan_header;
            $no_faktur_pembelian = $p->no_faktur_pembelian;
        }
        return view('penerimaan.edit', ['data' => $data, 'id_penerimaan_header' => $id_penerimaan_header, 'no_penerimaan' => $no_penerimaan, 'tgl_penerimaan' => $tgl_penerimaan, 'keterangan' => $keterangan, 'id_pemesanan_header' => $id_pemesanan_header, 'no_faktur_pembelian' => $no_faktur_pembelian]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenerimaanHeader $penerimaan)
    {
        $validated = $request->validate([
            'no_penerimaan' => 'required',
            'tgl_penerimaan' => 'required',
            'id_pemesanan_header' => 'required',
            'keterangan' => 'required',
            'no_faktur_pembelian' => 'required',
        ]);

        try {
            $update = PenerimaanHeader::where('id_penerimaan_header', $request->id_penerimaan_header)->update(['no_penerimaan' => $request->no_penerimaan, 'tgl_penerimaan' => $request->tgl_penerimaan, 'keterangan' => $request->keterangan, 'id_pemesanan_header' => $request->id_pemesanan_header, 'no_faktur_pembelian' => $request->no_faktur_pembelian]);
            return redirect()->route('penerimaan')->with('success', 'Data Berhasil di Update');
        } catch (\Exception $e) {
            return redirect('/penerimaan/edit/' . $request->id_penerimaan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_penerimaan_header)
    {
        try {
            $penerimaan_header = PenerimaanHeader::findOrFail($id_penerimaan_header);
            $penerimaan_header->delete();
            return redirect()->route('penerimaan')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('penerimaan')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id_penerimaan_header)
    {
        $header = DB::table('penerimaan_header as a')
            ->leftJoin('pemesanan_header as b', 'a.id_pemesanan_header', '=', 'b.id_pemesanan_header')
            ->leftJoin('supplier as c','b.id_supplier','c.id_supplier')
            ->where('a.id_penerimaan_header', '=', $id_penerimaan_header)
            ->select('a.*', 'b.*', 'a.keterangan', 'b.status as status_pemesanan', 'c.nama_supplier')
            ->get();

        $data = DB::table('penerimaan_header as a')
            ->leftjoin('penerimaan_detail as b', 'b.id_penerimaan_header', '=', 'a.id_penerimaan_header')
            ->leftjoin('item as c', 'b.id_item', '=', 'c.id_item')
            ->leftJoin('item_satuan as d', 'b.id_item_satuan','=','d.id_item_satuan')
            ->where('b.id_penerimaan_header', '=', $id_penerimaan_header)
            ->select('a.*', 'b.*', 'c.nama_item',DB::raw("
            CASE
                when b.id_item_satuan ISNULL then c.satuan
                else d.satuan
            END as satuan
            ")
            )
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pemesanan_header = $p->id_pemesanan_header;
                $no_penerimaan = $p->no_penerimaan;
                $tgl_penerimaan = $p->tgl_penerimaan;
                $no_pemesanan = $p->no_pemesanan;
                $keterangan = $p->keterangan;
                $status_pemesanan = $p->status_pemesanan;
                $nama_supplier = $p->nama_supplier;
            }
            $data_detail_po = DB::table('pemesanan_detail as a')
                ->leftjoin('item as i', 'a.id_item', '=', 'i.id_item')
                ->leftJoin('item_satuan as c', 'a.id_item_satuan','=','c.id_item_satuan')
                ->where('a.id_pemesanan_header', '=', $id_pemesanan_header)
                ->select('a.*', 'i.nama_item', DB::raw("
                CASE
                    when a.id_item_satuan ISNULL then i.satuan
                    else c.satuan
                END as satuan
                ")
                )
                ->get();
        }
        $data_item = DB::table('item')->get();
        return view('penerimaan.detail', ['title' => "Detail Penerimaan Atas Pemesanan No " . $no_pemesanan, 'data_detail_po' => $data_detail_po, 'id_penerimaan_header' => $id_penerimaan_header,'id_pemesanan_header' => $id_pemesanan_header, 'no_penerimaan' => $no_penerimaan, 'no_pemesanan' => $no_pemesanan, 'tgl_penerimaan' => $tgl_penerimaan, 'keterangan' => $keterangan, 'status_pemesanan' => $status_pemesanan, 'nama_supplier' => $nama_supplier, 'data' => $data, 'data_item' => $data_item]);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_penerimaan_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',

        ]);
        try {
            $get_po_detail = DB::table('pemesanan_detail as a')
                ->leftJoin('item_satuan as b','a.id_item_satuan','=','b.id_item_satuan')
                ->where('a.id_pemesanan_header', '=',$request->id_pemesanan_header)
                ->where('a.id_item', '=', $request->id_item)
                ->select('a.*',
                DB::raw("
                CASE
                    when a.id_item_satuan NOTNULL then b.satuan
                    else NULL
                END as satuan,
                CASE
                    when a.id_item_satuan NOTNULL then b.konversi
                    else 1
                END as konversi,
                CASE
                    when a.id_item_satuan NOTNULL then b.operator
                    else '*'
                END as operator
                ")
                )
                ->get();
            $kuantitas_pemesanan = 0;
            foreach($get_po_detail as $dt){
                $kuantitas_pemesanan = $dt->kuantitas;
            }
            if($request->kuantitas > $kuantitas_pemesanan){
                return redirect('/penerimaan/detail/' . $request->id_penerimaan_header)->with('warning', 'Kuantitas Penerimaan Lebih Besar Dari Pemesanan');
            } else {
                foreach($get_po_detail as $data_detail){
                    $store = PenerimaanDetail::create([
                        'id_penerimaan_header' => $request->id_penerimaan_header,
                        'id_item' => $request->id_item,
                        'kuantitas' => $request->kuantitas,
                        'base_price' => $data_detail->base_price,
                        'ppn' => $data_detail->ppn,
                        'id_item_satuan' => $data_detail->id_item_satuan,
                        'user_id_created' => Auth::user()->id
                    ]);

                    if ($data_detail->id_item_satuan == ''){
                        $kuantitas = $request->kuantitas;
                        $base_price = $data_detail->base_price;
                        $ppn = $data_detail->ppn;
                    }
                    else {
                        if($data_detail->operator == '*'){
                            $kuantitas = $request->kuantitas * $data_detail->konversi;
                            $base_price = $data_detail->base_price/$data_detail->konversi;
                            $ppn = $data_detail->ppn/$data_detail->konversi;
                        }
                        else {
                            $kuantitas = $request->kuantitas / $data_detail->konversi;
                            $base_price = $data_detail->base_price*$data_detail->konversi;
                            $ppn = $data_detail->ppn*$data_detail->konversi;
                        }
                    }
                    $stock = Persediaan::create([
                        'user_id_created' => Auth::user()->id,
                        'tgl_persediaan' => $request->tgl_penerimaan,
                        'keterangan' => 'penerimaan no ' . $request->no_penerimaan,
                        'id_item' => $request->id_item,
                        'kuantitas' => $kuantitas,
                        'harga_satuan' => $base_price+$ppn,
                        'id_penerimaan_detail' => $store->id_penerimaan_detail
                    ]);
                }

                return redirect('/penerimaan/detail/' . $request->id_penerimaan_header)->with('success', 'Data Berhasil
                di Input');
            }

            
        } catch (\Exception $e) {
            return redirect('/penerimaan/detail/' . $request->id_penerimaan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail($id_penerimaan_detail)
    {
        try {
            $penerimaan_detail = PenerimaanDetail::findOrFail($id_penerimaan_detail);
            $get = $penerimaan_detail->get();
            $id_penerimaan_header = 0;
            foreach ($get as $data) {
                $id_penerimaan_header = $data->id_penerimaan_header;
            }

            $penerimaan_detail->delete();
            return redirect('/penerimaan/detail/' . $id_penerimaan_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('penerimaan/detail/' . $id_penerimaan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_penerimaan_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_penerimaan($id_penerimaan_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal) {
                $msg = $data_jurnal->jurnal_penerimaan;
            }
            return redirect('penerimaan')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('penerimaan')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('penerimaan.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
}
