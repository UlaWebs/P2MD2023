<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PersediaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::table('persediaan as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin(DB::raw('(select id_persediaan_header, sum(kuantitas) as total_pengambilan from pengambilan group by id_persediaan_header) as c'), 'a.id_persediaan_header', '=', 'c.id_persediaan_header')
            ->groupBy('a.id_item', 'b.satuan', 'b.nama_item', 'b.jenis_item')
            ->select('a.id_item', 'b.nama_item', 'b.satuan', 'b.jenis_item', DB::raw('sum(a.kuantitas) as total_persediaan'), DB::raw('sum(c.total_pengambilan) as total_pengambilan'), DB::raw('sum(a.kuantitas*a.harga_satuan) - sum(COALESCE(c.total_pengambilan, 0)*a.harga_satuan) as nilai_persediaan'))
            ->get();

        return view('persediaan.index', ['data' => $data, 'title' => 'Persediaan']);
    }

    public function detail($id)
    {
        $data = DB::table('persediaan as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin(DB::raw('(select id_persediaan_header, sum(kuantitas) as total_pengambilan from pengambilan group by id_persediaan_header) as c'), 'a.id_persediaan_header', '=', 'c.id_persediaan_header')
            ->where('a.id_item', '=', $id)
            ->orderBy('a.tgl_persediaan', 'asc')
            ->select('a.*', 'b.*', DB::raw('COALESCE(c.total_pengambilan, 0) as total_pengambilan'))
            ->get();

        return view('persediaan.detail', ['data' => $data, 'title' => 'Detail Persediaan']);
    }


    public function excel($id){
        $header = DB::table('persediaan as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin(DB::raw('(select id_persediaan_header, sum(kuantitas) as total_pengambilan from pengambilan group by
                id_persediaan_header) as c'), 'a.id_persediaan_header', '=', 'c.id_persediaan_header')
            ->groupBy('a.id_item', 'b.satuan', 'b.nama_item', 'b.jenis_item')
            ->select('a.id_item', 'b.nama_item', 'b.satuan', 'b.jenis_item', DB::raw('sum(a.kuantitas) as
                total_persediaan'), DB::raw('sum(c.total_pengambilan) as total_pengambilan'),
            DB::raw('sum(a.kuantitas*a.harga_satuan) - sum(COALESCE(c.total_pengambilan, 0)*a.harga_satuan) as
                nilai_persediaan'))
            ->where('a.id_item', '=', $id)
            ->get();

        foreach($header as $dt_header){
            $nama_item = $dt_header->nama_item;
            $jenis = $dt_header->jenis_item;
            $stock = $dt_header->total_persediaan - $dt_header->total_pengambilan;
            $nilai = $dt_header->nilai_persediaan;
        }

        $data = DB::table('persediaan as a')
            ->leftjoin('item as b', 'a.id_item', '=', 'b.id_item')
            ->leftJoin(DB::raw('(select id_persediaan_header, sum(kuantitas) as total_pengambilan from pengambilan group by
                id_persediaan_header) as c'), 'a.id_persediaan_header', '=', 'c.id_persediaan_header')
            ->leftJoin('penerimaan_detail as d', 'd.id_penerimaan_detail', '=', 'a.id_penerimaan_detail')
            ->leftJoin('penerimaan_header as e', 'd.id_penerimaan_header', '=', 'e.id_penerimaan_header')
            ->leftJoin('produksi_detail_output as f', 'f.id_produksi_detail_output', '=', 'a.id_produksi_detail_output')
            ->leftJoin('produksi_header as g', 'g.id_produksi_header', '=', 'f.id_produksi_header')
            ->leftJoin('pembelian_tunai_detail as h', 'h.id_pembelian_tunai_detail', '=', 'a.id_pembelian_tunai_detail')
            ->leftJoin('pembelian_tunai_header as i', 'i.id_pembelian_tunai_header', '=','h.id_pembelian_tunai_header')
            ->where('a.id_item', '=', $id)
            ->orderBy('a.tgl_persediaan', 'asc')
            ->select('a.*', 'b.*', DB::raw('COALESCE(c.total_pengambilan, 0) as total_pengambilan'),
                DB::raw("
                    CASE
                        when a.id_penerimaan_detail NOTNULL then e.no_penerimaan
                        when a.id_produksi_detail_output NOTNULL then g.no_produksi
                        when a.id_pembelian_tunai_detail NOTNULL then i.no_pembelian_tunai
                        else ''
                    END as nomor_faktur
                "),
                DB::raw("
                    to_char(a.tgl_persediaan,'DD FMMonth YYYY') as tgl_persediaan_formatted
                ")
                )
            ->get();



        $spreadsheet = new Spreadsheet();

        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setCellValue('B2', 'Kartu Stok');
        $activeWorksheet->mergeCells('B2:G2');
        $activeWorksheet->getStyle('B2')->getFont()->setBold(true);
        $activeWorksheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getColumnDimension('B')->setWidth(21);
        $activeWorksheet->getColumnDimension('C')->setWidth(23);
        $activeWorksheet->getColumnDimension('D')->setWidth(27);
        $activeWorksheet->getColumnDimension('E')->setWidth(12);
        $activeWorksheet->getColumnDimension('F')->setWidth(14);
        $activeWorksheet->getColumnDimension('G')->setWidth(14);
        $activeWorksheet->setCellValue('B4', 'Nama Barang :');
        $activeWorksheet->setCellValue('B5', 'Jenis Barang :');
        $activeWorksheet->setCellValue('B6', 'Jumlah Stock Tersedia :');
        $activeWorksheet->setCellValue('B7', 'Total Nilai Stock :');
        $activeWorksheet->setCellValue('C4', $nama_item);
        $activeWorksheet->setCellValue('C5', $jenis);
        $activeWorksheet->setCellValue('C6', $stock);
        $activeWorksheet->setCellValue('C7', $nilai);
        $activeWorksheet->getStyle('C6:C7')->getNumberFormat()->setFormatCode('#,##0.00');
        $activeWorksheet->getStyle('C6:C7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $activeWorksheet->setCellValue('B9', 'Tanggal');
        $activeWorksheet->setCellValue('C9', 'No. Faktur (Penjualan/Pembelian)');
        $activeWorksheet->setCellValue('D9', 'Keterangan');
        $activeWorksheet->setCellValue('E9', 'Stock Masuk');
        $activeWorksheet->setCellValue('F9', 'Stock Keluar');
        $activeWorksheet->setCellValue('G9', 'Stock Tersedia');
        $activeWorksheet->getStyle('B9:G9')->getAlignment()->setWrapText(true);
        $activeWorksheet->getStyle('B9:G9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $activeWorksheet->getStyle('B9:G9')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $activeWorksheet->getStyle('B9:G9')->getFont()->setBold(true);

        $allBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];
        $activeWorksheet->getStyle('B9:G9')->applyFromArray($allBorder);

        $i = 10;
        foreach($data as $dt_detail){
            $activeWorksheet->setCellValue('B'. $i, $dt_detail->tgl_persediaan_formatted);
            $activeWorksheet->setCellValue('C'. $i, $dt_detail->nomor_faktur);
            $activeWorksheet->setCellValue('D'. $i, $dt_detail->keterangan);
            $activeWorksheet->setCellValue('E'. $i, $dt_detail->kuantitas);
            $activeWorksheet->setCellValue('F'. $i, $dt_detail->total_pengambilan);
            $activeWorksheet->setCellValue('G'. $i, $dt_detail->kuantitas - $dt_detail->total_pengambilan);
            $activeWorksheet->getStyle('B'.$i.':G'.$i)->applyFromArray($allBorder);
            $activeWorksheet->getStyle('E'.$i.':G'.$i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $activeWorksheet->getStyle('B'.$i.':G'.$i)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $activeWorksheet->getStyle('B'.$i.':G'.$i)->getAlignment()->setWrapText(true);
            $i = $i+1;
        }

        // output
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Kartu Stok.xlsx"');
        $writer->save('php://output');
    }
}