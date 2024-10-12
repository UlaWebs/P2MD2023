<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanjoborderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('laporanjoborder.index', ['title' => "Job Order Cost Sheet"]);
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

        $title = "Job Order Cost Sheet " . date_format(date_create($request->tgl1), "d F Y") . " - " . date_format(date_create($request->tgl2), "d F Y");
        $tgl1 = date_create($request->tgl1);
        $tgl1 = date_format($tgl1, "Y-m-d");
        $tgl2 = date_create($request->tgl2);
        $tgl2 = date_format($tgl2, "Y-m-d");

        $get_header = DB::table('produksi_header as a')
        ->whereBetween('a.tgl_produksi',[$tgl1,$tgl2])
        ->OrderBy('a.tgl_produksi')
        ->get();

       $get_bbb = DB::table('produksi_detail_bhn_baku as a')
       ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
       ->leftjoin(
           DB::raw('(SELECT x.id_produksi_detail_bhn_baku,
           sum(x.kuantitas*y.harga_satuan) as harga_pengambilan
           FROM pengambilan as x LEFT JOIN persediaan as y ON x.id_persediaan_header = y.id_persediaan_header GROUP BY id_produksi_detail_bhn_baku) as c'),
           'a.id_produksi_detail_bhn_baku',
           '=',
           'c.id_produksi_detail_bhn_baku'
       )
       ->leftJoin('produksi_header as d','a.id_produksi_header', '=', 'd.id_produksi_header')
       ->whereBetween('d.tgl_produksi',[$tgl1,$tgl2]) 
       ->select('a.id_produksi_header','b.nama_item',DB::raw('sum(c.harga_pengambilan) as total_bbb'))
       ->groupBy('a.id_produksi_header','b.nama_item')
       ->get();

       $get_bop = DB::table('produksi_detail_bop as a')
       ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
       ->leftjoin(
           DB::raw('(SELECT x.id_produksi_detail_bop,
           sum(x.kuantitas*y.harga_satuan) as harga_pengambilan
           FROM pengambilan as x LEFT JOIN persediaan as y ON x.id_persediaan_header = y.id_persediaan_header GROUP BY id_produksi_detail_bop) as c'),
           'a.id_produksi_detail_bop',
           '=',
           'c.id_produksi_detail_bop'
       )
       ->leftJoin('produksi_header as d','a.id_produksi_header', '=', 'd.id_produksi_header')
       ->whereBetween('d.tgl_produksi',[$tgl1,$tgl2]) 
       ->select('a.id_produksi_header','b.nama_item',DB::raw('sum(c.harga_pengambilan) as total_bop'))
       ->groupBy('a.id_produksi_header','b.nama_item')
       ->get();

       $get_btk = DB::table('produksi_detail_tenaga_kerja as a')
            ->leftjoin('pekerja as b', 'a.id_pekerja', '=', 'b.id_pekerja')
            ->leftJoin('produksi_header as c','a.id_produksi_header', '=', 'c.id_produksi_header')
            ->whereBetween('c.tgl_produksi',[$tgl1,$tgl2]) 
            ->select('a.id_produksi_header','b.nama_pekerja',DB::raw('sum(a.biaya_tenaga_kerja) as total_biaya_tenaga_kerja'))
            ->groupBy('a.id_produksi_header','b.nama_pekerja')
            ->get();

        $get_output = DB::table('produksi_detail_output as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin('produksi_header as c','a.id_produksi_header', '=', 'c.id_produksi_header')
            ->whereBetween('c.tgl_produksi',[$tgl1,$tgl2]) 
            ->get();

        return view('laporanjoborder.show', ['title' => $title, 'header' => $get_header, 'bbb' => $get_bbb, 'bop' => $get_bop, 'btk' => $get_btk, 'output' => $get_output]);
    }
}
