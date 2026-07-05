<?php
namespace App\Services\Impl;

use App\Models\BankSampah;
use App\Models\BukuTabungan;
use App\Models\Category;
use App\Models\Gudang;
use App\Models\Organisasi;
use App\Models\Setoran;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Illuminate\Support\Str;

class UserServicesImpl implements UserServices
{
    private function generateGudangKode(): string
    {
        do {
            $kode = 'GDG-' . strtoupper(Str::random(3)) . '-' . rand(100, 999);
        } while (Gudang::where('kode', $kode)->exists());

        return $kode;
    }

    public function checkUser()
    {
        return Auth::user();
    }

    private function hitungPersentase(float $today, float $yesterday): float
    {
        if ($yesterday > 0) {
            return round((($today - $yesterday) / $yesterday) * 100, 2);
        }
        if ($today > 0) {
            return 100;
        }
        return 0;
    }

    public function getBanks()
    {
        return BankSampah::query();
    }

    public function doLogin(array $data)
    {
        $nik = $data['nik'];
        $password = $data['password'];
        $hashedNik = hash('sha256', $nik);

        $user = User::query()->where('nik_hash', $hashedNik)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Nik atau password anda salah');
        }

        if (!Hash::check($password, $user->password)) {
            return redirect()->route('login')->with('error', 'Nik atau password anda salah');
        }

        Auth::login($user);
        return redirect()->route('home');
    }

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                $hashedNik = hash('sha256', $data['nik']);
                User::create([
                    'nik' => $data['nik'],
                    'nik_hash' => $hashedNik,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'nomor_hp' => $data['nomor_hp'],
                    'mewakili' => $data['mewakili'],
                    'organisasi_id' => $data['organisasi_id'] ?? null,
                    'bank_sampah_id' => $data['bank_sampah_id'],
                    'password' => Hash::make($data['password']),
                ]);
                session()->flash('success', 'Berhasil');
            } catch (Throwable $th) {
                session()->flash('error', 'Terjadi Kesalahan');
            }
        });
    }

    public function updateUser(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $user = User::findOrFail($id);
                $hashedNik = hash('sha256', $data['nik']);
                $user->update([
                    'nik' => $data['nik'],
                    'nik_hash' => $hashedNik,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'nomor_hp' => $data['nomor_hp'],
                    'mewakili' => $data['mewakili'],
                    'organisasi_id' => $data['organisasi_id'] ?? null,
                    'bank_sampah_id' => $data['bank_sampah_id'],
                ]);
                session()->flash('success', 'Berhasil');
            } catch (Throwable $th) {
                session()->flash('error', 'Terjadi Kesalahan');
            }
        });
    }

    public function getUserById(int $id)
    {
        return User::with(['bukutabungans', 'setorans'])->find($id);
    }

    public function getBukuTabunganByUserId(int $id)
    {
        return BukuTabungan::with('bank')->where('user_id', $id)->get();
    }

    public function doLogout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function userBuilder()
    {
        return User::query();
    }

    public function createCategory(array $data)
    {
        return DB::transaction(function () use ($data) {
            try {
                Category::create(['name' => $data['name']]);
                session()->flash('success', 'Berhasil');
            } catch (Throwable $th) {
                session()->flash('error', 'Terjadi Kesalahan');
            }
        });
    }

    public function updateCategory(array $data, int $id)
    {
        return DB::transaction(function () use ($data, $id) {
            try {
                $category = Category::findOrFail($id);
                $category->update(['name' => $data['name']]);
                session()->flash('success', 'Berhasil');
            } catch (Throwable $th) {
                session()->flash('error', 'Terjadi Kesalahan');
            }
        });
    }

    public function categoryById(int $id)
    {
        return Category::findOrFail($id);
    }

    public function categoriesBuilder()
    {
        return Category::query();
    }

    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
            return session()->flash('success', 'Berhasil');
        }
    }

    public function delete(int $id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            return $category->delete();
        }
    }

    public function createBukuTabungan(int $userId, int $bankId)
    {
        return DB::transaction(function () use ($userId, $bankId) {
            try {
                $user = $this->getUserById($userId);

                $exists = BukuTabungan::where('user_id', $user->id)->where('bank_id', $bankId)->exists();

                if ($exists) {
                    session()->flash('failed', 'Nasabah sudah punya rekening di unit ini');
                    return null;
                }

                $bank = BankSampah::findOrFail($bankId);
                do {
                    $nomorRekening = $bank->kode_bank . '-' . str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
                } while (BukuTabungan::where('nomor_rekening', $nomorRekening)->exists());

                BukuTabungan::create([
                    'nama' => $user->name,
                    'nomor_rekening' => $nomorRekening,
                    'user_id' => $user->id,
                    'bank_id' => $bankId,
                ]);
                session()->flash('success', 'Berhasil');
            } catch (Throwable $th) {
                session()->flash('error', 'Terjadi Kesalahan');
            }
        });
    }

    public function getUserByUnitAndBook()
    {
        $user = $this->checkUser();
        if (!$user->unit->parent_id) {
            return User::with(['bukutabungans']);
        } else {
            return User::with([
                'bukutabungans' => function ($q) use ($user) {
                    $q->where('bank_id', $user->unit->id);
                },
            ])->whereHas('bukutabungans', function ($q) use ($user) {
                $q->where('bank_id', $user->unit->id);
            });
        }
    }

    public function totalNasabah()
    {
        $total = $this->getUserByUnitAndBook()->count();
        $todayDate = now()->startOfDay();
        $yesterdayDate = now()->subDay()->startOfDay();

        $today = $this->getUserByUnitAndBook()->whereDate('created_at', $todayDate)->count();
        $yesterday = $this->getUserByUnitAndBook()->whereDate('created_at', $yesterdayDate)->count();

        return [
            'total' => $total,
            'today' => $today,
            'yesterday' => $yesterday,
            'difference' => $today - $yesterday,
            'persentase' => $this->hitungPersentase($today, $yesterday),
        ];
    }

    public function bukuTabunganByAuthUser()
    {
        $user = $this->checkUser();
        return BukuTabungan::where('user_id', $user->id);
    }

    public function totalSaldoNasabah()
    {
        $totalSaldo = $this->bukuTabunganByAuthUser()->sum('saldo');
        return [
            'today' => $totalSaldo,
            'yesterday' => 0,
            'persentase' => $this->hitungPersentase($totalSaldo, 0),
        ];
    }

    public function totalBukuTabunganNasabah()
    {
        $user = $this->checkUser();
        return BukuTabungan::where('user_id', $user->id)->count();
    }

    public function getBankByUserId(int $id)
    {
        return BankSampah::where('id', $id)->first();
    }

    public function updateBankSampah(int $id, array $data)
    {
        $unit = $this->getBankByUserId($id);
        $unit->update([
            'nama' => $data['nama'],
            'kode_bank' => $data['kode_bank'],
            'alamat' => $data['alamat'],
            'jam_buka' => $data['jam_buka'],
            'jam_tutup' => $data['jam_tutup'],
            'telepon' => $data['telepon'],
        ]);
        session()->flash('success', 'Behasil');
    }

    public function updateProfile(int $id, array $data)
    {
        $hashedNik = hash('sha256', $data['nik']);
        $user = User::findOrFail($id);
        $user->update([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'nomor_hp' => $data['nomor_hp'],
            'nik' => $data['nik'],
            'nik_hash' => $hashedNik,
        ]);
        session()->flash('success', 'Behasil');
    }

    public function updatePassword(int $id, string $password)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => bcrypt($password),
        ]);
        session()->flash('success', 'Behasil');
    }

    public function nasabahAktifByBook(int $unitId)
    {
        return BukuTabungan::where('bank_id', $unitId)->count();
    }

    public function unitBuilder()
    {
        return BankSampah::query();
    }
    public function getUnitById(int $id)
    {
        return BankSampah::find($id);
    }
    public function createUnit(array $data)
    {
        return DB::transaction(function () use ($data) {
            $parent = BankSampah::first();
            $unit = BankSampah::create([
                'nama' => $data['nama'],
                'alamat' => $data['alamat'],
                'telepon' => $data['telepon'],
                'parent_id' => $parent->id,
                'kode_bank' => $data['kode_bank'],
                'jam_buka' => $data['jam_buka'],
                'jam_tutup' => $data['jam_tutup'],
            ]);
            Gudang::create([
                'kode' => $this->generateGudangKode(),
                'bank_id' => $unit->id,
            ]);
            session()->flash('success', 'Behasil');
        });
    }
    public function updateUnit(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $unit = BankSampah::find($id);
            $unit->update([
                'nama' => $data['nama'],
                'alamat' => $data['alamat'],
                'telepon' => $data['telepon'],
                'jam_buka' => $data['jam_buka'],
                'jam_tutup' => $data['jam_tutup'],
                'kode_bank' => $data['kode_bank'],
            ]);
            session()->flash('success', 'Behasil');
        });
    }

    public function createOrganisasi(array $data)
    {
        return DB::transaction(function () use ($data) {
            Organisasi::create([
                'nama' => $data['nama'],
            ]);
            session()->flash('success', 'Behasil');
        });
    }
    public function updateOrganisasi(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $org = Organisasi::find($id);
            $org->update([
                'nama' => $data['nama'],
            ]);
            session()->flash('success', 'Behasil');
        });
    }
    public function organisasiBuilder()
    {
        return Organisasi::query();
    }
    public function getOrganisasiById(int $id)
    {
        return Organisasi::find($id);
    }
}
