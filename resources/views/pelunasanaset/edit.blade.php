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

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pelunasan Aset</h1>
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">Ubah Data Pelunasan Aset</h4>
                        <div class="float-end">
                            <a class="btn btn-primary waves-effect waves-light" href="{{ route('pelunasanaset') }}">
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
                        <form action="{{ route('pelunasanaset.update') }}" method="post">
                            @csrf
                            <input type="text" name="id_perolehan_aset_header" id="id_perolehan_aset_header"
                                value="{{ $id_perolehan_aset_header }}" hidden>
                            <div class="mb-3"<label for="tgl_pelunasan_aset">Tanggal Pelunasan</label>
                                <input type="hidden" id="id_pelunasan_aset" value="{{ $id_pelunasan_aset }}"
                                    name="id_pelunasan_aset">

                                <input class="form-control form-control-solid" id="tgl_pelunasan_aset"
                                    name="tgl_pelunasan_aset" type="date" value="{{ $tgl_pelunasan_aset }}">
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

                            <div class="mb-3"><label for="nominal_pelunasan">Nominal Pelunasan</label>
                                <input class="form-control form-control-solid" id="nominal_pelunasan"
                                    name="nominal_pelunasan" type="text" placeholder="Contoh: 10 000"
                                    value="{{ $nominal_pelunasan }}">
                            </div>

                            <div class="mb-3"><label for="no_perolehan_aset">No Perolehan Header</label>
                                <input class="form-control form-control-solid" id="no_perolehan_aset"
                                    name="no_perolehan_aset" type="text" value="{{ $no_perolehan_aset }}" readonly>
                            </div>

                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pelunasanaset') }}"
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