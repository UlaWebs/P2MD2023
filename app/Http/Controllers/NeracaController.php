<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('neraca.index', ['title' => "Laporan Neraca"]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'required',
        ]);

        $title = "Laporan Neraca Per ";
        // . date_format(date_create($request->tgl), "d F Y")
        $tgl = date_create($request->tgl);
        $tgl = date_format($tgl, "Y-m-d");

        $get_activa = DB::select("SELECT * FROM neraca_aktiva('$tgl')");
        $get_kewajiban = DB::select("SELECT * FROM neraca_kewajiban('$tgl')");
        $get_modal = DB::select("SELECT * FROM neraca_modal('$tgl')");
        $get_labarugi = DB::select("SELECT * FROM neraca_labarugi('$tgl')");

        return view('neraca.show', [
            'title' => $title,
            'activa' => $get_activa,
            'kewajiban' => $get_kewajiban,
            'modal' => $get_modal,
            'labarugi' => $get_labarugi,
            'tgl' => $tgl,
        ]);
    }
}
