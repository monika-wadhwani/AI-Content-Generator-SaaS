<?php

namespace App\Http\Controllers;

use App\Models\BrandProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandProfileController extends Controller
{
    public function index(){
        $brandProfiles = auth()->user()->brandProfiles;

        return Inertia::render('BrandProfiles/Index', [
            'brandProfiles' => $brandProfiles
        ]);
    }

    public function create(){
        return Inertia::render('BrandProfiles/Create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'target_audience' => 'required|string|max:255',
            'tone' => 'required|string|max:255',
            'description' => 'required|string',
            'words_to_avoid' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        BrandProfile::create($validated);

        return redirect()->route('brand-profiles.index')->with('success', 'Brand profile created!');
    }
}
