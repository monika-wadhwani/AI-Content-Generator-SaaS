<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateContent;
use App\Models\BrandProfile;
use App\Models\ContentGenerator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentGenerationController extends Controller
{
    public function create(BrandProfile $brandProfile){
        $generations = $brandProfile->generations()->latest()->get();

        return Inertia::render('ContentGenerations/Create',[
            'brandProfile' => $brandProfile,
            'generations' => $generations,
        ]);
    }

    public function store(Request $request, BrandProfile $brandProfile){
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
        ]);

        $generation = ContentGenerator::create([
            'user_id' => auth()->id(),
            'brand_profile_id' => $brandProfile->id,
            'topic' => $validated['topic'],
            'status' => 'pending',
        ]);

        GenerateContent::dispatch($generation);

        return redirect()->back()->with('success', 'Content generation started!');
    }

    public function check(ContentGenerator $generation){
        return response()->json([
            'status' => $generation->status,
            'generated_content' => $generation->generated_content
        ]);
    }

    public function retry(ContentGenerator $generation){
        $generation->update(['status' => 'pending']);

        GenerateContent::dispatch($generation);

        return redirect()->back();
        
    }
}
