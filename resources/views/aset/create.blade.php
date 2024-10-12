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
                    <form action="{{ route('aset.store') }}" method="post">
                        @csrf
                        <div class="mb-3"><label for="kode_aset">Kode Aset</label>
                            <input class="form-control form-control-solid" id="kode_aset" name="kode_aset"
                                type="text" placeholder="Contoh: ASMSN001" value="{{ old('kode_aset') }}">
                        </div>


                        <div class="mb-3"><label for="nama_aset">Nama Aset</label>
                            <input class="form-control form-control-solid" id="nama_aset" name="nama_aset"
                                placeholder="Cth: Aset Mesin">{{ old('nama_aset') }}
                        </div>

                        <div class="mb-3"><label for="tingkat_penyusutan">Tingkat Penyusutan(Per Tahun)</label>
                            <input class="form-control form-control-solid" id="tingkat_penyusutan"
                                name="tingkat_penyusutan" placeholder="Cth: 10">{{ old('tingkat_penyusutan') }}
                        </div>

                        <div class="mb-3"><label for="id_kategori_aset">Nama Kategori Aset</label>
                            <select class="form-control form-control-solid" name="id_kategori_aset"
                                id="id_kategori_aset">
                                <option value="">-- Pilih Nama Jenis Aset --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_kategori_aset }}">{{ $p->nama_kategori_aset }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="id_akun_aktiva_tetap">Nama Akun Aktiva Tetap</label>
                            <select class="form-control form-control-solid" name="id_akun_aktiva_tetap"
                                id="id_akun_aktiva_tetap">
                                <option value="">-- Pilih Nama Akun Aktiva Tetap --</option>
                                @foreach ($data_aktiva as $p)
                                    <option value="{{ $p->id_akun }}">({{ $p->kode_akun }}) {{ $p->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="id_akun_beban_penyusutan">Nama Akun Beban Penyusutan</label>
                            <select class="form-control form-control-solid" name="id_akun_beban_penyusutan"
                                id="id_akun_beban_penyusutan">
                                <option value="">-- Pilih Nama Akun Beban Penyusutan --</option>
                                @foreach ($data_beban as $p)
                                    <option value="{{ $p->id_akun }}">({{ $p->kode_akun }}) {{ $p->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="id_akun_akumulasi_penyusutan">Nama Akun Akumulasi
                                Penyusutan</label>
                            <select class="form-control form-control-solid" name="id_akun_akumulasi_penyusutan"
                                id="id_akun_akumulasi_penyusutan">
                                <option value="">-- Pilih Nama Akun Akumulasi Penyusutan --</option>
                                @foreach ($data_akumulasi as $p)
                                    <option value="{{ $p->id_akun }}">({{ $p->kode_akun }}) {{ $p->nama_akun }}
                                    </option>
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
</x-app-layout>
