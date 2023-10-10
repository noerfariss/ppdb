<?php

namespace App\Providers;

use App\Models\Umum;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PhpOffice\PhpSpreadsheet\Calculation\Database\DVar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($e) {
            $umum = Umum::first();

            $e->with([
                'tanggal_sekarang' => Carbon::now()->timezone(zona_waktu())->isoFormat('dddd, DD MMMM YYYY'),
                'title_web' => $umum->nama,
                'logo' => ($umum->logo === NULL || $umum->logo === '' || $umum->logo == 'logo') ? '<h2 style="margin:.5rem 0 !important; font-weight:bold;">admin</h2>' : '<img src="' . url('/storage/' . $umum->logo) . '" height="50">',
                'logo_frontend' => $umum->logo,
                'favicon' => ($umum->favicon === NULL || $umum->favicon === '' || $umum->favicon == 'favicon') ? 'Admin' : '' . url('/storage/foto/' . $umum->favicon) . '',
            ]);
        });

        // $menus = $this->dataMenus();
        // view()->share('menus', $menus);

        Schema::defaultStringLength(191);
    }

    // public function dataMenus()
    // {
    //     $sql = "select
    //                 a.*
    //             from (
    //                 select
    //                     a.id,
    //                     a.parent_id,
    //                     '' as parent,
    //                     1 as level,
    //                     a.keterangan,
    //                     a.target,
    //                     case when a.artikel_id is not null then concat('pos/', b.slug)
    //                         when a.kategori_id is not null then concat('kategori/', c.slug)
    //                         when a.halaman_id is not null then concat('halaman/', d.slug)
    //                         when a.link_id is not null then e.url
    //                     else a.keterangan end as slug,

    //                     case when a.artikel_id is not null then b.judul
    //                         when a.kategori_id is not null then c.kategori
    //                         when a.halaman_id is not null then d.judul
    //                     else a.keterangan end as label ,

    //                     a.urutan,
    //                     concat(a.urutan, '|', a.id) as sort
    //                 from menus a
    //                     left join pos b on b.id = a.artikel_id
    //                     left join kategoris c on c.id = a.kategori_id
    //                     left join halamen d on d.id = a.halaman_id
    //                     left join menu_link e on e.id = a.link_id
    //                 where
    //                     a.parent_id is null

    //             union all

    //                 select
    //                     a.id,
    //                     a.parent_id,
    //                     e.keterangan as parent,
    //                     2 as level,
    //                     a.keterangan,
    //                     a.target,
    //                     case when a.artikel_id is not null then concat('pos/', b.slug)
    //                         when a.kategori_id is not null then concat('kategori/', c.slug)
    //                         when a.halaman_id is not null then concat('halaman/', d.slug)
    //                         when a.link_id is not null then ee.url
    //                     else a.keterangan end as slug,

    //                     case when a.artikel_id is not null then b.judul
    //                         when a.kategori_id is not null then c.kategori
    //                         when a.halaman_id is not null then d.judul
    //                     else a.keterangan end as label,

    //                     a.urutan,
    //                     concat(e.urutan, '|', a.parent_id, '|', a.urutan, '|', a.id) as sort
    //                 from menus a
    //                     left join pos b on b.id = a.artikel_id
    //                     left join kategoris c on c.id = a.kategori_id
    //                     left join halamen d on d.id = a.halaman_id
    //                     left join menu_link ee on ee.id = a.link_id
    //                     join menus e on e.id = a.parent_id
    //                 where
    //                     a.parent_id is not null
    //             ) a
    //             order by a.sort asc ";

    //     $data = DB::select($sql);
    //     $menus = $this->buildTree($data);
    //     return $menus;
    // }

    // public function buildTree(array $elements, $parentId = null)
    // {
    //     $branch = [];

    //     foreach ($elements as $element) {
    //         if ($element->parent_id === $parentId) {
    //             $children = $this->buildTree($elements, $element->id);
    //             if ($children) {
    //                 $element->parent_id = $children;
    //             }
    //             $branch[] = $element;
    //         }
    //     }

    //     return $branch;
    // }
}
