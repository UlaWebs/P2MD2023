<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PerubahanmodalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('perubahanmodal.index', ['title' => "Laporan Perubahan Modal"]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'required',
        ]);

        $title = "Laporan Perubahan Modal";
        // . date_format(date_create($request->tgl), "d F Y");
        $tgl = date_create($request->tgl);
        $tgl = date_format($tgl, "Y-m-d");
        $start_date = date("Y-m-01", strtotime($tgl));

        $get_modal_awal = DB::select("select * from neraca_modal ('$start_date') a left join akun b on a.kode_akun = b.kode_akun left join jenis_akun c on b.id_jenis_akun = c.id_jenis_akun");
        $nominal_modal_awal = 0;

        foreach ($get_modal_awal as $data_modal_awal) {
            if ($data_modal_awal->kode_tipe_akun == '31A') {
                $nominal_modal_awal = $nominal_modal_awal + $data_modal_awal->saldo;
            }
        }

        $get_labarugi = DB::select("SELECT * FROM neraca_labarugi('$tgl')");
        $nominal_laba_rugi = 0;
        foreach ($get_labarugi as $data_laba_rugi) {
            $nominal_laba_rugi = $nominal_laba_rugi + $data_laba_rugi->saldo;
        }

        $nominal_pengambilan_modal = 0;
        foreach ($get_modal_awal as $data_modal_awal) {
            if ($data_modal_awal->kode_tipe_akun == '31B') {
                $nominal_pengambilan_modal = $nominal_pengambilan_modal + $data_modal_awal->saldo;
            }
        }

        $saldo_modal_akhir = $nominal_modal_awal + $nominal_laba_rugi - $nominal_pengambilan_modal;

        return view('perubahanmodal.show', [
            'title' => $title,
            'modal' => $get_modal_awal,
            'nominal_modal_awal' => $nominal_modal_awal,
            'labarugi' => $nominal_laba_rugi,
            'pengambilan_modal' => $nominal_pengambilan_modal,
            'saldo_akhir' => $saldo_modal_akhir,
            'tgl' => $tgl,
        ]);
    }
}
