<x-app-layout>

    <div class="container-fluid">

        <div class="d-flex flex-row align-items-center justify-content-between">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
            <a href="{{ route('neracasaldo') }}" class="btn btn-primary btn-icon-split btn-sm">
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

                        @php
                        $total_aset = 0;
                        @endphp

                        @foreach ($activa as $p)
                        @if ($p->is_group == 0)
                        <tr>
                            <td>{{ $p->kode_akun }}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        @php
                        $total_aset = $total_aset + $p->saldo;
                        @endphp
                        @endif
                        @endforeach

                        @php
                        $total_kewajiban = 0;
                        @endphp

                        @foreach ($kewajiban as $p)
                        @if ($p->is_group == 0)
                        <tr>
                            <td>{{ $p->kode_akun }}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td></td>
                            <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total_kewajiban = $total_kewajiban + $p->saldo;
                        @endphp
                        @endif
                        @endforeach


                        @php
                        $total_modal = 0;
                        @endphp

                        @foreach ($modal as $p)
                        @if ($p->is_group == 0)
                        <tr>
                            <td>{{ $p->kode_akun }}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td></td>
                            <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total_modal = $total_modal + $p->saldo;
                        @endphp
                        @endif
                        @endforeach

                        @php
                        $total_pendapatan = 0;
                        @endphp

                        @foreach ($pendapatan as $p)
                        @if ($p->is_group == 0)
                        <tr>
                            <td>{{ $p->kode_akun }}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td></td>
                            <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total_pendapatan = $total_pendapatan + $p->saldo;
                        @endphp
                        @endif
                        @endforeach

                        @php
                        $total_hpp = 0;
                        @endphp

                        @foreach ($hpp as $p)
                        @if ($p->is_group == 0)
                        <tr>
                            <td>{{ $p->kode_akun }}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        @php
                        $total_hpp = $total_hpp + $p->saldo;
                        @endphp
                        @endif
                        @endforeach

                        @php
                        $total_beban = 0;
                        @endphp

                        @foreach ($beban as $p)
                        @if ($p->is_group == 0)
                        <tr>
                            <td>{{ $p->kode_akun }}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        @php
                        $total_beban = $total_beban + $p->saldo;
                        @endphp
                        @endif
                        @endforeach

                        @php
                        $total_pendapatan_lain = 0;
                        @endphp

                        @foreach ($pendapatan_lain as $p)
                        @if ($p->is_group == 0)
                        <tr>
                            <td>{{ $p->kode_akun }}</td>
                            <td>{{ $p->nama_akun }}</td>
                            <td></td>
                            <td align="right">{{ number_format($p->saldo, 2, ',', '.') }}</td>
                        </tr>
                        @php
                        $total_pendapatan_lain = $total_pendapatan_lain + $p->saldo;
                        @endphp
                        @endif
                        @endforeach

                        @php
                        $total_pasiva =
                        $total_kewajiban + $total_modal + $total_pendapatan + $total_pendapatan_lain;
                        $total_aktiva = $total_aset + $total_hpp + $total_beban;
                        @endphp

                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td align="right">
                                <strong>
                                    {{ number_format($total_aktiva, 2, ',', '.') }}
                                </strong>
                            </td>
                            <td align="right">
                                <strong>
                                    {{ number_format($total_pasiva, 2, ',', '.') }}
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>