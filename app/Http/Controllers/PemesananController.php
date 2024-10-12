<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\PemesananDetail;
use App\Models\PemesananHeader;
use App\Models\Supplier;
use App\Models\Kas;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('pemesanan_header as a')
            ->leftJoin('supplier as b', 'a.id_supplier', '=', 'b.id_supplier')
            ->leftJoin(DB::raw('(select id_pemesanan_header, sum(kuantitas*(base_price+ppn)) as subtotal_pemesanan from pemesanan_detail group by id_pemesanan_header) as c'), 'a.id_pemesanan_header', '=', 'c.id_pemesanan_header')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pemesanan') as d"), 'a.id_pemesanan_header', '=', 'd.id_transaksi')
            ->leftJoin(DB::raw('(select y.id_pemesanan_header, sum(kuantitas*(base_price+ppn)) as subtotal_penerimaan from penerimaan_detail as x left join penerimaan_header as y on x.id_penerimaan_header=y.id_penerimaan_header group by y.id_pemesanan_header) as e'),'a.id_pemesanan_header','=','e.id_pemesanan_header')
            ->leftJoin(DB::raw('(select yy.id_pemesanan_header, sum(xx.nominal_pembayaran) as total_pembayaran from pelunasan_pembelian as xx left join penerimaan_header as yy on xx.id_penerimaan_header=yy.id_penerimaan_header group by yy.id_pemesanan_header) as f'),'a.id_pemesanan_header','=','f.id_pemesanan_header')
            ->select('a.*', 'b.*', 'c.subtotal_pemesanan', 'd.no_jurnal', 'd.id_jurnal_header','e.subtotal_penerimaan','f.total_pembayaran',
            DB::raw("
                CASE
                    when COALESCE(e.subtotal_penerimaan,0)>0 AND a.tgl_jatuh_tempo <= now () AND
                    COALESCE (e.subtotal_penerimaan,0)>a.nominal_uang_muka + COALESCE (f.total_pembayaran,0) THEN 'NOK'
                    else 'OK'
                END as status_pembayaran,
                CASE
                     when COALESCE(e.subtotal_penerimaan,0)=0 then 'Belum Diterima'
                     when COALESCE(e.subtotal_penerimaan,0) > 0
                        AND COALESCE(e.subtotal_penerimaan,0) < COALESCE(c.subtotal_pemesanan,0) then 'Diterima Sebagian'
                    when COALESCE(e.subtotal_penerimaan,0) > 0 
                        AND COALESCE(e.subtotal_penerimaan,0) = COALESCE(c.subtotal_pemesanan,0) then 'Diterima Seluruhnya'
                    else ''
                END as status_penerimaan
            ")

            )
            ->orderBy('a.tgl_pemesanan', 'asc')
            ->get();
        return view('pemesanan/index', ['data' => $data, 'title' => "Pemesanan"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Supplier::all();
        $data_kas = Kas::all();
        return view('pemesanan.create', ['data' => $data, 'data_kas' => $data_kas]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_pemesanan' => 'required',
            'keterangan' => 'required',
            'id_supplier' => 'required',
            'tgl_jatuh_tempo' => 'required',
            'alamat_pengiriman' => 'required',
            'id_kas' => 'required',
            'nominal_uang_muka' => 'required',
        ]);

        $tgl = date_create($request->tgl_pemesanan);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_po = DB::select("SELECT * FROM no_po('$tgl_format')");
        foreach ($get_no_po as $p) {
            $no_po = $p->no_po;
        }

        try {
            $store = PemesananHeader::create(['no_pemesanan' => $no_po, 'tgl_pemesanan' => $request->tgl_pemesanan, 'keterangan' => $request->keterangan, 'status' => "Pending", 'id_supplier' => $request->id_supplier, 'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo, 'alamat_pengiriman' => $request->alamat_pengiriman, 'id_kas' => $request->id_kas, 'nominal_uang_muka' => $request->nominal_uang_muka,'user_id_created' => Auth::user()->id]);
            $id = $store->id_pemesanan_header;
            return redirect('/pemesanan/detail/' . $id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pemesanan/create/')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(pemesanan $pemesanan)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_pemesanan)
    {
        $header = DB::table('pemesanan_header as a')
            ->where('id_pemesanan_header', '=', $id_pemesanan)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pemesanan_header = $p->id_pemesanan_header;
                $tgl_pemesanan = $p->tgl_pemesanan;
                $keterangan = $p->keterangan;
                $tgl_jatuh_tempo = $p->tgl_jatuh_tempo;
                $id_supplier = $p->id_supplier;
                $alamat_pengiriman = $p->alamat_pengiriman;
                $nominal_uang_muka = $p->nominal_uang_muka;
            }
        }

        $data = Supplier::all();
        return view('pemesanan.edit', ['data' => $data, 'id_pemesanan_header' => $id_pemesanan_header, 'tgl_pemesanan' => $tgl_pemesanan, 'keterangan' => $keterangan, 'tgl_jatuh_tempo' => $tgl_jatuh_tempo, 'id_supplier' => $id_supplier, 'alamat_pengiriman' => $alamat_pengiriman, 'nominal_uang_muka' => $nominal_uang_muka]);
    }
    public function editdetail($id_pemesanan)
    {
        $header = DB::table('pemesanan_detail as a')
            ->where('id_pemesanan_detail', '=', $id_pemesanan)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pemesanan_detail = $p->id_pemesanan_detail;
                $id_pemesanan_header = $p->id_pemesanan_header;
                $id_item = $p->id_item;
                $kuantitas = $p->kuantitas;
                $base_price = $p->base_price;
                $ppn = $p->ppn;
                $harga_satuan = $base_price + $ppn;
                if ($p->id_item_satuan==''){
                    $id_item_satuan = 0;
                }
                else {
                    $id_item_satuan = $p->id_item_satuan;
                }
            }
        }

        $data_item = DB::table('item')->get();
        $data_satuan = DB::select("(select 0 as id_item_satuan, satuan, 1 as konversi, '*' as operator from item where id_item = $id_item) union (select id_item_satuan, satuan, konversi, operator from item_satuan where id_item = $id_item) order by id_item_satuan ");
        return view('pemesanan.editdetail', ['title' => "Edit Detail", 'id_pemesanan_header' => $id_pemesanan_header, 'data_item' => $data_item, 'data_satuan' => $data_satuan, 'id_pemesanan_detail' => $id_pemesanan_detail, 'id_item' => $id_item, 'kuantitas' => $kuantitas, 'harga_satuan' => $harga_satuan, 'id_item_satuan' => $id_item_satuan]);
    }
    public function updatedetail(Request $request)
    {
        $validated = $request->validate([
            'id_pemesanan_detail' => 'required',
            'id_pemesanan_header' => 'required',
            'id_item' => 'required',
            'kuantitas' => 'required',
            'id_item_satuan' => 'required',
            'harga_satuan' => 'required',
        ]);

        // $ppn = $request->harga_satuan * 11/100;
        // $base = round(($request->harga_satuan - $ppn), 2);
        $ppn = $request->harga_satuan * 11/100;
        $base = $request->harga_satuan;
        try {
            if($request->id_item_satuan==0){
                $store = PemesananDetail::where('id_pemesanan_detail', $request->id_pemesanan_detail)->update(['id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'base_price' => $base, 'ppn' => $ppn, 'user_id_created' => Auth::user()->id]);
            }
            else {
                $store = PemesananDetail::where('id_pemesanan_detail', $request->id_pemesanan_detail)->update(['id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'base_price' => $base, 'ppn' => $ppn, 'id_item_satuan' => $request->id_item_satuan, 'user_id_created' => Auth::user()->id]);
            }

            return redirect('/pemesanan/detail/' . $request->id_pemesanan_header)->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pemesanan/detail/' . $request->id_pemesanan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_pemesanan_header' => 'required',
            'tgl_pemesanan' => 'required',
            'keterangan' => 'required',
            'id_supplier' => 'required',
            'tgl_jatuh_tempo' => 'required',
            'alamat_pengiriman' => 'required',
            'nominal_uang_muka' => 'required',
        ]);

        try {
            $update = PemesananHeader::where('id_pemesanan_header', $request->id_pemesanan_header)->update(['tgl_pemesanan' => $request->tgl_pemesanan, 'keterangan' => $request->keterangan, 'status' => "Pending", 'id_supplier' => $request->id_supplier, 'tgl_jatuh_tempo' => $request->tgl_jatuh_tempo, 'alamat_pengiriman' => $request->alamat_pengiriman, 'nominal_uang_muka' => $request->nominal_uang_muka, 'user_id_created' => Auth::user()->id]);
            return redirect('/pemesanan/detail/' . $request->id_pemesanan_header)->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/pemesanan/detail/' . $request->id_pemesanan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_pemesanan)
    {
        try {
            $pemesanan = PemesananHeader::findOrFail($id_pemesanan);
            $pemesanan->delete();
            return redirect()->route('pemesanan')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('pemesanan')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id_pemesanan_header)
    {
        $header = DB::table('pemesanan_header as a')
            ->leftJoin('supplier as b', 'a.id_supplier', '=', 'b.id_supplier')
            ->leftJoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->where('id_pemesanan_header', '=', $id_pemesanan_header)
            ->select('a.*', 'b.*', 'c.*')
            ->get();

        $data = DB::table('pemesanan_detail as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin('item_satuan as c', 'a.id_item_satuan','=','c.id_item_satuan')
            ->where('id_pemesanan_header', '=', $id_pemesanan_header)
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
                $no_pemesanan = $p->no_pemesanan;
                $tgl_pemesanan = $p->tgl_pemesanan;
                $keterangan = $p->keterangan;
                $status = $p->status;
                $tgl_jatuh_tempo = $p->tgl_jatuh_tempo;
                $nama_supplier = $p->nama_supplier;
                $nama_kas = $p->nama_kas;
                $nominal_uang_muka = $p->nominal_uang_muka;
                $jenis_supplier = $p->jenis_supplier;
            }
        }
        $array_jenis_supplier= explode(" dan ",$jenis_supplier);
        $query=DB::table('item');
        foreach($array_jenis_supplier as $s){
            $query=$query->orWhere('jenis_item','=',trim($s));
        }
        $data_item=$query->get();
        return view('pemesanan.detail', ['title' => "Detail Pemesanan No " . $no_pemesanan, 'data_item' => $data_item, 'id_pemesanan_header' => $id_pemesanan_header, 'no_pemesanan' => $no_pemesanan, 'tgl_pemesanan' => $tgl_pemesanan, 'keterangan' => $keterangan, 'status' => $status, 'tgl_jatuh_tempo' => $tgl_jatuh_tempo, 'nama_supplier' => $nama_supplier, 'nama_kas' => $nama_kas, 'nominal_uang_muka' => $nominal_uang_muka,'data' => $data]);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_pemesanan_header' => 'required',
            'id_item' => 'required',
            'id_item_satuan' => 'required',
            'kuantitas' => 'required',
            'ppn' => 'required',
            'harga_satuan' => 'required',
        ]);
        if($request->ppn == 't'){
            // $ppn = $request->harga_satuan * 11/100;
            // $base = round(($request->harga_satuan - $ppn), 2);
            $base = $request->harga_satuan;
            $ppn = $base * 11/100;
        }
        else{
            $base = $request->harga_satuan;
            $ppn = 0;

        }
        try {
            if ($request->id_item_satuan == 0){
                $store = PemesananDetail::create(['id_pemesanan_header' => $request->id_pemesanan_header, 'id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'base_price' => $base, 'ppn' => $ppn, 'user_id_created' => Auth::user()->id]);
            }
            else {
                $store = PemesananDetail::create(['id_pemesanan_header' => $request->id_pemesanan_header, 'id_item' => $request->id_item, 'kuantitas' => $request->kuantitas, 'base_price' => $base, 'ppn' => $ppn,'id_item_satuan' => $request->id_item_satuan, 'user_id_created' => Auth::user()->id]);
            }

            return redirect('/pemesanan/detail/' . $request->id_pemesanan_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/pemesanan/detail/' . $request->id_pemesanan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail($id_pemesanan_detail)
    {
        $pemesanan_detail = PemesananDetail::where('id_pemesanan_detail', '=', $id_pemesanan_detail);
        $get = $pemesanan_detail->get();
        $id_pemesanan_header = 0;
        foreach ($get as $data) {
            $id_pemesanan_header = $data->id_pemesanan_header;
        }

        try {
            $pemesanan_detail->delete();
            return redirect('/pemesanan/detail/' . $id_pemesanan_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/pemesanan/detail/' . $id_pemesanan_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_pemesanan_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pemesanan($id_pemesanan_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal){
                $msg = $data_jurnal->jurnal_pemesanan;
            }
            return redirect ('pemesanan')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pemesanan')->with('warning', Errormsg::errordb($e->getCode()));
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
        return view('pemesanan.jurnaldetail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }
    public function getdetailbyitem($id_pemesanan_header, $id_item){
        $get_data = DB::table('pemesanan_detail as a')
        ->leftjoin('item as i', 'a.id_item', '=', 'i.id_item')
        ->leftJoin('item_satuan as c', 'a.id_item_satuan','=','c.id_item_satuan')
        ->where('a.id_pemesanan_header', '=', $id_pemesanan_header)
        ->where('a.id_item','=',$id_item)
        ->select('a.*', 'i.nama_item', DB::raw("
        CASE
            when a.id_item_satuan ISNULL then i.satuan
            else c.satuan
        END as satuan
        ")
    )
    ->get();
        return response()->json($get_data);
    }
    public function updatestatus($id,$id_penerimaan)
    {
        try {
            DB::table('pemesanan_header')->where('id_pemesanan_header','=',$id)->update(['status'=>'Selesai']);
            return redirect('/penerimaan/detail/' . $id_penerimaan)->with('success', 'Data Berhasil diUbah');
        } catch (\Exception $e) {
            return redirect('/penerimaan/detail/' . $id_penerimaan)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
    public function updateuangmuka(Request $request)
    {
        $validated = $request->validate([
            'id2' => 'required',
            'nominal_uang_muka' => 'required',
        ]);

        try {
            $update = PemesananHeader::where('id_pemesanan_header','=',$request->id2)->update(['nominal_uang_muka' => $request->nominal_uang_muka,'user_id_updated' => Auth::user()->id]);
            return redirect('/pemesanan/detail/' . $request->id2)->with('success', 'Data Berhasil Disimpan');
        } catch (\Exception $e) {
            return redirect('/pemesanan/detail/'.$request->id2)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function excel($id_pemesanan_header){
        $header = DB::table('pemesanan_header as a')
            ->leftJoin('supplier as b', 'a.id_supplier', '=', 'b.id_supplier')
            ->leftJoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->where('id_pemesanan_header', '=', $id_pemesanan_header)
            ->select('a.*', 'b.*', 'c.*')
            ->get();

        $data = DB::table('pemesanan_detail as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin('item_satuan as c', 'a.id_item_satuan','=','c.id_item_satuan')
            ->where('id_pemesanan_header', '=', $id_pemesanan_header)
            ->select('a.*', 'b.nama_item',
                DB::raw("
                    CASE
                    when a.id_item_satuan ISNULL then b.satuan
                    else c.satuan
                    END as satuan
                    ")
            )
            ->get();

        $spreadsheet = new Spreadsheet();

        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('D7', 'Kartu Stok');
        $activeWorksheet->mergeCells('D7:E7');
        $activeWorksheet->getStyle('D2')->getFont()->setBold(true);
        $activeWorksheet->getStyle('D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getColumnDimension('D')->setWidth(13);
        $activeWorksheet->setCellValue('A9', 'Kepada :');
        $activeWorksheet->setCellValue('A10', 'Alamat :');
        $activeWorksheet->setCellValue('A11', 'Keterangan :');
        $activeWorksheet->setCellValue('G9', 'No. PO :');
        $activeWorksheet->setCellValue('G10', 'Tanggal PO :');
        $activeWorksheet->setCellValue('B13', 'Barang yang dipesan :');
        $activeWorksheet->mergeCells('B13:C13');
        

        // output
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Kartu Pesanan.xlsx"');
        $writer->save('php://output');
    }
}