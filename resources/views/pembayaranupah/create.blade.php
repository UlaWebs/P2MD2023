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
                        <h4 class="m-0 font-weight-bold text-primary">Pembayaran Upah</h4>
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
                        <form action="{{ route('pembayaranupah.store') }}" method="post">
                            @csrf
                            <div class="mb-3"><label for="tgl_pembayaran">Tanggal Pembayaran</label>
                                <input class="form-control form-control-solid" id="tgl_pembayaran" name="tgl_pembayaran"
                                    type="date" placeholder="Contoh: Fulanah" value="{{ old('tgl_pembayaran') }}">
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="textbox" placeholder="Contoh: Pembayaran Upah Faulanah"
                                    value="{{ old('keterangan') }}">
                            </div>

                            <div class="mb-3"><label for="id_produksi_detail_tenaga_kerja">Produksi Detail Tenaga
                                    Kerja</label>
                                <select class="form-control form-control-solid" name="id_produksi_detail_tenaga_kerja"
                                    id="id_produksi_detail_tenaga_kerja">
                                    <option value="">-- Pilih No Produksi / Tenaga Kerja --</option>
                                    @foreach ($produksi_detail_tenaga_kerja as $p)
                                    <option value="{{ $p->id_produksi_detail_tenaga_kerja }}">{{ $p->no_produksi }} -
                                        @currency($p->biaya_tenaga_kerja) - {{ $p->nama_pekerja }} -
                                        {{ $p->tgl_produksi }}
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

                            <div class="mb-3"><label for="nominal">Nominal</label>
                                <input class="form-control form-control-solid" id="nominal" name="nominal" type="number"
                                    placeholder="Contoh: 80 000" value="{{ old('nominal') }}">
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pembayaranupah') }}"
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