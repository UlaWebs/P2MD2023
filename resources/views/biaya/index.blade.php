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
                            <h4 class="m-0 header-title text-primary">Data {{ $title }}</h4>

                            <a href="{{ route('biaya.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Tambah Data</span>
                            </a>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Biaya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Nama Biaya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach ($data as $row)
                                        <tr>
                                            <td>{{ $row->nama_biaya }}</td>
                                            <td>
                                                <a href="{{ route('biaya.edit', $row->id_biaya) }}" class="btn btn-success btn-circle">
                                                    <i class="fas fa-check"></i>
                                                </a>

                                                <a onclick="deleteConfirm(this); return false;" href="#" data-id="{{ $row->id_biaya }}" class="btn btn-danger btn-circle">
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
                    function deleteConfirm(e){
                        var tomboldelete = document.getElementById('btn-delete')
                        id = e.getAttribute('data-id');

                        var url3 = "{{url('biaya/destroy/')}}";
                        var url4 = url3.concat("/",id);
                        tomboldelete.setAttribute("href", url4); //akan meload kontroller delete

                        var pesan = "Data dengan ID <b>"
                        var pesan2 = " </b>akan dihapus"
                        var res = id;
                        document.getElementById("xid").innerHTML = pesan.concat(res,pesan2);

                        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'), {  keyboard: false });

                        myModal.show();

                    }
                </script>
                <!-- Logout Delete Confirmation-->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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