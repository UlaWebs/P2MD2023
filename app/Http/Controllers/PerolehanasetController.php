<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Inventaris;
use App\Models\Kas;
use App\Models\PerolehanAsetDetail;
use App\Models\PerolehanAsetHeader;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerolehanasetController extends Controller
{
    public function index()
    {
        $data = DB::table('perolehan_aset_header as a')
            ->leftJoin('vendor as b', 'a.id_vendor', '=', 'b.id_vendor')
            ->leftJoin('kas as  c', 'a.id_kas', '=', 'c.id_kas')
            ->leftJoin(DB::raw("(select * from jurnal_header where jenis_transaksi = 'perolehan aset') as d"), 'a.id_perolehan_aset_header', '=', 'd.id_transaksi')
            ->leftJoin(DB::raw('(select y.id_perolehan_aset_header, sum(kuantitas*harga_perolehan) as subtotal_perolehan from perolehan_aset_detail as x left join perolehan_aset_header as y on x.id_perolehan_aset_header=y.id_perolehan_aset_header group by y.id_perolehan_aset_header) as e'),'a.id_perolehan_aset_header','=','e.id_perolehan_aset_header')
            ->select('a.*', 'b.*', 'c.nama_kas', 'd.no_jurnal', 'd.id_jurnal_header',
            DB::raw("
                COALESCE(e.subtotal_perolehan,0) as subtotal_perolehan
            "))
            ->orderBy('a.tgl_perolehan_aset', 'asc')
            ->get();


        return view("perolehanaset.index", ['data' => $data, 'title' => "Perolehan Aset"]);
    }

    public function create()
    {
        $data = Vendor::all();
        $data_kas = Kas::all();
        return view("perolehanaset.create", ['data' => $data, 'data_kas' => $data_kas]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_perolehan_aset' => 'required',
            'keterangan' => 'required',
            'id_vendor' => 'required',
            'id_kas' => 'required',
            'nominal_kas' => 'required',
        ]);

        $tgl = date_create($request->tgl_perolehan_aset);
        $tgl_format = date_format($tgl, "Y-m-d");
        $get_no_pa = DB::select("SELECT * FROM no_pa('$tgl_format')");
        foreach ($get_no_pa as $p) {
            $no_pa = $p->no_pa;
        }

        try {
            $store = PerolehanAsetHeader::create(['no_perolehan_aset' => $no_pa, 'tgl_perolehan_aset' => $request->tgl_perolehan_aset, 'keterangan' => $request->keterangan, 'id_vendor' => $request->id_vendor, 'id_kas' => $request->id_kas, 'nominal_kas' => $request->nominal_kas,'user_id_created' => Auth::user()->id]);
            $id = $store->id_perolehan_aset_header;
            return redirect('/perolehanaset/detail/' . $id)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/perolehanaset/create/')->with('warning', $e->getMessage());
        }
    }

    public function edit($id_perolehan_aset_header)
    {
        $header = DB::table('perolehan_aset_header as a')
            ->where('id_perolehan_aset_header', '=', $id_perolehan_aset_header)
            ->select('a.*')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $id_perolehan_aset_header = $p->id_perolehan_aset_header;
                $tgl_perolehan_aset = $p->tgl_perolehan_aset;
                $keterangan = $p->keterangan;
                $id_vendor = $p->id_vendor;
                $id_kas = $p->id_kas;
                $nominal_kas = $p->nominal_kas;
            }
        }

        $data = Vendor::all();
        $data_kas = Kas::all();
        return view('perolehanaset.edit', [
            'data' => $data,
            'data_kas' => $data_kas,
            'id_perolehan_aset_header' => $id_perolehan_aset_header,
            'tgl_perolehan_aset' => $tgl_perolehan_aset,
            'keterangan' => $keterangan,
            'id_vendor' => $id_vendor,
            'id_kas' => $id_kas,
            'nominal_kas' => $nominal_kas
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id_perolehan_aset_header' => 'required',
            'tgl_perolehan_aset' => 'required',
            'keterangan' => 'required',
            'id_vendor' => 'required',
            'id_kas' => 'required',
            'nominal_kas' => 'required',
        ]);

        try {
            $update = PerolehanAsetHeader::where('id_perolehan_aset_header', $request->id_perolehan_aset_header)
            ->update([
                'tgl_perolehan_aset' => $request->tgl_perolehan_aset,
                'keterangan' => $request->keterangan,
                'id_vendor' => $request->id_vendor,
                'id_kas' => $request->id_kas,
                'nominal_kas' => $request->nominal_kas,
                'user_id_created' => Auth::user()->id
            ]);
            return redirect('/perolehanaset/detail/' . $request->id_perolehan_aset_header)->with('success', 'Data Berhasil di Edit');
        } catch (\Exception $e) {
            return redirect('/perolehanaset/detail/' . $request->id_perolehan_aset_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroy($id_perolehan_aset_header)
    {
        try {
            $perolehanaset = PerolehanAsetHeader::findOrFail($id_perolehan_aset_header);
            $perolehanaset->delete();
            return redirect()->route('perolehanaset')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('perolehanaset')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id_perolehan_aset_header)
    {
        $header = DB::table('perolehan_aset_header as a')
            ->leftJoin('vendor as b', 'a.id_vendor', '=', 'b.id_vendor')
            ->leftJoin('kas as c', 'a.id_kas', '=', 'c.id_kas')
            ->where('id_perolehan_aset_header', '=', $id_perolehan_aset_header)
            ->select('a.*', 'b.*', 'c.*')
            ->get();

        $data = DB::table('perolehan_aset_detail as a')
            ->leftjoin('aset as b', 'a.id_aset', '=', 'b.id_aset')
            ->where('id_perolehan_aset_header', '=', $id_perolehan_aset_header)
            ->select('a.*', 'b.nama_aset')
            ->get();

        if (count($header) == 0) {
        } else {
            foreach ($header as $p) {
                $no_perolehan_aset = $p->no_perolehan_aset;
                $tgl_perolehan_aset = $p->tgl_perolehan_aset;
                $keterangan = $p->keterangan;
                $nama_vendor = $p->nama_vendor;
                $id_kas = $p->id_kas;
                $nama_kas = $p->nama_kas;
                $nominal_kas = $p->nominal_kas;
            }
        }
        $query=DB::table('aset');
        $data_item=$query->get();

        return view('perolehanaset.detail', [
            'title' => "Detail Perolehan Aset No " . $no_perolehan_aset,
            'data_item' => $data_item,
            'id_perolehan_aset_header' => $id_perolehan_aset_header,
            'no_perolehan_aset' => $no_perolehan_aset,
            'tgl_perolehan_aset' => $tgl_perolehan_aset,
            'keterangan' => $keterangan,
            'nama_vendor' => $nama_vendor,
            'id_kas' => $id_kas,
            'nama_kas' => $nama_kas,
            'nominal_kas' => $nominal_kas,
            'data' => $data
        ]);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_perolehan_aset_header' => 'required',
            'id_aset' => 'required',
            'kuantitas' => 'required',
            'harga' => 'required',
            'satuan' => 'required',
        ]);
        try
        {
            $get_header = PerolehanAsetHeader::where('id_perolehan_aset_header', '=' , $request->id_perolehan_aset_header)->get();
            $tgl = '';
            $no_perolehan = '';

            foreach($get_header as $data_header){
                $tgl = $data_header->tgl_perolehan_aset;
                $no_perolehan = $data_header->no_perolehan_aset;
            }

            $store = PerolehanAsetDetail::create([
                'id_perolehan_aset_header' => $request->id_perolehan_aset_header,
                'id_aset' => $request->id_aset,
                'kuantitas' => $request->kuantitas,
                'harga_perolehan' => $request->harga,
                'satuan' => $request->satuan,
                'user_id_created' => Auth::user()->id
            ]);

            $kuantitas = $request->kuantitas;
            for($i = 0; $i < $kuantitas; $i++){
                $code = 'A'.strtoupper(substr(bin2hex(random_bytes(20)),0,6));
                $invent = Inventaris::create([
                    'user_id_created' => Auth::user()->id,
                    'tgl_perolehan_inventaris' => $tgl,
                    'keterangan' => 'Perolehan no ' . $no_perolehan,
                    'id_aset' => $request->id_aset,
                    'kuantitas' => 1,
                    'harga_perolehan' => $request->harga,
                    'id_perolehan_aset_detail' => $store->id_perolehan_aset_detail,
                    'nilai_residu' => $request->nilai_residu,
                    'kode_inventaris' => $code,
                ]);
            }

            

            return redirect('/perolehanaset/detail/' . $request->id_perolehan_aset_header)->with('success', 'Data Berhasil di Input');
        } catch (\Exception $e) {
            return redirect('/perolehanaset/detail/' . $request->id_perolehan_aset_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail($id_perolehan_aset_detail)
    {
        try {
            $perolehan_aset_detail = PerolehanAsetDetail::where('id_perolehan_aset_detail', '=', $id_perolehan_aset_detail);
            $get = $perolehan_aset_detail->get();
            $id_perolehan_aset_header = 0;
            foreach ($get as $data) {
                $id_perolehan_aset_header = $data->id_perolehan_aset_header;
            }

            $perolehan_aset_detail->delete();
            return redirect('/perolehanaset/detail/' . $id_perolehan_aset_header)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('perolehanaset/detail/' . $id_perolehan_aset_header)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function jurnal($id_perolehan_aset_header)
    {
        try {
            $jurnal = DB::select("select * from jurnal_perolehan($id_perolehan_aset_header)");
            $msg = "";
            foreach ($jurnal as $data_jurnal){
                $msg = $data_jurnal->jurnal_perolehan;
            }
            return redirect ('perolehanaset')->with('success', $msg);
        } catch (\Exception $e) {
            return redirect('perolehanaset')->with('warning', $e->getMessage());
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
        return view('perolehanaset.jurnaldetail', [
            'title' => "Jurnal No " . $no_jurnal,
            'no_jurnal' => $no_jurnal,
            'tgl_jurnal' => $tgl_jurnal,
            'keterangan' => $keterangan,
            'jenis_transaksi' => $jenis_transaksi,
            'data' => $data
        ]);
    }
}
