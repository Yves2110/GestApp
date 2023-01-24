<?php

namespace App\Http\Controllers;

use App\Models\guide;
use App\Models\service;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index()
    {
        $guides = guide::all();
        $services = service::all();
        return view('pages.guide', compact('guides', 'services'));
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'file' => 'required|mimes:pdf,xlsx,docx,|max:5048'
            ]
        );
        $input = $request->all();
        if ($docs = $request->file('file')) {
            $destinationPath = 'docs/';
            $guides = date('YmdHis') . "." . $docs->getClientOriginalExtension();
            $docs->move($destinationPath, $guides);
            $input['file'] = "$guides";
        }

        guide::create($input);
        return back()->with('message', 'Enregistrement effectué avec succès!');
    }

    public function destroy($id)
    {
        $guide=guide::find($id);
        $guide->delete();
        return back()->with('message', 'Suppression effectué avec succès!');
    }
}
