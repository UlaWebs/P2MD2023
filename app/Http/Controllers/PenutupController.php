<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Errormsg;
use App\Models\JurnalHeader;
use App\Models\JurnalDetail;
use Illuminate\Support\Facades\Auth;

class PenutupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataheader = DB::table('jurnal_header as a')
            ->where('jenis_transaksi', '=', 'closing')
            ->orderBy('tgl_jurnal', 'DESC')->get();
        $datadetail = DB::table('jurnal_detail as a')->leftJoin("akun as b", "a.id_akun", "=", "b.id_akun")->orderBy('a.id_jurnal_detail')->get();
        return view('penutup.index', ['dataheader' => $dataheader, 'datadetail' => $datadetail, 'title' => "Jurnal Penutup"]);
    }

    public function detail($id_jurnal_header)
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
        return view('penutup.detail', ['title' => "Jurnal No " . $no_jurnal, 'no_jurnal' => $no_jurnal, 'tgl_jurnal' => $tgl_jurnal, 'keterangan' => $keterangan, 'jenis_transaksi' => $jenis_transaksi, 'data' => $data]);
    }


    /**
     * Display the specified resource.
     */
    public function create()
    {
        return view('penutup.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_jurnal' => 'required',
            'keterangan' => 'required',
            'persentase' => 'required',
        ]);

        try {
            $tgl_jurnal = $request->tgl_jurnal;
            $persentase = $request->persentase;
            $keterangan = $request->keterangan;
            $user_id = Auth::user()->id;

            $store = DB::select("SELECT * FROM jurnal_penutup('$tgl_jurnal', '$keterangan', $user_id, $persentase )");
            foreach ($store as $p) {
                $message = $p->jurnal_penutup;
            }
            return redirect('/penutup/')->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/penutup/create')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_jurnal_header)
    {
        try {
            $jurnal_header = JurnalHeader::findOrFail($id_jurnal_header);
            $jurnal_header->delete();
            return redirect()->route('penutup')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('penutup')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
}