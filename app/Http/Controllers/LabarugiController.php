<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabarugiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('labarugi.index', ['title' => "Laporan Laba Rugi"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'tgl1' => 'required',
            'tgl2' => 'required',
        ]);

        $title = "Laporan Laba Rugi ";
        // . date_format(date_create($request->tgl1), "d F Y") . " - " . date_format(date_create($request->tgl2), "d F Y")
        $tgl1 = date_create($request->tgl1);
        $tgl1 = date_format($tgl1, "Y-m-d");
        $tgl2 = date_create($request->tgl2);
        $tgl2 = date_format($tgl2, "Y-m-d");

        $get_pendapatan = DB::select("SELECT * FROM laba_rugi_pendapatan('$tgl1', '$tgl2')");
        $get_hpp = DB::select("SELECT * FROM laba_rugi_hpp('$tgl1', '$tgl2')");
        $get_beban = DB::select("SELECT * FROM laba_rugi_beban('$tgl1', '$tgl2')");
        $get_pendapatan_lain = DB::select("SELECT * FROM laba_rugi_pendapatan_lain('$tgl1', '$tgl2')");

        return view('labarugi.show', [
            'title' => $title,
            'pendapatan' => $get_pendapatan,
            'hpp' => $get_hpp,
            'beban' => $get_beban,
            'pendapatan_lain' => $get_pendapatan_lain,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
        ]);
    }
}
