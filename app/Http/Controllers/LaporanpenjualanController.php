<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanpenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('laporanpenjualan.index', ['title' => "Laporan Penjualan"]);
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

        $title = "Laporan Penjualan " . date_format(date_create($request->tgl1), "d F Y") . " - " . date_format(date_create($request->tgl2), "d F Y");
        $tgl1 = date_create($request->tgl1);
        $tgl1 = date_format($tgl1, "Y-m-d");
        $tgl2 = date_create($request->tgl2);
        $tgl2 = date_format($tgl2, "Y-m-d");

        if($request->jenis=='k') {
            $data_header = DB::table('pengiriman_header as a')
            ->leftJoin('pemesanan_penjualan_header as b','a.id_pemesanan_penjualan_header','=','b.id_pemesanan_penjualan_header')
            ->leftJoin('pelanggan as c','b.id_pelanggan','=','c.id_pelanggan')
            ->leftjoin(DB::raw('(SELECT id_pengiriman_header, sum(x.nominal) as total_pelunasan FROM pelunasan_kredit as x group by x.id_pengiriman_header)as d'), 'a.id_pengiriman_header', '=', 'd.id_pengiriman_header')
            ->whereBetween('a.tgl_pengiriman',[$tgl1,$tgl2])
            ->select('a.*','c.nama_pelanggan',DB::raw('coalesce(d.total_pelunasan ,0.00) as total_pelunasan'))
            ->orderBy('a.tgl_pengiriman')
            ->get();

            $data_detail = DB::select("select a.*,d.kuantitas as kuantitas_pengiriman, e.nama_item from pengiriman_detail as a left join pengiriman_header as b on a.id_pengiriman_header = b.id_pengiriman_header left join pemesanan_penjualan_header as c on b.id_pemesanan_penjualan_header = c.id_pemesanan_penjualan_header left join pemesanan_penjualan_detail as d on c.id_pemesanan_penjualan_header = d.id_pemesanan_penjualan_header and d.id_item = a.id_item left join item as e on e.id_item = a.id_item where b.tgl_pengiriman between '$tgl1' and '$tgl2'");

        } else{
            $data_header= DB::table('penjualan_tunai_header as a')
            ->leftJoin ('pelanggan as b', 'a.id_pelanggan','=','b.id_pelanggan')
            ->whereBetween('a.tgl_penjualan',[$tgl1,$tgl2])
            ->orderBy('a.tgl_penjualan')
            ->get();

            $data_detail = DB::table('penjualan_tunai_detail as a')
            ->leftJoin('item as b','a.id_item','=','b.id_item')
            ->leftJoin('penjualan_tunai_header as c','a.id_penjualan_tunai_header','=','c.id_penjualan_tunai_header')
            ->whereBetween('c.tgl_penjualan',[$tgl1,$tgl2])
            ->get();

        }


        return view('laporanpenjualan.show', ['title' => $title, 'jenis'=> $request->jenis, 'data_header' => $data_header,'data_detail' => $data_detail]);
    }
}