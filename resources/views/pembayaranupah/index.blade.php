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
            <h4 class="m-0 header-title">Data {{ $title }}</h4>

            <a href="{{ route('pembayaranupah.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Data</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="width:1500px;">
                    <thead align="center">
                        <tr>
                            <th>No Pembayaran Upah</th>
                            <th>Tanggal Pembayaran Upah</th>
                            <th>No Produksi</th>
                            <th>Kas Keluar</th>
                            <th>Keterangan</th>
                            <th>Nama Upah</th>
                            <th>Biaya Tenaga Kerja</th>
                            <th>Nominal Pembayaran</th>
                            <th>Nomor Jurnal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td align="center">{{ $row->no_pembayaran }}</td>
                                <td align="center">{{ $row->tgl_pembayaran }}</td>
                                <td align="center">{{ $row->no_produksi }}</td>
                                <td align="center">{{ $row->nama_kas }}</td>
                                <td>{{ $row->keterangan }}</td>
                                <td>{{ $row->nama_pekerja }}</td>
                                <td align="right">@currency($row->biaya_tenaga_kerja)</td>
                                <td align="right">@currency($row->nominal)</td>
                                <td>
                                    @if ($row->no_jurnal != '')
                                        <a class = "btn btn-primary btn-circle"
                                            href="{{ route('pembayaranupah.jurnaldetail', $row->id_jurnal_header) }}">{{ $row->no_jurnal }}
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('pembayaranupah.edit', $row->id_pembayaran_upah) }}"
                                        class="btn btn-success btn-circle">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a onclick="deleteConfirm(this); return false;" href="#"
                                        data-id="{{ $row->id_pembayaran_upah }}" class="btn btn-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    @if ($row->no_jurnal == '')
                                        <a onclick="jurnalConfirm(this); return false;" href="#"
                                            data-id="{{ $row->id_pembayaran_upah }}" class="btn btn-info btn-circle">
                                            <!-- <i class="fas fa-pen-square"></i> --><i class="fas fa-database"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script>
        function deleteConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('pembayaranupah/destroy/') }}";
            var url4 = url3.concat("/", id);
            tomboldelete.setAttribute("href", url4); //akan meload kontroller delete

            var pesan = "Data dengan ID <b>"
            var pesan2 = " </b>akan dihapus"
            var res = id;
            document.getElementById("xid").innerHTML = pesan.concat(res, pesan2);

            var myModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
                keyboard: false
            });

            myModal.show();

        }

        function jurnalConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('pembayaranupah/jurnal/') }}";
            var url4 = url3.concat("/", id);
            tomboldelete.setAttribute("href", url4); //akan meload kontroller delete

            var pesan = "Data dengan ID <b>"
            var pesan2 = " </b>akan dijurnal"
            var res = id;
            document.getElementById("xid").innerHTML = pesan.concat(res, pesan2);

            var myModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
                keyboard: false
            });

            myModal.show();
        }
    </script>
    <!-- Logout Delete Confirmation-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="xid"></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a id="btn-delete" class="btn btn-primary" href="#">OK</a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
