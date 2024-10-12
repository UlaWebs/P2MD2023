<x-app-layout>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    <!-- Alert success -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <!-- Akhir alert success -->

    <!-- Alert success -->
    @if ($message = Session::get('warning'))
    <div class="alert alert-warning">
        <p>{{ $message }}</p>
    </div>
    @endif
    <!-- Akhir alert success -->
    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Tambah Data Penjualan Tunai</h4>

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
                    <form action="{{ route('penjualantunai.store') }}" method="post">
                        @csrf
                        <div class="mb-3"><label for="tgl_penjualan">Tanggal Penjualan Tunai</label>
                            <input class="form-control form-control-solid" id="tgl_penjualan" name="tgl_penjualan"
                                type="date" value="{{old('tgl_penjualan')}}">
                        </div>

                        <div class="mb-3"><label for="keterangan">Keterangan</label>
                            <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                type="textbox" placeholder="Pembelian Lemari Rotan" value="{{old('keterangan')}}">
                        </div>

                        <div class="mb-3"><label for="id_pelanggan">Nama Pelanggan</label>
                            <select class="form-control form-control-solid" name="id_pelanggan" id="id_pelanggan">
                                <option value="">-- Pilih Nama Pelanggan --</option>
                                @foreach ($pelanggan as $p)
                                <option value="{{ $p->id_pelanggan }}">{{ $p->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="kas">Kas</label>
                            <select class="form-control form-control-solid" name="kas" id="kas">
                                <option value="">-- Pilih Kas --</option>
                                @foreach ($kas as $p)
                                <option value="{{ $p->id_kas }}">{{ $p->nama_kas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/penjualantunai') }}"
                            role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>


    </div>


    <!-- /.container-fluid -->

</x-app-layout>