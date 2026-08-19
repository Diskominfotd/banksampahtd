<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class NasabahImport implements ToModel, WithValidation, WithStartRow, SkipsOnFailure
{
    use SkipsFailures;

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        if (empty($row[0]) || !isset($row[3]) || !isset($row[4])) {
            Log::info('Baris dilewati', ['row' => $row]);
            return null;
        }
        try {
            $nik = trim((string) $row[3]);
            $nomorHp = trim((string) $row[4]);
            $user = new User([
                'name' => $row[0],
                'email' => $row[1],
                'password' => Hash::make($row[2]),
                'nik' => encrypt($nik),
                'nik_hash' => hash('sha256', $nik),
                'nomor_hp' => $nomorHp,
                'bank_sampah_id' => Auth::user()->bank_sampah_id,
            ]);
            $user->assignRole('nasabah');
            return $user;
        } catch (\Throwable $e) {
            Log::error('Import nasabah gagal', ['row' => $row, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            '3' => [
                'required',
                'regex:/^[0-9]{16}$/',
                function ($attribute, $value, $fail) {
                    $nik = trim((string) $value);
                    if (User::where('nik_hash', hash('sha256', $nik))->exists()) {
                        $fail('NIK sudah terdaftar.');
                    }
                },
            ],
            '4' => ['required', 'regex:/^08[0-9]+$/', 'unique:users,nomor_hp'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '3.required' => 'NIK wajib diisi.',
            '3.regex' => 'NIK harus berupa 16 digit angka.',
            '4.required' => 'Nomor HP wajib diisi.',
            '4.regex' => 'Nomor HP harus diawali 08 dan hanya berisi angka.',
            '4.unique' => 'Nomor HP sudah terdaftar di database.',
        ];
    }
}
