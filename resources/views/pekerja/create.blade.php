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
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Data Upah</h6>

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
                        <form action="{{ route('pekerja.store') }}" method="post">
                            @csrf
                            <div class="mb-3"><label for="nama_pekerja">Nama Jenis Upah</label>
                                <input class="form-control form-control-solid" id="nama_pekerja" name="nama_pekerja"
                                    type="text" placeholder="Contoh: Fulanah" value="{{ old('nama_pekerja') }}">
                            </div>

                            <div class="mb-3"><label for="id_akun_biaya_upah">Nama Akun Biaya</label>
                                <select class="form-control form-control-solid" name="id_akun_biaya_upah"
                                    id="id_akun_biaya_upah">
                                    <option value="">-- Pilih Nama Jenis Akun --</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_akun }}">{{ $p->nama_akun }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="id_akun_utang_upah">Nama Akun Utang Upah</label>
                                <select class="form-control form-control-solid" name="id_akun_utang_upah"
                                    id="id_akun_utang_upah">
                                    <option value="">-- Pilih Nama Jenis Akun --</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_akun }}">{{ $p->nama_akun }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pekerja') }}"
                                role="button">Batal</a>

                        </form>
                        <!-- Akhir Dari Input Form -->
                    </div>
                </div>
            </div>


        </div>


        <!-- /.container-fluid -->
    </div>
</x-app-layout>
