<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Role;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelas;
use App\Models\Penerbit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class AjaxController extends Controller
{
    public function role(Request $request)
    {
        $term = $request->term;
        $data = Role::query()
            ->when($term, function ($e, $term) {
                $e->where('name', 'like', '%' . $term . '%');
            })
            ->select('id', 'name as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function provinsi(Request $request)
    {
        $term = $request->term;
        $data = Provinsi::query()
            ->when($term, function ($e, $term) {
                $e->where('provinsi', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->select('id', 'provinsi as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kota(Request $request)
    {
        $term = $request->term;
        $provinsi = $request->provinsi;
        $data = Kota::query()
            ->when($term, function ($e, $term) {
                $e->where('kota', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->when($provinsi, function ($e, $provinsi) {
                $e->where('provinsi_id', $provinsi);
            })
            ->select('id', 'kota as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function kecamatan(Request $request)
    {
        $term = $request->term;
        $kota = $request->kota;
        $data = Kecamatan::query()
            ->when($term, function ($e, $term) {
                $e->where('kecamatan', 'like', '%' . $term . '%');
            })
            ->where('status', true)
            ->where('kota_id', $kota)
            ->select('id', 'kecamatan as label');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }

    public function ganti_foto(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;
            $path = $request->path;

            switch ($path) {
                case 'buku':
                    $size_gambar = 300;
                    break;

                case 'foto':
                    $size_gambar = 300;
                    break;

                case 'anggota':
                    $size_gambar = 300;
                    break;

                case 'banner':
                    $size_gambar = 200;
                    break;

                case 'slider':
                    $size_gambar = 600;
                    break;

                case 'pos':
                    $size_gambar = 400;
                    break;

                case 'video':
                    $size_gambar = 400;
                    break;

                case 'album':
                    $size_gambar = 1000;
                    break;

                default:
                    $size_gambar = 300;
                    break;
            }

            $request->validate([
                'file' => 'required|image|max:2000'
            ]);

            $name = rand(9999, 9999999);
            $ext  = $file->getClientOriginalExtension();
            $foto = $name . '.' . $ext;

            $fullPath = $path . '/' . $foto;

            $path = $file->getRealPath();
            $thum = Image::make($path)->resize($size_gambar, $size_gambar, function ($size) {
                $size->aspectRatio();
            });

            $path = Storage::put($fullPath, $thum->stream());

            return response()->json([
                'file' => $fullPath,
            ]);
        }
    }

    public function ganti_pdf(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;
            $request->validate([
                'file' => 'required|mimes:pdf|max:2000'
            ]);

            $path = Storage::put('buku/pdf', $request->file);

            return response()->json([
                'file' => $path,
            ]);
        }
    }

    public function ganti_video(Request $request)
    {
        if ($request->has('file')) {
            $file = $request->file;

            DB::beginTransaction();
            try {
                $validator = Validator::make($request->all(), [
                    'file' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|required|max:30000'
                ]);

                if ($validator->fails()) {
                    return $this->responError($validator->errors());
                }

                $path = Storage::put('video', $request->file);
                return response()->json([
                    'file' => $path,
                ]);
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                Log::error($th->getMessage());
                return $this->responError($th->getMessage());
            }
        }
    }

    public function kategori(Request $request)
    {
        $term = $request->term;
        $data = Kategori::query()
            ->where('status', true)
            ->when($term, function ($e, $term) {
                $e->where('kategori', 'like', '%' . $term . '%');
            })
            ->select('id', 'kategori as label')
            ->orderBy('kategori', 'asc');

        if ($data->count() > 0) {
            return response()->json([
                'data'  => $data->get(),
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data'  => null,
            ]);
        }
    }
}
