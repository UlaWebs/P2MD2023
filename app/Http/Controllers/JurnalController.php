<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Akun;
use App\Models\JurnalHeader;
use App\Models\JurnalDetail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataheader = DB::table('jurnal_header as a')
            ->orderBy('tgl_jurnal', 'DESC')
            ->get();

        $datadetail = DB::table('jurnal_detail as a')
            ->leftJoin("akun as b", "a.id_akun", "=", "b.id_akun")
            ->orderBy('a.id_jurnal_detail')
            ->get();

        $minDate = $dataheader->min('tgl_jurnal');
        $maxDate = $dataheader->max('tgl_jurnal');

        return view('jurnal/index', [
            'dataheader' => $dataheader,
            'datadetail' => $datadetail,
            'title' => "Jurnal",
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jurnal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_jurnal' => 'required',
            'keterangan' => 'required'
        ]);

        $tgl = date_create($request->tgl_jurnal);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_jurnal = DB::select("SELECT * FROM no_jurnal_umum('$tgl_format')");
        foreach ($get_no_jurnal as $p) {
            $no_jurnal_umum = $p->no_jurnal_umum;
        }

        try {
            $store = JurnalHeader::create([
                'no_jurnal' => $no_jurnal_umum,
                'tgl_jurnal' => $request->tgl_jurnal,
                'keterangan' => $request->keterangan,
                'jenis_transaksi' => "Jurnal Umum",
                'user_id_created' => Auth::user()->id
            ]);
            $id = $store->id_jurnal_header;
            return redirect('/jurnal/detail/'.$id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/jurnal/create')->with('warning', Errormsg::errordb($e->getCode()));
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
            return redirect()->route('jurnal')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('jurnal')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id_jurnal_header)
    {
        $header = DB::table('jurnal_header as a')
            ->where('id_jurnal_header', '=', $id_jurnal_header)
            ->select('*')
            ->get();

        $data = DB::table('jurnal_detail as a')
            ->leftjoin('akun as b', 'a.id_akun', '=', 'b.id_akun')
            ->where('id_jurnal_header', '=', $id_jurnal_header)
            ->orderBy('a.debet','desc')
            ->orderBy('a.id_jurnal_detail')
            ->select('a.*', 'b.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $no_jurnal = $p->no_jurnal;
                $tgl_jurnal = $p->tgl_jurnal;
                $keterangan = $p->keterangan;
            }
        }
        $data_akun = DB::table('akun as a')
            ->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=','b.id_jenis_akun')
            ->where('b.is_group','=','f')
            ->orderBy('kode_akun')
            ->get();

        return view('jurnal.detail', [
            'title' => "Detail jurnal No " . $no_jurnal,
            'data_akun' => $data_akun,
            'id_jurnal_header' => $id_jurnal_header,
            'no_jurnal' => $no_jurnal,
            'tgl_jurnal' => $tgl_jurnal,
            'keterangan' => $keterangan,
            'data' => $data
        ]);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_jurnal_header' => 'required',
            'id_akun' => 'required',
            'nominal' => 'required',
            'dk' => 'required',
        ]);

        try {
            if($request->dk=='D'){
                $store = JurnalDetail::create(['id_jurnal_header' => $request->id_jurnal_header, 'id_akun' => $request->id_akun, 'debet' => $request->nominal, 'kredit' => 0, 'user_id_created' => Auth::user()->id]);
            }elseif($request->dk=='K'){
                $store = JurnalDetail::create(['id_jurnal_header' => $request->id_jurnal_header, 'id_akun' => $request->id_akun, 'debet' => 0, 'kredit' => $request->nominal, 'user_id_created' => Auth::user()->id]);
            }
            return redirect('/jurnal/detail/' . $request->id_jurnal_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/jurnal/detail/' . $request->id_jurnal_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail($id_jurnal_detail)
    {
        try {
            $jurnal_detail = JurnalDetail::where($id_jurnal_detail);
            $get = $jurnal_detail->get();
            $id_jurnal_header = 0;
            foreach ($get as $data) {
                $id_jurnal_header = $data->id_jurnal_header;
            }

            $jurnal_detail->delete();
            return redirect('/jurnal/detail/' . $id_jurnal_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/jurnal/detail/' . $id_jurnal_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

}
