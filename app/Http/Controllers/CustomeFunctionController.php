<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomeFunctionController extends Controller
{
    public function testing()
    {
        $user = User::with('roles')->find(1);
        $user->syncRoles(['supervisor']);

        return dd(json_encode($user, JSON_PRETTY_PRINT));
    }
}
