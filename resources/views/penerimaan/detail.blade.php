<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

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

                <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{route('penerimaan')}}"> Kembali </a>
                </div>
            </div>
            <div class="card-body">
                <div class="sub-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="float-start mt-3">
                                <p class="m-t-10"><strong>No Penerimaan : </strong> {{ $no_penerimaan }}</p>
                                <p class="m-t-10"><strong>Tanggal Penerimaan : </strong> {{ $tgl_penerimaan }}</p>
                                <p class="m-t-10"><strong>Keterangan : </strong> {{ $keterangan }}</p>
                            </div>
                            <div class="float-end mt-3">
                                <p class="m-t-10"><strong>No Pemesanan : </strong> {{ $no_pemesanan }} </p>   
                                <p class="m-t-10"><strong>Nama Supplier : </strong> {{ $nama_supplier }} </p>
                                <p class="m-t-10"><strong>Status Pemesanan : </strong> {{ $status_pemesanan }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($status_pemesanan == 'Pending')
                <div>
                        <a class="btn btn-success waves-effect waves-light" href="{{route('pemesanan.updatestatus',[$id_pemesanan_header,$id_penerimaan_header])}}">Selesaikan Pemesanan</a>
                </div>
                @endif
                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead align="center">
                            <tr>
                                <th>Nama Item</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                                <th>Harga Dasar</th>
                                <th>PPN</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tfoot align="center">
                            <tr>
                                <th>Nama Item</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                                <th>Harga Dasar</th>
                                <th>PPN</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($data_detail_po as $row)
                                <tr>
                                    <td>{{ $row->nama_item }}</td>
                                    <td align="center">{{ $row->kuantitas }}</td>
                                    <td align="center">{{ $row->satuan }}</td>
                                    <td align="right">@currency($row->base_price)</td>
                                    <td align="right">@currency($row->ppn)</td>
                                    <td align="right">@currency($row->base_price + $row->ppn)</td>
                                    <td align="right">@currency(($row->base_price + $row->ppn) * $row->kuantitas)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <form action="{{ route('penerimaan.storedetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_penerimaan_header" id="id_penerimaan_header" value="{{ $id_penerimaan_header }}">
                    <input type="hidden" name="no_penerimaan" id="no_penerimaan" value="{{ $no_penerimaan }}">
                    <input type="hidden" name="id_pemesanan_header" id="id_pemesanan_header" value="{{ $id_pemesanan_header }}">
                    <div class="mb-3"><label for="id_item">Item</label>
                        <select class="form-control form-control-solid" name="id_item" id="id_item">
                            <option value="">-- Pilih Nama Item--</option>
                            @foreach ($data_detail_po as $p)
                                <option value="{{ $p->id_item }}">{{ $p->nama_item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                        <input class="form-control form-control-solid" id="kuantitas" name="kuantitas" type="textbox"
                            placeholder="Contoh: 10" value="{{ old('kuantitas') }}">
                    </div>

                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <select class="form-control form-control-solid" name="id_item_satuan" id="id_item_satuan">
                            <!-- json -->
                        </select>
                    </div>

                    <div class="mb-3"><label for="harga_satuan">Harga Satuan</label>
                        <input class="form-control form-control-solid" id="harga_satuan" name="harga_satuan" readonly="readonly"
                            type="text" placeholder="Contoh: 10 000" value="{{ old('harga_satuan') }}">
                    </div>

                    <div class="mb-3">
                        <input class="form-control form-control-solid" id="tgl_penerimaan" name="tgl_penerimaan" type="hidden" placeholder="Contoh: Fulanah" value="{{ $tgl_penerimaan }}">
                    </div>
                    <br>
                    <!-- untuk tombol simpan -->

                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/penerimaan') }}" role="button">Batal</a>

                </form>
                <div class="table-responsive">

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead align="center">
                            <tr>
                                <th>Nama Item</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                                <th>Harga Dasar</th>
                                <th>PPN</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tfoot align="center">
                            <tr>
                                <th>Nama Item</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                                <th>Harga Dasar</th>
                                <th>PPN</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>
                                    <td>{{ $row->nama_item }}</td>
                                    <td align="center">{{ $row->kuantitas }}</td>
                                    <td align="center">{{ $row->satuan }}</td>
                                    <td align="right">@currency($row->base_price)</td>
                                    <td align="right">@currency($row->ppn)</td>
                                    <td align="right">@currency($row->base_price + $row->ppn)</td>
                                    <td align="right">@currency(($row->base_price + $row->ppn) * $row->kuantitas)</td>
                                    <td>
                                        <a onclick="deleteConfirm(this); return false;" href="#"
                                            data-id="{{ $row->id_penerimaan_detail }}"
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

            var url3 = "{{ url('penerimaan/destroydetail/') }}";
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
    <script>
        $("#id_item").change(function(){
       var id_item = $(this).val();
       var id_pemesanan_header = $("#id_pemesanan_header").val();
       var sel_satuan=$("#id_item_satuan");
       var harga_satuan = $("#harga_satuan");
       var url = '{{route("pemesanan.getdetailbyitem",[":id1",":id2"])}}';
       url=url.replace(':id1',id_pemesanan_header);
       url=url.replace(':id2',id_item);
       $.ajax({
       url:url,
       type:"get",
       dataType:"json",
       success:function(result){
        sel_satuan.empty();
        harga_satuan.val('');
        $.each(result,function(key,value){
        sel_satuan.append('<option value = "'+value.id_item_satuan+'">'+value.satuan+'</option>');
        var base_price=parseFloat(value.base_price);
        var ppn=parseFloat(value.ppn);
        harga_satuan.val(base_price+ppn);
        });
       }
       });
        });
    </script>
</x-app-layout>
