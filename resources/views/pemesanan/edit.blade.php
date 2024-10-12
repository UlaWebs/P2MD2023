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
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Ubah Data Pemesanan Pembelian</h4>
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
                    <form action="{{ route('pemesanan.update') }}" method="post">
                        @csrf
                        <div class="mb-3"<label for="tg_pemesanan">Tanggal Pemesanan</label>
                        <input type="hidden" id="id_pemesanan_header" value="{{ $id_pemesanan_header }}" name="id_pemesanan_header">
                        <input class="form-control form-control-solid" id="tgl_pemesanan" name="tgl_pemesanan" type="date" placeholder="Contoh: Fulanah" value="{{$tgl_pemesanan}}">
                        </div>

                        <div class="mb-3"><label for="keterangan">Keterangan</label>
                        <input class="form-control form-control-solid" id="keterangan" name="keterangan" type="textbox" placeholder="Contoh: Jl. Raya Bandung" value="{{$keterangan}}">
                        </div>

                        <div class="mb-3"><label for="id_supplier">Nama Supplier</label>
                            <select class="form-control form-control-solid" name="id_supplier" id="id_supplier">
                                <option value="">-- Pilih Nama Jenis Supplier --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_supplier }}"
                                    {{ $p->id_supplier == $id_supplier  ? 'selected' : '' }}>
                                    {{ $p->nama_supplier }} - {{ $p-> jenis_supplier }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="tgl_jatuh_tempo">Tanggal Jatuh Tempo</label>
                        <input class="form-control form-control-solid" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" type="date" placeholder="Contoh: Fulanah" value="{{$tgl_jatuh_tempo}}">
                        </div>

                        <div class="mb-3"><label for="alamat_pengiriman">Alamat</label>
                        <input class="form-control form-control-solid" id="alamat_pengiriman" name="alamat_pengiriman" type="textbox" placeholder="Contoh: Jl. Raya Bandung" value="{{$alamat_pengiriman}}">
                        </div>

                        <div class="mb-3"><label for="nominal_uang_muka">Nominal Uang Muka</label>
                                <input class="form-control form-control-solid" id="nominal_uang_muka" name="nominal_uang_muka" type="text" placeholder="Contoh: 10 000" value="@currency($nominal_uang_muka)">
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


    <!-- /.container-fluid -->
</div>
</x-app-layout>