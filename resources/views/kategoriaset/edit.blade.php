<x-app-layout>
    @section('title')
        {{ $title }} (Edit)
    @endsection
    <!-- Content Row -->
    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Ubah Data {{ $title }}</h4>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area" hidden>
                        <canvas id="myAreaChart"></canvas>
                    </div>

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
                    <form action="{{ route('kategori-aset.update') }}" method="post">
                        @csrf
                        <input class="form-control form-control-solid" id="id_kategori_aset" name="id_kategori_aset"
                            type="hidden" value="{{ $id_kategori_aset }}">

                        <div class="mb-3"><label for="nama_kategori_aset">Nama Kategori Aset</label>
                            <input class="form-control form-control-solid" id="nama_kategori_aset"
                                name="nama_kategori_aset" value="{{ $nama_kategori_aset }}">
                        </div>

                        <div class="mb-3"><label for="umur_ekonomis">Umur Ekonomis (Bulan)</label>
                            <input class="form-control form-control-solid" id="umur_ekonomis" name="umur_ekonomis"
                                type="text" placeholder="Contoh: 111" value="{{ $umur_ekonomis }}">
                        </div>

                        <div class="mb-3"><label for="metode_penyusutan">Metode Penyusutan</label>
                            <input class="form-control form-control-solid" id="metode_penyusutan"
                                name="metode_penyusutan" type="text" placeholder="Contoh: 111"
                                value="{{ $metode_penyusutan }}">
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/kategori-aset') }}"
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
