<?php

namespace Database\Seeders;

use App\Models\BankSampah;
use App\Models\BukuTabungan;
use App\Models\Category;
use App\Models\Gudang;
use App\Models\Kategori;
use App\Models\Organisasi;
use App\Models\Pengeluaran;
use App\Models\Setoran;
use App\Models\SubKategori;
use App\Models\Transaksi;
use App\Models\TransaksiBongkarGudang;
use App\Models\Trash;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
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
            'nama' => 'Bank Sampah Induk',
            'jenis' => 'induk',
            'alamat' => 'Jl. Sudirman No. 1',
            'telepon' => '081234567890',
            'kode_bank' => 'IDK',
            'jam_buka' => '08:00',
            'jam_tutup' => '16:00',
        ]);
        Gudang::create([
            'kode' => $this->generateGudangKode(),
            'bank_id' => $induk->id,
        ]);

        $bk2 = BankSampah::create([
            'nama' => 'Bank Sampah Unit Darling',
            'jenis' => 'unit',
            'parent_id' => $induk->id,
            'alamat' => 'Jl. Darling No. 2',
            'telepon' => '081234567891',
            'kode_bank' => 'DRI',
            'jam_buka' => '08:00',
            'jam_tutup' => '16:00',
        ]);
        Gudang::create([
            'kode' => $this->generateGudangKode(),
            'bank_id' => $bk2->id,
        ]);

        // $bk3 = BankSampah::create([
        //     'nama' => 'Bank Sampah Unit Panam',
        //     'jenis' => 'unit',
        //     'parent_id' => $induk->id,
        //     'alamat' => 'Jl. Panam',
        //     'telepon' => '082222222222',
        //     'kode_bank' => 'ABE',
        //     'jam_buka' => '08:00',
        //     'jam_tutup' => '16:00',
        // ]);
        // Gudang::create([
        //     'kode' => $this->generateGudangKode(),
        //     'bank_id' => $bk3->id,
        // ]);

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
            'nik' => '1234567890123450',
            'nik_hash' => hash('sha256', '1234567890123450'),
            'nomor_hp' => '081234567892',
            'mewakili' => true,
            'organisasi_id' => $org->id,
            'bank_sampah_id' => $induk->id,
        ]);
        $user1->assignRole($role1);

        // for ($i = 2; $i <= 10; $i++) {
        $user2 = User::factory()->create([
            'name' => 'Admin Unit Darling',
            'email' => 'adminDarling@example.com',
            'password' => bcrypt('rahasia'),
            'nik' => '1234567890123451',
            'nik_hash' => hash('sha256', '1234567890123451'),
            'nomor_hp' => '081234567893',
            'organisasi_id' => $org->id,
            'bank_sampah_id' => 2,
        ]);

        $user2->assignRole($role2);
        // }

        // for ($i = 1; $i <= 20; $i++) {
        //     $user = User::factory()->create([
        //         'name' => "nasabah{$i}",
        //         'email' => "nasabah{$i}@example.com",
        //         'password' => bcrypt('rahasia'),
        //         'nik' => '13010730059601' . str_pad($i, 2, '0', STR_PAD_LEFT),
        //         'nik_hash' => hash('sha256', '13010730059601' . str_pad($i, 2, '0', STR_PAD_LEFT)),
        //         'nomor_hp' => '0899999998' . str_pad($i, 2, '0', STR_PAD_LEFT),
        //         'organisasi_id' => $org->id,
        //         'bank_sampah_id' => fake()->randomElement([2, 3]),
        //     ]);

        //     $user->assignRole($role3);
        // }

        // for ($i = 1; $i <= 20; $i++) {
        //     $cat = Category::create([
        //         'name' => 'Kategori' . $i,
        //     ]);
        //     $categories[] = $cat->id;
        // }
        // for ($i = 1; $i <= 20; $i++) {
        //     $trash = Trash::create([
        //         'nama' => "Sampah {$i}",
        //         'syarat' => "Kondisi bagus {$i}",
        //         'category_id' => $categories[array_rand($categories)],
        //     ]);

        //     $trash->prices()->create([
        //         'bank_id' => 1,
        //         'type' => 'induk',
        //         'harga' => rand(1000, 10000),
        //     ]);
        // }

        $categories = [
            'AKI' => Category::firstOrCreate(['name' => 'AKI']),
            'KALENG' => Category::firstOrCreate(['name' => 'KALENG']),
            'MINYAK' => Category::firstOrCreate(['name' => 'MINYAK']),
            'BESI' => Category::firstOrCreate(['name' => 'BESI']),
            'PLASTIK' => Category::firstOrCreate(['name' => 'PLASTIK']),
            'KERTAS' => Category::firstOrCreate(['name' => 'KERTAS']),
            'LAINNYA' => Category::firstOrCreate(['name' => 'LAINNYA']),
        ];

        $data = [
            ['nama' => 'HVS', 'harga' => 1105, 'category' => 'KERTAS', 'syarat' => 'Kertas HVS bekas, tidak basah/lapuk'],
            ['nama' => 'Kardus', 'harga' => 845, 'category' => 'KERTAS', 'syarat' => 'Kardus kering, tidak basah'],
            ['nama' => 'Kertas Campur', 'harga' => 390, 'category' => 'KERTAS', 'syarat' => 'Campuran jenis kertas, tidak basah'],
            ['nama' => 'Koran', 'harga' => 1300, 'category' => 'KERTAS', 'syarat' => 'Koran bekas, tidak basah/lapuk'],
            ['nama' => 'Kertas Hancur', 'harga' => 845, 'category' => 'KERTAS', 'syarat' => 'Kertas hancur/sobek, tidak basah'],
            ['nama' => 'Sak Telur', 'harga' => 650, 'category' => 'KERTAS', 'syarat' => 'Sak/karton telur bekas'],

            ['nama' => 'Plastik Campur (Karah-karah)', 'harga' => 520, 'category' => 'PLASTIK', 'syarat' => 'Campuran plastik, kondisi apa adanya'],
            ['nama' => 'Botol Plastik Minuman', 'harga' => 780, 'category' => 'PLASTIK', 'syarat' => 'Botol plastik minuman bekas'],
            ['nama' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'harga' => 1625, 'category' => 'PLASTIK', 'syarat' => 'Tanpa tutup dan label, bersih dan kering'],
            ['nama' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'harga' => 1950, 'category' => 'PLASTIK', 'syarat' => 'Tanpa tutup plastik, bersih dan kering'],
            ['nama' => 'Gelas Plastik Minuman', 'harga' => 650, 'category' => 'PLASTIK', 'syarat' => 'Kondisi apa adanya'],

            ['nama' => 'Besi Padu', 'harga' => 1300, 'category' => 'BESI', 'syarat' => 'Besi padat/tebal'],
            ['nama' => 'Besi Campur', 'harga' => 975, 'category' => 'BESI', 'syarat' => 'Campuran jenis besi'],

            ['nama' => 'Kaleng Campur', 'harga' => 975, 'category' => 'KALENG', 'syarat' => 'Campuran jenis kaleng'],
            ['nama' => 'Kaleng Minuman Alumunium (Alma)', 'harga' => 6500, 'category' => 'KALENG', 'syarat' => 'Kaleng minuman aluminium bersih'],

            ['nama' => 'Aki', 'harga' => 8500, 'category' => 'AKI', 'syarat' => 'Aki bekas, boleh kondisi rusak'],

            ['nama' => 'Minyak Jelantah', 'harga' => 4000, 'category' => 'MINYAK', 'syarat' => 'Minyak jelantah, disaring dari kotoran kasar'],
            ['nama' => 'Campur-campur', 'harga' => 680, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Kulit', 'harga' => 680, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Buram', 'harga' => 800, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Tutup botol', 'harga' => 5000, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Plastik Minyak', 'harga' => 800, 'category' => 'PLASTIK', 'syarat' => '-'],
            ['nama' => 'Botol kaca', 'harga' => 500, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Magic', 'harga' => 1000, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Botol campur', 'harga' => 500, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Ampli Rusak', 'harga' => 1000, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Kipas Angin', 'harga' => 1000, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Karah', 'harga' => 1000, 'category' => 'LAINNYA', 'syarat' => '-'],
            ['nama' => 'Kertas Putih', 'harga' => 1500, 'category' => 'KERTAS', 'syarat' => '-'],
            ['nama' => 'Pet Bersih', 'harga' => 1500, 'category' => 'LAINNYA', 'syarat' => '-'],
        ];

        foreach ($data as $item) {
            $trash = Trash::create([
                'nama' => $item['nama'],
                'syarat' => $item['syarat'],
                'category_id' => $categories[$item['category']]->id,
            ]);

            $trash->prices()->create([
                'bank_id' => 1,
                'type' => 'induk',
                'harga' => $item['harga'],
            ]);
        }

        $adminUnitDarlingName = ['Adrian Darling', 'Naya Darling', 'Nova Darling', 'Tari Darling', 'Tuti Darling', 'Miza Darling', 'Oca Darling', 'lusy Darling', 'yeni Darling', 'Eka Darling'];

        foreach ($adminUnitDarlingName as $index => $name) {
            $nik = '12345678901235' . str_pad($index + 2, 2, '0', STR_PAD_LEFT);
            $hp = '08123456789' . str_pad($index + 2, 2, '0', STR_PAD_LEFT);

            $user = User::factory()->create([
                'name' => $name,
                'email' => 'adminDarling' . ($index + 1) . '@example.com',
                'password' => bcrypt('rahasia'),
                'nik' => $nik,
                'nik_hash' => hash('sha256', $nik),
                'nomor_hp' => $hp,
                'bank_sampah_id' => 2,
            ]);
            $user->assignRole($role2);
        }

        $names = [
            'ARIF GANI (KEC. TJ EMAS)',
            'Irza Fhaizati Anas',
            'NOFI HENDRI',
            'DEDIWAN PUTRA',
            'M NAZIR (PDAM)',
            'SRI MULYANI (PERTANIAN)',
            'IRWANDI (BAGIAN ORGANISASI)',
            'Dian Kumala Sari',
            'Mizanul Huda',
            'Puskesmas Sungayang (dr Iranovitha Dewy)',
            'Zulderi Evanita',
            'dr. DWINANDA EMIRA',
            'Dinda Dwi Lestari',
            'Besnaya Zalenzi',
            'Teddy Yuliswar',
            'Watma Yenti',
            'Khairiah Febri',
            'BPKD',
            'Mona Vera Wati Halda',
            'Winna Anggraeni',
            'Ivanka Nadya Syaura',
            'M. Daud (Renkeu Sekda)',
            'Juni Fiwaldi',
            'DESI RIMA (INSPEKTORAT)',
            'Sugesti Permana',
            'IRFAN FITRIADESH',
            'MAHDAYA LANDAN',
            'ADRIYANTI RUSTAM (BAPEDA)',
            'Nathan Raiffarde',
            'BPBD (Iihuska)',
            'Rani Novita Sari',
            'Elsa Maylanda',
            'Feri Afni',
            'Yeri Junaidi',
            'Vipiet Adriani (Bidang KPP)',
            'Lia',
            'FAUZIA ALVINO',
            'Rini',
            'Ira',
            'Yuni Astuti',
            'Busnika Hamidi, ST. MT',
            'Arsyifa P Adsha',
            'Connie Elfina',
            'SOFA NOFA (PMPTSP)',
            'Nanda',
            'Ocha',
            'NEDRI ANALITA',
            'Emelda',
            'AZZA',
            'HARNIWATI',
            'HERISON (KESBANGPOL)',
            'POM TK Permata Bunda',
            'Koperasi Desa Merah Putih Syariah Nagari Gunuang Rajo Tanah Datar ( Rahayatul Asni)',
            'AUREL AULIANISA',
            'Hasby Abyan Shauqi',
            'MUSTIKA SUARMAN (BAG. PBJ)',
            'Ririyanti Zahrul',
            'ZATINAL (Dinas Pariwisata Pemuda dan Olahraga)',
            'BADAN KESBANGPOL',
            'MTs Surau Quran Boarding School',
            'ABDURRAHMAN HADI (SEKDA)',
            'Mis',
            'Vini Ariani Erwin',
            'Nofriadi',
            'UTRI SATRIA PUTRA (BAGIAN AP)',
            'POPPY AZIZ',
            'Jusdawenti',
            'Ira Fitria Elisman',
            'Dewi Astuti',
            'Eva Darmasari',
            'Septia',
            'BANK NAGARI',
            'Yusuf Mardotillah',
            'Leni Mardiastuti',
            'Muhammad Alfatih Faeyza',
            'ADE PUTRA',
            'Dimas Fadhali Syafiq',
            'Lovely Harman Zulyadi',
            'DEDI TRIWIDONO S.STP (KOMINFO)',
            'Roseyanti',
            'Yeni Hanifah',
            'Annisa Mardhotillah',
            'Ainul Jannah ME',
            'MARDIAH',
            'Nagari Pangian',
            'Yeni Suhastri',
            'NOFI HENDRI',
            'Puskesmas sungayang(dr iranovitha dewy)',
            'Nadea Annisa',
        ];

        $nikStart = 1234567890123501;
        $hpStart = 8120001001;

        foreach ($names as $index => $name) {
            $nik = (string) ($nikStart + $index);
            $hp = '0' . ($hpStart + $index);

            $nasabah = User::factory()->create([
                'name' => $name,
                'email' => 'email' . ($index + 1) . '@example.com',
                'password' => bcrypt('rahasia'),
                'nik' => $nik,
                'nik_hash' => hash('sha256', $nik),
                'nomor_hp' => $hp,
                'bank_sampah_id' => 2,
            ]);
            $exists = BukuTabungan::where('user_id', $user->id)->where('bank_id', $nasabah->bank_sampah_id)->exists();
            $bank = BankSampah::findOrFail(2);
            do {
                $nomorRekening = $bank->kode_bank . '-' . str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
            } while (BukuTabungan::where('nomor_rekening', $nomorRekening)->exists());

            BukuTabungan::create([
                'nama' => $nasabah->name,
                'nomor_rekening' => $nomorRekening,
                'user_id' => $nasabah->id,
                'bank_id' => 2,
            ]);
            $nasabah->assignRole($role3);
        }

        $transaksidata = [
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '20/06/25', 'berat' => 1.34, 'jenis' => 'Kaleng Campur', 'total' => 1350],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '20/06/25', 'berat' => 0.44, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 440],
            ['nama' => 'Irza Fhaizati Anas', 'tanggal' => '20/06/25', 'berat' => 4.33, 'jenis' => 'Kardus', 'total' => 6495],
            ['nama' => 'NOFI HENDRI', 'tanggal' => '20/06/25', 'berat' => 2.145, 'jenis' => 'Kardus', 'total' => 3217],
            ['nama' => 'NOFI HENDRI', 'tanggal' => '20/06/25', 'berat' => 1.825, 'jenis' => 'Kaleng Campur', 'total' => 1825],
            ['nama' => 'NOFI HENDRI', 'tanggal' => '06/06/25', 'berat' => 2.25, 'jenis' => 'HVS', 'total' => 3825],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '20/06/25', 'berat' => 0.815, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 815],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '20/06/25', 'berat' => 3.19, 'jenis' => 'Kardus', 'total' => 3190],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '20/06/25', 'berat' => 0.5, 'jenis' => 'Botol Plastik Minuman', 'total' => 750],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '20/06/25', 'berat' => 2.9, 'jenis' => 'Kertas Campur', 'total' => 2900],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '20/06/25', 'berat' => 0.065, 'jenis' => 'Botol Plastik Minuman', 'total' => 97],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '20/06/25', 'berat' => 2.115, 'jenis' => 'Kertas Campur', 'total' => 2115],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '20/06/25', 'berat' => 2.095, 'jenis' => 'HVS', 'total' => 3561],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '20/06/25', 'berat' => 5.4, 'jenis' => 'Kardus', 'total' => 5400],
            ['nama' => 'IRWANDI (BAGIAN ORGANISASI)', 'tanggal' => '20/06/25', 'berat' => 4.65, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 6975],
            ['nama' => 'IRWANDI (BAGIAN ORGANISASI)', 'tanggal' => '20/06/25', 'berat' => 5.575, 'jenis' => 'HVS', 'total' => 9477],
            ['nama' => 'IRWANDI (BAGIAN ORGANISASI)', 'tanggal' => '20/06/25', 'berat' => 0.59, 'jenis' => 'Kertas Campur', 'total' => 590],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '20/06/25', 'berat' => 3.71, 'jenis' => 'Kertas Campur', 'total' => 3710],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '20/06/25', 'berat' => 3.15, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 472],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '20/06/25', 'berat' => 1.465, 'jenis' => 'Kaleng Campur', 'total' => 1465],
            ['nama' => 'Mizanul Huda', 'tanggal' => '20/06/25', 'berat' => 1.69, 'jenis' => 'Kardus', 'total' => 2535],
            ['nama' => 'Mizanul Huda', 'tanggal' => '20/06/25', 'berat' => 0.69, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 690],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '20/06/25', 'berat' => 1.14, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1140],
            ['nama' => 'Zulderi Evanita ', 'tanggal' => '25/06/25', 'berat' => 7.85, 'jenis' => 'Kertas Campur', 'total' => 7850],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '25/06/25', 'berat' => 7.85, 'jenis' => 'Kertas Campur', 'total' => 7850],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '01/07/25', 'berat' => 1.0, 'jenis' => 'HVS', 'total' => 2000],
            ['nama' => 'Dinda Dwi Lestari', 'tanggal' => '04/07/25', 'berat' => 2.98, 'jenis' => 'Kardus', 'total' => 3799],
            ['nama' => 'Besnaya Zalenzi', 'tanggal' => '04/07/25', 'berat' => 3.8, 'jenis' => 'Kardus', 'total' => 4845],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '04/07/25', 'berat' => 2.65, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2250],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '04/07/25', 'berat' => 0.8, 'jenis' => 'Kertas Campur', 'total' => 340],
            ['nama' => 'Teddy Yuliswar', 'tanggal' => '04/07/25', 'berat' => 4.95, 'jenis' => 'Botol Plastik Minuman', 'total' => 1683],
            ['nama' => 'Watma yenti', 'tanggal' => '04/07/25', 'berat' => 2.8, 'jenis' => 'Kardus', 'total' => 2800],
            ['nama' => 'Watma yenti', 'tanggal' => '04/07/25', 'berat' => 0.8, 'jenis' => 'Botol Plastik Minuman', 'total' => 1200],
            ['nama' => 'Watma yenti', 'tanggal' => '04/07/25', 'berat' => 3.4, 'jenis' => 'Kertas Campur', 'total' => 2720],
            ['nama' => 'Watma yenti', 'tanggal' => '04/07/25', 'berat' => 1.4, 'jenis' => 'Kaleng Campur', 'total' => 1400],
            ['nama' => 'Irza Fhaizati Anas', 'tanggal' => '04/07/25', 'berat' => 2.89, 'jenis' => 'Kertas Campur', 'total' => 2890],
            ['nama' => 'Khairiah Febri', 'tanggal' => '04/07/25', 'berat' => 38.8, 'jenis' => 'HVS', 'total' => 65960],
            ['nama' => 'BPKD', 'tanggal' => '04/07/25', 'berat' => 0.45, 'jenis' => 'Kertas Campur', 'total' => 2760],
            ['nama' => 'Mona Vera Wati Halda ', 'tanggal' => '04/07/25', 'berat' => 2.85, 'jenis' => 'HVS', 'total' => 4845],
            ['nama' => 'Mona Vera Wati Halda ', 'tanggal' => '04/07/25', 'berat' => 0.5, 'jenis' => 'Kardus', 'total' => 500],
            ['nama' => 'Winna Anggraeni', 'tanggal' => '04/07/25', 'berat' => 8.1, 'jenis' => 'HVS', 'total' => 13770],
            ['nama' => 'Ivanka Nadya Syaura', 'tanggal' => '04/07/25', 'berat' => 0.7, 'jenis' => 'Botol Plastik Minuman', 'total' => 1050],
            ['nama' => 'Ivanka Nadya Syaura', 'tanggal' => '04/07/25', 'berat' => 16.65, 'jenis' => 'HVS', 'total' => 28305],
            ['nama' => 'Ivanka Nadya Syaura', 'tanggal' => '04/07/25', 'berat' => 4.65, 'jenis' => 'Kertas Campur', 'total' => 3720],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '04/07/25', 'berat' => 1.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1000],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '04/07/25', 'berat' => 9.1, 'jenis' => 'Kertas Campur', 'total' => 7735],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '04/07/25', 'berat' => 3.75, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 3750],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '04/07/25', 'berat' => 0.65, 'jenis' => 'Besi Campur', 'total' => 828],
            ['nama' => 'Winna Anggraeni', 'tanggal' => '04/07/25', 'berat' => 8.1, 'jenis' => 'HVS', 'total' => 13770],
            ['nama' => 'M. Daud (Renkeu Sekda)', 'tanggal' => '04/07/25', 'berat' => 0.25, 'jenis' => 'Kertas Hancur', 'total' => 125],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 1.83, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1555],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 1.275, 'jenis' => 'Kertas Campur', 'total' => 1275],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 4.4, 'jenis' => 'Sak telur', 'total' => 4400],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 0.45, 'jenis' => 'HVS', 'total' => 1164],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 0.595, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 7586],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 1.26, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1071],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 26.5, 'jenis' => 'Kardus', 'total' => 33787],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '04/07/25', 'berat' => 0.685, 'jenis' => 'HVS', 'total' => 1164],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '04/07/25', 'berat' => 1.4, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1190],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '04/07/25', 'berat' => 3.6, 'jenis' => 'HVS', 'total' => 6120],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '04/07/25', 'berat' => 1.0, 'jenis' => 'Kertas Campur', 'total' => 850],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '04/07/25', 'berat' => 1.3, 'jenis' => 'Kardus', 'total' => 1657],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '04/07/25', 'berat' => 0.65, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 1657],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '04/07/25', 'berat' => 0.45, 'jenis' => 'Sak telur', 'total' => 600],
            ['nama' => 'DESI RIMA (INSPEKTORAT)', 'tanggal' => '04/07/25', 'berat' => 2.35, 'jenis' => 'Kertas Campur', 'total' => 1947],
            ['nama' => 'DESI RIMA (INSPEKTORAT)', 'tanggal' => '04/07/25', 'berat' => 11.6, 'jenis' => 'Kardus', 'total' => 14790],
            ['nama' => 'DESI RIMA (INSPEKTORAT)', 'tanggal' => '04/07/25', 'berat' => 1.75, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1487],
            ['nama' => 'DESI RIMA (INSPEKTORAT)', 'tanggal' => '04/07/25', 'berat' => 10.35, 'jenis' => 'Campur-campur', 'total' => 1035],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '04/07/25', 'berat' => 0.24, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 204],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '04/07/25', 'berat' => 4.83, 'jenis' => 'Kertas Campur', 'total' => 4830],
            ['nama' => 'Besnaya Zalenzi', 'tanggal' => '04/07/25', 'berat' => 3.8, 'jenis' => 'Kardus', 'total' => 4845],
            ['nama' => 'Besnaya Zalenzi', 'tanggal' => '04/07/25', 'berat' => 2.9, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2240],
            ['nama' => 'Dinda Dwi Lestari', 'tanggal' => '07/07/25', 'berat' => 0.1, 'jenis' => 'HVS', 'total' => 144],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '11/07/25', 'berat' => 6.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 5100],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '11/07/25', 'berat' => 1.5, 'jenis' => 'Kaleng Campur', 'total' => 2550],
            ['nama' => 'Sugesti Permana', 'tanggal' => '11/07/25', 'berat' => 2.7, 'jenis' => 'Kardus', 'total' => 3213],
            ['nama' => 'Sugesti Permana', 'tanggal' => '11/07/25', 'berat' => 5.5, 'jenis' => 'Kertas Campur', 'total' => 3740],
            ['nama' => 'Sugesti Permana', 'tanggal' => '11/07/25', 'berat' => 0.4, 'jenis' => 'Sak Telur', 'total' => 340],
            ['nama' => 'Sugesti Permana', 'tanggal' => '11/07/25', 'berat' => 1.4, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1190],
            ['nama' => 'IRFAN FITRIADESH', 'tanggal' => '11/07/25', 'berat' => 18.0, 'jenis' => 'Kertas Campur', 'total' => 12240],
            ['nama' => 'IRFAN FITRIADESH', 'tanggal' => '11/07/25', 'berat' => 4.0, 'jenis' => 'Sak Telur', 'total' => 3400],
            ['nama' => 'IRFAN FITRIADESH', 'tanggal' => '11/07/25', 'berat' => 0.3, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 255],
            ['nama' => 'Irza Fhaizati Anas', 'tanggal' => '11/07/25', 'berat' => 2.65, 'jenis' => 'Kardus', 'total' => 3154],
            ['nama' => 'MAHDAYA LANDAN', 'tanggal' => '11/07/25', 'berat' => 0.4, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 340],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '11/07/25', 'berat' => 52.6, 'jenis' => 'HVS', 'total' => 76007],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '11/07/25', 'berat' => 5.75, 'jenis' => 'Kertas Campur', 'total' => 3910],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '11/07/25', 'berat' => 4.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 3400],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '11/07/25', 'berat' => 7.95, 'jenis' => 'Kertas Hancur', 'total' => 10136],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '11/07/25', 'berat' => 2.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1700],
            ['nama' => 'Nathan Raiffarde', 'tanggal' => '11/07/25', 'berat' => 22.5, 'jenis' => 'HVS', 'total' => 32512],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '11/07/25', 'berat' => 6.5, 'jenis' => 'Kardus', 'total' => 7735],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '11/07/25', 'berat' => 0.6, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 510],
            ['nama' => 'Rani novita sari', 'tanggal' => '11/07/25', 'berat' => 1.9, 'jenis' => 'HVS', 'total' => 2746],
            ['nama' => 'Elsa maylanda', 'tanggal' => '11/07/25', 'berat' => 0.25, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 638],
            ['nama' => 'Feri afni', 'tanggal' => '11/07/25', 'berat' => 0.5, 'jenis' => 'Kardus', 'total' => 595],
            ['nama' => 'Yeri Junaidi', 'tanggal' => '11/07/25', 'berat' => 1.7, 'jenis' => 'Kertas Campur', 'total' => 1156],
            ['nama' => 'Yeri Junaidi', 'tanggal' => '11/07/25', 'berat' => 1.1, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 935],
            ['nama' => 'Vipiet Adriani (Bidang KPP)', 'tanggal' => '11/07/25', 'berat' => 1.9, 'jenis' => 'HVS', 'total' => 2746],
            ['nama' => 'Vipiet Adriani (Bidang KPP)', 'tanggal' => '11/07/25', 'berat' => 0.95, 'jenis' => 'Kertas Campur', 'total' => 646],
            ['nama' => 'Lia', 'tanggal' => '11/07/25', 'berat' => 9.4, 'jenis' => 'HVS', 'total' => 13583],
            ['nama' => 'Lia', 'tanggal' => '11/07/25', 'berat' => 9.9, 'jenis' => 'Kardus', 'total' => 11781],
            ['nama' => 'Dinda Dwi Lestari', 'tanggal' => '11/07/25', 'berat' => 0.35, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 892],
            ['nama' => 'Dinda Dwi Lestari', 'tanggal' => '11/07/25', 'berat' => 0.85, 'jenis' => 'Kardus', 'total' => 1012],
            ['nama' => 'FAUZIA ALVINO', 'tanggal' => '11/07/25', 'berat' => 3.0, 'jenis' => 'Kertas Campur', 'total' => 2040],
            ['nama' => 'Rini', 'tanggal' => '14/07/25', 'berat' => 1.3, 'jenis' => 'HVS', 'total' => 1878],
            ['nama' => 'Ira', 'tanggal' => '15/07/25', 'berat' => 0.3, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 765],
            ['nama' => 'Ira', 'tanggal' => '15/07/25', 'berat' => 1.4, 'jenis' => 'Kertas Campur', 'total' => 952],
            ['nama' => 'Yuni Astuti', 'tanggal' => '17/07/25', 'berat' => 0.45, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 382],
            ['nama' => 'Busnika Hamidi, ST. MT', 'tanggal' => '18/07/25', 'berat' => 37.7, 'jenis' => 'HVS', 'total' => 54477],
            ['nama' => 'Busnika Hamidi, ST. MT', 'tanggal' => '18/07/25', 'berat' => 0.9, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2295],
            ['nama' => 'Busnika Hamidi, ST. MT', 'tanggal' => '18/07/25', 'berat' => 1.3, 'jenis' => 'Kertas Campur', 'total' => 884],
            ['nama' => 'Irza Fhaizati Anas', 'tanggal' => '18/07/25', 'berat' => 4.8, 'jenis' => 'Kardus', 'total' => 5712],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '18/07/25', 'berat' => 0.2, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 170],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '18/07/25', 'berat' => 0.9, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2295],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '18/07/25', 'berat' => 2.25, 'jenis' => 'Minyak Jelantah', 'total' => 9000],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '18/07/25', 'berat' => 23.45, 'jenis' => 'HVS', 'total' => 33885],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '18/07/25', 'berat' => 2.9, 'jenis' => 'Kardus', 'total' => 3451],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '18/07/25', 'berat' => 12.3, 'jenis' => 'Aki', 'total' => 104550],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '18/07/25', 'berat' => 5.9, 'jenis' => 'Kertas Campur', 'total' => 4012],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '18/07/25', 'berat' => 21.0, 'jenis' => 'HVS', 'total' => 30345],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '18/07/25', 'berat' => 2.4, 'jenis' => 'Botol Plastik Minuman', 'total' => 2880],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '18/07/25', 'berat' => 1.3, 'jenis' => 'Kardus', 'total' => 1768],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '18/07/25', 'berat' => 0.1, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 320],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '18/07/25', 'berat' => 0.6, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1920],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '18/07/25', 'berat' => 39.1, 'jenis' => 'HVS', 'total' => 62560],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '18/07/25', 'berat' => 0.4, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 480],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '18/07/25', 'berat' => 1.0, 'jenis' => 'Kaleng Campur', 'total' => 1200],
            ['nama' => 'Winna anggraeni', 'tanggal' => '18/07/25', 'berat' => 2.71, 'jenis' => 'Minyak Jelantah', 'total' => 10840],
            ['nama' => 'Connie Elfina', 'tanggal' => '18/07/25', 'berat' => 15.15, 'jenis' => 'HVS', 'total' => 24240],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '18/07/25', 'berat' => 7.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 8400],
            ['nama' => 'Dinda Dwi Lestari', 'tanggal' => '18/07/25', 'berat' => 4.53, 'jenis' => 'HVS', 'total' => 7248],
            ['nama' => 'Feri afni', 'tanggal' => '18/07/25', 'berat' => 3.0, 'jenis' => 'Kertas Campur', 'total' => 1200],
            ['nama' => 'Nanda', 'tanggal' => '22/07/25', 'berat' => 0.25, 'jenis' => 'HVS', 'total' => 400],
            ['nama' => 'ocha', 'tanggal' => '23/07/25', 'berat' => 3.09, 'jenis' => 'Kertas Campur', 'total' => 1236],
            ['nama' => 'ocha', 'tanggal' => '23/07/25', 'berat' => 0.74, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2368],
            ['nama' => 'NEDRI ANALITA', 'tanggal' => '25/07/25', 'berat' => 1.4, 'jenis' => 'HVS', 'total' => 2240],
            ['nama' => 'Busnika Hamidi, ST. MT', 'tanggal' => '25/07/25', 'berat' => 31.0, 'jenis' => 'Kardus', 'total' => 42160],
            ['nama' => 'Besnaya Zalenzi', 'tanggal' => '25/07/25', 'berat' => 7.9, 'jenis' => 'Kardus', 'total' => 10744],
            ['nama' => 'Besnaya Zalenzi', 'tanggal' => '25/07/25', 'berat' => 2.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 2400],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '25/07/25', 'berat' => 3.0, 'jenis' => 'Kardus', 'total' => 4080],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '25/07/25', 'berat' => 0.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 600],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '25/07/25', 'berat' => 0.9, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2880],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '25/07/25', 'berat' => 2.0, 'jenis' => 'Kaleng Campur', 'total' => 2400],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '25/07/25', 'berat' => 0.4, 'jenis' => 'Kertas Campur', 'total' => 160],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '25/07/25', 'berat' => 95.0, 'jenis' => 'HVS', 'total' => 152000],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '25/07/25', 'berat' => 0.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 600],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '25/07/25', 'berat' => 5.0, 'jenis' => 'HVS', 'total' => 8000],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '25/07/25', 'berat' => 0.5, 'jenis' => 'Kardus', 'total' => 680],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '25/07/25', 'berat' => 0.1, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 320],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '25/07/25', 'berat' => 0.2, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 640],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '25/07/25', 'berat' => 13.0, 'jenis' => 'HVS', 'total' => 20800],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '25/07/25', 'berat' => 38.5, 'jenis' => 'Koran', 'total' => 92400],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '25/07/25', 'berat' => 22.0, 'jenis' => 'Sak Telur', 'total' => 17600],
            ['nama' => 'Arsyifa P Adsha', 'tanggal' => '25/07/25', 'berat' => 8.0, 'jenis' => 'kulit', 'total' => 4000],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '25/07/25', 'berat' => 25.0, 'jenis' => 'HVS', 'total' => 40000],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '25/07/25', 'berat' => 28.0, 'jenis' => 'Kardus', 'total' => 38080],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '25/07/25', 'berat' => 1.7, 'jenis' => 'Koran', 'total' => 4080],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '25/07/25', 'berat' => 11.0, 'jenis' => 'Kertas Campur', 'total' => 4400],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '25/07/25', 'berat' => 14.0, 'jenis' => 'Besi Padu', 'total' => 39200],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '25/07/25', 'berat' => 15.0, 'jenis' => 'Kertas Campur', 'total' => 6000],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '25/07/25', 'berat' => 1.5, 'jenis' => 'Botol Plastik Minuman', 'total' => 1800],
            ['nama' => 'Emelda', 'tanggal' => '25/07/25', 'berat' => 14.0, 'jenis' => 'HVS', 'total' => 22400],
            ['nama' => 'Emelda', 'tanggal' => '25/07/25', 'berat' => 3.8, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 4560],
            ['nama' => 'Irza Fhaizati Anas', 'tanggal' => '25/07/25', 'berat' => 8.0, 'jenis' => 'HVS', 'total' => 12800],
            ['nama' => 'AZZA', 'tanggal' => '25/07/25', 'berat' => 4.5, 'jenis' => 'HVS', 'total' => 7200],
            ['nama' => 'AZZA', 'tanggal' => '25/07/25', 'berat' => 17.5, 'jenis' => 'Kertas Campur', 'total' => 7000],
            ['nama' => 'AZZA', 'tanggal' => '25/07/25', 'berat' => 12.0, 'jenis' => 'Besi Padu', 'total' => 33600],
            ['nama' => 'AZZA', 'tanggal' => '25/07/25', 'berat' => 3.5, 'jenis' => 'Kaleng Campur', 'total' => 4200],
            ['nama' => 'HARNIWATI', 'tanggal' => '01/08/25', 'berat' => 294.0, 'jenis' => 'HVS', 'total' => 424830],
            ['nama' => 'HARNIWATI', 'tanggal' => '01/08/25', 'berat' => 44.0, 'jenis' => 'Kertas Campur', 'total' => 29920],
            ['nama' => 'HERISON (KESBANGPOL)', 'tanggal' => '01/08/25', 'berat' => 2.1, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2520],
            ['nama' => 'HERISON (KESBANGPOL)', 'tanggal' => '01/08/25', 'berat' => 1.2, 'jenis' => 'Gelas Plastik Minuman', 'total' => 1530],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '01/08/25', 'berat' => 3.6, 'jenis' => 'Kardus', 'total' => 4499],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '01/08/25', 'berat' => 3.4, 'jenis' => 'Kertas Campur', 'total' => 952],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '01/08/25', 'berat' => 1.8, 'jenis' => 'Botol Plastik Minuman', 'total' => 2295],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '01/08/25', 'berat' => 0.6, 'jenis' => 'Kaleng Campur', 'total' => 1020],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 13.0, 'jenis' => 'Kardus', 'total' => 17680],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 35.5, 'jenis' => 'HVS', 'total' => 51298],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 25.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 21250],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 2.8, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 8960],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 0.5, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1600],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 7.5, 'jenis' => 'Besi Padu', 'total' => 22500],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 35.5, 'jenis' => 'Besi Campur', 'total' => 45262],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 0.5, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 6000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'berat' => 23.0, 'jenis' => 'Kulit', 'total' => 9200],
            ['nama' => 'Mona Vera Wati Halda ', 'tanggal' => '08/08/25', 'berat' => 3.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2550],
            ['nama' => 'Mona Vera Wati Halda ', 'tanggal' => '08/08/25', 'berat' => 1.2, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 3840],
            ['nama' => 'Koperasi Desa Merah Putih Syariah Nagari Gunuang Rajo Tanah Datar ( Rahayatul Asni)', 'tanggal' => '15/08/25', 'berat' => 1.0, 'jenis' => 'Kardus', 'total' => 1190],
            ['nama' => 'Koperasi Desa Merah Putih Syariah Nagari Gunuang Rajo Tanah Datar ( Rahayatul Asni)', 'tanggal' => '15/08/25', 'berat' => 1.1, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1100],
            ['nama' => 'Koperasi Desa Merah Putih Syariah Nagari Gunuang Rajo Tanah Datar ( Rahayatul Asni)', 'tanggal' => '15/08/25', 'berat' => 1.6, 'jenis' => 'Botol Plastik Minuman', 'total' => 2040],
            ['nama' => 'Koperasi Desa Merah Putih Syariah Nagari Gunuang Rajo Tanah Datar ( Rahayatul Asni)', 'tanggal' => '15/08/25', 'berat' => 1.0, 'jenis' => 'Kulit', 'total' => 400],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 7.5, 'jenis' => 'HVS', 'total' => 12000],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 14.4, 'jenis' => 'Kardus', 'total' => 19584],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 0.4, 'jenis' => 'Sak Telur', 'total' => 320],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 0.9, 'jenis' => 'Gelas Plastik Minuman', 'total' => 1080],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 23.5, 'jenis' => 'Besi Campur', 'total' => 37600],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 0.7, 'jenis' => 'Kaleng Campur', 'total' => 840],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 0.65, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 7800],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 3.0, 'jenis' => 'Aki', 'total' => 25500],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '15/08/25', 'berat' => 2.1, 'jenis' => 'Kertas Campur', 'total' => 840],
            ['nama' => 'M NAZIR (PDAM)', 'tanggal' => '15/08/25', 'berat' => 2.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 8000],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '15/08/25', 'berat' => 15.5, 'jenis' => 'HVS', 'total' => 24800],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '15/08/25', 'berat' => 5.0, 'jenis' => 'Kardus', 'total' => 6800],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '15/08/25', 'berat' => 1.2, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 3840],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '15/08/25', 'berat' => 3.1, 'jenis' => 'Kertas Campur', 'total' => 1240],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '15/08/25', 'berat' => 10.2, 'jenis' => 'HVS', 'total' => 14739],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '15/08/25', 'berat' => 4.5, 'jenis' => 'Kertas Campur', 'total' => 1800],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '15/08/25', 'berat' => 131.5, 'jenis' => 'HVS', 'total' => 190018],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '15/08/25', 'berat' => 28.0, 'jenis' => 'Koran', 'total' => 112000],
            ['nama' => 'MUSTIKA SUARMAN (BAG. PBJ)', 'tanggal' => '15/08/25', 'berat' => 1.8, 'jenis' => 'Kardus', 'total' => 2448],
            ['nama' => 'MUSTIKA SUARMAN (BAG. PBJ)', 'tanggal' => '15/08/25', 'berat' => 0.3, 'jenis' => 'Botol Plastik Minuman', 'total' => 360],
            ['nama' => 'Ririyanti Zahrul', 'tanggal' => '15/08/25', 'berat' => 0.05, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 60],
            ['nama' => 'Ririyanti Zahrul', 'tanggal' => '15/08/25', 'berat' => 1.3, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 4160],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '15/08/25', 'berat' => 1.4, 'jenis' => 'Botol Plastik Minuman', 'total' => 1785],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '15/08/25', 'berat' => 0.4, 'jenis' => 'Gelas Plastik Minuman', 'total' => 510],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '22/08/25', 'berat' => 17.9, 'jenis' => 'HVS', 'total' => 25865],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '22/08/25', 'berat' => 0.4, 'jenis' => 'Kardus', 'total' => 476],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '22/08/25', 'berat' => 18.0, 'jenis' => 'Kertas Campur', 'total' => 12240],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '22/08/25', 'berat' => 0.3, 'jenis' => 'Sak Telur', 'total' => 255],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '22/08/25', 'berat' => 2.3, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1955],
            ['nama' => 'DESI RIMA (INSPEKTORAT)', 'tanggal' => '22/08/25', 'berat' => 28.1, 'jenis' => 'Kardus', 'total' => 33439],
            ['nama' => 'DESI RIMA (INSPEKTORAT)', 'tanggal' => '22/08/25', 'berat' => 3.1, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 7905],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '22/08/25', 'berat' => 1.1, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2805],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '22/08/25', 'berat' => 0.7, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1785],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '22/08/25', 'berat' => 5.2, 'jenis' => 'Kardus', 'total' => 6188],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '22/08/25', 'berat' => 0.7, 'jenis' => 'Sak Telur', 'total' => 595],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '22/08/25', 'berat' => 0.9, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 765],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '22/08/25', 'berat' => 1.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2550],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '22/08/25', 'berat' => 0.7, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1785],
            ['nama' => 'BPBD (iihuska)', 'tanggal' => '22/08/25', 'berat' => 0.3, 'jenis' => 'Kaleng Campur', 'total' => 510],
            ['nama' => 'Nadea Annisa', 'tanggal' => '22/08/25', 'berat' => 1.8, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 4590],
            ['nama' => 'Irza Fhaizati Anas', 'tanggal' => '22/08/25', 'berat' => 1.7, 'jenis' => 'Kardus', 'total' => 2023],
            ['nama' => 'Irza Fhaizati Anas', 'tanggal' => '22/08/25', 'berat' => 0.9, 'jenis' => 'Kertas Campur', 'total' => 612],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '29/08/25', 'berat' => 3.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 8750],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '29/08/25', 'berat' => 1.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 3825],
            ['nama' => 'Ririyanti Zahrul', 'tanggal' => '29/08/25', 'berat' => 18.5, 'jenis' => 'HVS', 'total' => 26732],
            ['nama' => 'Ririyanti Zahrul', 'tanggal' => '29/08/25', 'berat' => 4.2, 'jenis' => 'Kertas Campur', 'total' => 2856],
            ['nama' => 'Sugesti Permana', 'tanggal' => '29/08/25', 'berat' => 5.0, 'jenis' => 'Kardus', 'total' => 5950],
            ['nama' => 'Sugesti Permana', 'tanggal' => '29/08/25', 'berat' => 0.7, 'jenis' => 'Kertas Campur', 'total' => 476],
            ['nama' => 'Sugesti Permana', 'tanggal' => '29/08/25', 'berat' => 0.4, 'jenis' => 'Sak Telur', 'total' => 340],
            ['nama' => 'Sugesti Permana', 'tanggal' => '29/08/25', 'berat' => 3.7, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 4440],
            ['nama' => 'ZATINAL (Dinas Pariwisata Pemuda dan Olahraga)', 'tanggal' => '29/08/25', 'berat' => 6.0, 'jenis' => 'Kardus', 'total' => 7140],
            ['nama' => 'ZATINAL (Dinas Pariwisata Pemuda dan Olahraga)', 'tanggal' => '29/08/25', 'berat' => 2.3, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 5865],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '29/08/25', 'berat' => 8.0, 'jenis' => 'Kardus', 'total' => 9520],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '29/08/25', 'berat' => 0.4, 'jenis' => 'Kertas Campur', 'total' => 272],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '29/08/25', 'berat' => 3.0, 'jenis' => 'Sak Telur', 'total' => 2550],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '29/08/25', 'berat' => 1.9, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2280],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '29/08/25', 'berat' => 4.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 10200],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '29/08/25', 'berat' => 1.5, 'jenis' => 'Kaleng Campur', 'total' => 2550],
            ['nama' => 'BADAN KESBANGPOL ', 'tanggal' => '29/08/25', 'berat' => 5.0, 'jenis' => 'Kardus', 'total' => 5950],
            ['nama' => 'BADAN KESBANGPOL ', 'tanggal' => '29/08/25', 'berat' => 0.6, 'jenis' => 'Kertas Campur', 'total' => 408],
            ['nama' => 'BADAN KESBANGPOL ', 'tanggal' => '29/08/25', 'berat' => 7.2, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 8640],
            ['nama' => 'MTs Surau Quran Boarding SChool', 'tanggal' => '12/09/25', 'berat' => 6.2, 'jenis' => 'Kardus', 'total' => 7378],
            ['nama' => 'MTs Surau Quran Boarding SChool', 'tanggal' => '12/09/25', 'berat' => 5.0, 'jenis' => 'kulit', 'total' => 2000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 0.8, 'jenis' => 'HVS', 'total' => 1156],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 25.8, 'jenis' => 'Kardus', 'total' => 30702],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 7.3, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 6205],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 6.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 15300],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 0.5, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1275],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 11.7, 'jenis' => 'Kaleng Campur', 'total' => 19890],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 0.8, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 8840],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 1.1, 'jenis' => 'kulit', 'total' => 440],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 2.4, 'jenis' => 'buram', 'total' => 1920],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/09/25', 'berat' => 0.1, 'jenis' => 'plastik minyak', 'total' => 80],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '19/09/25', 'berat' => 146.0, 'jenis' => 'HVS', 'total' => 210970],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '19/09/25', 'berat' => 15.8, 'jenis' => 'Buram', 'total' => 9480],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '19/09/25', 'berat' => 2.5, 'jenis' => 'Kertas Campur', 'total' => 1700],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '19/09/25', 'berat' => 2.2, 'jenis' => 'Botol Plastik Minuman', 'total' => 2805],
            ['nama' => 'ABDURRAHMAN HADI (SEKDA)', 'tanggal' => '19/09/25', 'berat' => 25.0, 'jenis' => 'HVS', 'total' => 36125],
            ['nama' => 'ABDURRAHMAN HADI (SEKDA)', 'tanggal' => '19/09/25', 'berat' => 2.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 5100],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '19/09/25', 'berat' => 2.1, 'jenis' => 'Sak Telur', 'total' => 1785],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '19/09/25', 'berat' => 1.8, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 4590],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '19/09/25', 'berat' => 0.5, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1275],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '19/09/25', 'berat' => 4.8, 'jenis' => 'HVS', 'total' => 6936],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '19/09/25', 'berat' => 2.6, 'jenis' => 'Kardus', 'total' => 3094],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '19/09/25', 'berat' => 1.9, 'jenis' => 'Botol Plastik Minuman', 'total' => 2422],
            ['nama' => 'HARNIWATI', 'tanggal' => '26/09/25', 'berat' => 296.8, 'jenis' => 'HVS', 'total' => 428876],
            ['nama' => 'HARNIWATI', 'tanggal' => '26/09/25', 'berat' => 9.2, 'jenis' => 'Kardus', 'total' => 10948],
            ['nama' => 'HARNIWATI', 'tanggal' => '26/09/25', 'berat' => 78.0, 'jenis' => 'Kertas Campur', 'total' => 53040],
            ['nama' => 'HARNIWATI', 'tanggal' => '26/09/25', 'berat' => 1.0, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 2550],
            ['nama' => 'mis', 'tanggal' => '01/10/25', 'berat' => 1.4, 'jenis' => 'Kertas Campur', 'total' => 952],
            ['nama' => 'mis', 'tanggal' => '01/10/25', 'berat' => 0.66, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 842],
            ['nama' => 'Vini Ariani Erwin', 'tanggal' => '03/10/25', 'berat' => 1.5, 'jenis' => 'Kardus', 'total' => 1785],
            ['nama' => 'Vini Ariani Erwin', 'tanggal' => '03/10/25', 'berat' => 2.0, 'jenis' => 'Sak Telur', 'total' => 1700],
            ['nama' => 'Vini Ariani Erwin', 'tanggal' => '03/10/25', 'berat' => 0.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 425],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '03/10/25', 'berat' => 98.0, 'jenis' => 'HVS', 'total' => 141610],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '03/10/25', 'berat' => 12.0, 'jenis' => 'Kardus', 'total' => 14280],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '03/10/25', 'berat' => 12.0, 'jenis' => 'Kertas Campur', 'total' => 8160],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '03/10/25', 'berat' => 2.1, 'jenis' => 'Kertas Campur', 'total' => 1428],
            ['nama' => 'SOFA NOFA (PMPTSP)', 'tanggal' => '03/10/25', 'berat' => 4.0, 'jenis' => 'Besi Padu', 'total' => 12580],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '03/10/25', 'berat' => 131.0, 'jenis' => 'HVS', 'total' => 189295],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '03/10/25', 'berat' => 7.0, 'jenis' => 'Kardus', 'total' => 8330],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '03/10/25', 'berat' => 2.1, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1785],
            ['nama' => 'ADRIYANTI RUSTAM (BAPEDA)', 'tanggal' => '03/10/25', 'berat' => 1.4, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1190],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '03/10/25', 'berat' => 3.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 7650],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '03/10/25', 'berat' => 2.4, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 6120],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '03/10/25', 'berat' => 0.7, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 595],
            ['nama' => 'Rani novita sari', 'tanggal' => '03/10/25', 'berat' => 0.3, 'jenis' => 'Botol Plastik Minuman', 'total' => 382],
            ['nama' => 'Nofriadi', 'tanggal' => '10/10/25', 'berat' => 6.3, 'jenis' => 'HVS', 'total' => 8568],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '10/10/25', 'berat' => 30.0, 'jenis' => 'HVS', 'total' => 40800],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '10/10/25', 'berat' => 1.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2400],
            ['nama' => 'UTRI SATRIA PUTRA (BAGIAN AP)', 'tanggal' => '17/10/25', 'berat' => 0.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 1200],
            ['nama' => 'UTRI SATRIA PUTRA (BAGIAN AP)', 'tanggal' => '01/08/25', 'berat' => 2.9, 'jenis' => 'HVS', 'total' => 3944],
            ['nama' => 'UTRI SATRIA PUTRA (BAGIAN AP)', 'tanggal' => '01/08/25', 'berat' => 0.4, 'jenis' => 'Kardus', 'total' => 448],
            ['nama' => 'UTRI SATRIA PUTRA (BAGIAN AP)', 'tanggal' => '01/08/25', 'berat' => 2.1, 'jenis' => 'Kertas Campur', 'total' => 1344],
            ['nama' => 'IRWANDI (BAGIAN ORGANISASI)', 'tanggal' => '17/10/25', 'berat' => 10.0, 'jenis' => 'Kertas Campur', 'total' => 6400],
            ['nama' => 'IRWANDI (BAGIAN ORGANISASI)', 'tanggal' => '17/10/25', 'berat' => 11.0, 'jenis' => 'Kertas Hancur', 'total' => 13200],
            ['nama' => 'IRWANDI (BAGIAN ORGANISASI)', 'tanggal' => '17/10/25', 'berat' => 1.2, 'jenis' => 'Botol Plastik Minuman', 'total' => 1440],
            ['nama' => 'POPPY AZIZ', 'tanggal' => '17/10/25', 'berat' => 3.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 7200],
            ['nama' => 'POPPY AZIZ', 'tanggal' => '17/10/25', 'berat' => 0.2, 'jenis' => 'Tutup botol', 'total' => 1000],
            ['nama' => 'Jusdawenti', 'tanggal' => '17/10/25', 'berat' => 28.0, 'jenis' => 'Aki', 'total' => 238000],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '24/10/25', 'berat' => 4.2, 'jenis' => 'Sak Telur', 'total' => 3360],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '24/10/25', 'berat' => 0.5, 'jenis' => 'Sak Telur', 'total' => 400],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '24/10/25', 'berat' => 3.0, 'jenis' => 'Kardus', 'total' => 3360],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '24/10/25', 'berat' => 3.5, 'jenis' => 'Besi Padu', 'total' => 10360],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '24/10/25', 'berat' => 1.0, 'jenis' => 'Kaleng Campur', 'total' => 1600],
            ['nama' => 'ARIF GANI (KEC. TJ EMAS)', 'tanggal' => '24/10/25', 'berat' => 3.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2400],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 10.7, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 25680],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 2.1, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 5040],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 4.5, 'jenis' => 'Kaleng Campur', 'total' => 7200],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 0.4, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 4160],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 24.5, 'jenis' => 'Kardus', 'total' => 27440],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 10.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 8000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 14.0, 'jenis' => 'HVS', 'total' => 19040],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 5.5, 'jenis' => 'buram', 'total' => 3300],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '24/10/25', 'berat' => 2.9, 'jenis' => 'kulit', 'total' => 1450],
            ['nama' => 'Eva Darmasari', 'tanggal' => '31/10/25', 'berat' => 8.3, 'jenis' => 'HVS', 'total' => 11288],
            ['nama' => 'Eva Darmasari', 'tanggal' => '31/10/25', 'berat' => 0.4, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 320],
            ['nama' => 'Eva Darmasari', 'tanggal' => '31/10/25', 'berat' => 1.3, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 3120],
            ['nama' => 'Eva Darmasari', 'tanggal' => '31/10/25', 'berat' => 0.9, 'jenis' => 'kulit', 'total' => 720],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '31/10/25', 'berat' => 0.9, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2160],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '31/10/25', 'berat' => 0.95, 'jenis' => 'Kaleng Campur', 'total' => 1520],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '31/10/25', 'berat' => 3.2, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2560],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '31/10/25', 'berat' => 2.3, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 5520],
            ['nama' => 'MUSTIKA SUARMAN (BAG. PBJ)', 'tanggal' => '31/10/25', 'berat' => 7.5, 'jenis' => 'HVS', 'total' => 10200],
            ['nama' => 'MUSTIKA SUARMAN (BAG. PBJ)', 'tanggal' => '31/10/25', 'berat' => 1.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2400],
            ['nama' => 'septia', 'tanggal' => '04/11/25', 'berat' => 3.5, 'jenis' => 'Kardus', 'total' => 3920],
            ['nama' => 'septia', 'tanggal' => '04/11/25', 'berat' => 4.0, 'jenis' => 'MINYAK JELANTAH', 'total' => 20000],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '07/11/25', 'berat' => 1.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2400],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '07/11/25', 'berat' => 25.0, 'jenis' => 'HVS', 'total' => 34000],
            ['nama' => 'BANK NAGARI', 'tanggal' => '07/11/25', 'berat' => 4.3, 'jenis' => 'Kulit', 'total' => 3440],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '07/11/25', 'berat' => 5.0, 'jenis' => 'Kertas Campur', 'total' => 3200],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '07/11/25', 'berat' => 1.0, 'jenis' => 'Kardus', 'total' => 1120],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '07/11/25', 'berat' => 0.3, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 240],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '07/11/25', 'berat' => 4.3, 'jenis' => 'Kulit', 'total' => 3440],
            ['nama' => 'Yusuf Mardotillah', 'tanggal' => '20/11/25', 'berat' => 5.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 6000],
            ['nama' => 'Leni Mardiastuti', 'tanggal' => '21/11/25', 'berat' => 0.19, 'jenis' => 'HVS', 'total' => 258],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 7.0, 'jenis' => 'Sak Telur', 'total' => 5600],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 6.0, 'jenis' => 'Kaleng Campur', 'total' => 18000],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 4.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 3600],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 4.2, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 10080],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 0.6, 'jenis' => 'Tutup botol', 'total' => 1200],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 3.8, 'jenis' => 'Kaleng Campur', 'total' => 6080],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 2.0, 'jenis' => 'Kertas Campur', 'total' => 1280],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 2.5, 'jenis' => 'Botol kaca', 'total' => 1250],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '12/12/25', 'berat' => 16.0, 'jenis' => 'Kardus', 'total' => 17920],
            ['nama' => 'muhammad alfatih faeyza', 'tanggal' => '12/12/25', 'berat' => 3.5, 'jenis' => 'Magic', 'total' => 3500],
            ['nama' => 'muhammad alfatih faeyza', 'tanggal' => '12/12/25', 'berat' => 2.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2000],
            ['nama' => 'muhammad alfatih faeyza', 'tanggal' => '12/12/25', 'berat' => 2.5, 'jenis' => 'Botol Plastik Minuman', 'total' => 3000],
            ['nama' => 'muhammad alfatih faeyza', 'tanggal' => '12/12/25', 'berat' => 4.5, 'jenis' => 'Botol campur', 'total' => 2250],
            ['nama' => 'ADE PUTRA', 'tanggal' => '19/12/25', 'berat' => 3.0, 'jenis' => 'Kardus', 'total' => 3360],
            ['nama' => 'Dimas Fadhali Syafiq', 'tanggal' => '02/01/26', 'berat' => 21.0, 'jenis' => 'HVS', 'total' => 28560],
            ['nama' => 'Dimas Fadhali Syafiq', 'tanggal' => '02/01/26', 'berat' => 19.5, 'jenis' => 'HVS', 'total' => 26520],
            ['nama' => 'Dimas Fadhali Syafiq', 'tanggal' => '02/01/26', 'berat' => 2.3, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 5520],
            ['nama' => 'Dimas Fadhali Syafiq', 'tanggal' => '02/01/26', 'berat' => 1.1, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 2640],
            ['nama' => 'Dimas Fadhali Syafiq', 'tanggal' => '02/01/26', 'berat' => 4.2, 'jenis' => 'Kardus', 'total' => 4704],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 70.0, 'jenis' => 'HVS', 'total' => 95200],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 21.0, 'jenis' => 'Kardus', 'total' => 23520],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 14.0, 'jenis' => 'Kertas Campur', 'total' => 8960],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 13.7, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 10960],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 1.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 3600],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 4.5, 'jenis' => 'Kaleng Campur', 'total' => 7200],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 16.0, 'jenis' => 'Ampli rusak', 'total' => 16000],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '09/01/26', 'berat' => 10.9, 'jenis' => 'Magic', 'total' => 10900],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '09/01/26', 'berat' => 4.1, 'jenis' => 'HVS', 'total' => 5576],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '09/01/26', 'berat' => 3.0, 'jenis' => 'Kipas Angin', 'total' => 3000],
            ['nama' => 'Ira Fitria Elisman', 'tanggal' => '09/01/26', 'berat' => 1.2, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 2880],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '09/01/26', 'berat' => 0.3, 'jenis' => 'Plastik minyak', 'total' => 300],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '09/01/26', 'berat' => 1.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 3600],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '09/01/26', 'berat' => 3.2, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 7680],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '09/01/26', 'berat' => 1.9, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 4560],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 3.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 7200],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 5.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 4000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 10.0, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 24000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 7.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 18000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 3.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2400],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 3.0, 'jenis' => 'Kaleng Campur', 'total' => 4800],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 0.5, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 5200],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 1.4, 'jenis' => 'Plastik minyak', 'total' => 1400],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 4.0, 'jenis' => 'Kertas Campur', 'total' => 2560],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 2.5, 'jenis' => 'Sak Telur', 'total' => 2000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 1.6, 'jenis' => 'Besi Campur', 'total' => 2560],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 0.6, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 480],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '09/01/26', 'berat' => 28.0, 'jenis' => 'Kardus', 'total' => 31360],
            ['nama' => 'Emelda', 'tanggal' => '23/01/26', 'berat' => 2.5, 'jenis' => 'Botol Plastik Minuman', 'total' => 3000],
            ['nama' => 'Emelda', 'tanggal' => '23/01/26', 'berat' => 2.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 6000],
            ['nama' => 'Emelda', 'tanggal' => '23/01/26', 'berat' => 4.1, 'jenis' => 'Kardus', 'total' => 4592],
            ['nama' => 'Emelda', 'tanggal' => '26/01/26', 'berat' => 9.5, 'jenis' => 'Kertas Campur', 'total' => 6080],
            ['nama' => 'Emelda', 'tanggal' => '23/01/26', 'berat' => 2.0, 'jenis' => 'Kaleng Campur', 'total' => 3200],
            ['nama' => 'Emelda', 'tanggal' => '23/01/26', 'berat' => 0.8, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 640],
            ['nama' => 'Emelda', 'tanggal' => '23/01/26', 'berat' => 3.4, 'jenis' => 'Aki', 'total' => 28900],
            ['nama' => 'Eva Darmasari', 'tanggal' => '30/01/26', 'berat' => 2.8, 'jenis' => 'Kardus', 'total' => 3136],
            ['nama' => 'Eva Darmasari', 'tanggal' => '30/01/26', 'berat' => 2.9, 'jenis' => 'Kardus', 'total' => 3248],
            ['nama' => 'Eva Darmasari', 'tanggal' => '30/01/26', 'berat' => 1.5, 'jenis' => 'Sak Telur', 'total' => 1200],
            ['nama' => 'Eva Darmasari', 'tanggal' => '30/01/26', 'berat' => 1.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1200],
            ['nama' => 'Eva Darmasari', 'tanggal' => '30/01/26', 'berat' => 1.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 3600],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '30/01/26', 'berat' => 4.8, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 3840],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '30/01/26', 'berat' => 51.0, 'jenis' => 'HVS', 'total' => 69360],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '30/01/26', 'berat' => 19.0, 'jenis' => 'Kertas Campur', 'total' => 12160],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '30/01/26', 'berat' => 2.0, 'jenis' => 'Kaleng Campur', 'total' => 3200],
            ['nama' => 'M. Daud (Renkeu Sekda)', 'tanggal' => '30/01/26', 'berat' => 14.0, 'jenis' => 'Kertas Hancur', 'total' => 16800],
            ['nama' => 'M. Daud (Renkeu Sekda)', 'tanggal' => '30/01/26', 'berat' => 7.0, 'jenis' => 'HVS', 'total' => 9520],
            ['nama' => 'M. Daud (Renkeu Sekda)', 'tanggal' => '30/01/26', 'berat' => 1.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 1200],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '30/01/26', 'berat' => 2.4, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 5760],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '30/01/26', 'berat' => 3.3, 'jenis' => 'Kertas Campur', 'total' => 2112],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '30/01/26', 'berat' => 8.5, 'jenis' => 'Kardus', 'total' => 9520],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '06/02/26', 'berat' => 3.0, 'jenis' => 'Sak Telur', 'total' => 2400],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '06/02/26', 'berat' => 17.0, 'jenis' => 'Kardus', 'total' => 19040],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '06/02/26', 'berat' => 5.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 6000],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '06/02/26', 'berat' => 1.0, 'jenis' => 'Kaleng Campur', 'total' => 1600],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '06/02/26', 'berat' => 3.0, 'jenis' => 'Kertas Campur', 'total' => 1920],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '06/02/26', 'berat' => 100.0, 'jenis' => 'HVS', 'total' => 136000],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '06/02/26', 'berat' => 15.0, 'jenis' => 'Kertas Campur', 'total' => 9600],
            ['nama' => 'Roseyanti ', 'tanggal' => '06/02/26', 'berat' => 2.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 6000],
            ['nama' => 'Roseyanti ', 'tanggal' => '06/02/26', 'berat' => 3.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2400],
            ['nama' => 'Roseyanti ', 'tanggal' => '06/02/26', 'berat' => 8.1, 'jenis' => 'HVS', 'total' => 11016],
            ['nama' => 'Roseyanti ', 'tanggal' => '06/02/26', 'berat' => 9.2, 'jenis' => 'Kardus', 'total' => 10304],
            ['nama' => 'Roseyanti ', 'tanggal' => '06/02/26', 'berat' => 0.6, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 480],
            ['nama' => 'Ivanka Nadya Syaura', 'tanggal' => '13/02/26', 'berat' => 3.4, 'jenis' => 'Botol Plastik Minuman', 'total' => 4080],
            ['nama' => 'Ivanka Nadya Syaura', 'tanggal' => '13/02/26', 'berat' => 2.2, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1760],
            ['nama' => 'Ivanka Nadya Syaura', 'tanggal' => '13/02/26', 'berat' => 0.8, 'jenis' => 'Kaleng Campur', 'total' => 1280],
            ['nama' => 'POPPY AZIZ', 'tanggal' => '20/02/26', 'berat' => 8.3, 'jenis' => 'Kardus', 'total' => 9296],
            ['nama' => 'POPPY AZIZ', 'tanggal' => '20/02/26', 'berat' => 1.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1500],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '20/02/26', 'berat' => 46.0, 'jenis' => 'HVS', 'total' => 62560],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '20/02/26', 'berat' => 21.0, 'jenis' => 'Kulit', 'total' => 10500],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '20/02/26', 'berat' => 7.0, 'jenis' => 'Karah', 'total' => 7000],
            ['nama' => 'Yeni Hanifah', 'tanggal' => '20/02/26', 'berat' => 3.8, 'jenis' => 'Kardus', 'total' => 4256],
            ['nama' => 'Yeni Hanifah', 'tanggal' => '20/02/26', 'berat' => 1.4, 'jenis' => 'Kertas Campur', 'total' => 896],
            ['nama' => 'Yeni Hanifah', 'tanggal' => '20/02/26', 'berat' => 2.8, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 4200],
            ['nama' => 'UTRI SATRIA PUTRA (BAGIAN AP)', 'tanggal' => '27/02/26', 'berat' => 12.0, 'jenis' => 'Kertas Putih', 'total' => 18000],
            ['nama' => 'UTRI SATRIA PUTRA (BAGIAN AP)', 'tanggal' => '27/02/26', 'berat' => 1.2, 'jenis' => 'Kaleng Campur', 'total' => 1920],
            ['nama' => 'UTRI SATRIA PUTRA (BAGIAN AP)', 'tanggal' => '27/02/26', 'berat' => 0.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 400],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '27/02/26', 'berat' => 4.5, 'jenis' => 'Kardus', 'total' => 5040],
            ['nama' => 'Dian Kumala Sari', 'tanggal' => '27/02/26', 'berat' => 6.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 4800],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '27/02/26', 'berat' => 6.2, 'jenis' => 'Kardus', 'total' => 6944],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '27/02/26', 'berat' => 0.25, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 2600],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '27/02/26', 'berat' => 0.7, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1680],
            ['nama' => 'Juni Fiwaldi ', 'tanggal' => '27/02/26', 'berat' => 0.4, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 960],
            ['nama' => 'Annisa Mardhotillah', 'tanggal' => '27/02/26', 'berat' => 1.5, 'jenis' => 'Tutup Botol', 'total' => 3000],
            ['nama' => 'Annisa Mardhotillah', 'tanggal' => '27/02/26', 'berat' => 0.7, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 560],
            ['nama' => 'Annisa Mardhotillah', 'tanggal' => '27/02/26', 'berat' => 1.8, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 4320],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '27/02/26', 'berat' => 0.5, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1200],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '27/02/26', 'berat' => 43.0, 'jenis' => 'HVS', 'total' => 58480],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '27/02/26', 'berat' => 20.0, 'jenis' => 'Kertas Campur', 'total' => 12800],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '27/02/26', 'berat' => 16.0, 'jenis' => 'Kertas Campur', 'total' => 10240],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '27/02/26', 'berat' => 1.0, 'jenis' => 'Sak Telur', 'total' => 800],
            ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '11/03/26', 'berat' => 5.0, 'jenis' => 'Kardus', 'total' => 5600],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '13/03/26', 'berat' => 3.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 3600],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '13/03/26', 'berat' => 3.6, 'jenis' => 'Kardus', 'total' => 4032],
            ['nama' => 'DEDIWAN PUTRA', 'tanggal' => '13/03/26', 'berat' => 4.1, 'jenis' => 'Kaleng Campur', 'total' => 6560],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 8.0, 'jenis' => 'Kaleng Campur', 'total' => 12800],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 2.5, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 6000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 0.8, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 1920],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 2.4, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 5760],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 0.8, 'jenis' => 'Botol Plastik Minuman', 'total' => 960],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 3.5, 'jenis' => 'Kaleng Campur', 'total' => 5600],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 2.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 4800],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 0.4, 'jenis' => 'Kaleng Campur', 'total' => 640],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 6.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 4800],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 2.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 2400],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 36.0, 'jenis' => 'Kardus', 'total' => 40320],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 7.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 5600],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 9.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 7200],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 7.0, 'jenis' => 'Sak Telur', 'total' => 5600],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 6.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 4800],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 2.3, 'jenis' => 'Kaleng Campur', 'total' => 3680],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 7.5, 'jenis' => 'Plastik minyak', 'total' => 6000],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 23.0, 'jenis' => 'HVS', 'total' => 31280],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 3.6, 'jenis' => 'Kertas Campur', 'total' => 2304],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 1.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 1200],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '23/04/26', 'berat' => 3.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2400],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '30/04/26', 'berat' => 4.5, 'jenis' => 'Kaleng Campur', 'total' => 7200],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '30/04/26', 'berat' => 0.4, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 4160],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '30/04/26', 'berat' => 1.2, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 2880],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '30/04/26', 'berat' => 2.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1600],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '30/04/26', 'berat' => 1.3, 'jenis' => 'Minyak Jelantah', 'total' => 6500],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '30/04/26', 'berat' => 1.8, 'jenis' => 'Gelas Plastik Minuman Bersih (tanpa tutup plastik)', 'total' => 4320],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '30/04/26', 'berat' => 1.2, 'jenis' => 'Sak Telur', 'total' => 960],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '30/04/26', 'berat' => 6.5, 'jenis' => 'Kertas Campur', 'total' => 4160],
            ['nama' => 'Puskesmas sungayang(dr iranovitha dewy) ', 'tanggal' => '30/04/26', 'berat' => 4.5, 'jenis' => 'Kardus', 'total' => 3802],
            ['nama' => 'Vipiet Adriani (Bidang KPP)', 'tanggal' => '22/05/26', 'berat' => 19.0, 'jenis' => 'Kertas Campur', 'total' => 7410],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '22/05/26', 'berat' => 1.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 1625],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '22/05/26', 'berat' => 3.2, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 5200],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '22/05/26', 'berat' => 14.0, 'jenis' => 'HVS', 'total' => 15470],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '22/05/26', 'berat' => 4.5, 'jenis' => 'Kertas Campur', 'total' => 1755],
            ['nama' => 'AUREL AULIANISA', 'tanggal' => '22/05/26', 'berat' => 0.8, 'jenis' => 'Tutup botol', 'total' => 1200],
            ['nama' => 'M. Daud (Renkeu Sekda)', 'tanggal' => '22/05/26', 'berat' => 7.0, 'jenis' => 'Kertas Hancur', 'total' => 5915],
            ['nama' => 'Ainul Jannah ME', 'tanggal' => '29/05/26', 'berat' => 6.0, 'jenis' => 'Sak Telur', 'total' => 3900],
            ['nama' => 'Ainul Jannah ME', 'tanggal' => '29/05/26', 'berat' => 9.5, 'jenis' => 'Kardus', 'total' => 8028],
            ['nama' => 'Ainul Jannah ME', 'tanggal' => '29/05/26', 'berat' => 2.0, 'jenis' => 'Pet bersih', 'total' => 3000],
            ['nama' => 'Ainul Jannah ME', 'tanggal' => '29/05/26', 'berat' => 0.9, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 468],
            ['nama' => 'Ainul Jannah ME', 'tanggal' => '29/05/26', 'berat' => 2.2, 'jenis' => 'HVS', 'total' => 2431],
            ['nama' => 'Ainul Jannah ME', 'tanggal' => '29/05/26', 'berat' => 20.2, 'jenis' => 'HVS', 'total' => 22321],
            ['nama' => 'Ainul Jannah ME', 'tanggal' => '29/05/26', 'berat' => 3.0, 'jenis' => 'Kertas Campur', 'total' => 1170],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '29/05/26', 'berat' => 3.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1560],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '29/05/26', 'berat' => 2.0, 'jenis' => 'Sak Telur', 'total' => 1300],
            ['nama' => 'Lovely Harman Zulyadi', 'tanggal' => '29/05/26', 'berat' => 2.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 1560],
            ['nama' => 'MARDIAH', 'tanggal' => '09/06/26', 'berat' => 2.0, 'jenis' => 'Kardus', 'total' => 1690],
            ['nama' => 'MARDIAH', 'tanggal' => '09/06/26', 'berat' => 5.2, 'jenis' => 'Minyak Jelantah', 'total' => 20800],
            ['nama' => 'MARDIAH', 'tanggal' => '10/06/26', 'berat' => 2.0, 'jenis' => 'HVS', 'total' => 2210],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 0.7, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 1138],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 3.0, 'jenis' => 'Botol Plastik Minuman Bersih (tanpa tutup dan label)', 'total' => 4875],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 4.0, 'jenis' => 'Botol Plastik Minuman', 'total' => 3120],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 17.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 8840],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 2.7, 'jenis' => 'Kaleng Campur', 'total' => 2632],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 4.0, 'jenis' => 'Besi Campur', 'total' => 3900],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 5.0, 'jenis' => 'Besi Campur', 'total' => 4875],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 1.2, 'jenis' => 'Kaleng Campur', 'total' => 1170],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 0.8, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 416],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 0.3, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 156],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 1.1, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 572],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 2.5, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 1300],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 21.0, 'jenis' => 'Kardus', 'total' => 17745],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 10.0, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 5200],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 18.0, 'jenis' => 'HVS', 'total' => 19890],
            ['nama' => 'POM TK Permata Bunda', 'tanggal' => '12/06/26', 'berat' => 5.7, 'jenis' => 'Plastik Campur (Karah-karah)', 'total' => 2964],
            ['nama' => 'Nagari Pangian', 'tanggal' => '17/06/26', 'berat' => 0.46, 'jenis' => 'HVS', 'total' => 508],
            ['nama' => 'Nagari Pangian', 'tanggal' => '17/06/26', 'berat' => 0.4, 'jenis' => 'Kardus', 'total' => 338],
            ['nama' => 'Nagari Pangian', 'tanggal' => '17/06/26', 'berat' => 5.0, 'jenis' => 'minyak jelantah', 'total' => 20000],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '19/06/26', 'berat' => 3.2, 'jenis' => 'Botol Plastik Minuman', 'total' => 2496],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '19/06/26', 'berat' => 0.7, 'jenis' => 'Kaleng Minuman Alumunium (Alma)', 'total' => 4550],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '19/06/26', 'berat' => 5.6, 'jenis' => 'Kardus', 'total' => 4732],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '19/06/26', 'berat' => 0.6, 'jenis' => 'Kaleng Campur', 'total' => 585],
            ['nama' => 'dr. DWINANDA EMIRA', 'tanggal' => '19/06/26', 'berat' => 2.4, 'jenis' => 'Sak Telur', 'total' => 1560],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '19/06/26', 'berat' => 31.0, 'jenis' => 'HVS', 'total' => 34255],
            ['nama' => 'SRI MULYANI (PERTANIAN)', 'tanggal' => '19/06/26', 'berat' => 2.0, 'jenis' => 'Kardus', 'total' => 1690],
            ['nama' => 'Yeni Suhastri', 'tanggal' => '21/06/26', 'berat' => 6.0, 'jenis' => 'Kardus', 'total' => 5070],
            ['nama' => 'Hasby Abyan Shauqi', 'tanggal' => '21/06/26', 'berat' => 2.0, 'jenis' => 'Kardus', 'total' => 1690],
        ];
        // Preload semua data biar gak query per-baris (N+1)
        $books = BukuTabungan::get()->keyBy(fn($b) => strtolower(trim($b->nama)));
        $users = User::get()->keyBy(fn($u) => strtolower(trim($u->name)));
        $trashes = Trash::with('prices')->get()->keyBy(fn($t) => strtolower(trim($t->nama)));

        $skipped = [];
        $created = 0;

        foreach ($transaksidata as $index => $trx) {
            $namaKey = strtolower(trim($trx['nama']));
            $jenisKey = strtolower(trim($trx['jenis']));

            $book = $books->get($namaKey);
            $nb = $users->get($namaKey);
            $jns = $trashes->get($jenisKey);

            if (!$book || !$nb || !$jns) {
                $skipped[] = [
                    'index' => $index,
                    'alasan' => !$book ? 'buku_tabungan tidak ditemukan' : (!$nb ? 'user tidak ditemukan' : 'jenis sampah tidak ditemukan'),
                    'trx' => $trx,
                ];
                continue;
            }

            $price = $jns->prices->first();

            if (!$price) {
                $skipped[] = [
                    'index' => $index,
                    'alasan' => "harga untuk '{$trx['jenis']}' tidak ditemukan",
                    'trx' => $trx,
                ];
                continue;
            }

            $tanggal = \Carbon\Carbon::createFromFormat('d/m/y', $trx['tanggal']);

            $setoran = Setoran::create([
                'penyetor_id' => $nb->id,
                'total_berat' => $trx['berat'],
                'total_saldo' => $trx['total'],
                'tanggal' => $tanggal->format('Y-m-d'),
                'buku_tabungan_id' => $book->id,
                'admin_id' => 2,
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            $setoran->items()->create([
                'price_id' => $price->id,
                'trash_id' => $jns->id,
                'type' => 'induk',
                'berat' => $trx['berat'],
                'harga' => $price->harga,
                'sub_total' => $trx['total'],
            ]);

            $book->increment('saldo', $setoran->total_saldo);
            $created++;
        }

        Log::info("Seeder selesai: {$created} transaksi dibuat, " . count($skipped) . ' di-skip');
        if (!empty($skipped)) {
            Log::warning('Detail baris yang di-skip:', $skipped);
        }

        if (app()->runningInConsole()) {
            $this->command->info("{$created} transaksi berhasil dibuat.");
            if (!empty($skipped)) {
                $this->command->warn(count($skipped) . ' baris di-skip:');
                foreach ($skipped as $s) {
                    $this->command->line("  #{$s['index']}: {$s['trx']['nama']} / {$s['trx']['jenis']} — {$s['alasan']}");
                }
            }
        }

        $nasabahnarik = [['nama' => 'POM TK Permata Bunda', 'tanggal' => '08/08/25', 'total' => 180000, 'admin' => 'Naya'], ['nama' => 'Jusdawenti', 'tanggal' => '24/10/25', 'total' => 150000, 'admin' => 'Adrian'], ['nama' => 'DEDI TRIWIDONO S.STP (KOMINFO)', 'tanggal' => '06/02/26', 'total' => 780000, 'admin' => 'Adrian'], ['nama' => 'POM TK Permata Bunda', 'tanggal' => '02/07/26', 'total' => 550000, 'admin' => 'Adrian']];

        foreach ($nasabahnarik as $nbr) {
            $nsb = User::with(['unit.gudang'])
                ->where('name', $nbr['nama'])
                ->first();

            $adm = User::where('name', $nbr['admin'] . ' Darling')->first();

            if (!$nsb || !$adm || !$nsb->unit?->gudang) {
                continue;
            }

            $bt = BukuTabungan::where('user_id', $nsb->id)->first();

            if (!$bt || $bt->saldo < $nbr['total']) {
                continue;
            }

            $tanggal2 = Carbon::createFromFormat('d/m/y', $nbr['tanggal']);

            $trans = Transaksi::create([
                'total_penarikan' => $nbr['total'],
                'sisa_saldo' => $bt->saldo - $nbr['total'],
                'tanggal_transaksi' => $tanggal2,
                'owner_id' => $nsb->id,
                'admin_id' => $adm->id,
                'buku_tabungan_id' => $bt->id,
                'created_at' => $tanggal2,
                'updated_at' => $tanggal2,
            ]);

            $bt->decrement('saldo', $nbr['total']);

            Pengeluaran::create([
                'total_penarikan' => $trans->total_penarikan,
                'keterangan' => 'Penarikan tabungan oleh nasabah dengan nomor Rekening : ' . $bt->nomor_rekening,
                'admin_id' => $adm->id,
                'gudang_id' => $nsb->unit->gudang->id,
                               'created_at' => $tanggal2,
                'updated_at' => $tanggal2,
            ]);
        }

        $gdgTrx = [
            ['total' => 2923000, 'ket' => 'Penjualan sampah ke BSI', 'admin' => 'Adrian Darling', 'tanggal' => '01/09/25'],
            ['total' => 2095000, 'ket' => 'Penjualan sampah ke BSI', 'admin' => 'Adrian Darling', 'tanggal' => '03/10/25'],
            ['total' => 130000, 'ket' => 'Narsum Batu Basa', 'admin' => 'Adrian Darling', 'tanggal' => '03/10/25'],
            ['total' => 4300, 'ket' => 'Sisa Arus Kas Operasional', 'admin' => 'Adrian Darling', 'tanggal' => '05/10/25'],
            ['total' => 70000, 'ket' => 'Donasi narasumber', 'admin' => 'Adrian Darling', 'tanggal' => '14/10/25'],
            ['total' => 150000, 'ket' => 'Narsum di Padang magek', 'admin' => 'Adrian Darling', 'tanggal' => '16/10/25'],
            ['total' => 210000, 'ket' => 'Donasi Narasumber dan Penjualan Prelove', 'admin' => 'Adrian Darling', 'tanggal' => '31/10/25'],
            ['total' => 75000, 'ket' => 'Penjualan Prelove ke TK', 'admin' => 'Adrian Darling', 'tanggal' => '09/01/26'],
            ['total' => 2603000, 'ket' => 'Penjualan Sampah ke BSI', 'admin' => 'Adrian Darling', 'tanggal' => '20/04/26'],
            ['total' => 70000, 'ket' => 'Bayar memilah sampah', 'admin' => 'Adrian Darling', 'tanggal' => '25/05/26'],
        ];

        foreach ($gdgTrx as $index => $g) {
            $tanggal3 = \Carbon\Carbon::createFromFormat('d/m/y', $g['tanggal']);
            $admg = User::where('name', $g['admin'])->first();
            $gdg = Gudang::where('bank_id', 2)->first();
            $tgd = TransaksiBongkarGudang::create([
                'keterangan' => $g['ket'],
                'total_penarikan' => $g['total'],
                'admin_id' => $admg->id,
                'gudang_id' => $gdg->id,
                'created_at' => $tanggal3,
                'updated_at' => $tanggal3,
            ]);
        }

        $pengl = [['total' => 100000, 'ket' => 'Konsumsi botol sampah', 'admin' => 'Adrian Darling', 'tanggal' => '11/02/26'], ['total' => 150000, 'ket' => 'Bayar rambahan dan PLN', 'admin' => 'Adrian Darling', 'tanggal' => '27/03/26'], ['total' => 100000, 'ket' => 'Konsumsi botol sampah', 'admin' => 'Adrian Darling', 'tanggal' => '11/02/26']];

        foreach ($pengl as $pg) {
            $tanggal4 = \Carbon\Carbon::createFromFormat('d/m/y', $pg['tanggal']);
            $admg = User::where('name', $g['admin'])->first();
            $gdg = Gudang::where('bank_id', 2)->first();
            Pengeluaran::create([
                'total_penarikan' => $pg['total'],
                'keterangan' => $pg['ket'],
                'admin_id' => $admg->id,
                'gudang_id' => $gdg->id,
                'created_at' => $tanggal4,
                'updated_at' => $tanggal4,
            ]);
        }
    }
}