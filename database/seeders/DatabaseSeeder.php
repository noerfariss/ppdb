<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Form;
use App\Models\GrupForm;
use App\Models\Permission;
use App\Models\Role;
use App\Models\TahunAjar;
use App\Models\Template;
use App\Models\Umum;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProvinsiSeeder::class,
            KotaSeeder::class,
            KecamatanSeeder::class,
        ]);

        $user = User::create([
            'nama' => 'superadmin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Superadmin',
            'description' => 'untuk master admin',
        ]);

        $user->attachRole($role);

        $permissions = ['permission', 'role', 'umum', 'user', 'halaman', 'tahunajar', 'template'];
        foreach ($permissions as $item) {
            Permission::create([
                'grup' => $item,
                'name' => $item . '-create',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-read',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-update',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-delete',
            ]);

            Permission::create([
                'grup' => $item,
                'name' => $item . '-print',
            ]);
        }

        $permissionID = [];
        $getPermissions = Permission::all();
        foreach ($getPermissions as $row) {
            $permissionID[] = $row->id;
        }

        $role->attachPermissions($permissionID);

        Umum::create([
            'nama' => env('APP_NAME'),
            'timezone' => 'Asia/Jakarta'
        ]);


        // group form
        $grupForm = [
            [
                'id' => 1,
                'grup' => 'data siswa',
                'urut' => 1,
                'slug' => 'data-siswa',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'grup' => 'data sekolah',
                'urut' => 2,
                'slug' => 'data-sekolah',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'grup' => 'data ayah',
                'urut' => 3,
                'slug' => 'data-ayah',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'grup' => 'data ibu',
                'urut' => 4,
                'slug' => 'data-ibu',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'grup' => 'data wali',
                'urut' => 5,
                'slug' => 'data-wali',
                'created_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'grup' => 'data berkas',
                'urut' => 7,
                'slug' => 'data-berkas',
                'created_at' => Carbon::now(),
            ]
        ];

        GrupForm::insert($grupForm);

        // form
        $form = [
            [
                'grup_id' => 1,
                'label' => 'nisn',
                'urut' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 1,
                'label' => 'nama siswa',
                'urut' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 1,
                'label' => 'ttl',
                'urut' => 3,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 1,
                'label' => 'no. kependudukan',
                'urut' => 4,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 1,
                'label' => 'jenis kelamin',
                'urut' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 1,
                'label' => 'agama',
                'urut' => 6,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 1,
                'label' => 'alamat tinggal',
                'urut' => 7,
                'created_at' => Carbon::now(),
            ],

            // sekolah
            [
                'grup_id' => 2,
                'label' => 'asal sekolah',
                'urut' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 2,
                'label' => 'npsn sekolah asal',
                'urut' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 2,
                'label' => 'alamat sekolah asal',
                'urut' => 3,
                'created_at' => Carbon::now(),
            ],

            // ayah
            [
                'grup_id' => 3,
                'label' => 'nama ayah',
                'urut' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 3,
                'label' => 'ttl',
                'urut' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 3,
                'label' => 'agama',
                'urut' => 3,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 3,
                'label' => 'nik',
                'urut' => 4,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 3,
                'label' => 'alamat ktp',
                'urut' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 3,
                'label' => 'no. telepon',
                'urut' => 6,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 3,
                'label' => 'pekerjaan',
                'urut' => 7,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 3,
                'label' => 'gaji',
                'urut' => 8,
                'created_at' => Carbon::now(),
            ],

            // ibu
            [
                'grup_id' => 4,
                'label' => 'nama ibu',
                'urut' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 4,
                'label' => 'ttl',
                'urut' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 4,
                'label' => 'agama',
                'urut' => 3,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 4,
                'label' => 'nik',
                'urut' => 4,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 4,
                'label' => 'alamat ktp',
                'urut' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 4,
                'label' => 'no. telepon',
                'urut' => 6,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 4,
                'label' => 'pekerjaan',
                'urut' => 7,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 4,
                'label' => 'gaji',
                'urut' => 8,
                'created_at' => Carbon::now(),
            ],

            // wali
            [
                'grup_id' => 5,
                'label' => 'nama wali',
                'urut' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 5,
                'label' => 'ttl',
                'urut' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 5,
                'label' => 'agama',
                'urut' => 3,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 5,
                'label' => 'nik',
                'urut' => 4,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 5,
                'label' => 'alamat ktp',
                'urut' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 5,
                'label' => 'no. telepon',
                'urut' => 6,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 5,
                'label' => 'pekerjaan',
                'urut' => 7,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 5,
                'label' => 'gaji',
                'urut' => 8,
                'created_at' => Carbon::now(),
            ],

            // berkas
            [
                'grup_id' => 6,
                'label' => 'foto siswa',
                'urut' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 6,
                'label' => 'scan kk',
                'urut' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 6,
                'label' => 'print out nisn',
                'urut' => 3,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 6,
                'label' => 'akte lahir',
                'urut' => 4,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 6,
                'label' => 'ktp ayah',
                'urut' => 5,
                'created_at' => Carbon::now(),
            ],
            [
                'grup_id' => 6,
                'label' => 'ktp ibu',
                'urut' => 6,
                'created_at' => Carbon::now(),
            ],
        ];

        Form::insert($form);

        // template & tahun ajar
        Template::create([
            'id' => 1,
            'template' => 'template coba',
            'keterangan' => 'PPDB Reguler 2023/2024',
        ]);

        $dataForm = Form::all();
        foreach ($dataForm as $item) {
            $templateForm[] = [
                'template_id' => 1,
                'form_id' => $item->id,
                'wajib' => true,
                'created_at' => Carbon::now(),
            ];
        }

        DB::table('template_form')->insert($templateForm);

        TahunAjar::create([
            'tahun' => '2023/2024',
            'keterangan' => 'PPDB REGULER 2023/2024',
            'kuota' => 200,
            'mulai' => date('Y-m-01 02:00'),
            'akhir' => date('Y-m-27 12:00'),
            'slug' => '2023-2024',
            'template_id' => 1,
        ]);
    }
}
