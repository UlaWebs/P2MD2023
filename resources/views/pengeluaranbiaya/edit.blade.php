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
                        <h4 class="m-0 font-weight-bold text-primary">Ubah Data Pengeluaran Biaya</h4>

                        <!-- Tombol Tambah Data -->
                        <a href="#" class="btn btn-primary btn-icon-split btn-sm">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Tambah Data</span>
                        </a>
                        <!-- Akghir Tombol Tambah Data -->

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
                        <form action="{{ route('pengeluaranbiaya.update') }}" method="post">
                            @csrf
                            <input type="hidden" id="id_pengeluaran_biaya" name="id_pengeluaran_biaya" value="{{ $id_pengeluaran_biaya }}">
                            <div class="mb-3"><label for="tgl_pengeluaran_biaya">Tanggal Pengeluaran Biaya</label>
                                <input class="form-control form-control-solid" id="tgl_pengeluaran_biaya" name="tgl_pengeluaran_biaya"
                                    type="date" placeholder="Contoh: Fulanah" value="{{ $tgl_pengeluaran_biaya }}">
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="textbox" placeholder="Contoh: Jl. Raya Bandung" value="{{ $keterangan }}">
                            </div>

                            <div class="mb-3"><label for="id_biaya">Biaya</label>
                                <select class="form-control form-control-solid" name="id_biaya"
                                    id="id_biaya">
                                    <option value="">-- Pilih Biaya --</option>
                                    @foreach ($biaya as $p)
                                        <option value="{{ $p->id_biaya }}"
                                            {{ $p->id_biaya == $id_biaya ? 'selected' : '' }}>
                                            {{ $p->nama_biaya }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="id_kas">Kas</label>
                                <select class="form-control form-control-solid" name="id_kas" id="id_kas">
                                    <option value="">-- Pilih Kas --</option>
                                    @foreach ($kas as $p)
                                        <option value="{{ $p->id_kas }}"
                                            {{ $p->id_kas == $id_kas ? 'selected' : '' }}>{{ $p->nama_kas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="nominal">Nominal</label>
                                <input class="form-control form-control-solid" id="nominal"
                                    name="nominal" type="number" placeholder="Contoh: Fulanah"
                                    value="{{ $nominal }}">
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pengeluaranbiaya') }}"
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
