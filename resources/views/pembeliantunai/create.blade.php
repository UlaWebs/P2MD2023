<x-app-layout>


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pembelian Tunai</h1>
    </div>
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
    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Tambah Data Pembelian Tunai</h4>
                    <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{route('pembeliantunai')}}"> Kembali
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
                    <form action="{{ route('pembeliantunai.store') }}" method="post">
                        @csrf
                        <div class="mb-3"><label for="tanggal_pembelian_tunai">Tanggal Pembelian Tunai</label>
                            <input class="form-control form-control-solid" id="tanggal_pembelian_tunai"
                                name="tanggal_pembelian_tunai" type="date" value="{{old('tanggal_pembelian_tunai')}}">
                        </div>

                        <div class="mb-3"><label for="keterangan">Keterangan</label>
                            <input class="form-control form-control-solid" id="keterangan" name="keterangan"
                                type="textbox" placeholder="Pembelian Bahan Baku" value="{{old('keterangan')}}">
                        </div>

                        <div class="mb-3"><label for="id_supplier">Nama Supplier</label>
                            <select class="form-control form-control-solid" name="id_supplier" id="id_supplier">
                                <option value="">-- Pilih Nama Jenis Supplier--</option>
                                @foreach ($supplier as $p)
                                <option value="{{ $p->id_supplier }}">{{ $p->nama_supplier }} - {{ $p->jenis_supplier }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3"><label for="kas">Kas</label>
                            <select class="form-control form-control-solid" name="kas" id="kas">
                                <option value="">-- Pilih Kas --</option>
                                @foreach ($kas as $p)
                                <option value="{{ $p->id_kas }}">{{ $p->nama_kas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3"><label for="no_faktur_pembelian_tunai">No Faktur Pembelian</label>
                            <input class="form-control form-control-solid" id="no_faktur_pembelian_tunai"
                                name="no_faktur_pembelian_tunai" type="textbox" placeholder="FKTR-XXXX"
                                value="{{old('no_faktur_pembelian_tunai')}}">
                        </div>

                        <br>
                        <!-- untuk tombol simpan -->

                        <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                        <!-- untuk tombol batal simpan -->
                        <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pembeliantunai') }}"
                            role="button">Batal</a>

                    </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>
    </div>

</x-app-layout>