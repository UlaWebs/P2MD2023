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
                    <form action="{{ route('aset.update') }}" method="post">
                        @csrf
                        <input class="form-control form-control-solid" id="id_aset" name="id_aset" type="hidden"
                            value="{{ $id_aset }}">
                        <div class="mb-3"><label for="kode_aset">Kode Aset</label>
                            <input class="form-control form-control-solid" id="kode_aset" name="kode_aset"
                                type="text" placeholder="Contoh: 111" value="{{ $kode_aset }}">
                        </div>


                        <div class="mb-3"><label for="nama_aset">Nama Aset</label>
                            <input class="form-control form-control-solid" id="nama_aset" name="nama_aset"
                                value="{{ $nama_aset }}">
                        </div>

                        <div class="mb-3"><label for="tingkat_penyusutan">Tingkat Penyusutan (Per Tahun)</label>
                            <input class="form-control form-control-solid" id="tingkat_penyusutan"
                                name="tingkat_penyusutan" value="{{ $tingkat_penyusutan }}">
                        </div>

                        <div class="mb-3"><label for="jumlahkendaraanlabel">Nama Kategori Aset</label>
                            <select class="form-control form-control-solid" name="id_kategori_aset"
                                id="id_kategori_aset">
                                <option value="">-- Pilih Nama Kategori Aset --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_kategori_aset }}"
                                        {{ $p->id_kategori_aset == $id_kategori_aset ? 'selected' : '' }}>
                                        {{ $p->nama_kategori_aset }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="">Nama Akun Aktiva Tetap</label>
                            <select class="form-control form-control-solid" id="id_akun_aktiva_tetap"
                                name="id_akun_aktiva_tetap">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data2 as $p)
                                    <option value="{{ $p->id_akun }}"
                                        {{ $p->id_akun == $id_akun_aktiva_tetap ? 'selected' : '' }}>
                                        ({{ $p->kode_akun }})
                                        {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="">Nama Akun Beban Penyusutan</label>
                            <select class="form-control form-control-solid" id="id_akun_beban_penyusutan"
                                name="id_akun_beban_penyusutan">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data2 as $p)
                                    <option value="{{ $p->id_akun }}"
                                        {{ $p->id_akun == $id_akun_beban_penyusutan ? 'selected' : '' }}>
                                        ({{ $p->kode_akun }})
                                        {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="">Nama Akun Akumulasi Penyusutan</label>
                            <select class="form-control form-control-solid" id="id_akun_akumulasi_penyusutan"
                                name="id_akun_akumulasi_penyusutan">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data2 as $p)
                                    <option value="{{ $p->id_akun }}"
                                        {{ $p->id_akun == $id_akun_akumulasi_penyusutan ? 'selected' : '' }}>
                                        ({{ $p->kode_akun }})
                                        {{ $p->nama_akun }}</option>
                                @endforeach
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


    <!-- /.container-fluid -->
    </div>
</x-app-layout>
