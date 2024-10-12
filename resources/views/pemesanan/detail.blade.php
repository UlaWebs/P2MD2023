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
                    <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{ route('pemesanan') }}"> Kembali </a>
                    </div>
                </div>
                <hr>
                <div class="sub-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start mt-3">
                                <p class="m-t-10"><strong>No Pemesanan : </strong> {{ $no_pemesanan }}</p>
                                <p class="m-t-10"><strong>Tanggal Pemesanan : </strong> {{ $tgl_pemesanan }}</p>
                                <p class="m-t-10"><strong>Keterangan : </strong> {{ $keterangan }}</p>
                            </div>
                            <div class="float-end mt-3">
                                <p class="m-t-10"><strong>Nama Supplier : </strong> {{ $nama_supplier }} </p>
                                <p class="m-t-10"><strong>Status : </strong> {{ $status }} </p>
                                <p class="m-t-10"><strong>Nama Kas : </strong> {{ $nama_kas }} </p>
                                <p class="m-t-10"><strong>Nominal Uang Muka : </strong> @currency($nominal_uang_muka)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('pemesanan.storedetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_pemesanan_header" id="id_pemesanan_header"
                        value="{{ $id_pemesanan_header }}">
                    <div class="mb-3">
                        <label for="satuan" class="form-label">Nama Barang</label>
                        <select class="form-control form-control-solid" name="id_item" id="id_item">
                            <option value="">-- Pilih Nama Barang--</option>
                            @foreach ($data_item as $p)
                                <option value="{{ $p->id_item }}">{{ $p->jenis_item }} - {{ $p->nama_item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label for="kuantitas">Kuantitas Barang</label>
                        <input class="form-control form-control-solid" id="kuantitas" name="kuantitas" type="textbox"
                            placeholder="Contoh: 2" value="{{ old('kuantitas') }}">
                    </div>

                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <select class="form-control form-control-solid" name="id_item_satuan" id="id_item_satuan">
                            <!-- json -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ppn" class="form-label">Apakah Termasuk PPN?</label>
                        <select class="form-control form-control-solid" name="ppn" id="ppn">
                            <option value="t">Ya</option>
                            <option value="f">Tidak</option>
                        </select>
                    </div>

                    <div class="mb-3"><label for="harga_satuan">Harga Satuan</label>
                        <input class="form-control form-control-solid" id="harga_satuan" name="harga_satuan"
                            type="text" placeholder="Contoh: 20 000" value="{{ old('satuan') }}">
                    </div>
                    <!-- untuk tombol simpan -->
                    <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="btn btn-secondary waves-effect" href="{{ url('/pemesanan') }}" role="button">Batal</a>

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
                            <th>Nama Barang</th>
                            <th>Kuantitas Barang</th>
                            <th>Satuan</th>
                            <th>Harga Dasar</th>
                            <th>PPN</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0;

                        @endphp
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row->nama_item }}</td>
                                <td align="center">{{ $row->kuantitas }}</td>
                                <td align="center">{{ $row->satuan }}</td>
                                <td align="right"> @currency($row->base_price) </td>
                                <td align="right"> @currency($row->ppn) </td>
                                <td align="right"> @currency($row->base_price + $row->ppn)</td>
                                <td align="right"> @currency(($row->base_price + $row->ppn) * $row->kuantitas)</td>
                                <td>
                                    <a href="{{ route('pemesanan.editdetail', $row->id_pemesanan_detail) }}"
                                        class="btn btn-success btn-circle">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a onclick="deleteConfirm(this); return false;" href="#"
                                        data-id="{{ $row->id_pemesanan_detail }}" class="btn btn-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @php 
                            $total = $total + ($row->base_price + $row->ppn) * $row->kuantitas;
                            @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">Total Pemesanan</td>
                            <td align="right">@currency($total)</td>
                            <td></td>
                        </tr>
                        <tr>
                            <form action="{{ route('pemesanan.updateuangmuka') }}" method="post">
                                @csrf
                                <td colspan="6">Uang Muka</td>
                                <td align="right">
                                    <input type="hidden" name="id2" id="id2"
                                        value="{{ $id_pemesanan_header }}">
                                    <input type="text" name="nominal_uang_muka" id="nominal_uang_muka"
                                        value="{{ $nominal_uang_muka }}">
                                    <input type="submit" value="simpan"
                                        class="btn btn-primary waves-effect waves-light btn-xs">
                                </td>
                                <td></td>
                            </form>
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

            var url3 = "{{ url('pemesanan/destroydetail/') }}";
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
