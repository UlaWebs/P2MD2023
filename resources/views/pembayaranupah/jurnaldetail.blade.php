<x-app-layout>
    @section('title')
        {{ $title }}
    @endsection
    <!-- Akhir alert success -->

    <div class="row">
        <!-- DataTales Example -->
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-start">
                        <h1 class="h3 mb-2 text-gray-800 text-primary">Data {{ $title }}</h1>
                    </div>
                    <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{ route('pembayaranupah') }}">
                            Kembali </a>
                    </div>
                </div>
                <hr>
                <div class="sub-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start mt-3">
                                <p class="m-t-10"><strong>No Jurnal : </strong> {{ $no_jurnal }}</p>
                                <p class="m-t-10"><strong>Tanggal Jurnal : </strong> {{ $tgl_jurnal }}</p>
                                <p class="m-t-10"><strong>Keterangan : </strong> {{ $keterangan }}</p>
                                <p class="m-t-10"><strong>Jenis Transaksi : </strong> {{ $jenis_transaksi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered dt-responsive nowrap" id="responsive-datatable">
                    <thead align="center">
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td align="center">{{ $row->kode_akun }}</td>
                                <td >{{ $row->nama_akun }}</td>
                                <td align='right'>@currency($row->debet)</td>
                                <td align='right'>@currency($row->kredit)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>





    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <!-- Logout Delete Confirmation-->
</x-app-layout>
