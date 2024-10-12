<x-app-layout>
    <div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary">Ubah Data Barang</h4>

                    <a href="#" class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Edit Data</span>
                    </a>

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
                    <form action="{{ route('item.updatedetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_item_satuan" id="id_item_satuan"
                        value="{{ $id_item_satuan }}">
                        <input type="hidden" name="id_item" id="id_item"
                        value="{{ $id_item }}">

                    <div class="mb-3"><label for="satuan">Satuan</label>
                        <input class="form-control form-control-solid" id="satuan" name="satuan" type="text"
                            placeholder="Contoh: 2" value="{{ $satuan }}">
                    </div>
                    <div class="mb-3"><label for="operator">Operator</label>
                        <select class="form-control form-control-solid" name="operator" id="operator">
                            <option value="">-- Pilih Operator --</option>
                             <option value="*" {{ '*'== $operator  ? 'selected' : ''}}>Kali</option>
                            <option value="/"{{ '/'== $operator  ? 'selected' : ''}}>Bagi</option>
                         </select>
                    </div>
                    <div class="mb-3"><label for="konversi">Konversi</label>
                        <input class="form-control form-control-solid" id="konversi" name="konversi" type="number"
                            placeholder="Contoh: 2" value="{{ $konversi }}">
                    </div>

                    <!-- untuk tombol simpan -->
                    <input class="btn btn-primary waves-effect waves-light" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="btn btn-secondary waves-effect" href="{{ url('/item/detail/'.$id_item) }}" role="button">Batal</a>

                </form>
                    <!-- Akhir Dari Input Form -->
                </div>
            </div>
        </div>


    </div>


    <!-- /.container-fluid -->
</div>
</x-app-layout>