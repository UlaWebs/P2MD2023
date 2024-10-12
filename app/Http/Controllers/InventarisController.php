<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('inventaris as a')
            ->leftjoin('aset as b', 'a.id_aset', '=', 'b.id_aset')
            ->leftjoin('kategori_aset as d', 'b.id_kategori_aset', '=', 'd.id_kategori_aset')
            ->leftJoin(DB::raw('(select id_inventaris, sum(kuantitas_pengeluaran) as total_pengeluaran from pengeluaran_aset group by id_inventaris) as c'), 'a.id_inventaris', '=', 'c.id_inventaris')
            ->groupBy('a.id_aset', 'b.nama_aset', 'd.nama_kategori_aset')
            ->select('a.id_aset', 'b.nama_aset', 'd.nama_kategori_aset',
            DB::raw('sum(a.kuantitas) as total_inventaris'),
            DB::raw('sum(c.total_pengeluaran) as total_pengeluaran'),
            DB::raw('sum(a.kuantitas*a.harga_perolehan) - sum(COALESCE(c.total_pengeluaran, 0)*a.harga_perolehan) as nilai_inventaris'))
            ->get();

        return view('inventaris.index', ['data' => $data, 'title' => 'Inventaris']);
    }

    public function detail($id)
    {
        $data = DB::table('inventaris as a')
            ->leftjoin('aset as b', 'a.id_aset', '=', 'b.id_aset')
            ->leftJoin(DB::raw('(select id_inventaris, sum(kuantitas_pengeluaran) as total_pengeluaran from pengeluaran_aset group by id_inventaris) as c'), 'a.id_inventaris', '=', 'c.id_inventaris')
            ->where('a.id_aset', '=', $id)
            ->orderBy('a.tgl_perolehan_inventaris', 'asc')
            ->select('a.*', 'b.*', DB::raw('COALESCE(c.total_pengeluaran, 0) as total_pengeluaran'))
            ->get();

        return view('inventaris.detail', ['data' => $data, 'title' => 'Detail Inventaris']);
    }

    public function detail2($id)
    {
        $data = DB::table('inventaris as a')
            ->leftjoin('aset as b', 'a.id_aset', '=', 'b.id_aset')
            ->leftjoin('kategori_aset as d', 'b.id_kategori_aset', '=', 'd.id_kategori_aset')
            ->leftJoin(DB::raw('(select id_inventaris, count(id_depresiasi_aset) as n_depresiasi ,sum(nominal_depresiasi) as total_depresiasi
                from depresiasi_aset group by id_inventaris) as c'), 'a.id_inventaris', '=', 'c.id_inventaris')
            ->where('a.id_aset', '=', $id)
            ->orderBy('a.tgl_perolehan_inventaris', 'asc')
            ->select('a.*', 'b.*', DB::raw('COALESCE(c.total_depresiasi, 0) as total_depresiasi'),
                'd.umur_ekonomis', 'd.metode_penyusutan', DB::raw('COALESCE(c.n_depresiasi, 0) as n_depresiasi'))
            ->get();

        return view('inventaris.detail2', ['data' => $data, 'title' => 'Data Depresiasi']);
    }
}