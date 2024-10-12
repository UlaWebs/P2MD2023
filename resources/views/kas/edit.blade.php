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
                    <h4 class="m-0 font-weight-bold text-primary">Ubah Data Kas</h4>

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
                    <form action="{{ route('kas.update') }}" method="post">
                        @csrf
                        <input class="form-control form-control-solid" id="id_kas" name="id_kas" type="hidden" value="{{ $id_kas }}">

                        <div class="mb-3"><label for="jumlahpenginapanlabel">Nama</label>
                        <input class="form-control form-control-solid" id="nama_kas" name="nama_kas" type="text" value="{{ $nama_kas }}">
                        </div>

                        <div class="mb-3"><label for="">Nama Akun</label>
                            <select class="form-control form-control-solid" id="id_akun_kas" name="id_akun_kas">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{$p->id_akun == $id_akun_kas  ? 'selected' : ''}}>{{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/kas') }}" role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>


    </div>


    <!-- /.container-fluid -->
</div>
</x-app-layout>