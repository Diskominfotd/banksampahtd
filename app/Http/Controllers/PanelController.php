<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function home()
    {
        return view('panel.pages.home');
    }
    public function nasabah()
    {
        return view('panel.pages.nasabah');
    }
}
