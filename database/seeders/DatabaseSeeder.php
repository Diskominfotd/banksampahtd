<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Organisasi;
use App\Models\SubKategori;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $org = Organisasi::create([
            'nama' => 'Organisasi 1',
        ]);

        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'general']);
        $admin = Permission::create(['name' => 'view admin dashboard']);
        $user1 = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('rahasia'),
            'nik' => '1301073005960001',
            'nik_hash' => hash('sha256', '1301073005960001'),
            'nomor_hp' => '089999999993',
            'rekening' => '111111111',
            'mewakili' => true,
            'organisasi_id' => $org->id,
        ]);
        $user1->assignRole($role1);
        $role1->givePermissionTo($admin);

        $kategori = Kategori::create([
            'nama' => 'Plastik',
        ]);

        $subKategori = SubKategori::create([
            'nama' => 'Botol Plastik',
            'harga' => 5000,
            'kategori_id' => $kategori->id,
        ]);

    }
}
