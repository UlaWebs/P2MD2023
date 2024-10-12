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
                    <h4 class="m-0 header-title text-primary">Tambah Data Barang</h4>
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
                    <form action="{{ route('item.store') }}" method="post">
                        @csrf
                        <div class="mb-3"><label for="nama_item">Nama Item</label>
                        <input class="form-control form-control-solid" id="nama_item" name="nama_item" type="text" placeholder="Contoh: Kursi Rotan" value="{{old('nama_item')}}">
                        </div>

                        <div class="mb-3"><label for="satuan">Satuan</label>
                        <input class="form-control form-control-solid" id="satuan" name="satuan" type="text" placeholder="Contoh: Pcs" value="{{old('satuan')}}">
                        </div>

                        <div class="mb-3"><label for="jenis_item">Jenis Item</label>
                        <select class="form-control form-control-solid" name="jenis_item" id="jenis_item">
                                <option value="">-- Pilih Nama Jenis Item --</option>
                                <option value="Bahan Baku">Bahan Baku</option>
                                <option value="Bahan Penolong">Bahan Penolong</option>
                                <option value="Barang Jadi">Barang Jadi</option>
                                <option value="Barang Dalam Proses">Barang Dalam Proses</option>
                            </select>
                        </div>

                        <div class="mb-3"><label for="id_akun_persediaan">Nama Akun Persediaan</label>
                            <select class="form-control form-control-solid" name="id_akun_persediaan" id="id_akun_persediaan">
                                <option value="">-- Pilih Nama Jenis Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}">({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="id_akun_persediaan_dalam_perjalanan">Nama Akun Persediaan Perjalanan</label>
                            <select class="form-control form-control-solid" name="id_akun_persediaan_dalam_perjalanan" id="id_akun_persediaan_dalam_perjalanan">
                                <option value="">-- Pilih Nama Jenis Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}">({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="id_akun_hpp">Nama Akun HPP</label>
                            <select class="form-control form-control-solid" name="id_akun_hpp" id="id_akun_hpp">
                                <option value="">-- Pilih Nama Jenis Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}">({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label for="id_akun_penjualan">Nama Akun Penjualan</label>
                            <select class="form-control form-control-solid" name="id_akun_penjualan" id="id_akun_penjualan">
                                <option value="">-- Pilih Nama Jenis Akun --</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->id_akun }}">({{ $p->kode_akun }}) {{ $p->nama_akun }}</option>
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