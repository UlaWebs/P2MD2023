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

    <div class="row">
        <!-- DataTales Example -->
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-start">
                        <h1 class="h3 mb-2 text-gray-800 text-primary">{{ $title }}</h1>
                    </div>
                    <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{ route('produksi') }}"> Kembali </a>
                    </div>
                </div>
                <hr>
                <div class="sub-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start mt-3">
                                <p class="m-t-10"><strong>No Produksi : </strong> {{ $no_produksi }}</p>
                                <p class="m-t-10"><strong>Tanggal Produksi : </strong> {{ $tgl_produksi }}</p>
                                <p class="m-t-10"><strong>Keterangan : </strong> {{ $keterangan }}</p>
                                <p class="m-t-10"><strong>Status : </strong> {{ $status }} </p>
                                <p class="m-t-10"><strong>Tanggal Selesai : </strong> {{ $tgl_selesai }} </p>
                            </div>
                            <div class="float-end mt-3">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">Sub Menu Detail Produksi</h4>

                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#output" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                            Detail Output
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#bbb" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            Detail BBB
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#btk" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            Detail BTK
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#bop" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            Detail BOP
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="output">
                        @if ($status != 'done')
                        <form action="{{ route('produksi.storedetailoutput') }}" method="post" class="mb-3">
                            @csrf
                            <input type="hidden" name="id_produksi_header" id="id_produksi_header"
                                value="{{ $id_produksi_header }}">
                            <div class="mb-3">
                                <label for="id_item" class="form-label">Item</label>
                                <select class="form-control form-control-solid" name="id_item" id="id_item">
                                    <option value="">-- Pilih Nama Item--</option>
                                    @foreach ($data_item_produk_jadi as $p)
                                    <option value="{{ $p->id_item }}">{{ $p->nama_item }}
                                        ({{ $p->jenis_item }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                                <input class="form-control form-control-solid" id="kuantitas" name="kuantitas"
                                    type="textbox" placeholder="Contoh: 2" value="{{ old('kuantitas') }}">
                            </div>

                            <!-- untuk tombol simpan -->
                            <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="btn btn-success waves-effect"
                                href="{{ url('/produksi/finishproduksi/' . $id_produksi_header) }}"
                                role="button">Selesai</a>

                        </form>
                        @endif

                        <table class="table table-bordered table-responsive dt-responsive nowrap"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Harga Satuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_output as $row)
                                <tr>
                                    <td>{{ $row->nama_item }}</td>
                                    <td align="right">{{ number_format($row->kuantitas, 2, ',', '.') }}</td>
                                    <td align="right">{{ $row->satuan }}</td>
                                    <td align="right">{{ number_format($row->harga_satuan, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($status != 'done')
                                        <a onclick="deleteConfirm(this); return false;" href="#"
                                            data-id="{{ $row->id_produksi_detail_output }}"
                                            class="btn btn-danger btn-circle">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- BBB FORM TABLE --}}
                    <div class="tab-pane" id="bbb">
                        @if ($status != 'done')
                        <form action="{{ route('produksi.storedetailbbb') }}" method="post" class="mb-3">
                            @csrf
                            <input type="hidden" name="id_produksi_header" id="id_produksi_header"
                                value="{{ $id_produksi_header }}">
                            <div class="mb-3">
                                <label for="id_item" class="form-label">Item</label>
                                <select class="form-control form-control-solid" name="id_item" id="id_item">
                                    <option value="">-- Pilih Nama Item--</option>
                                    @foreach ($data_item_bbb as $p)
                                    <option value="{{ $p->id_item }}">{{ $p->nama_item }}
                                        ({{ $p->jenis_item }})
                                        ( {{ $p->stok . ' ' . $p->satuan }}) </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                                <input class="form-control form-control-solid" id="kuantitas" name="kuantitas"
                                    type="textbox" placeholder="Contoh: 2" value="{{ old('kuantitas') }}">
                            </div>

                            <!-- untuk tombol simpan -->
                            <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="btn btn-secondary waves-effect" href="{{ url('/produksi') }}"
                                role="button">Batal</a>

                        </form>
                        @endif

                        <table class="table table-bordered table-responsive dt-responsive nowrap"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Harga Pengambilan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_bbb as $row)
                                <tr>
                                    <td>{{ $row->nama_item }}</td>
                                    <td align="right">{{ number_format($row->kuantitas, 2, ',', '.') }}</td>
                                    <td align="right">{{ $row->satuan }}</td>
                                    <td align="right">{{ number_format($row->harga_pengambilan, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($status != 'done')
                                        <a onclick="deleteConfirmBBB(this); return false;" href="#"
                                            data-id="{{ $row->id_produksi_detail_bhn_baku }}"
                                            class="btn btn-danger btn-circle">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- BTK FORM TABLE --}}
                    <div class="tab-pane" id="btk">
                        @if ($status != 'done')
                        <form action="{{ route('produksi.storedetailbtk') }}" method="post" class="mb-3">
                            @csrf
                            <input type="hidden" name="id_produksi_header" id="id_produksi_header"
                                value="{{ $id_produksi_header }}">
                            <div class="mb-3">
                                <label for="id_pekerja" class="form-label">Pekerja</label>
                                <select class="form-control form-control-solid" name="id_pekerja" id="id_pekerja">
                                    <option value="">-- Pilih Nama Pekerja--</option>
                                    @foreach ($data_pekerja as $p)
                                    <option value="{{ $p->id_pekerja }}">{{ $p->nama_pekerja }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="biaya_tenaga_kerja">Biaya Tenaga Kerja</label>
                                <input class="form-control form-control-solid" id="biaya_tenaga_kerja"
                                    name="biaya_tenaga_kerja" type="textbox" placeholder="Contoh: 2"
                                    value="{{ old('biaya_tenaga_kerja') }}">
                            </div>

                            <!-- untuk tombol simpan -->
                            <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="btn btn-success waves-effect" href="{{ url('/produksi') }}"
                                role="button">Selesai</a>

                        </form>
                        @endif

                        <table class="table table-bordered table-responsive dt-responsive nowrap"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>Nama Pekerja</th>
                                    <th>Biaya Tenaga Kerja</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_detail_pekerja as $row)
                                <tr>
                                    <td>{{ $row->nama_pekerja }}</td>
                                    <td align="right">{{ number_format($row->biaya_tenaga_kerja, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($status != 'done')
                                        <a onclick="deleteConfirmBTK(this); return false;" href="#"
                                            data-id="{{ $row->id_produksi_detail_tenaga_kerja }}"
                                            class="btn btn-danger btn-circle">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- BOP FORM TABEL --}}
                    <div class="tab-pane" id="bop">
                        @if ($status != 'done')
                        <form action="{{ route('produksi.storedetailbop') }}" method="post" class="mb-3">
                            @csrf
                            <input type="hidden" name="id_produksi_header" id="id_produksi_header"
                                value="{{ $id_produksi_header }}">
                            <div class="mb-3">
                                <label for="id_item" class="form-label">Item</label>
                                <select class="form-control form-control-solid" name="id_item" id="id_item">
                                    <option value="">-- Pilih Nama Item--</option>
                                    @foreach ($data_item_bop as $p)
                                    <option value="{{ $p->id_item }}">{{ $p->nama_item }}
                                        ({{ $p->jenis_item }})
                                        ( {{ $p->stok . ' ' . $p->satuan }}) </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                                <input class="form-control form-control-solid" id="kuantitas" name="kuantitas"
                                    type="textbox" placeholder="Contoh: 2" value="{{ old('kuantitas') }}">
                            </div>

                            <!-- untuk tombol simpan -->
                            <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="btn btn-secondary waves-effect" href="{{ url('/produksi') }}"
                                role="button">Batal</a>

                        </form>
                        @endif

                        <table class="table table-bordered table-responsive dt-responsive nowrap"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Harga Pengambilan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_bop as $row)
                                <tr>
                                    <td>{{ $row->nama_item }}</td>
                                    <td align="right">{{ number_format($row->kuantitas, 2, ',', '.') }}</td>
                                    <td align="right">{{ $row->satuan }}</td>
                                    <td align="right">{{ number_format($row->harga_pengambilan, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($status != 'done')
                                        <a onclick="deleteConfirmBOP(this); return false;" href="#"
                                            data-id="{{ $row->id_produksi_detail_bop }}"
                                            class="btn btn-danger btn-circle">
                                            <i class="fas fa-trash"></i>
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
        </div> <!-- end card-->
    </div>

    <script>
        function deleteConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('produksi/destroydetailoutput/') }}";
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

    <script>
        function deleteConfirmBBB(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('produksi/destroydetailbbb/') }}";
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

    <script>
        function deleteConfirmBOP(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('produksi/destroydetailbop/') }}";
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

    <script>
        function deleteConfirmBTK(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('produksi/destroydetailbtk/') }}";
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