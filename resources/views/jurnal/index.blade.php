<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h3 text-gray-800">{{ $title }}</h1>
            {{-- <a href="{{ route('jurnal.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Data</span>
            </a> --}}
        </div>

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
            <div class="card-header py-3 text-center">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>
                <p class="m-0 font-weight-bold text-secondary">PT. Wonderfull Rotan</p>
                <p class="m-0 text-secondary">
                    {{ \Carbon\Carbon::parse($minDate)->format('d M Y') }} - {{
                    \Carbon\Carbon::parse($maxDate)->format('d M Y') }}
                </p>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="10%">Tanggal Jurnal</th>
                                <th width="20%">Akun</th>
                                <th width="2%">Ref</th>
                                <th width="10%">Debit</th>
                                <th width="10%">Kredit</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Tanggal Jurnal</th>
                                <th>Akun</th>
                                <th>Ref</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($dataheader as $row)
                            @php
                            $first = true;
                            @endphp
                            @foreach ($datadetail as $row2)
                            @if ($row->id_jurnal_header == $row2->id_jurnal_header)
                            <tr>
                                @if ($first)
                                <td
                                    rowspan="{{ $datadetail->where('id_jurnal_header', $row->id_jurnal_header)->count() }}">
                                    {{ $row->tgl_jurnal }}
                                </td>
                                @php
                                $first = false;
                                @endphp
                                @endif
                                <td> {{ $row2->nama_akun }}</td>
                                <td>{{ $row2->kode_akun }}</td>
                                <td align="right">
                                    @if ($row2->debet > 0)
                                    {{ number_format($row2->debet, 2, ',', '.') }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td align="right">
                                    @if ($row2->kredit > 0)
                                    {{ number_format($row2->kredit, 2, ',', '.') }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
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

            var url3 = "{{ url('jurnal/destroy/') }}";
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
                    <a id="btn-delete" class="btn btn-danger" href="#">Hapus</a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>