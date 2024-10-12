<x-app-layout>

    <div class="container-fluid">

        <div class="d-flex flex-row align-items-center justify-content-between">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
            <a href="{{ route('labarugi') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="text">Kembali</span>
            </a>
        </div>


        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 text-center">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>
                <p class="m-0 font-weight-bold text-secondary">PT. Wonderfull Rotan</p>
                <p class="m-0 text-secondary">
                    {{ \Carbon\Carbon::parse($tgl1)->format('d M Y') }} - {{
                    \Carbon\Carbon::parse($tgl2)->format('d M Y') }}
                </p>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td colspan="3">Aktifitas Operasi</td>
                        </tr>
                        @foreach ($data as $p)
                        <tr>
                            @php
                            $keterangan_transaksi = explode('#', $p->keterangan_transaksi);
                            $ket = $keterangan_transaksi[1];
                            @endphp
                            <td>{{ $ket }}</td>
                            @if ($keterangan_transaksi[2] == 'd')
                            <td align="right">
                                @currency($p->total_debet)
                            </td>
                            <td></td>
                            @else
                            <td></td>
                            <td align="right">
                                @currency($p->total_kredit)
                            </td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td colspan="3">Investasi</td>
                        </tr>

                        @foreach ($data2 as $p)
                        <tr>
                            @php
                            $keterangan_transaksi = explode('#', $p->keterangan_transaksi);
                            $ket = $keterangan_transaksi[1];
                            @endphp
                            <td>{{ $ket }}</td>
                            @if ($keterangan_transaksi[2] == 'd')
                            <td align="right">
                                @currency($p->total_debet)
                            </td>
                            <td></td>
                            @else
                            <td></td>
                            <td align="right">
                                @currency($p->total_kredit)
                            </td>
                            @endif
                        </tr>
                        @endforeach

                        <tr>
                            <td colspan="3">Aktifitas Pendanaan</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>