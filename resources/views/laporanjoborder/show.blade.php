<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>

                <a href="{{ route('laporanjoborder') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>

            </div>

            <div class="card-body">
                @foreach($header as $h)
                <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                    <tr>
                        <td width="20%">No Produksi</td>
                        <td>{{$h->no_produksi}}</td>
                    </tr>

                    @php $kuantitas=0;@endphp
                    @foreach($output as $o)
                    @if($h->id_produksi_header==$o->id_produksi_header)
                    <tr>
                        <td>Nama Produk</td>
                        <td>{{$o->nama_item}}</td>
                    </tr>

                    <tr>
                        <td>Jumlah Unit yang Dibuat</td>
                        <td>{{$o->kuantitas}}</td>
                    </tr>
                    @php $kuantitas=$kuantitas+$o->kuantitas;@endphp
                    @endif
                    @endforeach

                    <tr>
                        <td>Tanggal Mulai</td>
                        <td>{{$h->tgl_produksi}}</td>
                    </tr>

                    <tr>
                        <td>Tanggal Selesai</td>
                        <td>{{$h->tgl_selesai}}</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                <tr>
                                    <td align="center">Biaya yang Dikeluarkan dan Dibebankan</td>
                                </tr>

                                <tr>
                                    <td>
                                        <table width="100%">
                                            <tr>
                                                <td align="center" width="33%" valign="top">
                                                    <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                                        <tr>
                                                            <td align="center" width="50%">Bahan Baku</td>
                                                            <td align="center">Biaya</td>
                                                        </tr>
                                                        @php $sum_bbb=0;@endphp
                                                        @foreach($bbb as $a)
                                                        @if($h->id_produksi_header==$a->id_produksi_header)
                                                        <tr>
                                                            <td >{{$a->nama_item}}</td>
                                                            <td align="right">{{$a->total_bbb}}</td>
                                                        </tr>
                                                        @php $sum_bbb=$sum_bbb+$a->total_bbb;@endphp
                                                        @endif
                                                        @endforeach
                                                    </table> 
                                                </td>

                                                <td align="center" width="33%" valign="top">
                                                    <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                                        <tr>
                                                            <td align="center" width="50%">Tenaga Kerja</td>
                                                            <td align="center">Biaya</td>
                                                        </tr>
                                                        @php $sum_btk=0;@endphp
                                                        @foreach($btk as $b)
                                                        @if($h->id_produksi_header==$b->id_produksi_header)
                                                        <tr>
                                                            <td >{{$b->nama_pekerja}}</td>
                                                            <td align="right">{{$b->total_biaya_tenaga_kerja}}</td>
                                                        </tr>
                                                        @php $sum_btk=$sum_btk+$b->total_biaya_tenaga_kerja;@endphp
                                                        @endif
                                                        @endforeach
                                                    </table> 
                                                </td>

                                                <td align="center" width="34%" valign="top">
                                                    <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                                        <tr>
                                                            <td align="center" width="50%">Overhead Pabrik</td>
                                                            <td align="center">Biaya</td>
                                                        </tr>
                                                        @php $sum_bop=0;@endphp
                                                        @foreach($bop as $c)
                                                        @if($h->id_produksi_header==$c->id_produksi_header)
                                                        <tr>
                                                            <td >{{$c->nama_item}}</td>
                                                            <td align="right">{{$c->total_bop}}</td>
                                                        </tr>
                                                        @php $sum_bop=$sum_bop+$c->total_bop;@endphp
                                                        @endif
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td width="33%">
                                                    <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                                        <tr>
                                                            <td width="50%">Total</td>
                                                            <td align="right">{{$sum_bbb}}</td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td width="33%">
                                                    <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                                        <tr>
                                                            <td width="50%">Total</td>
                                                            <td align="right">{{$sum_btk}}</td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <td width="33%">
                                                    <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                                        <tr>
                                                            <td width="50%">Total</td>
                                                            <td align="right">{{$sum_bop}}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center">Ringkasan Total Biaya dan Biaya Per Unit</td>
                                </tr>

                                <tr>
                                    <td>
                                        <table class="table table-bordered table-responsive dt-responsive nowrap" id="responsive-datatable" width="100%">
                                            <tr>
                                                <td align="center"></td>
                                                <td align="center" width="16%">Total Biaya</td>
                                                <td align="center" width="17%">Biaya Per Unit</td>
                                            </tr>

                                            <tr>
                                                <td align="left">Biaya Bahan Baku</td>
                                                <td align="right" width="16%">{{$sum_bbb}}</td>
                                                <td align="right" width="17%">{{$sum_bbb/$kuantitas}}</td>
                                            </tr>

                                            <tr>
                                                <td align="left">Biaya Tenaga Kerja</td>
                                                <td align="right" width="16%">{{$sum_btk}}</td>
                                                <td align="right" width="17%">{{$sum_btk/$kuantitas}}</td>
                                            </tr>

                                            <tr>
                                                <td align="left">Biaya Overhead Pabrik</td>
                                                <td align="right" width="16%">{{$sum_bop}}</td>
                                                <td align="right" width="17%">{{$sum_bop/$kuantitas}}</td>
                                            </tr>

                                            <tr>
                                                <td align="left">Biaya Barang Jadi yang Diproduksi</td>
                                                <td align="right" width="16%">{{$sum_bbb+$sum_btk+$sum_bop}}</td>
                                                <td align="right" width="17%">{{($sum_bbb+$sum_btk+$sum_bop)/$kuantitas}}</td>
                                            </tr>
                                        </table>    
                                    </td>
                                </tr>
                            </table>
                        </td> 
                    </tr>

                    
                </table>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>
