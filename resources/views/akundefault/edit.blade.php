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
                    <form action="{{ route('akundefault.update') }}" method="post">
                        @csrf
                        <input class="form-control form-control-solid" id="id_akun_default" name="id_akun_default"
                            type="hidden" placeholder="Contoh: 111" value="{{ $id_akun_default }}">
                        <div class="mb-3"><label for="jumlahpenginapanlabel">Akun Default</label>
                            <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                type="text" placeholder="Contoh: 111" value="{{ $keterangan }}" readonly>
                        </div>

                        <div class="mb-3"><label for="jumlahkendaraanlabel">Nama Akun</label>
                            <select class="form-control form-control-solid" name="id_akun" id="id_akun">
                                <option value="">-- Pilih Nama Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{ $p->id_akun == $id_akun ? 'selected' : '' }}>
                                        {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/akundefault') }}"
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
