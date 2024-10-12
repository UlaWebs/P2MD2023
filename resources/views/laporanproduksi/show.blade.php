<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>
                <a href="{{ route('laporanproduksi') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        @php
                        $total_bbb = 0;
                        @endphp

                        @foreach ($bbb as $p)
                        <tr>
                            <td>{{ $p->nama_item }}</td>
                            <td align="right">{{ number_format($p->total_bbb, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total_bbb = $total_bbb + $p->total_bbb;
                        @endphp
                        @endforeach
                        <tr>
                            <td width="70%"><strong>Total Bahan Baku</strong></td>
                            <td align="right">
                                <strong>
                                    {{ number_format($total_bbb, 2, ',', '.') }}
                                </strong>
                            </td>
                        </tr>

                        @php
                        $total_btk = 0;
                        @endphp

                        @foreach ($btk as $p)
                        <tr>
                            <td>{{ $p->nama_pekerja }}</td>
                            <td align="right">{{ number_format($p->total_biaya_tenaga_kerja, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total_btk = $total_btk + $p->total_biaya_tenaga_kerja;
                        @endphp
                        @endforeach
                        <tr>
                            <td width="70%"><strong>Total Biaya Tenaga Kerja</strong></td>
                            <td align="right">
                                <strong>
                                    {{ number_format($total_btk, 2, ',', '.') }}
                                </strong>
                            </td>
                        </tr>

                        @php
                        $total_bop = 0;
                        @endphp

                        @foreach ($bop as $p)
                        <tr>
                            <td>{{ $p->nama_item }}</td>
                            <td align="right">{{ number_format($p->total_bop, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total_bop = $total_bop + $p->total_bop;
                        @endphp
                        @endforeach

                        <tr>
                            <td width="50%"><strong>Total Biaya Overhead</strong></td>
                            <td width="50%" align="right">
                                <strong>
                                    {{ number_format($total_bop, 2, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%"><strong>Total Biaya Produksi</strong></td>
                            <td width="50%" align="right">
                                <strong>
                                    {{ number_format($total_btk + $total_bbb + $total_bop, 2, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>