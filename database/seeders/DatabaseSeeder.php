<?php

namespace Database\Seeders;

use App\Models\BankSampah;
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
        $induk = BankSampah::create([
            'nama' => 'Bank Sampah Induk Pekanbaru',
            'jenis' => 'induk',
            'alamat' => 'Jl. Sudirman No. 1',
            'telepon' => '081234567890',
        ]);
        BankSampah::create([
            'nama' => 'Bank Sampah Unit Marpoyan',
            'jenis' => 'unit',
            'parent_id' => $induk->id,
            'alamat' => 'Jl. Marpoyan',
            'telepon' => '081111111111',
        ]);

        BankSampah::create([
            'nama' => 'Bank Sampah Unit Panam',
            'jenis' => 'unit',
            'parent_id' => $induk->id,
            'alamat' => 'Jl. Panam',
            'telepon' => '082222222222',
        ]);

        $org = Organisasi::create([
            'nama' => 'Organisasi 1',
        ]);

        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'general']);
        $admin = Permission::create(['name' => 'view admin dashboard']);
        $user1 = User::factory()->create([
            'name' => 'user induk',
            'email' => 'induk@example.com',
            'password' => bcrypt('rahasia'),
            'nik' => '1301073005960001',
            'nik_hash' => hash('sha256', '1301073005960001'),
            'nomor_hp' => '089999999993',
            'rekening' => '111111111',
            'mewakili' => true,
            'organisasi_id' => $org->id,
            'bank_sampah_id' => $induk->id,
        ]);
        $user1->assignRole($role1);
        $role1->givePermissionTo($admin);

         $user2 = User::factory()->create([
            'name' => 'user unit',
            'email' => 'unit@example.com',
            'password' => bcrypt('rahasia'),
            'nik' => '1301073005960002',
            'nik_hash' => hash('sha256', '1301073005960002'),
            'nomor_hp' => '089999999992',
            'rekening' => '111111112',
            'mewakili' => true,
            'organisasi_id' => $org->id,
            'bank_sampah_id' => 2,
        ]);
         $user2->assignRole($role1);
    }
}
