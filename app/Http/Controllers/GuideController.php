<?php

namespace App\Http\Controllers;

use App\Models\guide;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index()
    {
        $guides=guide::all();
        return view('pages.guide', ['guides' =>$guides]);
    }

   
    public function store(Request $request)
    {
        // dd($request);
        Request()->validate([
            'file' => 'required'
        ]);
        guide::create([
            'file' => $request->file
        ]);
        return back()->with('message', 'Enregistrement effectué avec succès!');
    }
}
