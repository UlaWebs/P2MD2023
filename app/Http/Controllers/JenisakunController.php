<?php

namespace App\Http\Controllers;

use App\Models\JenisAkun;
use Illuminate\Http\Request;

class JenisakunController extends Controller
{
    public function index()
    {
        $data = JenisAkun::all();
        return view('jenisakun/index', ['data' => $data, 'title' => "Jenis Akun"]);
    }
}
