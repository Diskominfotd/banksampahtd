<?php
namespace App\Services\Impl;

use App\Models\User;
use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserServicesImpl implements UserServices
{
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
            return User::create([
                'nik' => $data['nik'],
                'name' => $data['name'],
                'email' => $data['email'],
                'nomor_hp' => $data['nomor_hp'],
                'mewakili' => $data['mewakili'],
                'organisasi_id' => $data['organisasi_id'] ?? null,
                'password' => Hash::make($data['password']),
                'rekening' => $data['rekening'],
                
            ]);
        }); 
    }

    public function userBuilder()
    {
        return User::query();
    }
}
