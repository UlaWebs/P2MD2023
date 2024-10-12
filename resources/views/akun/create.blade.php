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
                    <form action="{{ route('akun.store') }}" method="post">
                        @csrf
                        <div class="mb-3"><label for="jumlahpenginapanlabel">Kode Akun</label>
                            <input class="form-control form-control-solid" id="kode_akun" name="kode_akun"
                                type="text" placeholder="Contoh: 111" value="{{ old('kode_akun') }}">
                        </div>


                        <div class="mb-3"><label for="jumlahkendaraanlabel">Nama Akun</label>
                            <input class="form-control form-control-solid" id="nama_akun" name="nama_akun"
                                placeholder="Cth: Kas">{{ old('nama_akun') }}
                        </div>

                        <div class="mb-3"><label for="jumlahkendaraanlabel">Nama Jenis Akun</label>
                            <select class="form-control form-control-solid" name="id_jenis_akun" id="id_jenis_akun">
                                <option value="">-- Pilih Nama Jenis Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_jenis_akun }}">{{ $p->nama_jenis_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/akun') }}" role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
