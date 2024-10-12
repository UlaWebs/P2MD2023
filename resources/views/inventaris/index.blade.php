<x-app-layout>
    @section('title')
        {{ $title }}
    @endsection
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
            <h4 class="m-0 header-title text-primary">Data {{ $title }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Aset</th>
                            <th>Kategori Aset</th>
                            <th>Total Inventaris</th>
                            <th>Total Pengeluaran</th>
                            <th>Stok Tersedia</th>
                            <th>Nilai Inventaris</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->nama_aset }}</td>
                                <td>{{ $row->nama_kategori_aset }}</td>
                                <td>{{ $row->total_inventaris }}</td>
                                <td>{{ $row->total_pengeluaran }}</td>
                                <td>{{ $row->total_inventaris - $row->total_pengeluaran }}</td>
                                <td align="right">@currency($row->nilai_inventaris)</td>
                                <td>
                                    <a href="{{ route('inventaris.detail', $row->id_aset) }}"
                                        class="btn btn-warning btn-circle">
                                        <i class="fas fa-list"></i>
                                    </a>
                                    <a href="{{ route('inventaris.detail2', $row->id_aset) }}"
                                        class="btn btn-primary btn-circle">
                                        <i class="fas fa-list"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
