<?php

namespace App\Http\Controllers;

use App\Models\PelepasanAset;
use App\Models\PengeluaranAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelepasanasetController extends Controller
{
    public function index()
    {
        $data = DB::table('pelepasan_aset as a')
            ->leftjoin('inventaris as b', 'a.id_inventaris', '=','b.id_inventaris')
            ->leftjoin('aset as c', 'c.id_aset','=','b.id_aset')
            ->leftjoin('kas as d','d.id_kas','=','a.id_kas')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'pelepasan aset') as e"), 'a.id_pelepasan_aset', '=', 'e.id_transaksi')
            ->select('a.*', 'b.kode_inventaris' ,'c.nama_aset', 'd.nama_kas','e.no_jurnal', 'e.id_jurnal_header')
            ->get();

        return view("pelepasanaset.index", [
            'data' => $data,
            'title' => 'Pelepasan Aset',
        ]);
    }

    public function create()
    {
        $data_inventaris = DB::table('inventaris as a')
            ->leftjoin('aset as b', 'a.id_aset', '=', 'b.id_aset')
            ->leftJoin(DB::raw('(select id_inventaris, sum(kuantitas_pengeluaran) as total_pengeluaran from pengeluaran_aset group by id_inventaris) as c'), 'a.id_inventaris', '=', 'c.id_inventaris')
            ->where(DB::raw('a.kuantitas - COALESCE(c.total_pengeluaran,0)'), '>', 0)
            ->orderBy('b.nama_aset', 'asc')
            ->orderBy('a.kode_inventaris','asc')
            ->select('a.*', 'b.*', DB::raw('COALESCE(c.total_pengeluaran, 0) as total_pengeluaran'))
            ->get();

        $data_kas = DB::table('kas as a')->get();

        return view('pelepasanaset.create', [
            'inventaris' => $data_inventaris,
            'kas' => $data_kas,
            'title' => 'Data Baru Pelepasan',
        ]);
    }

    public function store(Request $request){
        $validate = $request->validate([
            'tgl_pelepasan_aset'=> 'required',
            'kategori_pelepasan'=> 'required',
            'id_inventaris'=> 'required',
        ]);

        if($request->kategori_pelepasan=='Penjualan'){
            if(($request->id_kas=='') OR ($request->nominal_pelepasan<=0)){
                return redirect('pelepasanaset')->with('warning', 'Kas dan Nominal tidak boleh kosong');
            }
        }

        $tgl = date_create($request->tgl_pelepasan_aset);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pasan = DB::select("SELECT * FROM no_pasan('$tgl_format')");
        foreach ($get_no_pasan as $p) {
            $no_pasan = $p->no_pasan;
        }

        if($request->kategori_pelepasan=='Penjualan'){
            $store = PelepasanAset::create([
                'no_pelepasan_aset' => $no_pasan,
                'tgl_pelepasan_aset' => $request->tgl_pelepasan_aset,
                'id_inventaris' => $request->id_inventaris ,
                'id_kas' => $request->id_kas,
                'nominal_pelepasan' => $request->nominal_pelepasan,
                'kategori_pelepasan' => $request->kategori_pelepasan,
                'user_id_created' => Auth::user()->id
            ]);

            $pengurangan_stok = PengeluaranAset::create([
                'tgl_pengeluaran_aset' => $request->tgl_pelepasan_aset,
                'id_inventaris' => $request->id_inventaris,
                'kuantitas_pengeluaran' => 1,
                'id_pelepasan_aset' => $store->id_pelepasan_aset,
                'user_id_created' => Auth::user()->id
            ]);
        } else {
            $store = PelepasanAset::create([
                'no_pelepasan_aset' => $no_pasan,
                'tgl_pelepasan_aset' => $request->tgl_pelepasan_aset,
                'id_inventaris' => $request->id_inventaris,
                'nominal_pelepasan' => 0,
                'kategori_pelepasan' => $request->kategori_pelepasan,
                'user_id_created' => Auth::user()->id,
            ]);

            $pengurangan_stok = PengeluaranAset::create([
                'tgl_pengeluaran_aset' => $request->tgl_pelepasan_aset,
                'id_inventaris' => $request->id_inventaris,
                'kuantitas_pengeluaran' => 1,
                'id_pelepasan_aset' => $store->id_pelepasan_aset,
                'user_id_created' => Auth::user()->id
            ]);
        }

        return redirect('/pelepasanaset/')->with('success', 'Data Berhasil di Input');
    }

    public function edit($id_pelepasan_aset){
        $header = DB::table('pelepasan_aset as a')
            ->leftJoin('inventaris as b', 'a.id_inventaris','=','b.id_inventaris')
            ->leftJoin('aset as c','b.id_aset','=','c.id_aset')
            ->select('a.*', 'b.kode_inventaris', 'c.nama_aset')
            ->where('id_pelepasan_aset', '=', $id_pelepasan_aset)
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_pelepasan_aset = $p->id_pelepasan_aset;
                $tgl_pelepasan_aset = $p->tgl_pelepasan_aset;
                $id_kas = $p->id_kas;
                $kode_inventaris = $p->kode_inventaris;
                $nama_aset = $p->nama_aset;
                $nominal_pelepasan = $p->nominal_pelepasan;
                $kategori_pelepasan = $p->kategori_pelepasan;
                $no_pelepasan_aset = $p->no_pelepasan_aset;
            }
        }

        $data_kas = DB::table('kas as a')->get();

        return view('pelepasanaset.edit', [
            'data_kas' => $data_kas,
            'id_pelepasan_aset' => $id_pelepasan_aset,
            'tgl_pelepasan_aset' => $tgl_pelepasan_aset,
            'no_pelepasan_aset' => $no_pelepasan_aset,
            'id_kas' => $id_kas,
            'nominal_pelepasan' => $nominal_pelepasan,
            'kategori_pelepasan'=> $kategori_pelepasan,
            'kode_inventaris'=> $kode_inventaris,
            'nama_aset'=> $nama_aset,
        ]);
    }

    public function update(Request $request){
        $validate = $request->validate([
            'id_pelepasan_aset' => 'required',
            'tgl_pelepasan_aset'=> 'required',
            'kategori_pelepasan'=> 'required',
        ]);

        if($request->kategori_pelepasan=='Penjualan'){
            if(($request->id_kas=='') OR ($request->nominal_pelepasan<=0)){
                return redirect('pelepasanaset')->with('warning', 'Kas dan Nominal tidak boleh kosong');
            }
        }

        if($request->kategori_pelepasan=='Penjualan'){
            $store = PelepasanAset::where('id_pelepasan_aset', $request->id_pelepasan_aset)->update([
                'tgl_pelepasan_aset' => $request->tgl_pelepasan_aset,
                'id_kas' => $request->id_kas,
                'nominal_pelepasan' => $request->nominal_pelepasan,
                'kategori_pelepasan' => $request->kategori_pelepasan,
                'user_id_updated' => Auth::user()->id
            ]);

            $pengurangan_stok = PengeluaranAset::where('id_pelepasan_aset', $request->id_pelepasan_aset)->update([
                'tgl_pengeluaran_aset' => $request->tgl_pelepasan_aset,
                'user_id_updated' => Auth::user()->id
            ]);
        } else {
            //Kategori Pemusnahan
            $store = PelepasanAset::where('id_pelepasan_aset', $request->id_pelepasan_aset)->update([
                'tgl_pelepasan_aset' => $request->tgl_pelepasan_aset,
                'id_kas'=> NULL,
                'nominal_pelepasan' => 0,
                'kategori_pelepasan' => $request->kategori_pelepasan,
                'user_id_updated' => Auth::user()->id,
            ]);

            $pengurangan_stok = PengeluaranAset::where('id_pelepasan_aset', $request->id_pelepasan_aset)->update([
                'tgl_pengeluaran_aset' => $request->tgl_pelepasan_aset,
                'user_id_updated' => Auth::user()->id
            ]);
        }

        return redirect('/pelepasanaset/')->with('success', 'Data Berhasil di Input');
    }

    public function destroy($id_pelepasan_aset){
        $lunas = PelepasanAset::findOrFail($id_pelepasan_aset);
        $lunas->delete();

        return redirect()->route('pelepasanaset')->with('success', 'Data Berhasil di Hapus');
    }

    public function jurnal($id_pelepasan_aset)
    {
        try {
            $jurnal = DB::select("select * from jurnal_pelepasan_aset($id_pelepasan_aset)");
            $msg = "";
            foreach ($jurnal as $data_jurnal){
                $msg = $data_jurnal->jurnal_pelepasan_aset;
            }
            return redirect ('pelepasanaset')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('pelepasanaset')->with('warning', $e->getMessage());
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
        return view('pelepasanaset.jurnaldetail', [
            'title' => "Jurnal No " . $no_jurnal,
            'no_jurnal' => $no_jurnal,
            'tgl_jurnal' => $tgl_jurnal,
            'keterangan' => $keterangan,
            'jenis_transaksi' => $jenis_transaksi,
            'data' => $data
        ]);
    }
}