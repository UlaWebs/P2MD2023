<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

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

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Ubah Detail Data</h4>

                <div class="float-end">
                        <a class="btn btn-primary waves-effect waves-light" href="{{route('pemesanan')}}"> Kembali </a>
                </div>
            </div>
            <div class="card-body">

                <form action="{{ route('pemesanan.updatedetail') }}" method="post">
                    @csrf
                    <input type="hidden" name="id_pemesanan_detail" id="id_pemesanan_detail" value="{{ $id_pemesanan_detail }}">
                    <input type="hidden" name="id_pemesanan_header" id="id_pemesanan_header" value="{{ $id_pemesanan_header }}">
                    <div class="mb-3"><label for="id_item">Item</label>
                        <select class="form-control form-control-solid" name="id_item" id="id_item">
                            <option value="">-- Pilih Nama Item--</option>
                            @foreach ($data_item as $p)
                            <option value="{{ $p->id_item }}" {{$p->id_item == $id_item  ? 'selected' : ''}}>{{ $p->jenis_item }} - {{ $p->nama_item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3"><label for="kuantitas">Kuantitas</label>
                        <input class="form-control form-control-solid" id="kuantitas" name="kuantitas" type="textbox" placeholder="Contoh: 1" value="{{ $kuantitas }}">
                    </div>

                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <select class="form-control form-control-solid" name="id_item_satuan" id="id_item_satuan">
                        @foreach ($data_satuan as $p)
                            <option value="{{ $p->id_item_satuan }}" {{$p->id_item_satuan == $id_item_satuan  ? 'selected' : ''}}>{{ $p->satuan }}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ppn" class="form-label">Apakah Termasuk PPN?</label>
                        <select class="form-control form-control-solid" name="ppn" id="ppn">
                            <option value="t">Ya</option>
                            <option value="f">Tidak</option>
                        </select>
                        </div>

                    <div class="mb-3"><label for="harga_satuan">Harga Satuan</label>
                        <input class="form-control form-control-solid" id="harga_satuan" name="harga_satuan" type="text" placeholder="Contoh: 10 000" value="{{ $harga_satuan }}">
                    </div>

                    <br>
                    <!-- untuk tombol simpan -->

                    <input class="col-sm-1 btn btn-success btn-sm" type="submit" value="Simpan">

                    <!-- untuk tombol batal simpan -->
                    <a class="col-sm-1 btn btn-dark  btn-sm" href="{{ url('/pemesanan/detail/'.$id_pemesanan_header) }}" role="button">Batal</a>

                </form>
            </div>
        </div>

    </div>
    <script>
        $("#id_item").change(function(){
       var id_item = $(this).val();
       var sel_satuan=$("#id_item_satuan");
       var url = '{{route("item.getsatuan",":id")}}';
       url=url.replace(':id',id_item)
       $.ajax({
       url:url,
       type:"get",
       dataType:"json",
       success:function(result){
        sel_satuan.empty();
        $.each(result,function(key,value){
        sel_satuan.append('<option value = "'+value.id_item_satuan+'">'+value.satuan+'</option>');
        });
       }
       });
        });
    </script>
</x-app-layout>