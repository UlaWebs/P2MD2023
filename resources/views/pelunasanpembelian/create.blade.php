<x-app-layout>


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pelunasan Pembelian</h1>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Tambah Data Pelunasan Pembelian</h4>
                    <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{route('pelunasanpembelian.index')}}"> Kembali </a>
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
                    <form action="{{ route('pelunasanpembelian.store') }}" method="post">
                        @csrf
                        <div class="mb-3"><label for="tgl_pelunasan">Tanggal Pelunasan</label>
                            <input class="form-control form-control-solid" id="tgl_pelunasan" name="tgl_pelunasan"
                                type="date" placeholder="Contoh: Fulanah" value="{{ old('tgl_pelunasan') }}">
                        </div>

                        <div class="mb-3"><label for="keterangan">Keterangan</label>
                            <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                type="textbox" placeholder="Contoh: Pelunasan Pembelian untuk Pemesanan Rotan"
                                value="{{ old('keterangan') }}">
                        </div>

                        <div class="mb-3"><label for="id_penerimaan_header">Penerimaan</label>
                            <select class="form-control form-control-solid" name="id_penerimaan_header"
                                id="id_penerimaan_header">
                                <option value="">-- Pilih ID Penerimaan --</option>
                                @foreach ($penerimaan as $p)
                                    <option value="{{ $p->id_penerimaan_header }}">{{ $p->no_penerimaan }} - {{ $p->no_faktur_pembelian}}
                                      -  @currency($p->total_tagihan) - {{ $p->nama_supplier }} -
                                        {{ $p->tgl_penerimaan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="id_kas">Kas</label>
                            <select class="form-control form-control-solid" name="id_kas" id="id_kas">
                                <option value="">-- Pilih Kas --</option>
                                @foreach ($kas as $p)
                                    <option value="{{ $p->id_kas }}">{{ $p->nama_kas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="nominal_pembayaran">Nominal Pembayaran</label>
                            <input class="form-control form-control-solid" id="nominal_pembayaran"
                                name="nominal_pembayaran" type="number" placeholder="Contoh: 25 000"
                                value="{{ old('nominal_pembayaran') }}">
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pelunasanpembelian') }}"
                            role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>


    </div>
</x-app-layout>
