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
                <h4 class="m-0 font-weight-bold text-primary">{{ $title }}</h4>
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
                            <td width="100%">
                                <table width="100%">
                                    @php
                                    $total_pendapatan = 0;
                                    @endphp

                                    @foreach ($pendapatan as $p)
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
                                    $total_pendapatan = $total_pendapatan + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="50%"><strong>Total Pendapatan</strong></td>
                                        <td width="50%" align="right">
                                            <strong>
                                                {{ number_format($total_pendapatan, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="100%">
                                <table width="100%">
                                    @php
                                    $total_hpp = 0;
                                    @endphp

                                    @foreach ($hpp as $p)
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
                                    $total_hpp = $total_hpp + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="50%"><strong>Total Harga Pokok Penjualan</strong></td>
                                        <td width="50%" align="right">
                                            <strong>
                                                {{ number_format($total_hpp, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="100%">
                                <table width="100%">
                                    @php
                                    $total_beban = 0;
                                    @endphp

                                    @foreach ($beban as $p)
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
                                    $total_beban = $total_beban + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="50%"><strong>Total Beban</strong></td>
                                        <td width="50%" align="right">
                                            <strong>
                                                {{ number_format($total_beban, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td width="100%">
                                <table width="100%">
                                    @php
                                    $total_pendapatan_lain = 0;
                                    @endphp

                                    @foreach ($pendapatan_lain as $p)
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
                                    $total_pendapatan_lain = $total_pendapatan_lain + $p->saldo;
                                    @endphp
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="50%"><strong>Total Pendapatan Lain</strong></td>
                                        <td width="50%" align="right">
                                            <strong>
                                                {{ number_format($total_pendapatan_lain, 2, ',', '.') }}
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td width="50%"><strong>Laba / Rugi</strong></td>
                                        <td width="50%" align="right">
                                            <strong>
                                                {{ number_format($total_pendapatan - $total_hpp - $total_beban +
                                                $total_pendapatan_lain, 2, ',', '.') }}
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