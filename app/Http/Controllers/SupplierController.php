<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Akun;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\Errormsg;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('supplier as a')->leftJoin('akun as b', 'a.id_akun_hutang', '=', 'b.id_akun')
        ->leftJoin('akun as c', 'a.id_akun_uang_muka_pembelian','=', 'c.id_akun')
        ->select('a.*', 'b.nama_akun as nama_akun_hutang','c.nama_akun as nama_akun_uang_muka_pembelian')
        ->orderBy('a.jenis_supplier')
        ->get();
        return view('supplier/index', ['data' => $data, 'title' => "Supplier"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Akun::all();
        $id_akun_hutang = 51; // ID akun hutang default
        $id_akun_uang_muka_pembelian = 90; // ID akun uang muka pembelian default
        $supplier_type = DB::select('SELECT unnest(enum_range(NULL::supplier_type)) as supplier_type');
        return view('supplier.create', ['data' => $data,'supplier_type'=> $supplier_type, 'id_akun_hutang' => $id_akun_hutang, 'id_akun_uang_muka_pembelian' => $id_akun_uang_muka_pembelian]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required',
            'jenis_supplier' => 'required',
            'id_akun_hutang' => 'required',
            'id_akun_uang_muka_pembelian' => 'required',
        ]);

        $store = Supplier::create(['nama_supplier' => $request->nama_supplier, 'alamat_supplier' => $request->alamat_supplier, 'no_telp_supplier' => $request->no_telp_supplier, 'jenis_supplier' => $request->jenis_supplier, 'id_akun_hutang' => $request->id_akun_hutang,'id_akun_uang_muka_pembelian' => $request->id_akun_uang_muka_pembelian, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('supplier')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Display the specified resource.
     */
    public function show(supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_supplier)
    {
        $data = Akun::all();
        $supplier_type = DB::select('SELECT unnest(enum_range(NULL::supplier_type)) as supplier_type');
        $get = DB::table('supplier')->where('id_supplier', $id_supplier)->get();
        foreach ($get as $p) {
            $id_supplier = $p->id_supplier;
            $nama_supplier = $p->nama_supplier;
            $alamat_supplier = $p->alamat_supplier;
            $no_telp_supplier = $p->no_telp_supplier;
            $jenis_supplier = $p->jenis_supplier;
            $id_akun_hutang = $p->id_akun_hutang;
            $id_akun_uang_muka_pembelian = $p->id_akun_uang_muka_pembelian;
        }
        return view('supplier.edit', ['data' => $data, 'supplier_type' => $supplier_type, 'id_supplier' => $id_supplier, 'nama_supplier' => $nama_supplier, 'alamat_supplier' => $alamat_supplier, 'no_telp_supplier' => $no_telp_supplier, 'jenis_supplier' => $jenis_supplier,  'id_akun_hutang' => $id_akun_hutang, 'id_akun_uang_muka_pembelian' => $id_akun_uang_muka_pembelian]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, supplier $supplier)
    {
        $validated = $request->validate([
            'nama_supplier' => 'required',
            'jenis_supplier' => 'required',
            'id_akun_hutang' => 'required',
            'id_akun_uang_muka_pembelian' => 'required'
        ]);

        $store = Supplier::where('id_supplier', $request->id_supplier)->update(['nama_supplier' => $request->nama_supplier, 'alamat_supplier' => $request->alamat_supplier, 'no_telp_supplier' => $request->no_telp_supplier, 'jenis_supplier' => $request->jenis_supplier, 'id_akun_hutang' => $request->id_akun_hutang, 'id_akun_uang_muka_pembelian' => $request->id_akun_uang_muka_pembelian, 'user_id_updated' => Auth::user()->id]);
        return redirect()->route('supplier')->with('success', 'Data Berhasil di Input');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_supplier)
    {
        try {
            $supplier = Supplier::findOrFail($id_supplier);
            $supplier->delete();

            return redirect()->route('supplier')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect()->route('supplier')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

}
