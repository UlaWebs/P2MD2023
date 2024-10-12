<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>

                <a href="{{ route('labarugi') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>Pembelian Tunai</td>
                            <td></td>
                            <td align="right">
                                @foreach ($pembelian_tunai as $p)
                                    @currency($p->total)
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Pembayaran Ke Supplier</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td> Uang Muka Pesanan</td>
                            <td></td>
                            <td align="right">
                                @foreach ($uang_muka as $p)
                                    @currency($p->total)
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td> Pelunasan</td>
                            <td></td>
                            <td align="right">
                                @foreach ($pelunasan_pembelian as $p)
                                    @currency($p->total)
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Pembayaran Upah Produksi</td>
                            <td></td>
                            <td align="right">
                                @foreach ($upah as $p)
                                    @currency($p->total)
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
