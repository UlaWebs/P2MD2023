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
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Harga Perolehan</th>
                                <th>Nilai Residu</th>
                                <th>Stok Masuk</th>
                                <th>Stok Keluar</th>
                                <th>Stok Tersedia</th>
                                <th>Nilai Stok</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Nama Aset</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Harga Perolehan</th>
                                <th>Nilai Residu</th>
                                <th>Stok Masuk</th>
                                <th>Stok Keluar</th>
                                <th>Stok Tersedia</th>
                                <th>Nilai Stok</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td>{{ $row->nama_aset }}</td>
                                    <td>{{ $row->kode_inventaris }}</td>
                                    <td>{{ $row->tgl_perolehan_inventaris }}</td>
                                    <td>{{ $row->keterangan }}</td>
                                    <td>@currency($row->harga_perolehan)</td>
                                    <td>@currency($row->nilai_residu)</td>
                                    <td>{{ $row->kuantitas }}</td>
                                    <td>{{ $row->total_pengeluaran }}</td>
                                    <td>{{ $row->kuantitas - $row->total_pengeluaran }}</td>
                                    <td>@currency(($row->kuantitas - $row->total_pengeluaran) * $row->harga_perolehan)</td>
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
