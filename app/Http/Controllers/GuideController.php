<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\Service;
use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::all();
        $services = Service::all();
        return view('pages.guide', compact('guides', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fichier' => 'required|mimes:pdf,xlsx,docx|max:5120',
        ]);

        if ($file = $request->file('fichier')) {
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('documents', $filename, 'local');
            Guide::create(['fichier' => $filename]);
        }

        return back()->with('message', 'Enregistrement effectué avec succès!');
    }

    public function destroy($id)
    {
        $guide = Guide::findOrFail($id);
        $guide->delete();
        return back()->with('message', 'Suppression effectuée avec succès!');
    }
}
