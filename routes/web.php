<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AkundefaultController;
use App\Http\Controllers\AsetController;

// Master Data
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\DepresiasiasetController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\JenisakunController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\KategoriAsetController;
use App\Http\Controllers\LabarugiController;
use App\Http\Controllers\LaporanpenyusutanController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\NeracasaldoController;
use App\Http\Controllers\LaporanpembelianController;
use App\Http\Controllers\LaporanpenjualanController;
use App\Http\Controllers\LaporanproduksiController;
use App\Http\Controllers\LaporanjoborderController;
use App\Http\Controllers\LaporancashflowController;
use App\Http\Controllers\PekerjaController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelepasanasetController;
use App\Http\Controllers\PelunasanasetController;
use App\Http\Controllers\PengeluaranbiayaController;

// Transaksi
use App\Http\Controllers\PelunasankreditController;
use App\Http\Controllers\PelunasanpembelianController;
use App\Http\Controllers\PembayaranupahController;
use App\Http\Controllers\PembeliantunaiController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PemesananpenjualanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PenjualantunaiController;
use App\Http\Controllers\PenutupController;
use App\Http\Controllers\PerolehanasetController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PerubahanmodalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaldoawalController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin', function () {
    return view('admin/dashboard2');
});

Route::middleware('auth')->group(function () {
        // Route Akun
        Route::controller(AkunController::class)->group(function () {
            Route::get('/akun', 'index')->name('akun');
            Route::get('/akun/create', 'create')->name('akun.create');
            Route::post('/akun/store', 'store')->name('akun.store');
            Route::get('/akun/edit/{id}', 'edit')->name('akun.edit');
            Route::post('/akun/update', 'update')->name('akun.update');
            Route::get('/akun/destroy/{id}', 'destroy');
        });
        // End Route Akun

        // Route Aset
        Route::controller(AsetController::class)->group(function () {
            Route::get('/aset', 'index')->name('aset');
            Route::get('/aset/create', 'create')->name('aset.create');
            Route::post('/aset/store', 'store')->name('aset.store');
            Route::get('/aset/edit/{id}', 'edit')->name('aset.edit');
            Route::post('/aset/update', 'update')->name('aset.update');
            Route::get('/aset/destroy/{id}', 'destroy');
        });
        // End Route Aset

        // Route Kategori Aset
        Route::controller(KategoriAsetController::class)->group(function () {
            Route::get('/kategori-aset', 'index')->name('kategori-aset');
            Route::get('/kategori-aset/create', 'create')->name('kategori-aset.create');
            Route::post('/kategori-aset/store', 'store')->name('kategori-aset.store');
            Route::get('/kategori-aset/edit/{id}', 'edit')->name('kategori-aset.edit');
            Route::post('/kategori-aset/update', 'update')->name('kategori-aset.update');
            Route::get('/kategori-aset/destroy/{id}', 'destroy');
        });
        // End Route Kategori Aset

        // Route Jenis Akun
        Route::get('/jenisakun', [JenisakunController::class, 'index'])->name('jenisakun');

        // Route Kas
        Route::controller(KasController::class)->group(function () {
            Route::get('/kas', 'index')->name('kas');
            Route::get('/kas/create', 'create')->name('kas.create');
            Route::post('/kas/store', 'store')->name('kas.store');
            Route::get('/kas/edit/{id}', 'edit')->name('kas.edit');
            Route::post('/kas/update', 'update')->name('kas.update');
            Route::get('/kas/destroy/{id}', 'destroy');
        });
        // End Route Kas

        // Route Supplier
        Route::controller(SupplierController::class)->group(function () {
            Route::get('/supplier', 'index')->name('supplier');
            Route::get('/supplier/create', 'create')->name('supplier.create');
            Route::post('/supplier/store', 'store')->name('supplier.store');
            Route::get('/supplier/edit/{id}', 'edit')->name('supplier.edit');
            Route::post('/supplier/update', 'update')->name('supplier.update');
            Route::get('/supplier/destroy/{id}', 'destroy');
        });
        // End Route Supplier

        // Route Vendor
        Route::controller(VendorController::class)->group(function () {
            Route::get('/vendor', 'index')->name('vendor');
            Route::get('/vendor/create', 'create')->name('vendor.create');
            Route::post('/vendor/store', 'store')->name('vendor.store');
            Route::get('/vendor/edit/{id}', 'edit')->name('vendor.edit');
            Route::post('/vendor/update', 'update')->name('vendor.update');
            Route::get('/vendor/destroy/{id}', 'destroy');
        });
        // End Route Vendor

        // Route Pekerja
        Route::controller(PekerjaController::class)->group(function () {
            Route::get('/pekerja', 'index')->name('pekerja');
            Route::get('/pekerja/create', 'create')->name('pekerja.create');
            Route::post('/pekerja/store', 'store')->name('pekerja.store');
            Route::get('/pekerja/edit/{id}', 'edit')->name('pekerja.edit');
            Route::post('/pekerja/update', 'update')->name('pekerja.update');
            Route::get('/pekerja/destroy/{id}', 'destroy');
        });
        // End Route Pekerja

        // Route Pelanggan
        Route::controller(PelangganController::class)->group(function () {
            Route::get('/pelanggan', 'index')->name('pelanggan');
            Route::get('/pelanggan/create', 'create')->name('pelanggan.create');
            Route::post('/pelanggan/store', 'store')->name('pelanggan.store');
            Route::get('/pelanggan/edit/{id}', 'edit')->name('pelanggan.edit');
            Route::post('/pelanggan/update', 'update')->name('pelanggan.update');
            Route::get('/pelanggan/destroy/{id}', 'destroy');
        });
        // End Route Pelanggan

        // Route Biaya
        Route::controller(BiayaController::class)->group(function () {
            Route::get('/biaya', 'index')->name('biaya');
            Route::get('/biaya/create', 'create')->name('biaya.create');
            Route::post('/biaya/store', 'store')->name('biaya.store');
            Route::get('/biaya/edit/{id}', 'edit')->name('biaya.edit');
            Route::post('/biaya/update', 'update')->name('biaya.update');
            Route::get('/biaya/destroy/{id}', 'destroy');
        });
        // End Route Biaya

        // Route Item / Barang
        Route::controller(ItemsController::class)->group(function () {
            Route::get('/item', 'index')->name('item');
            Route::get('/item/create', 'create')->name('item.create');
            Route::post('/item/store', 'store')->name('item.store');
            Route::get('/item/edit/{id}', 'edit')->name('item.edit');
            Route::post('/item/update', 'update')->name('item.update');
            Route::get('/item/destroy/{id}', 'destroy');
            Route::get('/item/detail/{id}', 'detail')->name('item.detail');
            Route::post('/item/storedetail', 'storedetail')->name('item.storedetail');
            Route::get('/item/destroydetail/{id}', 'destroydetail');
            Route::get('/item/editdetail/{id}', 'editdetail')->name('item.editdetail');
            Route::post('/item/updatedetail', 'updatedetail')->name('item.updatedetail');

            Route::get('/item/detail2/{id}', 'detail2')->name('item.detail2');
            Route::post('/item/storedetail21', 'storedetail21')->name('item.storedetail21');
            Route::get('/item/destroydetail21/{id}', 'destroydetail21');
            Route::get('/item/editdetail21/{id}', 'editdetail21')->name('item.editdetail21');
            Route::post('/item/updatedetail21', 'updatedetail21')->name('item.updatedetail21');

            Route::post('/item/storedetail22', 'storedetail22')->name('item.storedetail22');
            Route::get('/item/destroydetail22/{id}', 'destroydetail22');
            Route::get('/item/editdetail22/{id}', 'editdetail22')->name('item.editdetail22');
            Route::post('/item/updatedetail22', 'updatedetail22')->name('item.updatedetail22');

            Route::post('/item/storedetail23', 'storedetail23')->name('item.storedetail23');
            Route::get('/item/destroydetail23/{id}', 'destroydetail23');
            Route::get('/item/editdetail23/{id}', 'editdetail23')->name('item.editdetail23');
            Route::post('/item/updatedetail23', 'updatedetail23')->name('item.updatedetail23');

            Route::get('/item/getsatuan/{id}', 'getsatuan')->name('item.getsatuan');
        });
        // End Route Item

        // Route Pemesanan
        Route::controller(PemesananController::class)->group(function () {
            // Pemesanan
            Route::get('/pemesanan', 'index')->name('pemesanan');
            Route::get('/pemesanan/create', 'create')->name('pemesanan.create');
            Route::post('/pemesanan/store', 'store')->name('pemesanan.store');
            Route::get('/pemesanan/edit/{id}', 'edit')->name('pemesanan.edit');
            Route::post('/pemesanan/update', 'update')->name('pemesanan.update');
            Route::post('/pemesanan/updateuangmuka', 'updateuangmuka')->name('pemesanan.updateuangmuka');
            Route::get('/pemesanan/destroy/{id}', 'destroy');
            Route::get('/pemesanan/jurnal/{id}', 'jurnal');
            Route::get('/pemesanan/jurnaldetail/{id}', 'jurnaldetail')->name('pemesanan.jurnaldetail');

            // Pemesanan Detail
            Route::get('/pemesanan/detail/{id}', 'detail')->name('pemesanan.detail');
            Route::get('/pemesanan/createdetail', 'createdetail')->name('pemesanan.createdetail');
            Route::post('/pemesanan/storedetail', 'storedetail')->name('pemesanan.storedetail');
            Route::get('/pemesanan/editdetail/{id}', 'editdetail')->name('pemesanan.editdetail');
            Route::post('/pemesanan/updatedetail', 'updatedetail')->name('pemesanan.updatedetail');
            Route::get('/pemesanan/destroydetail/{id}', 'destroydetail');
            Route::get('/pemesanan/getdetailbyitem/{id_pemesanan_header}/{id_item}', 'getdetailbyitem')->name('pemesanan.getdetailbyitem');
            Route::get('/pemesanan/updatestatus/{id}/{id_penerimaan}', 'updatestatus')->name('pemesanan.updatestatus');

            Route::get('/pemesanan/excel/{id}', 'excel')->name('pemesanan.excel');
        });
        // End Route Pemesanan

        // Route Penerimaan
        Route::controller(PenerimaanController::class)->group(function () {
            // Penerimaan
            Route::get('/penerimaan', 'index')->name('penerimaan');
            Route::get('/penerimaan/create', 'create')->name('penerimaan.create');
            Route::post('/penerimaan/store', 'store')->name('penerimaan.store');
            Route::get('/penerimaan/edit/{id}', 'edit')->name('penerimaan.edit');
            Route::post('/penerimaan/update', 'update')->name('penerimaan.update');
            Route::get('/penerimaan/destroy/{id}', 'destroy');
            Route::get('/penerimaan/jurnal/{id}', 'jurnal');
            Route::get('/penerimaan/jurnaldetail/{id}', 'jurnaldetail')->name('penerimaan.jurnaldetail');

            // Penerimaan Detail
            Route::get('/penerimaan/detail/{id}', 'detail')->name('penerimaan.detail');
            Route::get('/penerimaan/createdetail', 'createdetail')->name('penerimaan.createdetail');
            Route::post('/penerimaan/storedetail', 'storedetail')->name('penerimaan.storedetail');
            Route::get('/penerimaan/editdetail/{id}', 'editdetail')->name('penerimaan.editdetail');
            Route::post('/penerimaan/updatedetail', 'updatedetail')->name('penerimaan.updatedetail');
            Route::get('/penerimaan/destroydetail/{id}', 'destroydetail');
        });
        // End Route Penerimaan

        // Route Persediaan
        Route::get('/persediaan', [PersediaanController::class, 'index'])->name('persediaan');
        Route::get('/persediaan/excel/{id}', [PersediaanController::class, 'excel'])->name('persediaan.excel');
        Route::get('/persediaan/detail/{id}', [PersediaanController::class, 'detail'])->name('persediaan.detail');
        // End Route Persediaan

        // Route Pelunasan Pembelian
        Route::controller(PelunasanpembelianController::class)->group(function () {
            Route::get('/pelunasanpembelian', 'index')->name('pelunasanpembelian.index');
            Route::get('/pelunasanpembelian/create', 'create')->name('pelunasanpembelian.create');
            Route::post('/pelunasanpembelian/store', 'store')->name('pelunasanpembelian.store');
            Route::get('/pelunasanpembelian/edit/{id}', 'edit')->name('pelunasanpembelian.edit');
            Route::post('/pelunasanpembelian/update', 'update')->name('pelunasanpembelian.update');
            Route::get('/pelunasanpembelian/destroy/{id}', 'destroy')->name('pelunasanpembelian.destroy');
            Route::get('/pelunasanpembelian/jurnal/{id}', 'jurnal');
            Route::get('/pelunasanpembelian/jurnaldetail/{id}', 'jurnaldetail')->name('pelunasanpembelian.jurnaldetail');
        });
        // End Route Pelunasan Pembelian

        // Route Pembelian Tunai
        Route::controller(PembeliantunaiController::class)->group(function () {
            // Pembelian Tunai
            Route::get('/pembeliantunai', 'index')->name('pembeliantunai');
            Route::get('/pembeliantunai/create', 'create')->name('pembeliantunai.create');
            Route::post('/pembeliantunai/store', 'store')->name('pembeliantunai.store');
            Route::get('/pembeliantunai/edit/{id}', 'edit')->name('pembeliantunai.edit');
            Route::post('/pembeliantunai/update', 'update')->name('pembeliantunai.update');
            Route::get('/pembeliantunai/destroy/{id}', 'destroy')->name('pembeliantunai.destroy');
            Route::get('/pembeliantunai/jurnal/{id}', 'jurnal');
            Route::get('/pembeliantunai/jurnaldetail/{id}', 'jurnaldetail')->name('pembeliantunai.jurnaldetail');

            // Pembelian Tunai Detail
            Route::get('/pembeliantunai/detail/{id}', 'detail')->name('pembeliantunai.detail');
            Route::get('/pembeliantunai/createdetail', 'createdetail')->name('pembeliantunai.createdetail');
            Route::post('/pembeliantunai/storedetail', 'storedetail')->name('pembeliantunai.storedetail');
            Route::get('/pembeliantunai/editdetail/{id}', 'editdetail')->name('pembeliantunai.editdetail');
            Route::post('/pembeliantunai/updatedetail', 'updatedetail')->name('pembeliantunai.updatedetail');
            Route::get('/pembeliantunai/destroydetail/{id}', 'destroydetail');
        });
        // End Route Pembelian Tunai

        // Route Pelunasan Pembelian
        Route::controller(PelunasankreditController::class)->group(function () {
            Route::get('/pelunasankredit', 'index')->name('pelunasankredit');
            Route::get('/pelunasankredit/create', 'create')->name('pelunasankredit.create');
            Route::post('/pelunasankredit/store', 'store')->name('pelunasankredit.store');
            Route::get('/pelunasankredit/edit/{id}', 'edit')->name('pelunasankredit.edit');
            Route::post('/pelunasankredit/update', 'update')->name('pelunasankredit.update');
            Route::get('/pelunasankredit/destroy/{id}', 'destroy')->name('pelunasanpenkredit.destroy');

            Route::get('/pelunasankredit/jurnal/{id}', 'jurnal');
            Route::get('/pelunasankredit/jurnaldetail/{id}', 'jurnaldetail')->name('pelunasankredit.jurnaldetail');
        });
        // End Route Pelunasan Pembelian

        //Produksi
        Route::controller(ProduksiController::class)->group(function () {
            Route::get('/produksi', 'index')->name('produksi');
            Route::get('/produksi/create', 'create')->name('produksi.create');
            Route::post('/produksi/store', 'store')->name('produksi.store');
            Route::get('/produksi/edit/{id}', 'edit')->name('produksi.edit');
            Route::post('/produksi/update', 'update')->name('produksi.update');
            Route::get('/produksi/destroy/{id}', 'destroy')->name('produksi.destroy');
            Route::get('/produksi/detail/{id}', 'detail')->name('produksi.detail');
            Route::get('/produksi/jurnal/{id}', 'jurnal');
            Route::get('/produksi/jurnaldetail/{id}', 'jurnaldetail')->name('produksi.jurnaldetail');

            Route::post('/produksi/storedetailoutput', 'storedetailoutput')->name('produksi.storedetailoutput');
            Route::post('/produksi/storedetailbbb', 'storedetailbbb')->name('produksi.storedetailbbb');
            Route::post('/produksi/storedetailbop', 'storedetailbop')->name('produksi.storedetailbop');
            Route::post('/produksi/storedetailbtk', 'storedetailbtk')->name('produksi.storedetailbtk');

            Route::get('/produksi/editdetailoutput/{id}', 'editdetailoutput')->name('produksi.editdetailoutput');
            Route::get('/produksi/editdetailbbb/{id}', 'editdetailbbb')->name('produksi.editdetailbbb');
            Route::get('/produksi/editdetailbop/{id}', 'editdetailbop')->name('produksi.editdetailbop');
            Route::get('/produksi/editdetailbtk/{id}', 'editdetailbtk')->name('produksi.editdetailbtk');

            Route::post('/produksi/updatedetailoutput', 'updatedetailoutput')->name('produksi.updatedetailoutput');
            Route::post('/produksi/updatedetailbbb', 'updatedetailbbb')->name('produksi.updatedetailbbb');
            Route::post('/produksi/updatedetailbop', 'updatedetailbop')->name('produksi.updatedetailbop');
            Route::post('/produksi/updatedetailbtk', 'updatedetailbtk')->name('produksi.updatedetailbtk');

            Route::get('/produksi/destroydetailoutput/{id}', 'destroydetailoutput');
            Route::get('/produksi/destroydetailbbb/{id}', 'destroydetailbbb');
            Route::get('/produksi/destroydetailbop/{id}', 'destroydetailbop');
            Route::get('/produksi/destroydetailbtk/{id}', 'destroydetailbtk');
            Route::get('/produksi/finishproduksi/{id}', 'finishproduksi');
        });
        //End Produksi

        // Pemesanan Penjualan
        Route::controller(PemesananpenjualanController::class)->group(function () {
            Route::get('/pemesananpenjualan', 'index')->name('pemesananpenjualan');
            Route::get('/pemesananpenjualan/create', 'create')->name('pemesananpenjualan.create');
            Route::post('/pemesananpenjualan/store', 'store')->name('pemesananpenjualan.store');
            Route::get('/pemesananpenjualan/edit/{id}', 'edit')->name('pemesananpenjualan.edit');
            Route::post('/pemesananpenjualan/update', 'update')->name('pemesananpenjualan.update');
            Route::get('/pemesananpenjualan/destroy/{id}', 'destroy');
            Route::get('/pemesananpenjualan/jurnal/{id}', 'jurnal');
            Route::get('/pemesananpenjualan/jurnaldetail/{id}', 'jurnaldetail')->name('pemesananpenjualan.jurnaldetail');

            // Pemesanan Penjualan Detail
            Route::get('/pemesananpenjualan/detail/{id}', 'detail')->name('pemesananpenjualan.detail');
            Route::get('/pemesananpenjualan/createdetail', 'createdetail')->name('pemesananpenjualan.createdetail');
            Route::post('/pemesananpenjualan/storedetail', 'storedetail')->name('pemesananpenjualan.storedetail');
            Route::get('/pemesananpenjualan/editdetail/{id}', 'editdetail')->name('pemesananpenjualan.editdetail');
            Route::post('/pemesananpenjualan/updatedetail', 'updatedetail')->name('pemesananpenjualan.updatedetail');
            Route::get('/pemesananpenjualan/destroydetail/{id}', 'destroydetail');
        });
        // End Pemesanan Penjualan

        // Penjualan
        Route::controller(PenjualantunaiController::class)->group(function () {
            // Penjualan penjualan
            Route::get('/penjualantunai', 'index')->name('penjualantunai');
            Route::get('/penjualantunai/create', 'create')->name('penjualantunai.create');
            Route::post('/penjualantunai/store', 'store')->name('penjualantunai.store');
            Route::get('/penjualantunai/edit/{id}', 'edit')->name('penjualantunai.edit');
            Route::post('/penjualantunai/update', 'update')->name('penjualantunai.update');
            Route::get('/penjualantunai/destroy/{id}', 'destroy')->name('penjualantunai.destroy');

            Route::get('/penjualantunai/jurnal/{id}', 'jurnal');
            Route::get('/penjualantunai/jurnaldetail/{id}', 'jurnaldetail')->name('penjualantunai.jurnaldetail');

            // Penjualan Detail
            Route::get('/penjualantunai/detail/{id}', 'detail')->name('penjualantunai.detail');
            Route::get('/penjualantunai/createdetail', 'createdetail')->name('penjualantunai.createdetail');
            Route::post('/penjualantunai/storedetail', 'storedetail')->name('penjualantunai.storedetail');
            Route::get('/penjualantunai/editdetail/{id}', 'editdetail')->name('penjualantunai.editdetail');
            Route::post('/penjualantunai/updatedetail', 'updatedetail')->name('penjualantunai.updatedetail');
            Route::get('/penjualantunai/destroydetail/{id}', 'destroydetail');
        });
        //End penjualan

        Route::controller(PengirimanController::class)->group(function () {
            Route::get('/pengiriman', 'index')->name('pengiriman');
            Route::get('/pengiriman/create', 'create')->name('pengiriman.create');
            Route::post('/pengiriman/store', 'store')->name('pengiriman.store');
            Route::get('/pengiriman/edit/{id}', 'edit')->name('pengiriman.edit');
            Route::post('/pengiriman/update', 'update')->name('pengiriman.update');
            Route::get('/pengiriman/destroy/{id}', 'destroy');

            Route::get('/pengiriman/jurnal/{id}', 'jurnal');
            Route::get('/pengiriman/jurnaldetail/{id}', 'jurnaldetail')->name('pengiriman.jurnaldetail');

            // Pengiriman Detail
            Route::get('/pengiriman/detail/{id}', 'detail')->name('pengiriman.detail');
            Route::get('/pengiriman/createdetail', 'createdetail')->name('pengiriman.createdetail');
            Route::post('/pengiriman/storedetail', 'storedetail')->name('pengiriman.storedetail');
            Route::get('/pengiriman/editdetail/{id}', 'editdetail')->name('pengiriman.editdetail');
            Route::post('/pengiriman/updatedetail', 'updatedetail')->name('pengiriman.updatedetail');
            Route::get('/pengiriman/destroydetail/{id}', 'destroydetail');
        });
        // End Pengiriman

        Route::controller(JurnalController::class)->group(function () {
            Route::get('/jurnal', 'index')->name('jurnal');
            Route::get('/jurnal/create', 'create')->name('jurnal.create');
            Route::post('/jurnal/store', 'store')->name('jurnal.store');
            Route::get('/jurnal/edit/{id}', 'edit')->name('jurnal.edit');
            Route::post('/jurnal/update', 'update')->name('jurnal.update');
            Route::get('/jurnal/destroy/{id}', 'destroy');

            // Jurnal Detail
            Route::get('/jurnal/detail/{id}', 'detail')->name('jurnal.detail');
            Route::get('/jurnal/createdetail', 'createdetail')->name('jurnal.createdetail');
            Route::post('/jurnal/storedetail', 'storedetail')->name('jurnal.storedetail');
            Route::get('/jurnal/editdetail/{id}', 'editdetail')->name('jurnal.editdetail');
            Route::post('/jurnal/updatedetail', 'updatedetail')->name('jurnal.updatedetail');
            Route::get('/jurnal/destroydetail/{id}', 'destroydetail');
        });

        // Route Pembayaran Upah
        Route::controller(PembayaranupahController::class)->group(function () {
            Route::get('/pembayaranupah', 'index')->name('pembayaranupah');
            Route::get('/pembayaranupah/create', 'create')->name('pembayaranupah.create');
            Route::post('/pembayaranupah/store', 'store')->name('pembayaranupah.store');
            Route::get('/pembayaranupah/edit/{id}', 'edit')->name('pembayaranupah.edit');
            Route::post('/pembayaranupah/update', 'update')->name('pembayaranupah.update');
            Route::get('/pembayaranupah/destroy/{id}', 'destroy')->name('pembayaranupah.destroy');
            Route::get('/pembayaranupah/jurnal/{id}', 'jurnal');
            Route::get('/pembayaranupah/jurnaldetail/{id}', 'jurnaldetail')->name('pembayaranupah.jurnaldetail');
        });
        // End Route Pembayaran Upah

        // Route Pengeluaran Biaya
        Route::controller(PengeluaranbiayaController::class)->group(function () {
            Route::get('/pengeluaranbiaya', 'index')->name('pengeluaranbiaya');
            Route::get('/pengeluaranbiaya/create', 'create')->name('pengeluaranbiaya.create');
            Route::post('/pengeluaranbiaya/store', 'store')->name('pengeluaranbiaya.store');
            Route::get('/pengeluaranbiaya/edit/{id}', 'edit')->name('pengeluaranbiaya.edit');
            Route::post('/pengeluaranbiaya/update', 'update')->name('pengeluaranbiaya.update');
            Route::get('/pengeluaranbiaya/destroy/{id}', 'destroy')->name('pengeluaranbiaya.destroy');
            Route::get('/pengeluaranbiaya/jurnal/{id}', 'jurnal');
            Route::get('/pengeluaranbiaya/jurnaldetail/{id}', 'jurnaldetail')->name('pengeluaranbiaya.jurnaldetail');
        });
        // End Route Pembayaran Biaya

        // Route Akun Default
        Route::controller(AkundefaultController::class)->group(function () {
            Route::get('/akundefault', 'index')->name('akundefault');
            Route::get('/akundefault/edit/{id}', 'edit')->name('akundefault.edit');
            Route::post('/akundefault/update', 'update')->name('akundefault.update');
        });
        // End Route Akun Default

        // Route Saldo Awal
        Route::controller(SaldoawalController::class)->group(function () {
            Route::get('/saldoawal', 'index')->name('saldoawal');
            Route::get('/saldoawal/edit/{id}', 'edit')->name('saldoawal.edit');
            Route::post('/saldoawal/update', 'update')->name('saldoawal.update');
            Route::get('/saldoawal/destroy/{id}', 'destroy')->name('saldoawal.destroy');
        });
        // End Route Saldo Awal

        // Route Neraca
        Route::controller(NeracaController::class)->group(function () {
            Route::get('/neraca', 'index')->name('neraca');
            Route::get('/neraca/show', 'show')->name('neraca.show');
        });
        // End Route Neraca

        // Route Neraca Saldo
        Route::controller(NeracasaldoController::class)->group(function () {
            Route::get('/neracasaldo', 'index')->name('neracasaldo');
            Route::get('/neracasaldo/show', 'show')->name('neracasaldo.show');
        });
        // End Route Neraca

        // Route Laba Rugi
        Route::controller(LabarugiController::class)->group(function () {
            Route::get('/labarugi', 'index')->name('labarugi');
            Route::get('/labarugi/show', 'show')->name('labarugi.show');
        });
        // End Route Laba Rugi

        // Route Ledger
        Route::controller(LedgerController::class)->group(function () {
            Route::get('/ledger', 'index')->name('ledger');
            Route::get('/ledger/show', 'show')->name('ledger.show');
        });
        // End Route Ledger

        // Route Laporan Pembelian
        Route::controller(LaporanpembelianController::class)->group(function () {
            Route::get('/laporanpembelian', 'index')->name('laporanpembelian');
            Route::get('/laporanpembelian/show', 'show')->name('laporanpembelian.show');
        });
        // End Route Laporan Pembelian

        // Route Laporan Penjualan
        Route::controller(LaporanpenjualanController::class)->group(function () {
            Route::get('/laporanpenjualan', 'index')->name('laporanpenjualan');
            Route::get('/laporanpenjualan/show', 'show')->name('laporanpenjualan.show');
        });
        // End Route Laporan Penjualan

        // Route Laporan Produksi
        Route::controller(LaporanproduksiController::class)->group(function () {
            Route::get('/laporanproduksi', 'index')->name('laporanproduksi');
            Route::get('/laporanproduksi/show', 'show')->name('laporanproduksi.show');
        });
        // End Route Laporan Produksi

        // Route Laporanjoborder
        Route::controller(LaporanjoborderController::class)->group(function () {
            Route::get('/laporanjoborder', 'index')->name('laporanjoborder');
            Route::get('/laporanjoborder/show', 'show')->name('laporanjoborder.show');
        });
        // End Route Laporanjoborder

        // Route Laporanjoborder
        Route::controller(LaporanpenyusutanController::class)->group(function () {
            Route::get('/laporanpenyusutan', 'index')->name('laporanpenyusutan');
            Route::get('/laporanpenyusutan/show', 'show')->name('laporanpenyusutan.show');
        });
        // End Route Laporanjoborder

        // Route Perubahan Modal
        Route::controller(PerubahanmodalController::class)->group(function () {
            Route::get('/perubahanmodal', 'index')->name('perubahanmodal');
            Route::get('/perubahanmodal/show', 'show')->name('perubahanmodal.show');
        });
        // End Route Perubahan Modal

        // Route Perubahan Modal
        Route::controller(LaporancashflowController::class)->group(function () {
            Route::get('/laporancashflow', 'index')->name('laporancashflow');
            Route::get('/laporancashflow/show', 'show')->name('laporancashflow.show');
            Route::get('/laporancashflow/cashflow', 'cashflow')->name('laporancashflow.cashflow');
        });
        // End Route Perubahan Modal

        // Route Ledger
        Route::controller(PenutupController::class)->group(function () {
            Route::get('/penutup', 'index')->name('penutup');
            Route::get('/penutup/detail/{id}', 'detail')->name('penutup.detail');
            Route::get('/penutup/create', 'create')->name('penutup.create');
            Route::post('/penutup/store', 'store')->name('penutup.store');
            Route::get('/penutup/destroy/{id}', 'destroy');
        });

        // Route Perolehan Aset
        Route::controller(PerolehanasetController::class)->group(function () {
            // Perolehan Aset
            Route::get('/perolehanaset', 'index')->name('perolehanaset');
            Route::get('/perolehanaset/create', 'create')->name('perolehanaset.create');
            Route::post('/perolehanaset/store', 'store')->name('perolehanaset.store');
            Route::get('/perolehanaset/edit/{id}', 'edit')->name('perolehanaset.edit');
            Route::post('/perolehanaset/update', 'update')->name('perolehanaset.update');
            Route::post('/perolehanaset/updateuangmuka', 'updateuangmuka')->name('perolehanaset.updateuangmuka');
            Route::get('/perolehanaset/destroy/{id}', 'destroy');
            Route::get('/perolehanaset/jurnal/{id}', 'jurnal');
            Route::get('/perolehanaset/jurnaldetail/{id}', 'jurnaldetail')->name('perolehanaset.jurnaldetail');

            // Perolehan Aset Detail
            Route::get('/perolehanaset/detail/{id}', 'detail')->name('perolehanaset.detail');
            Route::get('/perolehanaset/createdetail', 'createdetail')->name('perolehanaset.createdetail');
            Route::post('/perolehanaset/storedetail', 'storedetail')->name('perolehanaset.storedetail');
            Route::get('/perolehanaset/destroydetail/{id}', 'destroydetail');
            Route::get('/perolehanaset/getdetailbyitem/{id_perolehanaset_header}/{id_item}', 'getdetailbyitem')->name('perolehanaset.getdetailbyitem');
            Route::get('/perolehanaset/updatestatus/{id}/{id_penerimaan}', 'updatestatus')->name('perolehanaset.updatestatus');
        });
        // End Route Perolehan Aset

        // Route Depresiasi Aset
        Route::controller(DepresiasiasetController::class)->group(function () {
            // Depresiasi Aset
            Route::get('/depresiasiaset', 'index')->name('depresiasiaset');
            Route::get('/depresiasiaset/create', 'create')->name('depresiasiaset.create');
            Route::post('/depresiasiaset/store', 'store')->name('depresiasiaset.store');
            Route::get('/depresiasiaset/destroy/{id}', 'destroy');
            Route::get('/depresiasiaset/detail/{id}', 'detail')->name('depresiasiaset.detail');
            Route::get('/depresiasiaset/jurnal/{id}', 'jurnal');
            Route::get('/depresiasiaset/jurnaldetail/{id}', 'jurnaldetail')->name('depresiasiaset.jurnaldetail');
        });
        // End Route Depresiasi Aset

        // Route Pelunasan Aset
        Route::controller(PelunasanasetController::class)->group(function () {
            // Pelunasan Aset
            Route::get('/pelunasanaset', 'index')->name('pelunasanaset');
            Route::get('/pelunasanaset/create', 'create')->name('pelunasanaset.create');
            Route::post('/pelunasanaset/store', 'store')->name('pelunasanaset.store');
            Route::get('/pelunasanaset/edit/{id}', 'edit')->name('pelunasanaset.edit');
            Route::post('/pelunasanaset/update', 'update')->name('pelunasanaset.update');
            Route::post('/pelunasanaset/updateuangmuka', 'updateuangmuka')->name('pelunasanaset.updateuangmuka');
            Route::get('/pelunasanaset/destroy/{id}', 'destroy');
            Route::get('/pelunasanaset/jurnal/{id}', 'jurnal');
            Route::get('/pelunasanaset/jurnaldetail/{id}', 'jurnaldetail')->name('pelunasanaset.jurnaldetail');
        });
        // End Route Pelunasan Aset

        // Route Pelepasan Aset
        Route::controller(PelepasanasetController::class)->group(function () {
            // Pelepasan Aset
            Route::get('/pelepasanaset', 'index')->name('pelepasanaset');
            Route::get('/pelepasanaset/create', 'create')->name('pelepasanaset.create');
            Route::post('/pelepasanaset/store', 'store')->name('pelepasanaset.store');
            Route::get('/pelepasanaset/edit/{id}', 'edit')->name('pelepasanaset.edit');
            Route::post('/pelepasanaset/update', 'update')->name('pelepasanaset.update');
            Route::post('/pelepasanaset/updateuangmuka', 'updateuangmuka')->name('pelepasanaset.updateuangmuka');
            Route::get('/pelepasanaset/destroy/{id}', 'destroy');
            Route::get('/pelepasanaset/jurnal/{id}', 'jurnal');
            Route::get('/pelepasanaset/jurnaldetail/{id}', 'jurnaldetail')->name('pelepasanaset.jurnaldetail');
        });
        // End Route Pelepasan Aset


        // Route Inventaris
        Route::controller(InventarisController::class)->group(function () {
            Route::get('/inventaris', 'index')->name('inventaris');
            Route::get('/inventaris/detail/{id}', 'detail')->name('inventaris.detail');
            Route::get('/inventaris/detail2/{id}', 'detail2')->name('inventaris.detail2');
        });
        // End Route Inventaris
    });

require __DIR__ . '/auth.php';