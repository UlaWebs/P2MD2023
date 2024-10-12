<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <!-- Alert success -->
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <!-- Akhir alert success -->

        <!-- Alert success -->
        @if ($message = Session::get('warning'))
            <div class="alert alert-warning">
                <p>{{ $message }}</p>
            </div>
        @endif
        <!-- Akhir alert success -->

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" style="width: 1500px" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama Aset</th>
                                <th>Kode</th>
                                <th>Harga Perolehan</th>
                                <th>Metode Penyusutan</th>
                                <th>Umur Ekonomis</th>
                                <th>Nilai Residu</th>
                                <th>Jumlah Penyusutan</th>
                                <th>Akumulasi Penyusutan</th>
                                <th>Nilai Aset (Saat Ini)</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama Aset</th>
                                <th>Kode</th>
                                <th>Harga Perolehan</th>
                                <th>Metode Penyusutan</th>
                                <th>Umur Ekonomis</th>
                                <th>Nilai Residu</th>
                                <th>Jumlah Penyusutan</th>
                                <th>Akumulasi Penyusutan</th>
                                <th>Nilai Aset (Saat Ini)</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td>{{ $row->nama_aset }}</td>
                                    <td>{{ $row->kode_inventaris }}</td>
                                    <td align="right">@currency($row->harga_perolehan)</td>
                                    <td>{{ $row->metode_penyusutan }}</td>
                                    <td>{{ $row->umur_ekonomis }} Bulan</td>
                                    <td align="right">@currency($row->nilai_residu)</td>
                                    <td align="center">{{ $row->n_depresiasi }}</td>
                                    <td align="right">@currency($row->total_depresiasi)</td>
                                    <td align="right">@currency($row->harga_perolehan - $row->total_depresiasi)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <!-- Page level plugins -->
    <script src="{{ asset('sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
</x-app-layout>
