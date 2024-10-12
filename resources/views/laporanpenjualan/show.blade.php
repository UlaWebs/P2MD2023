<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>

                <a href="{{ route('laporanpenjualan') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>

            </div>
            @if($jenis=='k')
            <div class="card-body">
                @foreach ($data_header as $header)
                <div class="table-responsive">
                    <table id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td><strong> Tanggal </strong> </td>
                            <td><strong>{{date_format(date_create($header->tgl_pengiriman), "d F Y") }} </strong> </td>
                            <td><strong> Pelanggan </strong> </td>
                            <td><strong>{{$header->nama_pelanggan}} </strong> </td>
                        </tr>
                        <tr>
                            <td><strong> No. Referensi </strong> </td>
                            <td><strong>{{$header->no_pengiriman}} </strong> </td>
                            <td><strong> Keterangan </strong> </td>
                            <td><strong>{{$header->keterangan}} </strong> </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table width="100%" class="table table-bordered">
                                    <tr>
                                        <th>Item</th>
                                        <th width="15%">Dikirim</th>
                                        <th width="15%">Dipesan</th>
                                        <th width="15%">Harga Satuan</th>
                                        <th width="15%">Subtotal</th>
                                    </tr>
                                    @php
                                    $total_penjualan=0;
                                    @endphp
                                    @foreach($data_detail as $detail)
                                    @if($header->id_pengiriman_header==$detail->id_pengiriman_header)
                                    <tr>
                                        <td>{{$detail->nama_item}}</td>
                                        <td align="right">{{number_format($detail->kuantitas, 2, ',','.')}}</td>
                                        <td align="right">{{number_format($detail->kuantitas_pengiriman, 2, ',','.')}}
                                        </td>
                                        <td align="right">{{number_format($detail->harga_satuan, 2, ',','.')}}</td>
                                        <td align="right">{{number_format(($detail->harga_satuan) * $detail->kuantitas,
                                            2, ',','.')}}</td>
                                    </tr>
                                    @php
                                    $total_penjualan=$total_penjualan + (($detail->harga_satuan) * $detail->kuantitas);
                                    @endphp
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="4"> Total </td>
                                        <td align="right"> {{number_format($total_penjualan, 2, ',','.')}} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"> Pembayaran</td>
                                        <td align="right"> {{number_format($header->total_pelunasan, 2, ',','.')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"> Saldo</td>
                                        <td align="right"> {{number_format($total_penjualan-$header->total_pelunasan, 2,
                                            ',','.')}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                @endforeach
            </div>
            @else
            <div class="card-body">
                @foreach ($data_header as $header)
                <div class="table-responsive">
                    <table id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td><strong> Tanggal </strong> </td>
                            <td><strong>{{date_format(date_create($header->tgl_penjualan), "d F Y") }} </strong> </td>
                            <td><strong> Pelanggan </strong> </td>
                            <td><strong>{{$header->nama_pelanggan}} </strong> </td>
                        </tr>
                        <tr>
                            <td><strong> No. Referensi </strong> </td>
                            <td><strong>{{$header->no_penjualan_header}} </strong> </td>
                            <td><strong> Keterangan </strong> </td>
                            <td><strong>{{$header->keterangan}} </strong> </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table width="100%" class="table table-bordered">
                                    <tr>
                                        <th>Item</th>
                                        <th width="15%">Kuantitas</th>
                                        <th width="15%">Harga Satuan</th>
                                        <th width="15%">Subtotal</th>
                                    </tr>
                                    @php
                                    $total_penjualan=0;
                                    @endphp
                                    @foreach($data_detail as $detail)
                                    @if($header->id_penjualan_tunai_header==$detail->id_penjualan_tunai_header)
                                    <tr>
                                        <td>{{$detail->nama_item}}</td>
                                        <td align="right">{{number_format($detail->kuantitas, 2, ',','.')}}</td>
                                        <td align="right">{{number_format($detail->harga_jual, 2, ',','.')}}</td>
                                        <td align="right">{{number_format(($detail->harga_jual) * $detail->kuantitas, 2,
                                            ',','.')}}</td>
                                    </tr>
                                    @php
                                    $total_penjualan=$total_penjualan + (($detail->harga_jual) * $detail->kuantitas);
                                    @endphp
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="3"> Total </td>
                                        <td align="right"> {{number_format($total_penjualan, 2, ',','.')}} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"> Pembayaran</td>
                                        <td align="right"> {{number_format($total_penjualan, 2, ',','.')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"> Saldo</td>
                                        <td align="right"> {{number_format($total_penjualan-$total_penjualan, 2,
                                            ',','.')}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
</x-app-layout>