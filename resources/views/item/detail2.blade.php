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

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Terjadi kesalahan pada saat input data. <br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    @endif

    <div class="row">
        <!-- DataTales Example -->
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-start">
                        <h1 class="h3 mb-2 text-gray-800 text-primary">{{ $title }}</h1>
                    </div>
                </div>
                <hr>
                <div class="sub-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start mt-3">
                                <p class="m-t-10"><strong>Nama Item : </strong> {{ $nama_item }}</p>
                                <p class="m-t-10"><strong>Satuan : </strong> {{ $satuan }}</p>
                                <p class="m-t-10"><strong>Jenis Item : </strong> {{ $jenis_item }}</p>
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
                <h4 class="header-title mb-4">Sub Menu BOM</h4>

                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="#bbb" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
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
                    {{-- BBB FORM TABLE --}}
                    <div class="tab-pane active" id="bbb">
                        <form action="{{ route('item.storedetail21') }}" method="post" class="mb-3">
                            @csrf
                            <input type="hidden" name="id_item" id="id_item" value="{{ $id_item }}">
                            <div class="mb-3">
                                <label for="id_item_bbb" class="form-label">Item</label>
                                <select class="form-control form-control-solid" name="id_item_bbb" id="id_item_bbb">
                                    <option value="">-- Pilih Nama Item--</option>
                                    @foreach ($data_item_bbb as $p)
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
                            <a class="btn btn-secondary waves-effect" href="{{ url('/item') }}"
                                role="button">Batal</a>

                        </form>

                        <table class="table table-bordered table-responsive dt-responsive nowrap"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_bbb as $row)
                                    <tr>
                                        <td>{{ $row->nama_item }}</td>
                                        <td>{{ $row->kuantitas }}</td>
                                        <td>{{ $row->satuan }}</td>
                                        <td>
                                            <a onclick="deleteConfirmBBB(this); return false;" href="#"
                                                data-id="{{ $row->id_bom_bbb }}" class="btn btn-danger btn-circle">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- BTK FORM TABLE --}}
                    <div class="tab-pane" id="btk">
                        <form action="{{ route('item.storedetail22') }}" method="post" class="mb-3">
                            @csrf
                            <input type="hidden" name="id_item" id="id_item" value="{{ $id_item }}">
                            <div class="mb-3">
                                <label for="id_pekerja" class="form-label">Jenis Upah</label>
                                <select class="form-control form-control-solid" name="id_pekerja" id="id_pekerja">
                                    <option value="">-- Pilih Nama Jenis Upah--</option>
                                    @foreach ($data_item_btk as $p)
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
                            <a class="btn btn-secondary waves-effect" href="{{ url('/item') }}"
                                role="button">Batal</a>

                        </form>

                        <table class="table table-bordered table-responsive dt-responsive nowrap"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>Nama Jenis Upah</th>
                                    <th>Biaya Tenaga Kerja</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_btk as $row)
                                    <tr>
                                        <td>{{ $row->nama_pekerja }}</td>
                                        <td>{{ $row->nominal }}</td>
                                        <td>
                                            <a onclick="deleteConfirmBTK(this); return false;" href="#"
                                                data-id="{{ $row->id_bom_btk }}" class="btn btn-danger btn-circle">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- BOP FORM TABEL --}}
                    <div class="tab-pane" id="bop">
                        <form action="{{ route('item.storedetail23') }}" method="post" class="mb-3">
                            @csrf
                            <input type="hidden" name="id_item" id="id_item" value="{{ $id_item }}">
                            <div class="mb-3">
                                <label for="id_item_bop" class="form-label">Item</label>
                                <select class="form-control form-control-solid" name="id_item_bop" id="id_item_bop">
                                    <option value="">-- Pilih Nama Item--</option>
                                    @foreach ($data_item_bop as $p)
                                        <option value="{{ $p->id_item }}">
                                            {{ $p->nama_item }}
                                            ({{ $p->jenis_item }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                                <input class="form-control form-control-solid" id="kuantitas" name="kuantitas"
                                    type="textbox" placeholder="Contoh: 2" value="{{ old('kuantitas') }}">
                            </div>

                            <div class="mb-3"><label for="target_output">Jumlah Target</label>
                                <input class="form-control form-control-solid" id="target_output"
                                    name="target_output" type="textbox" placeholder="Contoh: 2"
                                    value="{{ old('target_output') }}">
                            </div>

                            <!-- untuk tombol simpan -->
                            <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="btn btn-secondary waves-effect" href="{{ url('/item') }}"
                                role="button">Batal</a>

                        </form>

                        <table class="table table-bordered table-responsive dt-responsive nowrap"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th>Nama Item</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Target Output</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_bop as $row)
                                    <tr>
                                        <td>{{ $row->nama_item }}</td>
                                        <td>{{ $row->kuantitas }}</td>
                                        <td>{{ $row->satuan }}</td>
                                        <td>{{ $row->target_output }}</td>
                                        <td>
                                            <a onclick="deleteConfirmBOP(this); return false;" href="#"
                                                data-id="{{ $row->id_bom_bop }}" class="btn btn-danger btn-circle">
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
        </div> <!-- end card-->
    </div>


    <script>
        function deleteConfirmBBB(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('item/destroydetail21/') }}";
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

            var url3 = "{{ url('item/destroydetail23/') }}";
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

            var url3 = "{{ url('item/destroydetail22/') }}";
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
