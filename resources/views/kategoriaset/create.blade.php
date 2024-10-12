<x-app-layout>
    @section('title')
    {{ $title }}
    @endsection

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 header-title text-primary">Tambah Data {{ $title }}</h4>
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
                    <form action="{{ route('kategori-aset.store') }}" method="post">
                        @csrf
                        <div class="mb-3"><label for="nama_kategori_aset">Nama Kategori Aset</label>
                            <input class="form-control form-control-solid" id="nama_kategori_aset"
                                name="nama_kategori_aset" type="text" placeholder="Contoh: Aset Motor"
                                value="{{ old('nama_kategori_aset') }}">
                        </div>


                        <div class="mb-3"><label for="umur_ekonomis">Umur Ekonomis (Bulan)</label>
                            <input class="form-control form-control-solid" id="umur_ekonomis" name="umur_ekonomis"
                                placeholder="Cth: 12" value="{{ old('umur_ekonomis') }}">
                        </div>

                        <div class="mb-3"><label for="metode_penyusutan">Metode Penyusutan</label>
                            <select class="form-control form-control-solid" name="metode_penyusutan"
                                id="metode_penyusutan">
                                <option value="">-- Pilih Metode Penyusutan Aset--</option>
                                <option value="garislurus">-- Garis Lurus--</option>
                                <option value="saldomenurun">-- Saldo Menurun--</option>
                                <option value="angkatahun">-- Angka Tahun --</option>
                            </select>
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/aset') }}" role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>