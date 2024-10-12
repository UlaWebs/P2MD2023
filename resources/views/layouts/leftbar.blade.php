<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">

            <img src="{{ asset('src/assets/images/users/user-1.jpg') }}" alt="user-img" title="UMKM WONDERFUL ROTAN"
                class="rounded-circle img-thumbnail avatar-md">
            <div class="dropdown">
                <a href="#" class="user-name dropdown-toggle h5 mt-2 mb-1 d-block" data-bs-toggle="dropdown"
                    aria-expanded="false">{{ Auth::user()->name }}</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>

            <p class="text-muted left-user-info">Admin</p>

            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="#" class="text-muted left-user-info">
                        <i class="mdi mdi-cog"></i>
                    </a>
                </li>

                <li class="list-inline-item">
                    <a href="#">
                        <i class="mdi mdi-power"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                {{-- Menu Master Data --}}
                <li>
                    <a href="#masterdata" data-bs-toggle="collapse">
                        <i class="mdi mdi-email-outline"></i>
                        <span> Master Data</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="masterdata">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('akun') }}">Akun</a>
                            </li>
                            <li>
                                <a href="{{ route('akundefault') }}">Akun Default</a>
                            </li>
                            <li>
                                <a href="{{ route('aset') }}">Aset</a>
                            </li>
                            <li>
                                <a href="{{ route('item') }}">Barang</a>
                            </li>
                            <li>
                                <a href="{{ route('biaya') }}">Biaya</a>
                            </li>
                            <li>
                                <a href="{{ route('jenisakun') }}">Jenis Akun</a>
                            </li>
                            <li>
                                <a href="{{ route('pekerja') }}">Jenis Upah</a>
                            </li>
                            <li>
                                <a href="{{ route('kas') }}">Kas</a>
                            </li>
                            <li>
                                <a href="{{ route('kategori-aset') }}">Kategori Aset</a>
                            </li>
                            <li>
                                <a href="{{ route('pelanggan') }}">Pelanggan</a>
                            </li>
                            <li>
                                <a href="{{ route('supplier') }}">Supplier</a>
                            </li>
                            <li>
                                <a href="{{ route('vendor') }}">Vendor</a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Menu Transaksi --}}
                <li>
                    <a href="#transaksi" data-bs-toggle="collapse">
                        <i class="mdi mdi-email-outline"></i>
                        <span> Transaksi</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="transaksi">
                        <ul class="nav-second-level">
                            <li>
                                <b>Saldo</b>
                            <li>
                                <a href="{{ route('saldoawal') }}">Saldo Awal Akun</a>
                            </li>
                </li>

                <li>
                    <b>Manajemen Aset</b>
                <li>
                    <a href="{{ route('perolehanaset') }}">Perolehan Aset</a>
                </li>
                <li>
                    <a href="{{ route('inventaris') }}">Inventaris Aset</a>
                </li>
                <li>
                    <a href="{{ route('depresiasiaset') }}">Depresiasi Aset</a>
                </li>
                <li>
                    <a href="{{ route('pelunasanaset') }}">Pelunasan Aset</a>
                </li>
                <li>
                    <a href="{{ route('pelepasanaset') }}">Pelepasan Aset</a>
                </li>
                </li>

                <li>
                    <b>Pembelian Langsung</b>
                <li>
                    <a href="{{ route('pembeliantunai') }}">Pembelian Tunai</a>
                </li>
                </li>
                <li><b>Pembelian Tidak Langsung</b>
                <li>
                    <a href="{{ route('pemesanan') }}">Pemesanan Bahan</a>
                </li>
                <li>
                    <a href="{{ route('penerimaan') }}">Penerimaan Bahan</a>
                </li>
                <li>
                    <a href="{{ route('pelunasanpembelian.index') }}">Pelunasan</a>
                </li>
                </li>
                <li><b>Produksi</b>
                <li>
                    <a href="{{ route('produksi') }}">Produksi Bahan</a>
                </li>
                <li>
                    <a href="{{ route('pembayaranupah') }}">Pembayaran Upah</a>
                </li>
                </li>
                <li><b>Penjualan Langsung</b>
                <li>
                    <a href="{{ route('penjualantunai') }}">Penjualan Tunai</a>
                </li>
                </li>
                <li><b>Penjualan Tidak Langsung</b>
                <li>
                    <a href="{{ route('pemesananpenjualan') }}">Pemesanan Barang</a>
                </li>
                <li>
                    <a href="{{ route('pengiriman') }}">Pengiriman Barang</a>
                </li>
                <li>
                    <a href="{{ route('pelunasankredit') }}">Pelunasan</a>
                </li>
                </li>
                <li><b>Biaya Lain-lain</b>
                <li>
                    <a href="{{ route('pengeluaranbiaya') }}">Pengeluaran Biaya</a>
                </li>
                </li>
                <li><b>Persediaan</b>
                <li>
                    <a href="{{ route('persediaan') }}">Persediaan Bahan dan Barang</a>
                </li>
                </li>
            </ul>
        </div>
        </li>

        {{-- Menu Laporan --}}
        <li>
            <a href="#laporan" data-bs-toggle="collapse">
                <i class="mdi mdi-clipboard-outline"></i>
                <span> Laporan </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="laporan">
                <ul class="nav-second-level">
                    <li>
                        <a href="{{ route('laporanpembelian') }}">Laporan Pembelian</a>
                    </li>
                    <li>
                        <a href="{{ route('laporanpenjualan') }}">Laporan Penjualan</a>
                    </li>
                    <li>
                        <a href="{{ route('laporanproduksi') }}">Laporan Produksi</a>
                    </li>
                    <li>
                        <a href="{{ route('laporanpenyusutan') }}">Laporan Penyusutan</a>
                    </li>
                    <li>
                        <a href="{{ route('laporanjoborder') }}">Job Order Cost Sheet</a>
                    </li>
                    <li>
                        <a href="{{ route('jurnal') }}">Jurnal</a>
                    </li>
                    <li>
                        <a href="{{ route('penutup') }}">Jurnal Penutup</a>
                    </li>
                    <li>
                        <a href="{{ route('ledger') }}">Buku Besar</a>
                    </li>
                    <li>
                        <a href="{{ route('labarugi') }}">Laba / Rugi</a>
                    </li>
                    <li>
                        <a href="{{ route('neraca') }}">Neraca</a>
                    </li>
                    <li>
                        <a href="{{ route('laporancashflow') }}">Laporan Arus Kas</a>
                    </li>
                    <li>
                        <a href="{{ route('neracasaldo') }}">Neraca Saldo</a>
                    </li>
                    <li>
                        <a href="{{ route('perubahanmodal') }}">Perubahan Modal</a>
                    </li>
                </ul>
            </div>
        </li>
        </ul>
    </div>
    </li>
    </ul>


</div>
<!-- End Sidebar -->

<div class="clearfix"></div>

</div>
<!-- Sidebar -left -->

</div>