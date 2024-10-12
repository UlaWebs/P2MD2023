<x-app-layout>

    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $title }}</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">Data {{ $title }}</h4>

                <a href="{{ route('laporanpembelian') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>

            </div>
            @if($jenis=='k')
            <div class="card-body">
                @foreach ($data_header as $header)
                    <div class="table-responsive">
                        <table id="dataTable" width="100%" cellspacing="0">
                            <tr>
                                <td><strong> Tanggal  </strong> </td>
                                <td><strong>{{date_format(date_create($header->tgl_penerimaan), "d F Y") }}  </strong> </td>
                                <td><strong> Pemasok  </strong> </td>
                                <td><strong>{{$header->nama_supplier}}  </strong> </td>
                            </tr>
                            <tr>
                                <td><strong> No. Referensi  </strong> </td>
                                <td><strong>{{$header->no_penerimaan}}  </strong> </td>
                                <td><strong> Keterangan  </strong> </td>
                                <td><strong>{{$header->keterangan}}  </strong> </td>
                            </tr>
                            <tr>
                                <td  colspan="4"> 
                                    <table width="100%" class="table table-bordered"> 
                                    <tr>
                                        <th>Item</th>
                                        <th width="15%">Diterima</th>
                                        <th width="15%">Dipesan</th>
                                        <th width="15%">Harga Satuan</th>
                                        <th width="15%">Subtotal</th>
                                    </tr>
                                    @php 
                                        $total_pembelian=0;
                                    @endphp
                                    @foreach($data_detail as $detail)
                                        @if($header->id_penerimaan_header==$detail->id_penerimaan_header)
                                            <tr>
                                                <td>{{$detail->nama_item}}</td>
                                                <td align="right">{{number_format($detail->kuantitas, 2, ',','.')}}</td>
                                                <td align="right">{{number_format($detail->kuantitas_pemesanan, 2, ',','.')}}</td>
                                                <td align="right">{{number_format($detail->base_price + $detail->ppn, 2, ',','.')}}</td>
                                                <td align="right">{{number_format(($detail->base_price + $detail->ppn) * $detail->kuantitas, 2, ',','.')}}</td>
                                            </tr>
                                            @php
                                            $total_pembelian=$total_pembelian + (($detail->base_price + $detail->ppn) * $detail->kuantitas);
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="4"> Total </td>
                                        <td align="right"> {{number_format($total_pembelian, 2, ',','.')}} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"> Uang Muka Pembelian</td>
                                        <td align="right"> {{number_format($header->nominal_uang_muka, 2, ',','.')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"> Pembayaran</td>
                                        <td align="right"> {{number_format($header->total_pelunasan, 2, ',','.')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"> Saldo</td>
                                        <td align="right"> {{number_format($total_pembelian-$header->total_pelunasan-$header->nominal_uang_muka, 2, ',','.')}}</td>
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
                                <td><strong> Tanggal  </strong> </td>
                                <td><strong>{{date_format(date_create($header->tanggal_pembelian_tunai), "d F Y") }}  </strong> </td>
                                <td><strong> Pemasok  </strong> </td>
                                <td><strong>{{$header->nama_supplier}}  </strong> </td>
                            </tr>
                            <tr>
                                <td><strong> No. Referensi  </strong> </td>
                                <td><strong>{{$header->no_pembelian_tunai}}  </strong> </td>
                                <td><strong> Keterangan  </strong> </td>
                                <td><strong>{{$header->keterangan}}  </strong> </td>
                            </tr>
                            <tr>
                                <td  colspan="4"> 
                                    <table width="100%" class="table table-bordered"> 
                                    <tr>
                                        <th>Item</th>
                                        <th width="15%">Kuantitas</th>
                                        <th width="15%">Harga Satuan</th>
                                        <th width="15%">Subtotal</th>
                                    </tr>
                                    @php 
                                        $total_pembelian=0;
                                    @endphp
                                    @foreach($data_detail as $detail)
                                        @if($header->id_pembelian_tunai_header==$detail->id_pembelian_tunai_header)
                                            <tr>
                                                <td>{{$detail->nama_item}}</td>
                                                <td align="right">{{number_format($detail->kuantitas, 2, ',','.')}}</td>
                                                <td align="right">{{number_format($detail->base_price + $detail->ppn, 2, ',','.')}}</td>
                                                <td align="right">{{number_format(($detail->base_price + $detail->ppn) * $detail->kuantitas, 2, ',','.')}}</td>
                                            </tr>
                                            @php
                                            $total_pembelian=$total_pembelian + (($detail->base_price + $detail->ppn) * $detail->kuantitas);
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="3"> Total </td>
                                        <td align="right"> {{number_format($total_pembelian, 2, ',','.')}} </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"> Pembayaran</td>
                                        <td align="right"> {{number_format($total_pembelian, 2, ',','.')}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"> Saldo</td>
                                        <td align="right"> {{number_format($total_pembelian-$total_pembelian, 2, ',','.')}}</td>
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
