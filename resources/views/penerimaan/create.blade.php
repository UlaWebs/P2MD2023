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
                        <h4 class="m-0 font-weight-bold text-primary">Tambah Data Penerimaan Bahan</h4>

                <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{route('penerimaan')}}"> Kembali </a>
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
                        <form action="{{ route('penerimaan.store') }}" method="post">
                            @csrf
                            <div class="mb-3"><label for="tgl_penerimaan">Tanggal Penerimaan</label>
                                <input class="form-control form-control-solid" id="tgl_penerimaan" name="tgl_penerimaan"
                                    type="date" placeholder="Contoh: Fulanah" value="{{ old('tgl_penerimaan') }}">
                            </div>

                            <div class="mb-3"><label for="id_pemesanan_header">ID Pemesanan</label>
                                <select class="form-control form-control-solid" name="id_pemesanan_header"
                                    id="id_pemesanan_header">
                                    <option value="">-- Pilih No Pemesanan--</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_pemesanan_header }}">
                                        {{ $p->no_pemesanan }} - {{ $p->status }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="text" placeholder="Contoh: Penerimaan Produk Rotan" value="{{ old('keterangan') }}">
                            </div>

                            <div class="mb-3"><label for="no_faktur_pembelian">No Faktur Pembelian</label>
                                <input class="form-control form-control-solid" id="no_faktur_pembelian" name="no_faktur_pembelian"
                                    type="text" placeholder="Contoh: xxx" value="{{ old('no_faktur_pembelian') }}">
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/penerimaan') }}"
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
