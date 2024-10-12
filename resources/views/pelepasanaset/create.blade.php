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
                        <h4 class="m-0 header-title text-primary">Tambah Data Pelepasan Aset</h4>
                        <div class="float-end">
                            <a class="btn btn-primary waves-effect waves-light" href="{{ route('pelepasanaset') }}">
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
                        <form action="{{ route('pelepasanaset.store') }}" method="post">
                            @csrf
                            <div class="mb-3"><label for="tgl_pelepasan_aset">Tanggal Pelepasan Aset</label>
                                <input class="form-control form-control-solid" id="tgl_pelepasan_aset"
                                    name="tgl_pelepasan_aset" type="date" placeholder="Contoh: Fulanah"
                                    value="{{ old('tgl_pelepasan_aset') }}">
                            </div>

                            <div class="mb-3"><label for="kategori_pelepasan">Tipe Pelepasan</label>
                                <select class="form-control form-control-solid" name="kategori_pelepasan"
                                    id="kategori_pelepasan">
                                    <option value="">-- Pilih Tipe--</option>
                                    <option value="Penjualan">Penjualan</option>
                                    <option value="Pemusnahan">Pemusnahan</option>
                                </select>
                            </div>

                            <div class="mb-3"><label for="id_inventaris">Inventaris</label>
                                <select class="form-control form-control-solid" name="id_inventaris" id="id_inventaris">
                                    <option value="">-- Pilih Aset--</option>
                                    @foreach ($inventaris as $p)
                                        <option value="{{ $p->id_inventaris }}">{{ $p->nama_aset }}
                                            ({{ $p->kode_inventaris }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div style="display: none;" id="per">
                                <div class="mb-3"><label for="id_kas">Nama Kas</label>
                                    <select class="form-control form-control-solid" name="id_kas" id="id_kas">
                                        <option value="">-- Pilih Nama Kas--</option>
                                        @foreach ($kas as $p)
                                            <option value="{{ $p->id_kas }}">{{ $p->nama_kas }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3"><label for="nominal_pelepasan">Nominal Pelunasan</label>
                                    <input class="form-control form-control-solid" id="nominal_pelepasan"
                                        name="nominal_pelepasan" type="text" placeholder="Contoh: 10 000"
                                        value="{{ old('nominal_pelepasan') }}">
                                </div>
                            </div>
                            <br>
                            <!-- untuk tombol simpan -->

                            <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                            <!-- untuk tombol batal simpan -->
                            <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pelepasanaset') }}"
                                role="button">Batal</a>

                        </form>
                        <!-- Akhir Dari Input Form -->
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script>
        $('#kategori_pelepasan').change(
            function() {
                var kat = $('#kategori_pelepasan').val();
                if (kat == 'Penjualan') {
                    $('#per').css('display', 'block');
                } else {
                    $('#per').css('display', 'none');
                }
            }
        );
    </script>
</x-app-layout>
