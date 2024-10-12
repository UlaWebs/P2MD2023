<x-app-layout>
    <div class="container-fluid">
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

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 header-title text-primary">Tambah Data Pemesanan Pembelian</h4>
                        <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{route('pemesanan')}}"> Kembali </a>
                </div>
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
                        <form action="{{ route('pemesanan.store') }}" method="post">
                            @csrf
                            <div class="mb-3"><label for="tgl_pemesanan">Tanggal Pemesanan</label>
                            <input class="form-control form-control-solid" id="tgl_pemesanan" name="tgl_pemesanan" type="date" placeholder="Contoh: Fulanah" value="{{old('tgl_pemesanan')}}">
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                            <input class="form-control form-control-solid" id="keterangan" name="keterangan" type="textbox" placeholder="Contoh: Pemesanan Bahan Baku" value="{{old('keterangan')}}">
                            </div>

                            <div class="mb-3"><label for="id_supplier">Nama Supplier</label>
                                <select class="form-control form-control-solid" name="id_supplier" id="id_supplier">
                                    <option value="">-- Pilih Nama Supplier--</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_supplier }}">{{ $p->nama_supplier }} - {{ $p-> jenis_supplier }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="tgl_jatuh_tempo">Tanggal Jatuh Tempo</label>
                            <input class="form-control form-control-solid" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" type="date" placeholder="Contoh: Fulanah" value="{{old('tgl_jatuh_tempo')}}">
                            </div>

                            <div class="mb-3"><label for="alamat_pengiriman">Alamat</label>
                            <input class="form-control form-control-solid" id="alamat_pengiriman" name="alamat_pengiriman" type="textbox" placeholder="Contoh: Jl. Raya Bandung" value="{{old('alamat_pengiriman')}}">
                            </div>

                            <div class="mb-3"><label for="id_kas">Kas</label>
                                <select class="form-control form-control-solid" name="id_kas" id="id_kas">
                                    <option value="">-- Pilih Nama Kas --</option>
                                    @foreach ($data_kas as $p)
                                        <option value="{{ $p->id_kas }}">{{ $p->nama_kas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="nominal_uang_muka">Nominal Uang Muka</label>
                                <input class="form-control form-control-solid" id="nominal_uang_muka" name="nominal_uang_muka" type="text" placeholder="Contoh: 10 000" value="{{ old('nominal') }}">
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pemesanan') }}" role="button">Batal</a>

                        </form>
                        <!-- Akhir Dari Input Form -->
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>