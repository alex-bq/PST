<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public $usuarios = [
        [
            'id' => 1,
            'nombre' => 'Alex',
            'rol' => 2,
        ],
        [
            'id' => 2,
            'nombre' => 'Rodrigo',
            'rol' => 1,
        ],
        [
            'id' => 3,
            'nombre' => 'admin',
            'rol' => 0,
        ],
    ];


    public function index()
    {
        return view('index');

    }

}
