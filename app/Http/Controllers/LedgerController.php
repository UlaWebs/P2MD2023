<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $akun_list = DB::table('akun')->orderBy('kode_akun')->get();

        return view('ledger.index', ['title' => "Laporan Buku Besar", 'akun_list' => $akun_list]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'tgl1' => 'required',
            'tgl2' => 'required',
            'id_akun' => 'nullable|exists:akun,id_akun', // validasi untuk id_akun
        ]);

        $title = "Buku Besar";
        //. date_format(date_create($request->tgl1), "d F Y") . " - " . date_format(date_create($request->tgl2), "d F Y")
        $tgl1 = date_create($request->tgl1);
        $tgl1 = date_format($tgl1, "Y-m-d");
        $tgl2 = date_create($request->tgl2);
        $tgl2 = date_format($tgl2, "Y-m-d");

        $tgl_awal = date('Y-m-d', (strtotime('-1 day', strtotime($tgl1))));

        // Query untuk akun yang dipilih atau semua akun jika tidak ada yang dipilih
        $data_akun = DB::table('akun as a')
            ->leftJoin('jenis_akun as b', 'a.id_jenis_akun', '=', 'b.id_jenis_akun')
            ->select('a.*', DB::raw("(SELECT saldo FROM saldo_akun('$tgl_awal', a.id_akun)) as saldo_awal"),
                DB::raw("(SELECT saldo FROM saldo_akun('$tgl2', a.id_akun)) as saldo_akhir"), 'b.posisi')
            ->when($request->id_akun, function ($query) use ($request) {
                return $query->where('a.id_akun', $request->id_akun);
                })
            ->where('b.is_group', '=', 'f')
            ->orderBy('a.kode_akun', 'asc')
            ->get();

        $data_transaksi = DB::table('jurnal_detail as a')
            ->leftJoin('jurnal_header as b', 'a.id_jurnal_header', '=', 'b.id_jurnal_header')
            ->where('b.tgl_jurnal', '>=', $tgl1)
            ->where('b.tgl_jurnal', '<=', $tgl2) ->orderBy('b.tgl_jurnal', 'asc')
            ->get();

        return view('ledger.show', [
            'title' => $title,
            'data_akun' => $data_akun,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'tgl_awal' => $tgl_awal,
            'data_transaksi' => $data_transaksi
        ]);
    }
}
