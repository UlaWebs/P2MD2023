<x-app-layout>

    <div class="container-fluid">

        <div class="d-flex flex-row align-items-center justify-content-between">
            <!-- Page Heading -->
            <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>
            <a href="{{ route('perubahanmodal') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 text-center">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>
                <p class="m-0 font-weight-bold text-secondary">PT. Wonderfull Rotan</p>
                <p class="m-0 text-secondary">
                    Per {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
                </p>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>Modal Awal</td>
                            <td align="right">{{ number_format($nominal_modal_awal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Laba Bersih</td>
                            <td align="right">{{ number_format($labarugi, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Pengambilan Modal</td>
                            <td align="right">{{ number_format($pengambilan_modal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Modal Akhir</td>
                            <td align="right">{{ number_format($saldo_akhir, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>