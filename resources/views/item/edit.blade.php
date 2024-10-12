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
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Ubah Data Barang</h4>

                    <a href="#" class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Edit Data</span>
                    </a>

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
                    <form action="{{ route('item.update') }}" method="post">
                        @csrf
                        <input class="form-control form-control-solid" id="id_item" name="id_item" type="hidden" value="{{ $id_item }}">

                        <div class="mb-3"><label for="jumlahpenginapanlabel">Nama</label>
                        <input class="form-control form-control-solid" id="nama_item" name="nama_item" type="text" value="{{ $nama_item }}">
                        </div>

                        <div class="mb-3"><label for="satuan">Satuan</label>
                        <input class="form-control form-control-solid" id="satuan" name="satuan" type="text" value="{{ $satuan }}">
                        </div>

                        <div class="mb-3"><label for="jenis_item">Jenis Item</label>
                        <select class="form-control form-control-solid" name="jenis_item" id="jenis_item">
                                <option value="">-- Pilih Nama Jenis Item --</option>
                                <option value="Bahan Baku" {{ $jenis_item == "Bahan Baku" ? 'selected' : '' }}>Bahan Baku</option>
                                <option value="Bahan Penolong" {{ $jenis_item == "Bahan Penolong" ? 'selected' : '' }}>Bahan Penolong</option>
                                <option value="Barang Jadi" {{ $jenis_item == "Barang Jadi" ? 'selected' : '' }}>Barang Jadi</option>
                                <option value="Barang Dalam Proses" {{ $jenis_item == "Barang Dalam Proses" ? 'selected' : '' }}>Barang Dalam Proses</option>
                            </select>
                        </div>

                        <div class="mb-3"><label for="">Nama Akun Persediaan</label>
                            <select class="form-control form-control-solid" id="id_akun_persediaan" name="id_akun_persediaan">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{$p->id_akun == $id_akun_persediaan  ? 'selected' : ''}}>({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="">Nama Akun Persediaan Perjalanan</label>
                            <select class="form-control form-control-solid" id="id_akun_persediaan_dalam_perjalanan" name="id_akun_persediaan_dalam_perjalanan">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{$p->id_akun == $id_akun_persediaan_dalam_perjalanan  ? 'selected' : ''}}>({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="">Nama Akun HPP</label>
                            <select class="form-control form-control-solid" id="id_akun_hpp" name="id_akun_hpp">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{$p->id_akun == $id_akun_hpp  ? 'selected' : ''}}>({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label for="">Nama Akun Penjualan</label>
                            <select class="form-control form-control-solid" id="id_akun_penjualan" name="id_akun_penjualan">
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}" {{$p->id_akun == $id_akun_penjualan  ? 'selected' : ''}}>({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/item') }}" role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>


    </div>


    <!-- /.container-fluid -->
</div>
</x-app-layout>