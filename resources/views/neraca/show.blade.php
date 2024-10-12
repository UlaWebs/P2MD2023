<x-app-layout>

    <div class="container-fluid">

        <div class="d-flex flex-row align-items-center justify-content-between">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
            <a href="{{ route('neraca') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="text">Kembali</span>
            </a>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 text-center">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>
                <p class="m-0 font-weight-bold text-secondary">PT. Wonderfull Rotan</p>
                <p class="m-0 text-secondary">
                    {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
                </p>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td width="50%">
                                <table width="100%">
                                    @php
                                    $total_aktiva = 0;
                                    @endphp

                                    @foreach ($activa as $p)
                                    <tr>
                                        @if ($p->is_group == 1)
                                        <td><strong>{{ $p->nama_akun }}</strong></td>
                                        @else
                                        <td>{{ $p->nama_akun }}</td>
                                        @endif
                                        @if ($p->is_group == 0)
                                        <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                                        @else
                                        <td></td>
                                        @endif
                                    </tr>
                                    @php
                                    $total_aktiva = $total_aktiva + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>
                            </td>
                            <td width="50%">
                                <table width="100%">
                                    @php
                                    $total_kewajiban = 0;
                                    @endphp

                                    @foreach ($kewajiban as $p)
                                    <tr>
                                        @if ($p->is_group == 1)
                                        <td><strong>{{ $p->nama_akun }}</strong></td>
                                        @else
                                        <td>{{ $p->nama_akun }}</td>
                                        @endif
                                        @if ($p->is_group == 0)
                                        <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                                        @else
                                        <td></td>
                                        @endif
                                    </tr>
                                    @php
                                    $total_kewajiban = $total_kewajiban + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>

                                <table width="100%">
                                    @php
                                    $total_modal = 0;
                                    @endphp

                                    @foreach ($modal as $p)
                                    <tr>
                                        @if ($p->is_group == 1)
                                        <td><strong>{{ $p->nama_akun }}</strong></td>
                                        @else
                                        <td>{{ $p->nama_akun }}</td>
                                        @endif
                                        @if ($p->is_group == 0)
                                        <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                                        @else
                                        <td></td>
                                        @endif
                                    </tr>
                                    @php
                                    $total_modal = $total_modal + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>

                                <table width="100%">
                                    @php
                                    $total_labarugi = 0;
                                    @endphp

                                    @foreach ($labarugi as $p)
                                    <tr>
                                        <td><strong>Laba/Rugi Berjalan</strong></td>
                                        <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                                    </tr>
                                    @php
                                    $total_labarugi = $total_labarugi + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @php
                        $total_pasiva = $total_kewajiban + $total_modal + $total_labarugi;
                        @endphp
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="50%"><strong>Total Aktiva</strong></td>
                                        <td width="50%" align="right">
                                            <strong>
                                                {{ number_format($total_aktiva, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="50%"><strong>Total Pasiva</strong></td>
                                        <td width="50%" align="right">
                                            <strong>
                                                {{ number_format($total_pasiva, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>