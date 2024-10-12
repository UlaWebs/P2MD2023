<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NeracasaldoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('neracasaldo.index', ['title' => "Laporan Neraca Saldo"]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'required',
        ]);

        $title = "Laporan Neraca Saldo Per ";
        //. date_format(date_create($request->tgl), "d F Y")
        $tgl = date_create($request->tgl);
        $tgl = date_format($tgl, "Y-m-d");
        $start_date = date("Y-m-01", strtotime($tgl));

        $get_activa = DB::select("SELECT * FROM neraca_aktiva('$tgl')");
        $get_kewajiban = DB::select("SELECT * FROM neraca_kewajiban('$tgl')");
        $get_modal = DB::select("SELECT * FROM neraca_modal('$tgl')");

        $get_pendapatan = DB::select("SELECT * FROM laba_rugi_pendapatan('$start_date', '$tgl')");
        $get_hpp = DB::select("SELECT * FROM laba_rugi_hpp('$start_date', '$tgl')");
        $get_beban = DB::select("SELECT * FROM laba_rugi_beban('$start_date', '$tgl')");
        $get_pendapatan_lain = DB::select("SELECT * FROM laba_rugi_pendapatan_lain('$start_date', '$tgl')");

        return view('neracasaldo.show', [
            'title' => $title,
            'activa' => $get_activa,
            'kewajiban' => $get_kewajiban,
            'modal' => $get_modal,
            'pendapatan' => $get_pendapatan,
            'hpp' => $get_hpp,
            'beban' => $get_beban,
            'pendapatan_lain' => $get_pendapatan_lain,
            'tgl' => $tgl,
        ]);
    }
}
