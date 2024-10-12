<x-app-layout>
    <link href="{{ asset('src/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/libs/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
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
                        <h4 class="m-0 header-title text-primary">{{ $title }}</h4>

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
                        <form action="{{ route('laporanpenyusutan.show') }}" method="get">
                            @csrf
                            <div class="mb-3"><label for="id_inventaris">Inventaris</label>
                                <select class="form-control form-control-solid" name="id_inventaris" id="id_inventaris">
                                    <option value="">-- Pilih Nama Jenis Aset --</option>
                                    @php
                                        $currentCategory = null;
                                    @endphp
                                    @foreach ($data as $p)
                                        @if ($currentCategory != $p->id_kategori_aset)
                                            @if ($currentCategory != null)
                                                </optgroup>
                                            @endif
                                            <optgroup label="{{ $p->nama_kategori_aset }}">
                                                @php
                                                    $currentCategory = $p->id_kategori_aset;
                                                @endphp
                                        @endif
                                        <option value="{{ $p->id_inventaris }}">{{ $p->nama_aset }}
                                            ({{ $p->kode_aset }})</option>
                                    @endforeach
                                    @if ($currentCategory != null)
                                        </optgroup>
                                    @endif
                                </select>
                            </div>
                            <!-- untuk tombol tampilkan-->

                            <input class="col-sm-3 btn btn-success btn-sm" type="submit" value="Tampilkan">
                        </form>
                        <!-- Akhir Dari Input Form -->
                    </div>
                </div>
            </div>


        </div>
    </div>


    <script src="{{ asset('src/assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('#id_inventaris').select2();
        });
    </script>



</x-app-layout>
