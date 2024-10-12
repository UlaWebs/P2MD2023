<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanpembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('laporanpembelian.index', ['title' => "Laporan Pembelian"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'tgl1' => 'required',
            'tgl2' => 'required',
        ]);

        $title = "Laporan Pembelian " . date_format(date_create($request->tgl1), "d F Y") . " - " . date_format(date_create($request->tgl2), "d F Y");
        $tgl1 = date_create($request->tgl1);
        $tgl1 = date_format($tgl1, "Y-m-d");
        $tgl2 = date_create($request->tgl2);
        $tgl2 = date_format($tgl2, "Y-m-d");

        if($request->jenis == 'k') {
            $data_header = DB::table('penerimaan_header as a')
                ->leftJoin('pemesanan_header as b', 'a.id_pemesanan_header', '=', 'b.id_pemesanan_header')
                ->leftJoin('supplier as c', 'b.id_supplier', '=', 'c.id_supplier')
                ->leftJoin(DB::raw('(SELECT id_penerimaan_header, sum(x.nominal_pembayaran) as total_pelunasan FROM pelunasan_pembelian as x group by x.id_penerimaan_header) as d'), 'a.id_penerimaan_header', '=', 'd.id_penerimaan_header')
                ->whereBetween('a.tgl_penerimaan', [$tgl1, $tgl2]) 
                ->select('a.*', 'c.nama_supplier', DB::raw('coalesce(d.total_pelunasan, 0.00) as total_pelunasan'), 'b.nominal_uang_muka')
                ->orderBy('a.tgl_penerimaan')
                ->get();

            $data_detail = DB::select("SELECT a.*, d.kuantitas as kuantitas_pemesanan, e.nama_item 
                FROM penerimaan_detail as a 
                LEFT JOIN penerimaan_header as b ON a.id_penerimaan_header = b.id_penerimaan_header 
                LEFT JOIN pemesanan_header as c ON b.id_pemesanan_header = c.id_pemesanan_header 
                LEFT JOIN pemesanan_detail as d ON c.id_pemesanan_header = d.id_pemesanan_header AND d.id_item = a.id_item 
                LEFT JOIN item as e ON e.id_item = a.id_item 
                WHERE b.tgl_penerimaan BETWEEN '$tgl1' AND '$tgl2'");
        } else {
            $data_header = DB::table('pembelian_tunai_header as a')
                ->leftJoin('supplier as b', 'a.id_supplier', '=', 'b.id_supplier')
                ->whereBetween('a.tanggal_pembelian_tunai', [$tgl1, $tgl2])
                ->orderBy('a.tanggal_pembelian_tunai')
                ->get();

            $data_detail = DB::table('pembelian_tunai_detail as a')
                ->leftJoin('item as b', 'a.id_item', '=', 'b.id_item')
                ->leftJoin('pembelian_tunai_header as c', 'a.id_pembelian_tunai_header', '=', 'c.id_pembelian_tunai_header')
                ->whereBetween('c.tanggal_pembelian_tunai', [$tgl1, $tgl2])
                ->get();
        }

        return view('laporanpembelian.show', [
            'title' => $title,
            'jenis' => $request->jenis,
            'data_header' => $data_header,
            'data_detail' => $data_detail
        ]);
    }
}
