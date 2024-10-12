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
                        <h4 class="m-0 font-weight-bold text-primary">Ubah Data Pemesanan Penjualan</h4>

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
                        <form action="{{ route('pemesananpenjualan.update') }}" method="post">
                            @csrf
                            <div class="mb-3"<label for="tgl_pemesanan">Tanggal Pemesanan</label>
                                <input type="hidden" id="id_pemesanan_penjualan_header"
                                    value="{{ $id_pemesanan_penjualan_header }}" name="id_pemesanan_penjualan_header">
                                <input class="form-control form-control-solid" id="tgl_pemesanan" name="tgl_pemesanan"
                                    type="date" placeholder="Contoh: Fulanah" value="{{ $tgl_pemesanan }}">
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="textbox" placeholder="Contoh: Jl. Raya Bandung" value="{{ $keterangan }}">
                            </div>

                            <div class="mb-3"><label for="id_pelanggan">Nama pelanggan</label>
                                <select class="form-control form-control-solid" name="id_pelanggan" id="id_pelanggan">
                                    <option value="">-- Pilih Nama Pelanggan --</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_pelanggan }}"
                                            {{ $p->id_pelanggan == $id_pelanggan ? 'selected' : '' }}>
                                            {{ $p->nama_pelanggan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="tgl_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                <input class="form-control form-control-solid" id="tgl_jatuh_tempo"
                                    name="tgl_jatuh_tempo" type="date" placeholder="Contoh: Fulanah"
                                    value="{{ $tgl_jatuh_tempo }}">
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pemesananpenjualan') }}"
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
