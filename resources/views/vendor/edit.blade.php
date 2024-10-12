<x-app-layout>
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">{{ isset($vendor) ? 'Edit Data Vendor' : 'Tambah
                            Data Vendor' }}</h4>
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
                        <form action="{{ route('vendor.update') }}" method="post">
                            @csrf
                            <input class="form-control form-control-solid" id="id_vendor" name="id_vendor" type="hidden"
                                value="{{ $id_vendor }}">
                            <div class="mb-3">
                                <label for="nama_vendor">Nama Vendor</label>
                                <input class="form-control form-control-solid" id="nama_vendor" name="nama_vendor"
                                    type="text" placeholder="Contoh: Fulan" value="{{ $nama_vendor }}">
                            </div>

                            <div class="mb-3">
                                <label for="alamat_vendor">Alamat Vendor</label>
                                <input class="form-control form-control-solid" id="alamat_vendor" name="alamat_vendor"
                                    type="text" placeholder="Contoh: Jl. Raya Bandung" value="{{ $alamat_vendor }}">
                            </div>

                            <div class="mb-3">
                                <label for="no_telp_vendor">No Telepon Vendor</label>
                                <input class="form-control form-control-solid" id="no_telp_vendor" name="no_telp_vendor"
                                    type="text" placeholder="Contoh: 081222312222" value="{{ $no_telp_vendor }}">
                            </div>

                            <div class="mb-3">
                                <label for="id_akun_hutang">Nama Akun Hutang</label>
                                <select class="form-control form-control-solid" name="id_akun_hutang"
                                    id="id_akun_hutang">
                                    <option value="">-- Pilih Nama Akun Hutang --</option>
                                    @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{ $p->id_akun ==
                                        $id_akun_hutang ? 'selected' : '' }}>
                                        {{ $p->nama_akun }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->
                            <input class="col-sm-1 btn btn-success btn-sm" type="submit">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark btn-sm" href="{{ url('/vendor') }}" role="button">Batal</a>

                        </form>
                        <!-- Akhir Dari Input Form -->
                    </div>
                </div>
            </div>
        </div>

        <!-- /.container-fluid -->
    </div>
</x-app-layout>