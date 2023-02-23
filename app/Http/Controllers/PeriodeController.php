<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function view(){
        $trimestres=Periode::all();
        return view('pages.trimestre', compact('trimestres'));
    }
}
