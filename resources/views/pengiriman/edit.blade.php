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
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">Ubah Data Pengiriman</h4>

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
                        <form action="{{ route('pengiriman.update') }}" method="post">
                            @csrf
                            <input class="form-control form-control-solid" id="id_pengiriman_header"
                                name="id_pengiriman_header" type="hidden" placeholder="Contoh: 111"
                                value="{{ $id_pengiriman_header }}">
                            <div class="mb-3"><label for="no_pengiriman">No pengiriman</label>
                                <input class="form-control form-control-solid" id="no_pengiriman" name="no_pengiriman"
                                    type="text" placeholder="Contoh: 111" value="{{ $no_pengiriman }}">
                            </div>


                            <div class="mb-3"><label for="tgl_pengiriman">Tanggal pengiriman</label>
                                <input class="form-control form-control-solid" id="tgl_pengiriman" name="tgl_pengiriman"
                                    value="{{ $tgl_pengiriman }}" type="date">
                            </div>

                            <div class="mb-3"><label for="id_pemesanan_penjualan_header">No Pemesanan
                                    Penjualan</label>
                                <select class="form-control form-control-solid" name="id_pemesanan_penjualan_header"
                                    id="id_pemesanan_penjualan_header">
                                    <option value="">-- Pilih No Pemesanan Penjualan --</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_pemesanan_penjualan_header }}"
                                            {{ $p->id_pemesanan_penjualan_header == $id_pemesanan_penjualan_header ? 'selected' : '' }}>
                                            {{ $p->no_pemesanan_penjualan_header }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="text" placeholder="Contoh: 111" value="{{ $keterangan }}">
                            </div>

                            <div class="mb-3"><label for="no_invoice">Nomor Invoice</label>
                                <input class="form-control form-control-solid" id="no_invoice" name="no_invoice"
                                    type="text" placeholder="Contoh: 111" value="{{ $no_invoice }}">
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pengiriman') }}"
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
