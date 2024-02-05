<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{
    public function admin()
    {
        return view('admin.admin');
    }
    public function mCorte()
    {
        return view('admin.mantencion.corte');
    }
    public function mCalidad()
    {
        return view('admin.mantencion.calidad');
    }
    public function mDestino()
    {
        return view('admin.mantencion.destino');
    }
    public function mCalibre()
    {
        return view('admin.mantencion.calibre');
    }
}
