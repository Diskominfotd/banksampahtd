<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class NasabahExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        return User::with([
            'bukutabungans' => function ($q) use ($user) {
                $q->where('bank_id', $user->unit->id);
            },
        ])
            ->whereHas('bukutabungans', function ($q) use ($user) {
                $q->where('bank_id', $user->unit->id);
            })
            ->whereHas('roles', function ($q) {
                $q->where('name', 'nasabah');
            })
            ->get();
    }
}
