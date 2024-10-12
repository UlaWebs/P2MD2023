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
                @if ($btnJurnal == 'T')
                <a onclick="deleteConfirm(this); return false;" href="#" data-id="{{ $tgl_depresiasi_aset }}"
                    class="btn btn-primary btn-circle">
                    Jurnal
                </a>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No Depresiasi</th>
                                <th>Tanggal Depresiasi</th>
                                <th>Nama Aset</th>
                                <th>Kode Inventaris</th>
                                <th>Nominal Depresiasi</th>
                                <th>Jurnal</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No Depresiasi</th>
                                <th>Tanggal Depresiasi</th>
                                <th>Nama Aset</th>
                                <th>Kode Inventaris</th>
                                <th>Nominal Depresiasi</th>
                                <th>Jurnal</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->no_depresiasi_aset }}</td>
                                <td>{{ $row->tgl_depresiasi_aset }}</td>
                                <td>{{ $row->nama_aset }}</td>
                                <td>{{ $row->kode_inventaris }}</td>
                                <td align="right">@currency($row->nominal_depresiasi)</td>
                                <td>
                                    @if ($row->no_jurnal != '')
                                    <a class="btn btn-primary btn-circle"
                                        href="{{ route('depresiasiaset.jurnaldetail', $row->id_jurnal_header) }}">{{
                                        $row->no_jurnal }}
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

    </div>


    <!-- Page level plugins -->
    <script src="{{ asset('sbadmin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>

    <script>
        function deleteConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('depresiasiaset/jurnal/') }}";
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
                    <a id="btn-delete" class="btn btn-primary" href="#">Ok</a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>