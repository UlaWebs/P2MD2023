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

            <a href="{{ route('pemesanan.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Data</span>
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered dt-responsive nowrap" id="responsive-datatable" style="width:2800px;">
                <b style="color: black;">Keterangan Warna: </b><br>
                <div style="width: 30px; height: 20px; background-color: lightyellow; border: 1px solid black;"></div>
                <b>Pesanan belum diterima</b></br>
                <div style="width: 30px; height: 20px; background-color: lightgreen; border: 1px solid black;"></div>
                <b>Pesanan diterima sebagian</b></br>
                <div style="width: 30px; height: 20px; background-color: white; border: 1px solid black;"></div>
                <b>Pesanan diterima seluruhnya</b></br><br>
                <thead>
                    <tr align="center">
                        <th>No Pemesanan Pembelian</th>
                        <th>Tanggal Pembelian</th>
                        <th>Keterangan</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Uang Muka</th>
                        <th>Total Pemesanan</th>
                        <th>Total Penerimaan</th>
                        <th>Nomor Jurnal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr align="center">
                        <th>No Pemesanan Pembelian</th>
                        <th>Tanggal Pembelian</th>
                        <th>Keterangan</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Tanggal Jatuh Tempo</th>
                        <th>Uang Muka</th>
                        <th>Total Pemesanan</th>
                        <th>Total Penerimaan</th>
                        <th>Nomor Jurnal</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($data as $row)
                        @php
                            if ($row->status_penerimaan == 'Belum Diterima') {
                                $warna = 'lightyellow';
                            } elseif ($row->status_penerimaan == 'Diterima Sebagian') {
                                $warna = 'lightgreen';
                            } else {
                                $warna = 'white';
                            }
                        @endphp
                        <tr bgcolor="{{ $warna }}">
                            <td align="center">{{ $row->no_pemesanan }}</td>
                            <td align="center">{{ $row->tgl_pemesanan }}</td>
                            <td>{{ $row->keterangan }}</td>
                            <td>{{ $row->nama_supplier }}</td>
                            <td align="center">{{ $row->status_penerimaan }}</td>
                            <td align="center">{{ $row->tgl_jatuh_tempo }}</td>
                            <td align="right"> @currency($row->nominal_uang_muka)</td>
                            <td align="right"> @currency($row->subtotal_pemesanan)</td>
                            <td align="right"> @currency($row->subtotal_penerimaan)</td>
                            <td>
                                @if ($row->no_jurnal != '')
                                    <a class = "btn btn-primary btn-circle"
                                        href="{{ route('pemesanan.jurnaldetail', $row->id_jurnal_header) }}">{{ $row->no_jurnal }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="icon-container">
                                    @if ($row->no_jurnal == '')
                                        <a href="{{ route('pemesanan.edit', $row->id_pemesanan_header) }}"
                                            class="btn btn-success btn-circle">
                                            <i class="fas fa-check"></i>
                                        </a>

                                        <a href="{{ route('pemesanan.detail', $row->id_pemesanan_header) }}"
                                            class="btn btn-warning btn-circle">
                                            <i class="fas fa-list"></i>
                                        </a>

                                        <a onclick="deleteConfirm(this); return false;" href="#"
                                            data-id="{{ $row->id_pemesanan_header }}"
                                            class="btn btn-danger btn-circle">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a onclick="jurnalConfirm(this); return false;" href="#"
                                            data-id="{{ $row->id_pemesanan_header }}" class="btn btn-info btn-circle">
                                            <i class="fas fa-pen-square"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('pemesanan.excel', $row->id_pemesanan_header) }}"
                                        class="btn btn-primary btn-circle">
                                        <i class="far fa-file-excel"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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

            var url3 = "{{ url('pemesanan/destroy/') }}";
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

            var url3 = "{{ url('pemesanan/jurnal/') }}";
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
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body" id="xid"></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a id="btn-delete" class="btn btn-danger" href="#">OK</a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
