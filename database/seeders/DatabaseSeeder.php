<?php

namespace Database\Seeders;

use App\Models\BankSampah;
use App\Models\Category;
use App\Models\Gudang;
use App\Models\Kategori;
use App\Models\Organisasi;
use App\Models\SubKategori;
use App\Models\Trash;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    private function generateGudangKode(): string
    {
        do {
            $kode = 'GDG-' . strtoupper(Str::random(3)) . '-' . rand(100, 999);
        } while (Gudang::where('kode', $kode)->exists());

        return $kode;
    }
    public function run(): void
    {
        $induk = BankSampah::create([
            'nama' => 'Bank Sampah Induk Pekanbaru',
            'jenis' => 'induk',
            'alamat' => 'Jl. Sudirman No. 1',
            'telepon' => '081234567890',
            'kode_bank' => 'ABC',
            'jam_buka' => '08:00',
            'jam_tutup' => '16:00',
        ]);
        Gudang::create([
            'kode' => $this->generateGudangKode(),
            'bank_id' => $induk->id,
        ]);

        $bk2 = BankSampah::create([
            'nama' => 'Bank Sampah Unit Marpoyan',
            'jenis' => 'unit',
            'parent_id' => $induk->id,
            'alamat' => 'Jl. Marpoyan',
            'telepon' => '081111111111',
            'kode_bank' => 'ABD',
            'jam_buka' => '08:00',
            'jam_tutup' => '16:00',
        ]);
        Gudang::create([
            'kode' => $this->generateGudangKode(),
            'bank_id' => $bk2->id,
        ]);

        $bk3 = BankSampah::create([
            'nama' => 'Bank Sampah Unit Panam',
            'jenis' => 'unit',
            'parent_id' => $induk->id,
            'alamat' => 'Jl. Panam',
            'telepon' => '082222222222',
            'kode_bank' => 'ABE',
            'jam_buka' => '08:00',
            'jam_tutup' => '16:00',
        ]);
        Gudang::create([
            'kode' => $this->generateGudangKode(),
            'bank_id' => $bk3->id,
        ]);

        $org = Organisasi::create([
            'nama' => 'Organisasi 1',
        ]);

        $role1 = Role::create(['name' => 'supervisor']);
        $role2 = Role::create(['name' => 'admin']);
        $role3 = Role::create(['name' => 'nasabah']);

        $supervisordashboard = Permission::create(['name' => 'view supervisor dashboard']);
        $admindashboard = Permission::create(['name' => 'view admin dashboard']);
        $nasabahdashboard = Permission::create(['name' => 'view dashboard nasabah']);

        $role1->givePermissionTo($supervisordashboard);
        $role2->givePermissionTo($admindashboard);
        $role3->givePermissionTo($nasabahdashboard);

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

        for ($i = 2; $i <= 10; $i++) {
            $user = User::factory()->create([
                'name' => "user unit{$i}",
                'email' => "unit{$i}@example.com",
                'password' => bcrypt('rahasia'),
                'nik' => '13010730059600' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'nik_hash' => hash('sha256', '13010730059600' . str_pad($i, 2, '0', STR_PAD_LEFT)),
                'nomor_hp' => '0899999999' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'organisasi_id' => $org->id,
                'bank_sampah_id' => fake()->randomElement([2, 3]),
            ]);

            $user->assignRole($role2);
        }

        for ($i = 1; $i <= 20; $i++) {
            $user = User::factory()->create([
                'name' => "nasabah{$i}",
                'email' => "nasabah{$i}@example.com",
                'password' => bcrypt('rahasia'),
                'nik' => '13010730059601' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'nik_hash' => hash('sha256', '13010730059601' . str_pad($i, 2, '0', STR_PAD_LEFT)),
                'nomor_hp' => '0899999998' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'organisasi_id' => $org->id,
                'bank_sampah_id' => fake()->randomElement([2, 3]),
            ]);

            $user->assignRole($role3);
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
