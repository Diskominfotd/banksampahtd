<?php

namespace Database\Seeders;

use App\Models\BankSampah;
use App\Models\Category;
use App\Models\Kategori;
use App\Models\Organisasi;
use App\Models\SubKategori;
use App\Models\Trash;
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
        $induk = BankSampah::create([
            'nama' => 'Bank Sampah Induk Pekanbaru',
            'jenis' => 'induk',
            'alamat' => 'Jl. Sudirman No. 1',
            'telepon' => '081234567890',
            'kode_bank' => 'ABC',
        ]);
        BankSampah::create([
            'nama' => 'Bank Sampah Unit Marpoyan',
            'jenis' => 'unit',
            'parent_id' => $induk->id,
            'alamat' => 'Jl. Marpoyan',
            'telepon' => '081111111111',
            'kode_bank' => 'ABD',
        ]);

        BankSampah::create([
            'nama' => 'Bank Sampah Unit Panam',
            'jenis' => 'unit',
            'parent_id' => $induk->id,
            'alamat' => 'Jl. Panam',
            'telepon' => '082222222222',
            'kode_bank' => 'ABE',
        ]);

        $org = Organisasi::create([
            'nama' => 'Organisasi 1',
        ]);

        $role1 = Role::create(['name' => 'supervisor']);
        $role2 = Role::create(['name' => 'admin']);
        $supervisor = Permission::create(['name' => 'view admin dashboard']);
        $user1 = User::factory()->create([
            'name' => 'user induk',
            'email' => 'induk@example.com',
            'password' => bcrypt('rahasia'),
            'nik' => '1301073005960001',
            'nik_hash' => hash('sha256', '1301073005960001'),
            'nomor_hp' => '089999999993',
            'mewakili' => true,
            'organisasi_id' => $org->id,
            'bank_sampah_id' => $induk->id,
        ]);
        $user1->assignRole($role1);
        $role1->givePermissionTo($supervisor);

        for ($i = 2; $i <= 20; $i++) {
            $user = User::factory()->create([
                'name' => "user unit{$i}",
                'email' => "unit{$i}@example.com",
                'password' => bcrypt('rahasia'),
                'nik' => '13010730059600' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'nik_hash' => hash('sha256', '13010730059600' . str_pad($i, 2, '0', STR_PAD_LEFT)),
                'nomor_hp' => '0899999999' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'mewakili' => true,
                'organisasi_id' => $org->id,
                'bank_sampah_id' => fake()->randomElement([2, 3]),
            ]);

            $user->assignRole($role2);
        }
        for ($i = 1; $i <= 20; $i++) {
            $cat = Category::create([
                'name' => 'Kategori' . $i,
            ]);
            $categories[] = $cat->id;
        }
        for ($i = 1; $i <= 20; $i++) {
            $trash = Trash::create([
                'nama' => "Sampah {$i}",
                'syarat' => "Kondisi bagus {$i}",
                'category_id' => $categories[array_rand($categories)],
            ]);

            $trash->prices()->create([
                'bank_id' => 1,
                'type' => 'induk',
                'harga' => rand(1000, 10000),
            ]);
        }
    }
}
