<?php
namespace App\Services\Impl;

use App\Models\BankSampah;
use App\Models\BukuTabungan;
use App\Models\Category;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserServicesImpl implements UserServices
{
    public function checkUser()
    {
        return Auth::user();
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
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
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
            return session()->flash('success', 'Berhasil');
        });
    }
    public function updateUser(int $id, array $data)
    {
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
        return session()->flash('success', 'Berhasil');
    }

    public function getUserById(int $id)
    {
        return User::query()->find($id);
    }
    public function getBukuTabunganByUserId(int $id)
    {
        return BukuTabungan::where('user_id', $id)->get();
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

    public function createCategory(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            return Category::create([
                'name' => $data['name'],
            ]);
        });
    }

    public function updateCategory(array $data, int $id): Category
    {
        return DB::transaction(function () use ($data, $id) {
            $category = Category::findOrFail($id);
            $category->update([
                'name' => $data['name'],
            ]);
            return $category;
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
            $user = $this->getUserById($userId);

            $exists = BukuTabungan::where('user_id', $user->id)->where('bank_id', $bankId)->exists();
            if ($exists) {
                session()->flash('failed', 'Nasabah sudah punya rekening di unit ini');
                return null;
            }
            $bank = BankSampah::findOrFail($bankId);
            do {
                $nomorRekening = $bank->kode_bank . str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            } while (BukuTabungan::where('nomor_rekening', $nomorRekening)->exists());

            BukuTabungan::create([
                'nama' => $user->name,
                'nomor_rekening' => $nomorRekening,
                'user_id' => $user->id,
                'bank_id' => $bankId,
            ]);
            return session()->flash('success', 'Berhasil');
        });
    }

    public function getUserByUnitAndBook()
    {
        $user = $this->checkUser();

        return User::with([
            'bukutabungans' => function ($q) use ($user) {
                $q->where('bank_id', $user->unit->id);
            },
        ])->whereHas('bukutabungans', function ($q) use ($user) {
            $q->where('bank_id', $user->unit->id);
        });
    }
}
