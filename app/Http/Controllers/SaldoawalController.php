<?php

namespace App\Http\Controllers;

use App\Helpers\Errormsg;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaldoawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('akun as a')
            ->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')
            ->leftjoin(DB::raw("(select * from jurnal_detail x left join jurnal_header y on x.id_jurnal_header = y.id_jurnal_header where y.jenis_transaksi = 'saldo awal') as c"), 'a.id_akun', '=', 'c.id_akun')
            ->where('b.is_group', 'f')
            ->select('a.id_akun', 'a.kode_akun', 'a.nama_akun', 'b.id_jenis_akun', 'b.nama_jenis_akun', 'b.is_group', 'b.posisi', DB::raw("
            case
                when b.posisi = 'D' then coalesce(sum(c.debet) - sum(c.kredit), 0)
                when b.posisi = 'K' then coalesce(sum(c.kredit) - sum(c.debet), 0)
            end as saldo_awal
            "))
            ->groupBy('a.id_akun', 'a.kode_akun', 'a.nama_akun', 'b.id_jenis_akun', 'b.nama_jenis_akun', 'b.is_group', 'b.posisi')
            ->orderBy('a.kode_akun')
            ->get();
        return view('saldoawal.index', ['data' => $data, 'title' => 'Saldo Awal']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_akun)
    {
        $get = DB::table('akun as a')
            ->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')
            ->leftjoin(DB::raw("(select * from jurnal_detail x left join jurnal_header y on x.id_jurnal_header = y.id_jurnal_header where y.jenis_transaksi = 'saldo awal') as c"), 'a.id_akun', '=', 'c.id_akun')
            ->where('b.is_group', 'f')
            ->where('a.id_akun', $id_akun)
            ->select('a.id_akun', 'a.kode_akun', 'a.nama_akun', 'b.id_jenis_akun', 'b.nama_jenis_akun', 'b.is_group', 'b.posisi', DB::raw("
            case
                when b.posisi = 'D' then coalesce(sum(c.debet) - sum(c.kredit), 0)
                when b.posisi = 'K' then coalesce(sum(c.kredit) - sum(c.debet), 0)
            end as saldo_awal
            "))
            ->groupBy('a.id_akun', 'a.kode_akun', 'a.nama_akun', 'b.id_jenis_akun', 'b.nama_jenis_akun', 'b.is_group', 'b.posisi')
            ->orderBy('a.kode_akun')
            ->get();

        foreach ($get as $p) {
            $id_akun = $p->id_akun;
            $nama_akun = $p->nama_akun;
            $saldo_awal = $p->saldo_awal;
        }
        return view('saldoawal.edit', ['id_akun' => $id_akun, 'nama_akun' => $nama_akun, 'saldo_awal' => $saldo_awal, 'title' => 'Saldo Awal']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, akun $akun)
    {
        $validated = $request->validate([
            'id_akun' => 'required',
            'saldo_awal' => 'required',
        ]);

        $user_id = Auth::user()->id;
        try {
            $store = DB::select("SELECT * FROM saldo_awal($request->id_akun, $request->saldo_awal, $user_id)");
            foreach ($store as $p) {
                $message = $p->saldo_awal;
            }
            return redirect('/saldoawal')->with('success', $message);
        } catch (\Exception $e) {
            return redirect('/saldoawal')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }

    public function destroy($id_akun)
    {
        try {
            $delete = DB::table('jurnal_header')->where('id_transaksi', $id_akun)->where('jenis_transaksi', 'saldo awal')->delete();
            return redirect('/saldoawal')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/saldoawal')->with('warning', Errormsg::errordb($e->getCode()));
        }
    }
}
