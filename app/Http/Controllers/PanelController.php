<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function login()
    {
        return view('panel.pages.login');
    }
    public function home()
    {
        return view('panel.pages.home');
    }
    public function nasabah()
    {
        return view('panel.pages.nasabah');
    }
    public function kategori()
    {
        return view('panel.pages.category');
    }
    public function harga()
    {
        return view('panel.pages.price');
    }
    public function setoran()
    {
        return view('panel.pages.setoran');
    }
    public function catatSetoran()
    {
        return view('panel.pages.catat-setoran');
    }
    public function penarikanSaldo()
    {
        return view('panel.pages.penarikan');
    }
}
