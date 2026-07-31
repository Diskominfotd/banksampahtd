<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomeFunctionController extends Controller
{
    public function testing()
    {
        // $user = User::with('roles')->find(1);
        // $user->syncRoles(['supervisor']);

        $users = User::whereIn('nomor_hp', function ($query) {
            $query->select('nomor_hp')->from('users')->whereNotNull('nomor_hp')->where('nomor_hp', '!=', '')->groupBy('nomor_hp')->havingRaw('COUNT(*) > 1');
        })
            ->orderBy('nomor_hp')
            ->get();

        return dd(json_encode($users, JSON_PRETTY_PRINT));
    }
}
