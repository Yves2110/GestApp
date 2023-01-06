<?php

namespace App\Http\Controllers;

use App\Models\service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services=service::all();
        return view('layouts/home', ['services'=>$services] );
    }

    public function serviceajout()
    {
        return view('pages/serviceajout');
    }

    public function servicestore(Request $request)
    {
        request()->validate([
            'service' => 'required'
        ]);
        service::create ([
            "service" =>$request->service,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');

    }
}
