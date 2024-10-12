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
                        <h4 class="m-0 header-title text-primary">{{$title}}</h4>

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
                        <form action="{{ route('neraca.show') }}" method="get">
                            @csrf
                            <div class="mb-3"><label for="tgl">Per Tanggal</label>
                            <input class="form-control form-control-solid" id="tgl" name="tgl" type="date"  value="{{old('tgl_pemesanan')}}">
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
</x-app-layout>