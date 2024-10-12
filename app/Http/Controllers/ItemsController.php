<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Akun;
use App\Models\ItemSatuan;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Errormsg;
use App\Models\BomBbb;
use App\Models\BomBop;
use App\Models\BomBtk;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('item as a')
            ->leftJoin('akun as b', 'a.id_akun_persediaan', '=', 'b.id_akun')
            ->leftJoin('akun as c', 'a.id_akun_persediaan_dalam_perjalanan', '=', 'c.id_akun')
            ->leftJoin('akun as d', 'a.id_akun_hpp', '=', 'd.id_akun')
            ->orderBy('kode_item')
            ->get();
        return view('item.index', ['data' => $data, 'title' => "item"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Akun::orderBy('kode_akun')->get();
        return view('item.create', ['data' => $data]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_item' => 'required'
        ]);
        $jenis_item = $request->jenis_item;
        $get_kode_item = DB::select("SELECT * FROM kode_item('$jenis_item')");
        foreach ($get_kode_item as $p) {
            $kode_item = $p->kode_item;
        }

        $store = Item::create(['kode_item' => $kode_item, 'nama_item' => $request->nama_item, 'satuan' => $request->satuan, 'jenis_item' => $request->jenis_item, 'id_akun_persediaan' => $request->id_akun_persediaan, 'id_akun_persediaan_dalam_perjalanan' => $request->id_akun_persediaan_dalam_perjalanan, 'id_akun_hpp' => $request->id_akun_hpp, 'id_akun_penjualan' => $request->id_akun_penjualan, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('item')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_item)
    {
        $data = Akun::orderBy('kode_akun')->get();
        $get = DB::table('item')->where('id_item', $id_item)->get();
        foreach ($get as $p) {
            $id_item = $p->id_item;
            $nama_item = $p->nama_item;
            $satuan = $p->satuan;
            $jenis_item = $p->jenis_item;
            $id_akun_persediaan = $p->id_akun_persediaan;
            $id_akun_persediaan_dalam_perjalanan = $p->id_akun_persediaan_dalam_perjalanan;
            $id_akun_hpp = $p->id_akun_hpp;
            $id_akun_penjualan = $p->id_akun_penjualan;
        }
        return view('item.edit', ['data' => $data, 'id_item' => $id_item, 'nama_item' => $nama_item, 'satuan' => $satuan, 'jenis_item' => $jenis_item,  'id_akun_persediaan' => $id_akun_persediaan, 'id_akun_persediaan_dalam_perjalanan' => $id_akun_persediaan_dalam_perjalanan, 'id_akun_hpp' => $id_akun_hpp, 'id_akun_penjualan' => $id_akun_penjualan]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, item $item)
    {
        $validated = $request->validate([
            'nama_item' => 'required'
        ]);

        $store = Item::where('id_item', $request->id_item)->update(['nama_item' => $request->nama_item, 'satuan' => $request->satuan, 'jenis_item' => $request->jenis_item, 'id_akun_persediaan' => $request->id_akun_persediaan, 'id_akun_persediaan_dalam_perjalanan' => $request->id_akun_persediaan_dalam_perjalanan, 'id_akun_hpp' => $request->id_akun_hpp, 'id_akun_penjualan' => $request->id_akun_penjualan, 'user_id_updated' => Auth::user()->id]);
        return redirect()->route('item')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_item)
    {

        try {
            $item = Item::findOrFail($id_item);
            $item->delete();

            return redirect()->route('item')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('item')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function detail($id_item)
    {
        $get = DB::table('item')->where('id_item', $id_item)->get();
        $data = DB::table('item_satuan')->where('id_item', $id_item)->get();
        foreach ($get as $p) {
            $id_item = $p->id_item;
            $nama_item = $p->nama_item;
            $satuan = $p->satuan;
            $jenis_item = $p->jenis_item;
        }
        return view('item.detail', ['data' => $data, 'title' => "Satuan Item", 'id_item' => $id_item, 'nama_item' => $nama_item, 'satuan' => $satuan, 'jenis_item' => $jenis_item]);
    }

    public function storedetail(Request $request)
    {
        $validated = $request->validate([
            'id_item' => 'required',
            'satuan' => 'required',
            'operator' => 'required',
            'konversi' => 'required',
        ]);

        $store = ItemSatuan::create(['id_item' => $request->id_item, 'satuan' => $request->satuan, 'operator' => $request->operator, 'konversi' => $request->konversi, 'user_id_created' => Auth::user()->id]);
        return redirect('/item/detail/' . $request->id_item)->with('success', 'Data Berhasil di Input');
    }

    public function destroydetail($id_item_satuan)
    {

        try {
            $get_item_satuan = DB::table('item_satuan')->where('id_item_satuan', '=', $id_item_satuan)->get();
            foreach ($get_item_satuan as $p) {
                $id_item = $p->id_item;
            }
            $item = ItemSatuan::findOrFail($id_item_satuan);
            $item->delete();

            return redirect('/item/detail/' . $id_item)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/item/detail/' . $id_item)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function editdetail($id_item_satuan)
    {
        $get = DB::table('item_satuan')->where('id_item_satuan', $id_item_satuan)->get();
        foreach ($get as $p) {
            $id_item = $p->id_item;
            $id_item_satuan = $p->id_item_satuan;
            $satuan = $p->satuan;
            $operator = $p->operator;
            $konversi = $p->konversi;
        }
        return view('item.editdetail', ['id_item' => $id_item, 'id_item_satuan' => $id_item_satuan, 'satuan' => $satuan, 'operator' => $operator, 'konversi' => $konversi]);
    }

    public function updatedetail(Request $request, item $item)
    {
        $validated = $request->validate([
            'id_item_satuan' => 'required',
            'satuan' => 'required',
            'operator' => 'required',
            'konversi' => 'required',
        ]);

        $store = ItemSatuan::where('id_item_satuan', $request->id_item_satuan)->update(['satuan' => $request->satuan, 'operator' => $request->operator, 'konversi' => $request->konversi, 'user_id_updated' => Auth::user()->id]);
        return redirect('/item/detail/' . $request->id_item)->with('success', 'Data Berhasil di Ubah');
    }
    public function getsatuan($id_item)
    {
        $getsatuan = DB::select("(select 0 as id_item_satuan, satuan, 1 as konversi, '*' as operator from item where id_item = $id_item) union (select id_item_satuan, satuan, konversi, operator from item_satuan where id_item = $id_item) order by id_item_satuan ");

        return response()->json($getsatuan);
    }

    // Detail 2
    public function detail2($id_item)
    {
        $get = DB::table('item')->where('id_item', $id_item)->get();
        $data_bbb = DB::table('bom_bbb')
            ->leftJoin('item', 'bom_bbb.id_item_bbb', '=', 'item.id_item')
            ->where('bom_bbb.id_item', $id_item)->get();
        $data_btk = DB::table('bom_btk')
            ->leftJoin('pekerja', 'bom_btk.id_pekerja', '=', 'pekerja.id_pekerja')
            ->where('bom_btk.id_item', $id_item)->get();
        $data_bop = DB::table('bom_bop')
            ->leftJoin('item', 'bom_bop.id_item_bop', '=', 'item.id_item')
            ->where('bom_bop.id_item', $id_item)->get();
        $data_item_bbb = DB::table('item')->where('jenis_item', 'Bahan Baku')->orWhere('jenis_item', 'Barang Dalam Proses')->get();
        $data_item_bop = DB::table('item')->where('jenis_item', 'Bahan Penolong')->get();
        $data_item_btk = DB::table('pekerja')->get();

        foreach ($get as $p) {
            $id_item = $p->id_item;
            $nama_item = $p->nama_item;
            $satuan = $p->satuan;
            $jenis_item = $p->jenis_item;
        }
        return view('item.detail2', [
            'data_bbb' => $data_bbb,
            'data_btk' => $data_btk,
            'data_bop' => $data_bop,
            'data_item_bbb' => $data_item_bbb,
            'data_item_bop' => $data_item_bop,
            'data_item_btk' => $data_item_btk,
            'title' => "Satuan Item",
            'id_item' => $id_item,
            'nama_item' => $nama_item,
            'satuan' => $satuan,
            'jenis_item' => $jenis_item
        ]);
    }

    // Store Detail 21 = Bahan Baku
    public function storedetail21(Request $request)
    {
        $validated = $request->validate([
            'id_item' => 'required',
            'id_item_bbb' => 'required',
            'kuantitas' => 'required',
        ]);

        $store = BomBbb::create([
            'id_item' => $request->id_item,
            'id_item_bbb' => $request->id_item_bbb,
            'kuantitas' => $request->kuantitas,
            'user_id_created' => Auth::user()->id
        ]);

        return redirect('/item/detail2/' . $request->id_item)->with('success', 'Data Berhasil di Input');
    }

    // Store Detail 22 = Jenis Upah
    public function storedetail22(Request $request)
    {
        $validated = $request->validate([
            'id_item' => 'required',
            'id_pekerja' => 'required',
            'biaya_tenaga_kerja' => 'required',
        ]);

        $store = BomBtk::create([
            'id_item' => $request->id_item,
            'id_pekerja' => $request->id_pekerja,
            'nominal' => $request->biaya_tenaga_kerja,
            'user_id_created' => Auth::user()->id
        ]);

        return redirect('/item/detail2/' . $request->id_item)->with('success', 'Data Berhasil di Input');
    }

    // Store Detail 23 = Barang Penolong
    public function storedetail23(Request $request)
    {
        $validated = $request->validate([
            'id_item' => 'required',
            'id_item_bop' => 'required',
            'kuantitas' => 'required',
            'target_output' => 'required',
        ]);

        $store = BomBop::create([
            'id_item' => $request->id_item,
            'id_item_bop' => $request->id_item_bop,
            'kuantitas' => $request->kuantitas,
            'target_output' => $request->target_output,
            'user_id_created' => Auth::user()->id
        ]);

        return redirect('/item/detail2/' . $request->id_item)->with('success', 'Data Berhasil di Input');
    }

    public function destroydetail21($id)
    {

        try {
            $get_bom_bbb = DB::table('bom_bbb')->where('id_bom_bbb', '=', $id)->get();
            foreach ($get_bom_bbb as $p) {
                $id_item = $p->id_item;
            }
            $item = BomBbb::findOrFail($id);
            $item->delete();

            return redirect('/item/detail2/' . $id_item)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/item/detail2/' . $id_item)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail22($id)
    {

        try {
            $get_bom_btk = DB::table('bom_btk')->where('id_bom_btk', '=', $id)->get();
            foreach ($get_bom_btk as $p) {
                $id_item = $p->id_item;
            }
            $item = BomBtk::findOrFail($id);
            $item->delete();

            return redirect('/item/detail2/' . $id_item)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/item/detail2/' . $id_item)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroydetail23($id)
    {

        try {
            $get_bom_bop = DB::table('bom_bop')->where('id_bom_bop', '=', $id)->get();
            foreach ($get_bom_bop as $p) {
                $id_item = $p->id_item;
            }
            $item = BomBop::findOrFail($id);
            $item->delete();

            return redirect('/item/detail2/' . $id_item)->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/item/detail2/' . $id_item)->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
}