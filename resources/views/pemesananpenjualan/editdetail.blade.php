<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

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

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>

                <a href="{{ route('pemesanan.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Tambah Data</span>
                </a>
            </div>
            <div class="card-body">

                <form action="{{ route('pemesananpenjualan.updatedetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_pemesanan_penjualan_detail" id="id_pemesanan_penjualan_detail"
                        value="{{ $id_pemesanan_penjualan_detail }}">
                    <input type="hidden" name="id_pemesanan_penjualan_header" id="id_pemesanan_penjualan_header"
                        value="{{ $id_pemesanan_penjualan_header }}">
                    <div class="mb-3"><label for="id_item">Item</label>
                        <select class="form-control form-control-solid" name="id_item" id="id_item">
                            <option value="">-- Pilih Nama Item--</option>
                            @foreach ($data_item as $p)
                                <option value="{{ $p->id_item }}" {{ $p->id_item == $id_item ? 'selected' : '' }}>
                                    {{ $p->nama_item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                        <input class="form-control form-control-solid" id="kuantitas" name="kuantitas" type="textbox"
                            placeholder="Contoh: Jl. Raya Bandung" value="{{ $kuantitas }}">
                    </div>

                    <div class="mb-3"><label for="harga_satuan">Harga Satuan</label>
                        <input class="form-control form-control-solid" id="harga_satuan" name="harga_satuan"
                            type="text" placeholder="Contoh: Fulanah" value="{{ $harga_satuan }}">
                    </div>

                    <br>
                    <!-- untuk tombol simpan -->

                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pemesananpenjualan') }}"
                        role="button">Batal</a>

                </form>
            </div>
        </div>

    </div>
</x-app-layout>
