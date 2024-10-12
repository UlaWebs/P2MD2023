<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanpenyusutanController extends Controller
{
    public function index(){
        $data = DB::table('inventaris as a')
            ->leftJoin('aset as b', 'a.id_aset', '=', 'b.id_aset')
            ->leftJoin('kategori_aset as c', 'b.id_kategori_aset', '=', 'c.id_kategori_aset')
            ->select('a.*', 'b.nama_aset', 'b.kode_aset' ,'c.nama_kategori_aset', 'c.id_kategori_aset')
            ->get();

        return view('laporanpenyusutan.index', ['data' => $data,'title' => "Laporan Penyusutan"]);
    }

    public function show(Request $request){
        $validated = $request->validate([
            'id_inventaris' => 'required',
        ]);

        $title = "Laporan Penyusutan ";

        $data = DB::table('inventaris as a')
            ->leftJoin('aset as b', 'a.id_aset', '=', 'b.id_aset')
            ->leftJoin('depresiasi_aset as e', 'a.id_inventaris', '=', 'e.id_inventaris')
            ->leftJoin('perolehan_aset_detail as c', 'b.id_aset', '=', 'c.id_aset')
            ->select('b.nama_aset', 'e.tgl_depresiasi_aset', 'e.nominal_depresiasi', 'c.harga_perolehan')
            ->where('a.id_inventaris', '=', $request->id_inventaris)
            ->orderBy('e.tgl_depresiasi_aset', 'asc') // pastikan data diurutkan berdasarkan tanggal depresiasi
            ->get();

        return view('laporanpenyusutan.show', ['title' => $title, 'data' => $data]);
    }
}
