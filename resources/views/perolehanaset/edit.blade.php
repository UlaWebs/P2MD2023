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
                        <h4 class="m-0 font-weight-bold text-primary">Ubah Data Perolehan Aset</h4>
                        <div class="float-end">
                            <a class="btn btn-primary waves-effect waves-light" href="{{ route('perolehanaset') }}">
                                Kembali
                            </a>
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
                        <form action="{{ route('perolehanaset.update') }}" method="post">
                            @csrf
                            <div class="mb-3"<label for="tgl_perolehan_aset">Tanggal Pemesanan</label>
                                <input type="hidden" id="id_perolehan_aset_header"
                                    value="{{ $id_perolehan_aset_header }}" name="id_perolehan_aset_header">

                                <input class="form-control form-control-solid" id="tgl_perolehan_aset"
                                    name="tgl_perolehan_aset" type="date" value="{{ $tgl_perolehan_aset }}">
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="textbox" placeholder="Contoh: Perolehan Aset Mobil"
                                    value="{{ $keterangan }}">
                            </div>

                            <div class="mb-3"><label for="id_vendor">Nama Supplier</label>
                                <select class="form-control form-control-solid" name="id_vendor" id="id_vendor">
                                    <option value="">-- Pilih Nama Jenis Supplier --</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_vendor }}"
                                            {{ $p->id_vendor == $id_vendor ? 'selected' : '' }}>
                                            {{ $p->nama_vendor }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="id_kas">Kas</label>
                                <select class="form-control form-control-solid" name="id_kas" id="id_kas">
                                    <option value="">-- Pilih Nama Kas --</option>
                                    @foreach ($data_kas as $p)
                                        <option value="{{ $p->id_kas }}"
                                            {{ $p->id_kas == $id_kas ? 'selected' : '' }}>
                                            {{ $p->nama_kas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="nominal_kas">Nominal Uang Kas</label>
                                <input class="form-control form-control-solid" id="nominal_kas" name="nominal_kas"
                                    type="text" placeholder="Contoh: 10 000" value="@currency($nominal_kas)">
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/perolehanaset') }}"
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
