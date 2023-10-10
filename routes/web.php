<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TahunAjarController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UmumController;
use App\Http\Controllers\UserController;
use App\Mail\HelloMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email', function () {
    try {
        Mail::to('noerfaris@gmail.com')->send(new HelloMail());
        return 'berhasil';
    } catch (\Throwable $th) {
        return $th->getMessage();
    }
});

Route::middleware('xss')->group(function () {
    Route::get('/', function () {
        return view('app');
    });

    Route::get('/', [FrontController::class, 'index'])->name('user.home');
    Route::any('/login', [AuthController::class, 'login'])->name('user.login');
    Route::any('/register', [AuthController::class, 'register'])->name('user.register');
    Route::any('/verifikasi', [AuthController::class, 'verifikasiKode'])->name('user.verifikasi');

    Route::middleware('auth:siswa')->prefix('user')->group(function () {
        Route::get('/', [FrontController::class, 'dashboard'])->name('user.dashboard');
        Route::any('/profil', [FrontController::class, 'profil'])->name('user.profil');
        Route::any('/password', [FrontController::class, 'password'])->name('user.password');
        Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
        Route::get('/grup/{tahun}/{grup}', [FrontController::class, 'grupDetail'])->name('user.grup');
        Route::post('/grup/simpan', [FrontController::class, 'simpanForm'])->name('user.simpanform');
    });



    // Backend / dashboard
    Route::any('/administrator', [LoginController::class, 'index'])->name('login');
    Route::middleware(['auth'])->prefix('auth')->group(function () {
        // Authentication
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::any('/profil', [UserController::class, 'profil'])->name('profil');
        Route::post('/simpan-foto', [UserController::class, 'simpan_foto'])->name('simpan-foto');

        Route::any('/password', [UserController::class, 'password'])->name('password');
        Route::post('/ganti-password', [UserController::class, 'ganti_password'])->name('ganti-password');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::any('/weblog', [LogController::class, 'index'])->name('aktivitas');
        Route::get('/force-login/{id}', [LoginController::class, 'force_login'])->name('force-login');

        Route::resource('tahunajar', TahunAjarController::class);
        Route::post('/tahunajar-ajax', [TahunAjarController::class, 'ajax'])->name('tahunajar-ajax');
        Route::post('/tahunajar-delete', [TahunAjarController::class, 'delete'])->name('tahunajar.delete');
        Route::get('/tahunajar-duplikat/{tahunajar}', [TahunAjarController::class, 'duplikat'])->name('tahunajar.duplikat');
        Route::get('/tahunajar/duplikat/{tahunajar}', [TahunAjarController::class, 'duplikatEdit'])->name('tahunajar.duplikat.edit');

        Route::resource('template', TemplateController::class);
        Route::post('/template-ajax', [TemplateController::class, 'ajax'])->name('template-ajax');
        Route::post('/template-delete', [TemplateController::class, 'delete'])->name('template.delete');
        Route::get('/template-duplikat/{template}', [TemplateController::class, 'duplikat'])->name('template.duplikat');
        Route::get('/template/duplikat/{template}', [TemplateController::class, 'duplikatEdit'])->name('template.duplikat.edit');

        // Pengaturan
        Route::singleton('umum', UmumController::class);

        Route::resource('user', UserController::class);
        Route::post('/ajax-user', [UserController::class, 'ajax'])->name('ajax-user');

        Route::resource('role', RoleController::class);
        Route::post('/ajax-role', [RoleController::class, 'ajax'])->name('ajax-roles');
        Route::resource('permission', PermissionController::class);
        Route::post('/ajax-permission', [PermissionController::class, 'ajax'])->name('ajax-permission');

        // ajax
        Route::prefix('ajax')->group(function () {
            Route::post('/role', [AjaxController::class, 'role'])->name('drop-role');
            Route::post('/provinsi', [AjaxController::class, 'provinsi'])->name('drop-provinsi');
            Route::post('/kota', [AjaxController::class, 'kota'])->name('drop-kota');
            Route::post('/kecamatan', [AjaxController::class, 'kecamatan'])->name('drop-kecamatan');
            Route::post('/kelas', [AjaxController::class, 'kelas'])->name('drop-kelas');
            Route::post('/kategori', [AjaxController::class, 'kategori'])->name('drop-kategori');
            Route::post('/penerbit', [AjaxController::class, 'penerbit'])->name('drop-penerbit');
            Route::post('/ganti-foto', [AjaxController::class, 'ganti_foto'])->name('ganti-foto');
            Route::post('/ganti-pdf', [AjaxController::class, 'ganti_pdf'])->name('ganti-pdf');
            Route::post('/ganti-video', [AjaxController::class, 'ganti_video'])->name('ganti-video');
        });
    });
});
