<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformeController extends Controller
{
    public function index()
    {
        if (!session('user')) {
            return redirect('/login');
        } else if ((session('user')['cod_rol'] == 1 || session('user')['cod_rol'] == 2)) {
            return redirect('/main');
        }

        return view('informes');
    }


}