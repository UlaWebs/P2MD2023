<x-app-layout>
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 header-title text-primary">Tambah Data Pemesanan</h4>

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
                        <form action="{{ route('pemesananpenjualan.store') }}" method="post">
                            @csrf
                            <div class="mb-3"><label for="tgl_pemesanan">Tanggal Pemesanan</label>
                                <input class="form-control form-control-solid" id="tgl_pemesanan" name="tgl_pemesanan"
                                    type="date" placeholder="Contoh: Fulanah" value="{{ old('tgl_pemesanan') }}">
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="textbox" placeholder="Contoh: Pemesanan Penjualan dari ..."
                                    value="{{ old('keterangan') }}">
                            </div>

                            <div class="mb-3"><label for="id_pelanggan">Nama Pelanggan</label>
                                <select class="form-control form-control-solid" name="id_pelanggan" id="id_pelanggan">
                                    <option value="">-- Pilih Nama Pelanggan --</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_pelanggan }}">{{ $p->nama_pelanggan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="tgl_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                <input class="form-control form-control-solid" id="tgl_jatuh_tempo"
                                    name="tgl_jatuh_tempo" type="date" placeholder="Contoh: Fulanah"
                                    value="{{ old('tgl_jatuh_tempo') }}">
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
    </div>
</x-app-layout>
