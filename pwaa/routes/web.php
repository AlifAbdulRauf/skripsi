<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\Admin\DataDokterController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Dokter\IzinController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\RekamController;
use App\Http\Controllers\Admin\ReservasiController;
use App\Http\Controllers\Dokter\DokterHomeController;
use App\Http\Controllers\Owner\KeuanganController;
use App\Http\Controllers\Owner\PerawatanController;
use App\Http\Controllers\Owner\PengeluaranController;
use App\Http\Controllers\Owner\PemasukanController;
use App\Http\Controllers\Pasien\ReservasiPasienController;
use App\Http\Controllers\WhatsAppController;

use App\Models\Perawatan;

require __DIR__.'/auth.php';

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/manifest.json', function () {
    return response()->file(public_path('manifest.json'));
});

Route::get('/serviceworker.js', function () {
    return response()->file(public_path('serviceworker.js'));
});



Route::post('/send-whatsapp', [WhatsAppController::class, 'sendWhatsAppMessage'])->name('whatsapp.send');


Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('landing_page');
    });
    Route::get('/pasien/history', [ReservasiPasienController::class, 'index'])->name('pasien.history');
    Route::get('/pasien/add', [ReservasiController::class, 'add'])->name('pasien.add');
    Route::post('/pasien/insert', [ReservasiPasienController::class, 'insert_pasien_lama'])->name('pasienlama.insert');
    Route::get('/pasien/add_pasien_lama', [ReservasiPasienController::class, 'add_pasien_lama'])->name('pasienlama.add');
    Route::post('/pasien/insert_pasien_lama', [ReservasiPasienController::class, 'insert'])->name('pasien.insert');
    Route::get('/api/dokter-by-lokasi-p', [ReservasiPasienController::class, 'getDokterByLokasi']);
    Route::get('/api/unavailable-times-p', [ReservasiPasienController::class, 'getUnavailableTimes']);
    Route::get('/api/available-dates-p', [ReservasiPasienController::class, 'getAvailableDates']);
    Route::get('/api/available-days-p', [ReservasiPasienController::class, 'getAvailableDays']);
    Route::get('/api/booked-times-p', [ReservasiPasienController::class, 'getBookedTimes']);
    Route::get('/api/sesi-by-dokter-and-hari-p', [ReservasiPasienController::class, 'getSesiByDokterAndHari']);


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
});

Route::get('/', function () {
    return view('landing_page');
});
Route::get('/offline', function () {
    return view('offline');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', [HomeController::class, 'index'])->name('admin');
    Route::post('/admin/filter', [HomeController::class, 'filter'])->name('home.filter');
});


Route::middleware(['role:admin'])->group(function () {
    // Routes untuk DataDokter
    Route::get('/datadokter', [DataDokterController::class, 'index'])->name('datadokter.index');
    Route::get('/datadokter/add', [DataDokterController::class, 'add'])->name('datadokter.add');
    Route::post('/datadokter/insert', [DataDokterController::class, 'insert'])->name('datadokter.insert');
    Route::get('/datadokter/edit/{id}', [DataDokterController::class, 'edit'])->name('datadokter.edit');
    Route::post('/datadokter/update/{id}', [DataDokterController::class, 'update'])->name('datadokter.update');
    Route::delete('/datadokter/destroy/{id}', [DataDokterController::class, 'destroy'])->name('datadokter.destroy');

    // Routes untuk Jadwal DataDokter
    Route::get('/datadokter/jadwal/{id}', [JadwalController::class, 'index'])->name('dokterjadwal.index');
    Route::get('/datadokter/jadwal/add/{id}', [JadwalController::class, 'add'])->name('dokterjadwal.add');
    Route::post('/datadokter/jadwal/insert/{id}', [JadwalController::class, 'insert'])->name('dokterjadwal.insert');
    Route::get('/datadokter/jadwal/edit/{id}', [JadwalController::class, 'edit'])->name('dokterjadwal.edit');
    Route::post('/datadokter/jadwal/update/{id}', [JadwalController::class, 'update'])->name('dokterjadwal.update');
    Route::delete('/datadokter/jadwal/destroy/{id}', [JadwalController::class, 'destroy'])->name('dokterjadwal.destroy');

    // Routes untuk Reservasi Admin
    Route::get('/reservasi/admin/', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/admin/add/', [ReservasiController::class, 'add'])->name('reservasi.add');
    Route::get('/reservasi/admin/add/pasienlama', [ReservasiController::class, 'add_pasien_lama'])->name('reservasi_pasien_lama.add');
    Route::post('/reservasi/admin/insert/', [ReservasiController::class, 'insert'])->name('reservasi.insert');
    Route::post('/reservasi/admin/insertps/', [ReservasiController::class, 'insert_pasien_lama'])->name('reservasi.insertps');
    Route::get('/reservasi/admin/edit/{id}', [ReservasiController::class, 'edit'])->name('reservasi.edit');
    Route::post('/reservasi/admin/update/{id}', [ReservasiController::class, 'update'])->name('reservasi.update');
    Route::delete('/reservasi/admin/destroy/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
    Route::post('/reservasi/admin/terima/{id}', [ReservasiController::class, 'terima'])->name('reservasi.terima');
    Route::post('/reservasi/admin/tolak/{id}', [ReservasiController::class, 'tolak'])->name('reservasi.tolak');
    Route::get('/reservasi/pasien/', [ReservasiController::class, 'pasien'])->name('reservasi.pasien');
    Route::get('/api/dokter-by-lokasi', [ReservasiController::class, 'getDokterByLokasi']);
    Route::get('/api/unavailable-times', [ReservasiController::class, 'getUnavailableTimes']);
    Route::get('/api/available-dates', [ReservasiController::class, 'getAvailableDates']);
    Route::get('/api/available-days', [ReservasiController::class, 'getAvailableDays']);
    Route::get('/api/booked-times', [ReservasiController::class, 'getBookedTimes']);

    Route::get('/api/sesi-by-dokter-and-hari', [ReservasiController::class, 'getSesiByDokterAndHari']);
    Route::get('/reservasi/getDoctors', [ReservasiController::class, 'getDokterByLokasi'])->name('reservasi.getDoctors');
    Route::get('/reservasi/getTimeSlots', [ReservasiController::class, 'getTimeSlots'])->name('reservasi.getTimeSlots');



    // Routes untuk DataPasien
    Route::get('/adatapasien', [PasienController::class, 'index'])->name('datapasien.index');
    Route::get('/adatapasien/add', [PasienController::class, 'add'])->name('datapasien.add');
    Route::post('/adatapasien/insert', [PasienController::class, 'insert'])->name('datapasien.insert');
    Route::get('/adatapasien/edit/{id}', [PasienController::class, 'edit'])->name('datapasien.edit');
    Route::post('/adatapasien/update/{id}', [PasienController::class, 'update'])->name('datapasien.update');
    Route::delete('/adatapasien/destroy/{id}', [PasienController::class, 'destroy'])->name('datapasien.destroy');

    // Routes untuk Rekam Pasien
    Route::get('/datapasien/rekam/{id}', [RekamController::class, 'index'])->name('admin.rekampasien');
    Route::get('/datapasien/rekam/edit/{id}', [RekamController::class, 'edit'])->name('admin.rekampasien.edit');
    Route::post('/datapasien/rekam/update/{id}', [RekamController::class, 'update'])->name('admin.rekampasien.update');

    // Routes untuk DataUser
    Route::get('/datauser', [UserController::class, 'index'])->name('datauser.index');
    Route::get('/datauser/add', [UserController::class, 'add'])->name('datauser.add');
    Route::post('/datauser/insert', [UserController::class, 'insert'])->name('datauser.insert');
    Route::get('/datauser/edit/{id}', [UserController::class, 'edit'])->name('datauser.edit');
    Route::post('/datauser/update/{id}', [UserController::class, 'update'])->name('datauser.update');
    Route::delete('/datauser/destroy/{id}', [UserController::class, 'destroy'])->name('datauser.destroy');

    Route::get('/datauserdokter/add', [UserController::class, 'add_dokter'])->name('datauserdokter.add');
    Route::post('/datauserdokter/insert', [UserController::class, 'insert_dokter'])->name('datauserdokter.insert');
});




Route::get('/profil', function () {
    return view('admin.profil');
});

// Routes untuk dokter

Route::middleware(['role:Dokter'])->group(function () {
    Route::get('/dokter',[DokterHomeController::class, 'index'])->name('dokter.index');
    Route::get('/dataabsen',[IzinController::class, 'index'])->name('dataabsen.index');
    Route::get('/dataabsen/add',[IzinController::class, 'add'])->name('dataabsen.add');
    Route::get('/dataabsen/edit{id}',[IzinController::class, 'edit'])->name('dataabsen.edit');
    Route::post('/dataabsen/update{id}',[IzinController::class, 'update'])->name('dataabsen.update');
    Route::post('/dataabsen/insert',[IzinController::class, 'insert'])->name('dataabsen.insert');
    Route::delete('/dataabsen/destroy{id}',[IzinController::class, 'destroy'])->name('dataabsen.destroy');
    
    Route::get('/ddatapasien',[PasienController::class, 'index'])->name('ddatapasien.index');
    Route::get('/ddatapasien/add', [PasienController::class, 'add'])->name('ddatapasien.add');
    Route::post('/ddatapasien/insert', [PasienController::class, 'inser'])->name('ddatapasien.insert');
    Route::get('/ddatapasien/edit/{id}', [PasienController::class, 'edit'])->name('ddatapasien.edit');
    Route::post('/ddatapasien/update/{id}', [PasienController::class, 'update'])->name('ddatapasien.update');
    Route::delete('/ddatapasien/destroy{id}', [PasienController::class, 'destroy'])->name('ddatapasien.destroy');
    Route::get('/ddatapasien/rekam/{id}', [RekamController::class, 'index'])->name('dokter.rekampasien');
    Route::get('/ddatapasien/rekam/edit/{id}', [RekamController::class, 'edit'])->name('dokter.rekampasien.edit');
    Route::post('/ddatapasien/rekam/update/{id}', [RekamController::class, 'update'])->name('dokter.rekampasien.update');
});

Route::get('/kalender', function () {
    return view('absence');
});

// Route::resource('absences', AbsenceController::class);

Route::middleware(['role:owner'])->group(function () {
        // Routes untuk owner
    // Route::get('/owner', function () {
    //     return view('owner.home');
    // });
    Route::get('/owner', [HomeController::class, 'index'])->name('owner.index');
    Route::post('/owner/filter', [HomeController::class, 'filter'])->name('owner.filter');

    Route::get('/odatadokter', [DataDokterController::class, 'index'])->name('odatadokter.index');

    // Routes untuk Jadwal DataDokter
    Route::get('/odatadokter/jadwal/{id}', [JadwalController::class, 'index'])->name('odatadokter.jadwal');

    Route::get('/odatapasien', [PasienController::class, 'index'])->name('odatapasien.index');
    Route::get('/datalayanan', [PerawatanController::class, 'index'])->name('layanan.index');
    Route::get('/datalayanan/add', [PerawatanController::class, 'add'])->name('layanan.add');
    Route::post('/datalayanan/insert', [PerawatanController::class, 'insert'])->name('layanan.insert');
    Route::get('/datalayanan/edit/{id}', [PerawatanController::class, 'edit'])->name('layanan.edit');
    Route::post('/datalayanan/update/{id}', [PerawatanController::class, 'update'])->name('layanan.update');
    Route::delete('/datalayanan/destroy/{id}', [PerawatanController::class, 'destroy'])->name('layanan.destroy');

    Route::get('/datapengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/datapengeluaran/add', [PengeluaranController::class, 'add'])->name('pengeluaran.add');
    Route::post('/datapengeluaran/insert', [PengeluaranController::class, 'insert'])->name('pengeluaran.insert');
    Route::get('/datapengeluaran/edit/{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
    Route::post('/datapengeluaran/update/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
    Route::delete('/datapengeluaran/destroy/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');


    Route::get('/datapemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');

    Route::get('/pengeluaran/edit', function () {
        return view('owner.datapengeluaran_edit');
    });
    Route::get('/pengeluaran/add', function () {
        return view('owner.datapengeluaran_add');
    });
    Route::get('/odatapasien/rekam/{id}', [RekamController::class, 'index'])->name('owner.rekampasien');
    Route::get('/owner/izin',[IzinController::class, 'index'])->name('owner.izin.index');
    Route::post('/owner/izin/terima/{id}',[IzinController::class, 'terima'])->name('owner.izin.terima');
    Route::post('/owner/izin/tolak/{id}',[IzinController::class, 'tolak'])->name('owner.izin.tolak');

});

