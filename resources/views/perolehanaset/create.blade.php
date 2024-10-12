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
                        <h4 class="m-0 header-title text-primary">Tambah Data Perolehan Aset</h4>
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
                        <form action="{{ route('perolehanaset.store') }}" method="post">
                            @csrf
                            <div class="mb-3"><label for="tgl_perolehan_aset">Tanggal Perolehan Aset</label>
                                <input class="form-control form-control-solid" id="tgl_perolehan_aset"
                                    name="tgl_perolehan_aset" type="date" placeholder="Contoh: Fulanah"
                                    value="{{ old('tgl_perolehan_aset') }}">
                            </div>

                            <div class="mb-3"><label for="keterangan">Keterangan</label>
                                <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                    type="textbox" placeholder="Contoh: Perolehan Aset ..."
                                    value="{{ old('keterangan') }}">
                            </div>

                            <div class="mb-3"><label for="id_vendor">Nama Vendor</label>
                                <select class="form-control form-control-solid" name="id_vendor" id="id_vendor">
                                    <option value="">-- Pilih Nama Vendor--</option>
                                    @foreach ($data as $p)
                                        <option value="{{ $p->id_vendor }}">{{ $p->nama_vendor }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="id_kas">Kas</label>
                                <select class="form-control form-control-solid" name="id_kas" id="id_kas">
                                    <option value="">-- Pilih Nama Kas --</option>
                                    @foreach ($data_kas as $p)
                                        <option value="{{ $p->id_kas }}">{{ $p->nama_kas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3"><label for="nominal_kas">Nominal Uang Kas</label>
                                <input class="form-control form-control-solid" id="nominal_kas" name="nominal_kas"
                                    type="text" placeholder="Contoh: 10 000" value="{{ old('nominal_kas') }}">
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
    </div>
</x-app-layout>
