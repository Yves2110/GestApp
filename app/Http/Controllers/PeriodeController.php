<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PeriodeController extends Controller
{
    public function view()
    {
        $trimestres = Periode::all();
        $services   = Service::all();
        return view('pages.trimestre', compact('trimestres', 'services'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate(['label' => 'required|string|max:100|unique:periodes,label']);

        Periode::create(['label' => $request->label]);
        Cache::forget('periodes_all');

        return back()->with('message', 'Période créée avec succès.');
    }
}
