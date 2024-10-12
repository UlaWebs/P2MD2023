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

    @if ($message = Session::get('errors'))
    <div class="alert alert-danger">
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
                    <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{ route('perolehanaset') }}"> Kembali
                        </a>
                    </div>
                </div>
                <hr>
                <div class="sub-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start mt-3">
                                <p class="m-t-10"><strong>No Perolehan Aset : </strong> {{ $no_perolehan_aset }}</p>
                                <p class="m-t-10"><strong>Tanggal Perolehan : </strong> {{ $tgl_perolehan_aset }}</p>
                                <p class="m-t-10"><strong>Keterangan : </strong> {{ $keterangan }}</p>
                            </div>
                            <div class="float-end mt-3">
                                <p class="m-t-10"><strong>Nama Vendor : </strong> {{ $nama_vendor }} </p>
                                <p class="m-t-10"><strong>Nama Kas : </strong> {{ $nama_kas }} </p>
                                <p class="m-t-10"><strong>Nominal Uang Kas : </strong> @currency($nominal_kas)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('perolehanaset.storedetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_perolehan_aset_header" id="id_perolehan_aset_header"
                        value="{{ $id_perolehan_aset_header }}">
                    <div class="mb-3">
                        <label for="id_aset" class="form-label">Nama Aset</label>
                        <select class="form-control form-control-solid" name="id_aset" id="id_aset">
                            <option value="">-- Pilih Nama Aset--</option>
                            @foreach ($data_item as $p)
                            <option value="{{ $p->id_aset }}">{{ $p->nama_aset }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                        <input class="form-control form-control-solid" id="kuantitas" name="kuantitas" type="textbox"
                            placeholder="Contoh: 2" value="{{ old('kuantitas') }}">
                    </div>

                    <div class="mb-3"><label for="harga">Harga Perolehan</label>
                        <input class="form-control form-control-solid" id="harga" name="harga" type="number"
                            placeholder="Contoh: 200.000" value="{{ old('harga') }}">
                    </div>

                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <select class="form-control form-control-solid" name="satuan" id="satuan">
                            <option value="">-- Pilih Satuan--</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Buah">Buah</option>
                            <option value="Kg">Kg</option>
                        </select>
                    </div>

                    <div class="mb-3"><label for="nilai_residu">Perkiraan Residu</label>
                        <input class="form-control form-control-solid" id="nilai_residu" name="nilai_residu" type="text"
                            placeholder="Contoh: 10.000" value="{{ old('nilai_residu') }}">
                    </div>
                    <!-- untuk tombol simpan -->
                    <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="btn btn-secondary waves-effect" href="{{ url('/perolehanaset') }}" role="button">Batal</a>

                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered dt-responsive nowrap" id="responsive-datatable">
                    <thead>
                        <tr align="center">
                            <th>Nama Aset</th>
                            <th>Kuantitas Aset</th>
                            <th>Harga Perolehan</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total = 0;
                        @endphp
                        @foreach ($data as $row)
                        <tr>
                            <td>{{ $row->nama_aset }}</td>
                            <td align="center">{{ $row->kuantitas }}</td>
                            <td align="right">@currency($row->harga_perolehan)</td>
                            <td align="right"> @currency($row->harga_perolehan * $row->kuantitas)</td>
                            <td>
                                <a onclick="deleteConfirm(this); return false;" href="#"
                                    data-id="{{ $row->id_perolehan_aset_detail }}" class="btn btn-danger btn-circle">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php
                        $total = $total + $row->harga_perolehan * $row->kuantitas;
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total Perolehan</td>
                            <td align="right">@currency($total)</td>
                            <td></td>
                        </tr>
                    </tfoot>
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
        $("#id_item").change(function() {
            var id_item = $(this).val();
            var sel_satuan = $("#id_item_satuan");
            var url = '{{ route('item.getsatuan', ':id') }}';
            url = url.replace(':id', id_item)
            $.ajax({
                url: url,
                type: "get",
                dataType: "json",
                success: function(result) {
                    sel_satuan.empty();
                    $.each(result, function(key, value) {
                        sel_satuan.append('<option value = "' + value.id_item_satuan + '">' +
                            value.satuan + '</option>');
                    });
                }
            });
        });
    </script>

    <script>
        function deleteConfirm(e) {
            var tomboldelete = document.getElementById('btn-delete')
            id = e.getAttribute('data-id');

            var url3 = "{{ url('perolehanaset/destroydetail/') }}";
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