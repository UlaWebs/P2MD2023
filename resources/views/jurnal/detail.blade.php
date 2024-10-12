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

    <div class="row">
    <!-- DataTales Example -->
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <div class="float-start">
                        <h1 class="h3 mb-2 text-gray-800 text-primary">Data {{ $title }}</h1>
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
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('jurnal.storedetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_jurnal_header" id="id_jurnal_header"
                        value="{{ $id_jurnal_header }}">
                    <div class="mb-3">
                        <label for="id_akun" class="form-label">Akun</label>
                        <select class="form-control form-control-solid" name="id_akun" id="id_akun">
                            <option value="">-- Pilih Nama Akun--</option>
                            @foreach ($data_akun as $p)
                                <option value="{{ $p->id_akun }}">{{ $p->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label for="nominal">Nominal</label>
                        <input class="form-control form-control-solid" id="nominal" name="nominal" type="number"
                            placeholder="Contoh: 2" value="{{ old('nominal') }}">
                    </div>

                    <div class="mb-3"><label for="dk">Debet/Kredit</label>
                        <select class="form-control form-control-solid" name="dk" id="dk">
                                <option value="D">Debet</option>
                                <option value="K">Kredit</option>
                            </select>
                        </div>

                    <!-- untuk tombol simpan -->
                    <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="btn btn-secondary waves-effect" href="{{ url('/jurnal') }}" role="button">Batal</a>

                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered dt-responsive nowrap" id="responsive-datatable">
                    <thead>
                        <tr>
                            <th>Kode Akun</th>
                            <th>Nama Akun</th>
                            <th>Debet</th>
                            <th>Kredit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->kode_akun }}</td>
                                <td>{{ $row->nama_akun }}</td>
                                <td>{{ $row->debet }}</td>
                                <td>{{ $row->kredit }}</td>
                                <td>

                                    <a onclick="deleteConfirm(this); return false;" href="#"
                                        data-id="{{ $row->id_jurnal_detail }}"
                                        class="btn btn-danger btn-circle">
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
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>

    <script>
        function deleteConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('jurnal/destroydetail/') }}";
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
