<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex flex-row align-items-center justify-content-between">
            <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
            <a href="{{ route('ledger') }}" class="btn btn-primary btn-icon-split btn-sm">
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
                @foreach ($data_akun as $akun)
                @php
                $saldo = $akun->saldo_awal;
                $is_debit_account = $akun->posisi == 'D';
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td colspan="4"><strong>{{ $akun->kode_akun }}</strong>
                                <strong>{{ $akun->nama_akun }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Saldo Awal</strong></td>
                            <td align="right"><strong>{{ number_format($akun->saldo_awal, 2, ',', '.') }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Tanggal</th>
                            <th>Keterangan</th>
                            <th width="15%">Debet</th>
                            <th width="15%">Kredit</th>
                        </tr>
                        @foreach ($data_transaksi as $transaksi)
                        @if ($transaksi->id_akun == $akun->id_akun)
                        <tr>
                            <td>{{ date_format(date_create($transaksi->tgl_jurnal), 'd F Y') }}</td>
                            <td>{{ $transaksi->keterangan }}</td>
                            <td align="right">{{ number_format($transaksi->debet, 2, ',', '.') }}</td>
                            <td align="right">{{ number_format($transaksi->kredit, 2, ',', '.') }}</td>
                        </tr>

                        @php
                        if ($is_debit_account) {
                        // Akun bertambah di debet dan berkurang di kredit
                        $saldo += $transaksi->debet;
                        $saldo -= $transaksi->kredit;
                        } else {
                        // Akun bertambah di kredit dan berkurang di debet
                        $saldo -= $transaksi->debet;
                        $saldo += $transaksi->kredit;
                        }
                        @endphp
                        @endif
                        @endforeach
                        <tr>
                            <td colspan="3"><strong>Saldo Akhir</strong></td>
                            <td align="right">
                                <strong>{{ number_format($saldo, 2, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </table>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>