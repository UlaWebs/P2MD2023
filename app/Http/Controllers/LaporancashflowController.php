<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporancashflowController extends Controller
{
    public function index(){
        return view('laporancashflow.index', ['title' => "Laporan Arus Kas"]);
    }

    public function show(Request $request){
        $validated = $request->validate([
            'tgl1' => 'required',
            'tgl2' => 'required',
        ]);

        $title = "Laporan Arus Kas " . date_format(date_create($request->tgl1), "d F Y") . " - " .
        date_format(date_create($request->tgl2), "d F Y");
        $tgl1 = date_create($request->tgl1);
        $tgl1 = date_format($tgl1, "Y-m-d");
        $tgl2 = date_create($request->tgl2);
        $tgl2 = date_format($tgl2, "Y-m-d");

        $get_pembelian_tunai = DB::table('jurnal_detail as a')
            ->leftJoin('jurnal_header as b', 'a.id_jurnal_header', '=', 'b.id_jurnal_header')
            ->leftJoin('akun as c', 'c.id_akun', '=', 'a.id_akun')
            ->leftJoin('jenis_akun as d', 'c.id_jenis_akun', '=', 'd.id_jenis_akun')
            ->where('d.kode_tipe_akun', '=', '11A')
            ->where('b.jenis_transaksi', '=', 'pembelian tunai')
            ->whereBetween('tgl_jurnal', [$tgl1, $tgl2])
            ->select(DB::raw(
                'SUM(a.kredit) as total'
            ))
            ->get();

        $get_uang_muka = DB::table('jurnal_detail as a')
            ->leftJoin('jurnal_header as b', 'a.id_jurnal_header', '=', 'b.id_jurnal_header')
            ->leftJoin('akun as c', 'c.id_akun', '=', 'a.id_akun')
            ->leftJoin('jenis_akun as d', 'c.id_jenis_akun', '=', 'd.id_jenis_akun')
            ->where('d.kode_tipe_akun', '=', '11A')
            ->where('b.jenis_transaksi', '=', 'pemesanan')
            ->whereBetween('tgl_jurnal', [$tgl1, $tgl2])
            ->select(DB::raw(
                'SUM(a.kredit) as total'
            ))
            ->get();

        $get_pelunasan_pembelian = DB::table('jurnal_detail as a')
            ->leftJoin('jurnal_header as b', 'a.id_jurnal_header', '=', 'b.id_jurnal_header')
            ->leftJoin('akun as c', 'c.id_akun', '=', 'a.id_akun')
            ->leftJoin('jenis_akun as d', 'c.id_jenis_akun', '=', 'd.id_jenis_akun')
            ->where('d.kode_tipe_akun', '=', '11A')
            ->where('b.jenis_transaksi', '=', 'pelunasan pembelian')
            ->whereBetween('tgl_jurnal', [$tgl1, $tgl2])
            ->select(DB::raw(
                'SUM(a.kredit) as total'
            ))
            ->get();

        $upah = DB::table('jurnal_detail as a')
            ->leftJoin('jurnal_header as b', 'a.id_jurnal_header', '=', 'b.id_jurnal_header')
            ->leftJoin('akun as c', 'c.id_akun', '=', 'a.id_akun')
            ->leftJoin('jenis_akun as d', 'c.id_jenis_akun', '=', 'd.id_jenis_akun')
            ->where('d.kode_tipe_akun', '=', '11A')
            ->where('b.jenis_transaksi', '=', 'pembayaran upah')
            ->whereBetween('tgl_jurnal', [$tgl1, $tgl2])
            ->select(DB::raw(
                'SUM(a.kredit) as total'
            ))
            ->get();

        return view('laporancashflow.show', [
            'title' => $title,
            'pembelian_tunai' => $get_pembelian_tunai,
            'uang_muka' => $get_uang_muka,
            'pelunasan_pembelian' => $get_pelunasan_pembelian,
            'upah' => $upah,
        ]);
    }

    public function cashflow(Request $request){
        $validated = $request->validate([
        'tgl1' => 'required',
        'tgl2' => 'required',
        ]);

        $title = "Laporan Arus Kas ";
        // . date_format(date_create($request->tgl1), "d F Y") . " - " . date_format(date_create($request->tgl2), "d F Y")
        $tgl1 = date_create($request->tgl1);
        $tgl1 = date_format($tgl1, "Y-m-d");
        $tgl2 = date_create($request->tgl2);
        $tgl2 = date_format($tgl2, "Y-m-d");

        $get_data = DB::table('jurnal_detail as a')
            ->leftJoin('jurnal_header as b', 'a.id_jurnal_header', '=', 'b.id_jurnal_header')
            ->leftJoin('akun as c', 'c.id_akun', '=', 'a.id_akun')
            ->leftJoin('jenis_akun as d', 'c.id_jenis_akun', '=', 'd.id_jenis_akun')
            ->where('d.kode_tipe_akun', '=', '11A')
            ->where(function ($query) {
                $query->where('b.jenis_transaksi','=','pembelian tunai')
                    ->orWhere('b.jenis_transaksi','=','pemesanan')
                    ->orWhere('b.jenis_transaksi','=','pelunasan pembelian')
                    ->orWhere('b.jenis_transaksi','=','pembayaran upah')
                    ->orWhere('b.jenis_transaksi','=','penjualan tunai')
                    ->orWhere('b.jenis_transaksi','=','pelunasan kredit')
                    ->orWhere('b.jenis_transaksi','=','pengeluaran biaya');
            })
            ->whereBetween('tgl_jurnal', [$tgl1, $tgl2])
            ->select('b.jenis_transaksi',
                DB::raw(
                    "
                    CASE
                        WHEN b.jenis_transaksi = 'pembelian tunai' THEN '1#Pembelian Tunai#k'
                        WHEN b.jenis_transaksi = 'pemesanan' THEN '2#Pemesanan#k'
                        WHEN b.jenis_transaksi = 'pelunasan pembelian' THEN '3#Pelunasan#k'
                        WHEN b.jenis_transaksi = 'pembayaran upah' THEN '4#Pembayaran Upah#k'
                        WHEN b.jenis_transaksi = 'penjualan tunai' THEN '5#Penjualan Tunai#d'
                        WHEN b.jenis_transaksi = 'pelunasan kredit' THEN '6#Pelunasan Kredit#d'
                        WHEN b.jenis_transaksi = 'pengeluaran biaya' THEN '7#Pengeluaran Biaya#k'
                    END as keterangan_transaksi
                    "
                )
             ,DB::raw(
                'SUM(a.kredit) as total_kredit, SUM(a.debet) as total_debet'
                ))
            ->groupBy('b.jenis_transaksi')
            ->orderBy('keterangan_transaksi')
            ->get();

        $get_data2 = DB::table('jurnal_detail as a')
            ->leftJoin('jurnal_header as b', 'a.id_jurnal_header', '=', 'b.id_jurnal_header')
            ->leftJoin('akun as c', 'c.id_akun', '=', 'a.id_akun')
            ->leftJoin('jenis_akun as d', 'c.id_jenis_akun', '=', 'd.id_jenis_akun')
            ->where('d.kode_tipe_akun', '=', '11A')
            ->where(function ($query) {
                $query->where('b.jenis_transaksi','=','perolehan aset')
                    ->orWhere('b.jenis_transaksi','=','pelepasan aset');
            })
            ->whereBetween('tgl_jurnal', [$tgl1, $tgl2])
            ->select('b.jenis_transaksi',
                DB::raw(
                    "
                        CASE
                        WHEN b.jenis_transaksi = 'perolehan aset' THEN '1#Perolehan Aset#k'
                        WHEN b.jenis_transaksi = 'pelepasan aset' THEN '2#Pelepasan Aset#d'
                        END as keterangan_transaksi
                    "
                )
                ,DB::raw(
                'SUM(a.kredit) as total_kredit, SUM(a.debet) as total_debet'
                ))
            ->groupBy('b.jenis_transaksi')
            ->orderBy('keterangan_transaksi')
            ->get();

        return view('laporancashflow.cashflow', [
            'title' => $title,
            'data' => $get_data,
            'data2' => $get_data2,
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
        ]);
    }
}
