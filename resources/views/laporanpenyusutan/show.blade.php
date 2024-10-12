<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>

                <a href="{{ route('laporanpenjualan') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    {{-- <style>
                        table,
                        th,
                        td {
                            border: 1px solid black;
                        }
                    </style> --}}
                    <table id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal & Tahun</th>
                                <th>Penyusutan</th>
                                <th>Akumulasi Penyusutan</th>
                                <th>Nilai Sisa</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            <tr>
                                <td>Harga Perolehan</td>
                                <td colspan="3" align="right">{{ subtotal_perolehan }}</td>
                            </tr>
                            <tr>
                                <td>{{ tgl_depresiasi_aset }}</td>
                                <td>{{ nominal_depresiasi }}</td>
                                <td>{{ nominal_depresiasi = nominal_depresiasi - nominal_depresiasi }}</td>
                                <td align="right">{{ harga_perolehan - nominal_depresiasi }}</td>
                            </tr>
                        </tbody> --}}

                        <tbody>
                            @if ($data->isNotEmpty())
                                <tr>
                                    <td>Harga Perolehan</td>
                                    <td colspan="3" align="right">
                                        Rp. {{ number_format($data[0]->harga_perolehan, 2, ',', '.') }}</td>
                                </tr>
                                @php
                                    $akumulasiPenyusutan = 0;
                                    $hargaPerolehan = $data[0]->harga_perolehan;
                                @endphp
                                @foreach ($data as $item)
                                    @php
                                        $akumulasiPenyusutan += $item->nominal_depresiasi;
                                        $nilaiSisa = $hargaPerolehan - $akumulasiPenyusutan;
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_depresiasi_aset)->format('Y - F') }}
                                        </td>
                                        <td align="center">Rp.
                                            {{ number_format($item->nominal_depresiasi, 2, ',', '.') }}</td>
                                        <td align="center">Rp. {{ number_format($akumulasiPenyusutan, 2, ',', '.') }}
                                        </td>
                                        <td align="right">Rp. {{ number_format($nilaiSisa, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
