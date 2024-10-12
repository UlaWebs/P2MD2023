<x-app-layout>

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

            <a href="{{ route('pembeliantunai.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Data</span>
            </a>
        </div>
        <div class="card-body">
            <div>
                {{ $no_penjualan_header }}
                {{ $tgl_penjualan }}
                {{ $keterangan }}
                {{ $nama_pelanggan }}
                {{ $nama_kas }}
            </div>

            <form action="{{ route('penjualantunai.storedetail') }}" method="post">
                @csrf
                <input type="hidden" name="id_penjualan_tunai_header" id="id_penjualan_tunai_header"
                    value="{{ $id_penjualan_tunai_header }}">
                <input type="hidden" name="no_penjualan_header" id="no_penjualan_header"
                    value="{{ $no_penjualan_header }}">
                <input type="hidden" name="tgl_penjualan" id="tgl_penjualan" value="{{ $tgl_penjualan }}">
                <div class="mb-3"><label for="id_item">Item</label>
                    <select class="form-control form-control-solid" name="id_item" id="id_item">
                        <option value="">-- Pilih Nama Item--</option>
                        @foreach ($data_item as $p)
                        <option value="{{ $p->id_item }}">{{ $p->nama_item }} ({{ $p->stok }}
                            {{ $p->satuan }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                    <input class="form-control form-control-solid" id="kuantitas" name="kuantitas" type="textbox"
                        placeholder="Contoh: 2" value="{{ old('kuantitas') }}">
                </div>

                <div class="mb-3"><label for="harga_jual">Harga Jual</label>
                    <input class="form-control form-control-solid" id="harga_jual" name="harga_jual" type="text"
                        placeholder="Contoh: 10 000" value="{{ old('satuan') }}">
                </div>

                <br>
                <!-- untuk tombol simpan -->

                <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                <!-- untuk tombol batal simpan -->
                <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/penjualantunai') }}" role="button">Batal</a>

            </form>
            <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th>Kuantitas</th>
                            <th>Harga Jual</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama Item</th>
                            <th>Kuantitas</th>
                            <th>Harga Jual</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($data as $row)
                        <tr>
                            <td>{{ $row->nama_item }}</td>
                            <td>{{ $row->kuantitas }}</td>
                            <td>{{ $row->harga_jual }}</td>
                            <td>{{ $row->harga_jual * $row->kuantitas }}</td>
                            <td>
                                <a onclick="deleteConfirm(this); return false;" href="#"
                                    data-id="{{ $row->id_penjualan_tunai_detail }}" class="btn btn-danger btn-circle">
                                    <i class="fas fa-trash"></i>
                                </a>
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

            var url3 = "{{ url('penjualantunai/destroydetail/') }}";
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