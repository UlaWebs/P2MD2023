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

            <a href="{{ route('produksi.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Data</span>
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-bordered dt-responsive nowrap" id="responsive-datatable"
                style="width:2800px;">
                <thead>
                    <tr>
                        <th>No Produksi</th>
                        <th>Tanggal Produksi</th>
                        <th>Nama Produk</th>
                        <th>Kuantitas</th>
                        <th>BBB</th>
                        <th>BTKL</th>
                        <th>Overhead</th>
                        <th>Biaya Produksi</th>
                        <th>Harga Satuan</th>
                        <th>Status</th>
                        <th>No Jurnal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $row->no_produksi }}</td>
                            <td>{{ $row->tgl_produksi }}</td>
                            <td>{{ $row->nama_item }}</td>
                            <td align="right">{{ number_format($row->kuantitas,2,',','.') }}</td>
                            <td align="right">{{ number_format($row->total_bbb,2,',','.') }}</td>
                            <td align="right">{{ number_format($row->total_btk,2,',','.') }}</td>
                            <td align="right">{{ number_format($row->total_bop,2,',','.') }}</td>
                            <td align="right">{{ number_format($row->biaya_produksi,2,',','.') }}</td>
                            <td align="right">{{ number_format($row->harga_satuan,2,',','.') }}</td>
                            <td>{{ $row->status }}</td>
                            <td>
                                @if ($row->no_jurnal != '')
                                    <a class = "btn btn-primary btn-circle"
                                        href="{{ route('produksi.jurnaldetail', $row->id_jurnal_header) }}">{{ $row->no_jurnal }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('produksi.edit', $row->id_produksi_header) }}"
                                    class="btn btn-success btn-circle">
                                    <i class="fas fa-check"></i>
                                </a>

                                <a href="{{ route('produksi.detail', $row->id_produksi_header) }}"
                                    class="btn btn-warning btn-circle">
                                    <i class="fas fa-list"></i>
                                </a>

                                <a onclick="deleteConfirm(this); return false;" href="#"
                                    data-id="{{ $row->id_produksi_header }}" class="btn btn-danger btn-circle">
                                    <i class="fas fa-trash"></i>
                                </a>
                                @if ($row->no_jurnal == '' and $row->status == 'done')
                                    <a onclick="jurnalConfirm(this); return false;" href="#"
                                        data-id="{{ $row->id_produksi_header }}" class="btn btn-info btn-circle">
                                        <i class="fas fa-pen-square"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>



    <script>
        function deleteConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('produksi/destroy/') }}";
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

            var url3 = "{{ url('produksi/jurnal/') }}";
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
