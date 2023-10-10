<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanFormRequest;
use App\Models\GrupForm;
use App\Models\Siswa;
use App\Models\SiswaRegister;
use App\Models\SiswaRegisterForm;
use App\Models\TahunAjar;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function index()
    {
        return Inertia::render('Index');
    }

    public function dashboard()
    {
        $tahunajar = TahunAjar::query()
            ->where('status', true)
            ->get();

        $grups = TahunAjar::query()
            ->with('template', function ($e) use ($tahunajar) {
                $e->with('form', function ($e) use ($tahunajar) {
                    $e->with('grup', function ($e) {
                        $e->select('id', 'grup', 'slug');
                    })->withCount([
                        'jawaban' => function ($e) use ($tahunajar) {
                            $e->whereHas('siswa_register', function ($e) use ($tahunajar) {
                                $e->where('siswa_id', Auth::guard('siswa')->user()->id)->where('tahunajar_id', $tahunajar->pluck('id')->toArray());
                            })->where(function ($e) {
                                $e->where('jawaban', '<>', '')->orWhere(DB::raw('jawaban IS NOT NULL'));
                            });
                        }
                    ]);
                })->select('id', 'template');
            })
            ->where('status', true)
            ->select('id', 'tahun', 'kuota', 'mulai', 'akhir', 'template_id')
            ->get();

        $dataCollect = collect($grups)->map(function ($item) {
            return [
                'id' => $item->id,
                'tahun' => $item->tahun,
                'kuota' => $item->kuota,
                'mulai' => $item->mulai,
                'akhir' => $item->akhir,
                'grup' => collect($item->template->form)->groupBy('grup.grup')->map(function ($item) {
                    return [
                        'slug' => $item[0]->grup->slug,
                        'form' => collect($item)->count(),
                        'jawaban' => collect($item)->sum('jawaban_count'),
                        'persen' => (int) round((collect($item)->sum('jawaban_count') / collect($item)->count()) * 100, 0)
                    ];
                })->toArray()
            ];
        })->toArray();

        return Inertia::render('Dashboard/Index', [
            'judul' => 'Dashboard',
            'grups' => $dataCollect,
            'tahunajar' => $tahunajar,
        ]);
    }

    public function profil(Request $request)
    {
        if ($request->method() === 'POST') {
            $request->validate([
                'nomor' => 'required|numeric|min:6',
                'email' => 'required|email',
                'nama' => 'required'
            ]);

            DB::beginTransaction();
            try {
                Siswa::query()
                    ->where('id', Auth::id())->update([
                        'nama' => Str::upper($request->nama),
                    ]);
                DB::commit();

                return redirect()->route('user.profil')->with('pesanSukses', 'Profil berhasil diperbarui');
            } catch (\Throwable $th) {
                Log::warning($th->getMessage());
                DB::rollBack();

                return redirect()->route('user.profil')->with('pesan', 'Terjadi kesalahan, cobalah kembali');
            }
        }

        $profil = Siswa::query()->select('nomor', 'email', 'nama')->find(Auth::id());
        return Inertia::render('Dashboard/Profil', [
            'data' => $profil
        ]);
    }

    public function password(Request $request)
    {
        if ($request->method() === 'POST') {
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ]);

            DB::beginTransaction();
            try {
                Siswa::query()->where('id', Auth::id())->update(['password' => Hash::make($request->password)]);
                DB::commit();
                return redirect()->route('user.password')->with('pesanSukses', 'Password berhasil diperbarui');
            } catch (\Throwable $th) {
                Log::warning($th->getMessage());
                DB::rollBack();

                return redirect()->route('user.password')->with('pesan', 'Terjadi kesalahan, cobalah kembali');
            }
        }


        return Inertia::render('Dashboard/Password');
    }

    public function grupDetail($tahunajar, $grup)
    {
        $data = TahunAjar::query()
            ->withWhereHas('form', function ($e) use ($grup, $tahunajar) {
                $e->withWhereHas('grup', function ($e) use ($grup) {
                    $e->where('slug', $grup);
                })->with('jawaban', function ($e) use ($tahunajar) {
                    $e->whereHas('siswa_register', function ($e) use ($tahunajar) {
                        $e->where('siswa_id', Auth::guard('siswa')->user()->id)->where('tahunajar_id', $tahunajar);
                    });
                });
            })
            ->where('id', $tahunajar)
            ->first();

        if ($data === null) {
            return abort(404);
        }

        $dataCollect = [
            'id' => $data->id,
            'tahun' => $data->tahun,
            'keterangan' => $data->keterangan,
            'kuota' => $data->kuota,
            'mulai' => $data->mulai,
            'akhir' => $data->akhir,
            'slug' => $data->slug,
            // 'template_id' => $data->template_id,
            // 'status' => $data->status,
            // 'created_at' => $data->created_at,
            // 'updated_at' => $data->updated_at,
            'form' => collect($data->form)->map(function ($item) {
                return [
                    'id' => $item->id,
                    'grup_id' => $item->grup_id,
                    'label' => $item->label,
                    'urut' => $item->urut,
                    'pivot' => $item->pivot->toArray(),
                    'grup' => $item->grup->toArray(),
                    'jawaban' => $item->jawaban ? $item->jawaban->jawaban : null,
                ];
            })->toArray(),

        ];

        return Inertia::render('Dashboard/Form', [
            'data' => $dataCollect,
        ]);
    }

    public function simpanForm(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'labels.*.jawaban' => 'required_if:labels.*.wajib,1'
        ], [
            'labels.*.jawaban.required_if' => 'Form wajib diisi',
        ]);

        $tahun = TahunAjar::where('slug', $request->tahun)->first()->id;
        $grup = $request->grup;

        if ($validasi->fails()) {
            $errors = collect($validasi->errors())->map(function ($item) {
                return $item[0];
            })->values()->toArray();

            $errorLabels = [];
            foreach ($request->labels as $label) {
                $errorLabels[$label['form_id']] = $label['wajib'] == 1 ? ($label['jawaban'] == '' ? $errors[0] : '')  : '';
            }

            return redirect()->route('user.grup', ['tahun' => $tahun, 'grup' => $grup])->with([
                'pesan' => $errorLabels,
            ]);
        }


        DB::beginTransaction();
        try {
            $timestamps = now()->format('mdYHis');
            $unique = mt_rand(1000, 3999);
            $nomorRegister = $timestamps . $unique . Auth::guard('siswa')->user()->id;

            $cek = SiswaRegister::where([
                'tahunajar_id' => $tahun,
                'siswa_id' => Auth::guard('siswa')->user()->id,
            ]);

            if ($cek->count() > 0) {
                $register = $cek->first();
            } else {
                $register = SiswaRegister::create([
                    'tahunajar_id' => $tahun,
                    'siswa_id' => Auth::guard('siswa')->user()->id,
                    'nomor_register' => $nomorRegister,
                ]);
            }

            if ($request->labels) {
                foreach ($request->labels as $label) {
                    $form_ids[] = $label['form_id'];
                    $dataLabels[] = [
                        'siswa_register_id' => $register->id,
                        'form_id' => $label['form_id'],
                        'jawaban' => $label['jawaban'],
                        'created_at' => now(),
                    ];
                }
                SiswaRegisterForm::where('siswa_register_id', $register->id)->whereIn('form_id', $form_ids)->delete();
                SiswaRegisterForm::insert($dataLabels);
            }


            DB::commit();
            return redirect()->route('user.grup', ['tahun' => $tahun, 'grup' => $grup])->with([
                'pesanSukses' => 'Data berhasil dilengkapi',
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();

            return redirect()->route('user.grup', ['tahun' => $tahun, 'grup' => $grup])->with([
                'pesan' => 'Terjadi kesalahan',
            ]);
        }
    }
}
