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

            <a href="{{ route('penerimaan.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Data</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="width: 1500px" cellspacing="0">
                <b style="color: black;">Keterangan Warna: </b><br>
                <div style="width: 30px; height: 20px; background-color: lightyellow; border: 1px solid black;"></div>
                <b>Pesanan belum dilunasi</b></br>
                <div style="width: 30px; height: 20px; background-color: lightgreen; border: 1px solid black;"></div>
                <b>Pesanan dilunasi sebagian</b></br>
                <div style="width: 30px; height: 20px; background-color: white; border: 1px solid black;"></div>
                <b>Pesanan dilunasi seluruhnya</b></br><br>
                    <thead>
                        <tr>
                            <th>No Penerimaan</th>
                            <th>Tanggal Penerimaan</th>
                            <th>No Pemesanan Pembelian</th>
                            <th>No Faktur Pembelian</th>
                            <th>Keterangan</th>
                            <th>Total Penerimaan</th>
                            <th>Total Pemesanan</th>
                            <th>Total Pembayaran</th>
                            <th>Saldo Utang</th>
                            <th>Status</th>
                            <th>Nomor Jurnal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            @php
                                if ($row->subtotal_penerimaan - $row->nominal_uang_muka - $row->total_pembayaran > 0) {
                                    $warna = 'lightyellow';
                                } else {
                                    $warna = 'white';
                                }
                            @endphp
                            <tr bgcolor="{{ $warna }}">
                                <td>{{ $row->no_penerimaan }}</td>
                                <td>{{ $row->tgl_penerimaan }}</td>
                                <td>{{ $row->no_pemesanan }}</td>
                                <td>{{ $row->no_faktur_pembelian }}</td>
                                <td>{{ $row->keterangan }}</td>
                                <td align="right">@currency($row->subtotal_penerimaan)</td>
                                <td align="right">@currency($row->subtotal_pemesanan)</td>
                                <td>@currency($row->total_pembayaran + $row->nominal_uang_muka)</td>
                                <td>@currency($row->subtotal_penerimaan - $row->nominal_uang_muka - $row->total_pembayaran)</td>
                                <td>
                                    @if ($row->subtotal_penerimaan - $row->nominal_uang_muka - $row->total_pembayaran > 0)
                                        Belum Lunas
                                    @else
                                        Lunas
                                    @endif
                                </td>
                                <td>
                                    @if ($row->no_jurnal != '')
                                        <a class = "btn btn-primary btn-circle"
                                            href="{{ route('penerimaan.jurnaldetail', $row->id_jurnal_header) }}">{{ $row->no_jurnal }}
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('penerimaan.edit', $row->id_penerimaan_header) }}"
                                        class="btn btn-success btn-circle">
                                        <i class="fas fa-check"></i>
                                    </a>

                                    <a href="{{ route('penerimaan.detail', $row->id_penerimaan_header) }}"
                                        class="btn btn-warning btn-circle">
                                        <i class="fas fa-list"></i>
                                    </a>

                                    <a onclick="deleteConfirm(this); return false;" href="#"
                                        data-id="{{ $row->id_penerimaan_header }}" class="btn btn-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    @if ($row->no_jurnal == '')
                                        <a onclick="jurnalConfirm(this); return false;" href="#"
                                            data-id="{{ $row->id_penerimaan_header }}" class="btn btn-info btn-circle">
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
    </div>


    <script>
        function deleteConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('penerimaan/destroy/') }}";
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

            var url3 = "{{ url('penerimaan/jurnal/') }}";
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
                    <a id="btn-delete" class="btn btn-danger" href="#">OK</a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
