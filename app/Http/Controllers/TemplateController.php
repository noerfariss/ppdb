<?php

namespace App\Http\Controllers;

use App\Facade\Weblog;
use App\Models\GrupForm;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Laratrust\LaratrustFacade as Laratrust;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:template-read')->only('index');
        $this->middleware('permission:template-create')->only(['create', 'store']);
        $this->middleware('permission:template-update')->only(['edit', 'update']);
        $this->middleware('permission:template-delete')->only('delete');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.template.index');
    }

    public function ajax(Request $request)
    {
        $status = $request->status;
        $cari = $request->cari;

        $data = Template::query()
            ->when($cari, function ($e, $cari) {
                $e->where(function ($e) use ($cari) {
                    $e->where('template', 'like', '%' . $cari . '%')->orWhere('keterangan', 'like', '%' . $cari . '%');
                });
            })
            ->where('status', $status)
            ->orderBy('id', 'desc');

        // if ($request->filled('export')) {
        // return Excel::download(new UserExport($data->get(), $request->all()), 'USER.xlsx');
        // }

        return DataTables::eloquent($data)
            ->addColumn('aksi', function ($e) {
                $btnEdit = Laratrust::isAbleTo('template-update') ? '<a href="' . route('template.edit', ['template' => $e->id]) . '" class="btn btn-xs "><i class="bx bx-edit"></i></a>' : '';
                $btnDelete = Laratrust::isAbleTo('template-delete') ?  '<a href="' . route('template.destroy', ['template' => $e->id]) . '" data-title="' . $e->judul . '" class="btn btn-xs text-danger btn-hapus"><i class="bx bx-trash"></i></a>' : '';
                $btnReload = Laratrust::isAbleTo('template-update') ? '<a href="' . route('template.destroy', ['template' => $e->id]) . '" data-title="' . $e->judul . '" data-status="' . $e->status . '" class="btn btn-outline-secondary btn-xs btn-hapus"><i class="bx bx-refresh"></i></i></a>' : '';
                $btnCopy = '<a href="' . route('template.duplikat', ['template' => $e->id]) . '" data-title="' . $e->judul . '" data-duplikatedit="' . route('template.duplikat.edit', ['template' => $e->id]) . '" class="btn btn-default btn-xs btn-copy" title="Copy"><i class="bx bx-copy-alt"></i></i></a>';
                if ($e->status == true) {
                    return $btnCopy . ' ' .  $btnEdit . ' ' . $btnDelete;
                } else {
                    return $btnReload;
                }
            })
            ->rawColumns(['mulai', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $validator = JsValidatorFacade::make([
            'template' => 'required',
            'keterangan' => 'nullable',
        ]);

        $grups = GrupForm::query()
            ->with('form', fn ($e) => $e->orderBy('urut'))
            ->where('status', true)
            ->orderBy('urut')
            ->get();

        return view('backend.template.create', compact('validator', 'grups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'template' => 'required',
            'keterangan' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $template = Template::create([
                'template' => $request->template,
                'keterangan' => $request->keterangan,
            ]);

            $this->__templateForm($request, $template);

            Weblog::set('Menambahkan template : ' . $request->template);
            DB::commit();

            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-success"><b>' . $request->template . '</b> berhasil ditambahkan</div>',
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    private function __templateForm($request, $template, $edit = false)
    {
        if ($request->exists('form')) {
            if ($edit) {
                DB::table('template_form')->where('template_id', $template->id)->delete();
            }

            foreach ($request->form as $item) {
                $entry[] = [
                    'template_id' => $template->id,
                    'form_id' => $item,
                    'wajib' => in_array($item, $request->form_wajib) ? true : false,
                    'created_at' => Carbon::now(),
                ];
            }

            DB::table('template_form')->insert($entry);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
        $validator = JsValidatorFacade::make([
            'template' => 'required',
            'keterangan' => 'nullable',
        ]);

        $grups = GrupForm::query()
            ->with('form', function ($e) use ($template) {
                $e->leftJoin('template_form as b', function ($leftJoin) use ($template) {
                    $leftJoin->on('b.form_id', '=', 'id')->where('template_id', $template->id);
                });
            })
            ->withCount([
                'form' => function ($e) use ($template) {
                    $e->join('template_form as b', function ($join) use ($template) {
                        $join->on('b.form_id', '=', 'id')->where('template_id', $template->id);
                    });
                }
            ])
            ->where('status', true)
            ->orderBy('urut')
            ->get();

        return view('backend.template.edit', compact('validator', 'template', 'grups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'template' => 'required',
            'keterangan' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            Template::find($template->id)->update([
                'template' => $request->template,
                'keterangan' => $request->keterangan,
            ]);

            $this->__templateForm($request, $template, true);

            Weblog::set('Memperbarui tahun ajar : ' . $request->template);
            DB::commit();

            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-success"><b>' . $request->template . '</b> berhasil diperbarui</div>',
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
        $getData = Template::whereIn('id', $request->id)->get();
        $datas = collect($getData)->implode('judul', ', ');

        DB::beginTransaction();
        try {
            Template::whereIn('id', $request->id)->update(['status' => false]);
            Weblog::set('Menghapus template secara kolektif : ' . $datas);
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
    public function destroy(Template $template)
    {
        $status = $template->status;
        DB::beginTransaction();
        try {
            if ($status == true) {
                Template::find($template->id)->update(['status' => false]);
                Weblog::set('Menghapus template : ' . $template->template);
            } else {
                Template::find($template->id)->update(['status' => true]);
                Weblog::set('Mengaktifkan template : ' . $template->template);
            }

            DB::commit();

            return response()->json([
                'pesan' => '<b>' . $template->template . '</b> berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning($th->getMessage());
            return response()->json([
                'pesan' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    public function duplikat($template)
    {
        $id = (int) $template;
        $data = Template::find($id);

        DB::beginTransaction();
        try {
            $new = $data->replicate();
            $new->created_at = Carbon::now();
            $new->save();

            $form = DB::table('template_form')->where('template_id', $id);
            if ($form->count() > 0) {
                foreach ($form->get() as $item) {
                    $templateform[] = [
                        'template_id' => $new->id,
                        'form_id' => $item->form_id,
                        'wajib' => $item->wajib,
                        'created_at' => Carbon::now(),
                    ];
                }

                DB::table('template_form')->insert($templateform);
            }

            Weblog::set('Duplikat template : ' . $data->template);
            DB::commit();

            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-success">Pos <b>' . $data->template . '</b> berhasil diduplikat</div>',
            ]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }

    public function duplikatEdit(Template $template)
    {
        DB::beginTransaction();
        try {
            $new = $template->replicate();
            $new->created_at = Carbon::now();
            $new->save();

            $form = DB::table('template_form')->where('template_id', $template->id);
            if ($form->count() > 0) {
                foreach ($form->get() as $item) {
                    $templateform[] = [
                        'template_id' => $new->id,
                        'form_id' => $item->form_id,
                        'wajib' => $item->wajib,
                        'created_at' => Carbon::now(),
                    ];
                }

                DB::table('template_form')->insert($templateform);
            }

            Weblog::set('Duplikat & edit template : ' . $template->template);
            DB::commit();

            return redirect()->route('template.edit', ['template' => $new->id]);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with([
                'pesan' => '<div class="alert alert-danger">Terjadi kesalahan, cobalah kembali</div>',
            ]);
        }
    }
}
