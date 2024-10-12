<x-app-layout>
    <div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Ubah Data Supplier</h4>

                    <a href="#" class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Edit Data</span>
                    </a>

                </div>
                <!-- Card Body -->
                <div class="card-body">

                    <!-- Display Error jika ada error -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Akhir Display Error -->

                    <!-- Awal Dari Input Form -->
                    <form action="{{ route('supplier.update') }}" method="post">
                        @csrf
                        <input class="form-control form-control-solid" id="id_supplier" name="id_supplier" type="hidden" value="{{ $id_supplier }}">

                        <div class="mb-3"><label for="jumlahpenginapanlabel">Nama</label>
                        <input class="form-control form-control-solid" id="nama_supplier" name="nama_supplier" type="text" value="{{ $nama_supplier }}">
                        </div>

                        <div class="mb-3"><label for="alamat_supplier">Alamat Supplier</label>
                        <input class="form-control form-control-solid" id="alamat_supplier" name="alamat_supplier" type="text" value="{{ $alamat_supplier }}">
                        </div>

                        <div class="mb-3"><label for="no_telp_supplier">No Telepon Supplier</label>
                        <input class="form-control form-control-solid" id="no_telp_supplier" name="no_telp_supplier" type="text" value="{{ $no_telp_supplier }}">
                        </div>

                        <div class="mb-3"><label for="">Nama Akun Hutang</label>
                            <select class="form-control form-control-solid" id="id_akun_hutang" name="id_akun_hutang">
                                <option value="">-- Pilih Akun Hutang --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{$p->id_akun == $id_akun_hutang  ? 'selected' : ''}}>{{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label for="">Nama Akun Uang Muka Pembelian</label>
                            <select class="form-control form-control-solid" id="id_akun_uang_muka_pembelian" name="id_akun_uang_muka_pembelian">
                                <option value="">-- Pilih Akun Uang Muka Pembelian --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{$p->id_akun == $id_akun_uang_muka_pembelian  ? 'selected' : ''}}>{{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label for="">Jenis Supplier</label>
                            <select class="form-control form-control-solid" id="jenis_supplier" name="jenis_supplier">
                                <option value="">-- Pilih Jenis Supplier --</option>
                                @foreach ($supplier_type as $p)
                                    <option value="{{ $p->supplier_type }}" {{$p->supplier_type == $jenis_supplier  ? 'selected' : ''}}>{{ $p->supplier_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/supplier') }}" role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>


    </div>


    <!-- /.container-fluid -->
</div>
</x-app-layout>