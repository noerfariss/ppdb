<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::guard('siswa')->check()) {
            return redirect()->route('user.dashboard');
        }

        if ($request->method() === 'POST') {
            $request->validate([
                'nomor' => 'required|numeric|min:6',
                'password' => 'required|min:6'
            ]);

            $cek = Auth::guard('siswa')->attempt($request->only(['nomor', 'password']));
            if ($cek) {
                return redirect()->route('user.dashboard');
            } else {
                return redirect()->route('user.login')->with('pesan', 'Whatsapp atau Password salah!');
            }
        }

        return Inertia::render('Login', [
            'judul' => 'PPDB',
        ]);
    }

    public function register(Request $request)
    {
        if (Auth::guard('siswa')->check()) {
            return redirect()->route('user.dashboard');
        }

        if ($request->method() === 'POST') {
            $request->validate([
                'nomor' => [
                    'required',
                    'numeric',
                    'min:11',
                    Rule::unique('siswas', 'nomor'),
                ],
                'nama' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('siswas', 'email')
                ],
                'password' => 'required|confirmed|min:6'
            ]);

            DB::beginTransaction();
            try {
                Siswa::create([
                    'nomor' => $request->nomor,
                    'email' => $request->email,
                    'nama' => Str::upper($request->nama),
                    'kode_verifikasi' => rand(888, 88888),
                    'expired' => Carbon::now()->addMinutes(3),
                    'password' => Hash::make($request->password),
                ]);
                DB::commit();

                session()->flush();
                session()->push('nomor', $request->nomor);
                return redirect()->route('user.verifikasi');
            } catch (\Throwable $th) {
                Log::warning($th->getMessage());
                DB::rollBack();

                return redirect()->route('user.register')->with('pesan', 'Terjadi kesalaha, cobalah kembali');
            }
        }

        return Inertia::render('Daftar');
    }

    public function verifikasiKode(Request $request)
    {
        if (!session('nomor')) {
            return redirect()->route('user.login');
        }

        if ($request->method() === 'POST') {
            $request->validate([
                'kode' => 'required',
                'nomor' => 'required'
            ]);

            $cek = Siswa::query()->where([
                'nomor' => $request->nomor,
                'kode_verifikasi' => $request->kode,
                'is_verifikasi' => false
            ])->first();

            if ($cek === null) {
                return redirect()->route('user.verifikasi')->with('pesan', 'Kode yang Anda masukkan salah!');
            }

            $expired = Carbon::parse($cek->expired)->timezone(zona_waktu());
            if (Carbon::now()->timezone(zona_waktu())->greaterThan($expired)) {
                return redirect()->route('user.verifikasi')->with('pesan', 'Kode Anda sudah kadaluarsa');
            }

            DB::beginTransaction();
            try {
                Siswa::query()->where([
                    'nomor' => $request->nomor,
                    'kode_verifikasi' => $request->kode,
                    'is_verifikasi' => false
                ])->update([
                    'is_verifikasi' => true,
                    'verifikasi' => Carbon::now(),
                ]);

                session()->flush();

                DB::commit();
                return redirect()->route('user.login')->with('pesanSukses', 'Verifikasi berhasil, silahkan masuk');
            } catch (\Throwable $th) {
                Log::warning($th->getMessage());
                DB::rollBack();

                return redirect()->route('user.verifikasi')->with('pesan', $th->getMessage());
            }
        }

        return Inertia::render('Verifikasi', [
            'nomor' => session('nomor'),
        ]);
    }

    public function logout()
    {
        Auth::guard('siswa')->logout();
        return redirect()->route('user.login');
    }
}
