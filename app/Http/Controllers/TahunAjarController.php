<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\TahunAjar;
use App\Models\Template;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TahunAjarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tahunajar-read')->only('index');
        $this->middleware('permission:tahunajar-create')->only(['create', 'store']);
        $this->middleware('permission:tahunajar-update')->only(['edit', 'update']);
        $this->middleware('permission:tahunajar-delete')->only('delete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.tahunajar.index');
    }

    public function ajax(Request $request)
    {
        $status = $request->status;
        $cari = $request->cari;

        $data = TahunAjar::query()
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    $e->where('tahun', 'like', '%' . $cari . '%')->orWhere('keterangan', 'like', '%' . $cari . '%');
                });
            })
            ->where('status', $status)
            ->orderBy('id', 'desc');

        // if ($request->filled('export')) {
        // return Excel::download(new UserExport($data->get(), $request->all()), 'USER.xlsx');
        // }

        return DataTables::eloquent($data)
            ->editColumn('mulai', function ($e) {
                return Carbon::parse($e->mulai)->isoFormat('DD MMM YYYY HH:mm');
            })
            ->editColumn('akhir', function ($e) {
                return Carbon::parse($e->akhir)->isoFormat('DD MMM YYYY HH:mm');
            })
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('tahunajar-update') ? '<a href="' . route('tahunajar.edit', ['tahunajar' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('tahunajar-delete') ?  '<a href="' . route('tahunajar.destroy', ['tahunajar' => $e->id]) . '" data-title="' . $e->judul . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('tahunajar-update') ? '<a href="' . route('tahunajar.destroy', ['tahunajar' => $e->id]) . '" data-title="' . $e->judul . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';
                $btnCopy = '<a href="' . route('tahunajar.duplikat', ['tahunajar' => $e->id]) . '" data-title="' . $e->judul . '" data-duplikatedit="' . route('tahunajar.duplikat.edit', ['tahunajar' => $e->id]) . '" class="btn btn-default btn-xs btn-copy" title="Copy"><i class="bx bx-copy-alt"></i></i></a>';
                if ($e->status == true) {
                    return $btnCopy . ' ' .  $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            // ->editColumn('created_at', fn ($e) => Carbon::parse($e->created_at)->timezone(zona_waktu())->isoFormat('DD MMM YYYY HH:mm'))
            ->rawColumns(['mulai', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $validator = JsValidatorFacade::make([
            'tahun' => 'required',
            'keterangan' => 'nullable',
            'kuota' => 'required|numeric|min:1',
            'mulai' => 'required',
            'akhir' => 'required',
            'template' => 'required',
        ], [
            'mulai.required' => 'Periode mulai wajib diisi',
            'akhir.required' => 'Periode akhir wajib diisi'
        ]);

        $template = Template::query()->where('status', true)->get();

        return view('backend.tahunajar.create', compact('validator', 'template'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required',
            'keterangan' => 'nullable',
            'kuota' => 'required|numeric|min:1',
            'mulai' => 'required',
            'akhir' => 'required',
            'template' => 'required',
        ], [
            'mulai.required' => 'Periode mulai wajib diisi',
            'akhir.required' => 'Periode akhir wajib diisi'
        ]);

        DB::beginTransaction();
        try {
            TahunAjar::create([
                'tahun' => $request->tahun,
                'keterangan' => $request->keterangan,
                'kuota' => $request->kuota,
                'mulai' => $request->mulai,
                'akhir' => $request->akhir,
                'slug' => Str::slug($request->tahun, '-'),
                'template_id' => $request->template,
            ]);

            Weblog::set('Menambahkan tahun ajar : ' . $request->tahun);
            DB::commit();

            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-success"><b>' . $request->tahun . '</b> berhasil ditambahkan</div>',
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAjar $tahunAjar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjar $tahunajar)
    {
        $validator = JsValidatorFacade::make([
            'tahun' => [
                'required',
                // Rule::unique('tahun_ajars', 'tahun')->ignore($tahunajar->id, 'id'),
            ],
            'keterangan' => 'nullable',
            'kuota' => 'required|numeric|min:1',
            'mulai' => 'required',
            'akhir' => 'required',
        ], [
            'mulai.required' => 'Periode mulai wajib diisi',
            'akhir.required' => 'Periode akhir wajib diisi'
        ]);

        return view('backend.tahunajar.edit', compact('validator', 'tahunajar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAjar $tahunajar)
    {
        $request->validate([
            'tahun' => [
                'required',
                // Rule::unique('tahun_ajars', 'tahun')->ignore($tahunajar->id, 'id'),
            ],
            'keterangan' => 'nullable',
            'kuota' => 'required|numeric|min:1',
            'mulai' => 'required',
            'akhir' => 'required',
        ], [
            'mulai.required' => 'Periode mulai wajib diisi',
            'akhir.required' => 'Periode akhir wajib diisi'
        ]);

        DB::beginTransaction();
        try {
            TahunAjar::find($tahunajar->id)->update([
                'tahun' => $request->tahun,
                'kuota' => $request->kuota,
                'mulai' => $request->mulai,
                'akhir' => $request->akhir,
                'keterangan' => $request->keterangan,
            ]);

            Weblog::set('Memperbarui tahun ajar : ' . $request->tahun);
            DB::commit();

            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-success"><b>' . $request->tahun . '</b> berhasil diperbarui</div>',
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    public function delete(Request $request)
    {
        $getData = TahunAjar::whereIn('id', $request->id)->get();
        $datas = collect($getData)->implode('judul', ', ');

        DB::beginTransaction();
        try {
            TahunAjar::whereIn('id', $request->id)->update(['status' => false]);
            Weblog::set('Menghapus tahun ajar secara kolektif : ' . $datas);
            DB::commit();

            return $this->responOk();
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return $this->responError('Terjadi kesalahan, cobalah kembali');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAjar $tahunajar)
    {
        $status = $tahunajar->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                TahunAjar::find($tahunajar->id)->update(['status' => false]);
                Weblog::set('Menghapus tahun ajar : ' . $tahunajar->tahun);
            } else {
                TahunAjar::find($tahunajar->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan tahun ajar : ' . $tahunajar->tahun);
            }

            DB::commit();

            return response()->json([
                'pesan' => '<b>' . $tahunajar->tahun . '</b> berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return response()->json([
                'pesan' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    public function duplikat($tahunajar)
    {
        $id = (int) $tahunajar;
        $data = TahunAjar::find($id);

        DB::beginTransaction();
        try {
            $new = $data->replicate();
            $new->created_at = Carbon::now();
            $new->slug = $data->slug . '-' . strtolower(Str::random(7));
            $new->save();

            Weblog::set('Duplikat tahun ajar : ' . $data->tahun);
            DB::commit();

            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-success">Pos <b>' . $data->tahun . '</b> berhasil diduplikat</div>',
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    public function duplikatEdit(TahunAjar $tahunajar)
    {
        DB::beginTransaction();
        try {
            $new = $tahunajar->replicate();
            $new->created_at = Carbon::now();
            $new->slug = $tahunajar->slug . '-' . strtolower(Str::random(7));
            $new->save();

            Weblog::set('Duplikat & edit tahun ajar : ' . $tahunajar->tahun);
            DB::commit();

            return redirect()->route('tahunajar.edit', ['tahunajar' => $new->id]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }
}
