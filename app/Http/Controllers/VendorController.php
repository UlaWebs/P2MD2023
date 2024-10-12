<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('vendor as a')
        ->leftJoin('akun as b', 'a.id_akun_hutang', '=', 'b.id_akun')
        ->select('a.*', 'b.nama_akun as nama_akun_hutang')
        ->get();
        return view('vendor/index', ['data' => $data, 'title' => "Vendor"]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Akun::all();
        return view('vendor.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required',
            'id_akun_hutang' => 'required',
        ]);

        $store = Vendor::create(['nama_vendor' => $request->nama_vendor, 'alamat_vendor' => $request->alamat_vendor, 'no_telp_vendor' => $request->no_telp_vendor, 'id_akun_hutang' => $request->id_akun_hutang, 'user_id_created' => Auth::user()->id]);
        return redirect()->route('vendor')->with('success', 'Data Berhasil di Input');
    }

    public function edit($id_vendor)
    {
        $vendor = Vendor::findOrFail($id_vendor);
        $data = Akun::all();
        $get = DB::table('vendor')->where('id_vendor', $id_vendor)->get();
        foreach ($get as $p) {
            $id_vendor = $p->id_vendor;
            $nama_vendor = $p->nama_vendor;
            $alamat_vendor = $p->alamat_vendor;
            $no_telp_vendor = $p->no_telp_vendor;
            $id_akun_hutang = $p->id_akun_hutang;
        }

        return view('vendor.edit', [
            'vendor' => $vendor,
            'data' => $data,
            'id_vendor' => $id_vendor,
            'nama_vendor' => $nama_vendor,
            'alamat_vendor' => $alamat_vendor,
            'no_telp_vendor' => $no_telp_vendor,
            'id_akun_hutang' => $id_akun_hutang,
        ]);
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required',
            'id_akun_hutang' => 'required',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
            'alamat_vendor' => $request->alamat_vendor,
            'no_telp_vendor' => $request->no_telp_vendor,
            'id_akun_hutang' => $request->id_akun_hutang,
            'user_id_updated' => Auth::user()->id
        ]);

        return redirect()->route('vendor.index')->with('success', 'Data Berhasil di Update');
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', 'Data Berhasil di Hapus');
    }
}