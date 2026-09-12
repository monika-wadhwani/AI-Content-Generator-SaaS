<?php

namespace App\Jobs;

use App\Models\ContentGenerator;
use App\Services\ContentGeneratorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateContent implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ContentGenerator $generation)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ContentGeneratorService $generator): void
    {
        try{
            $brand = $this->generation->brandProfile;
    
            $content = $generator->generate($brand, $this->generation->topic);
    
            $this->generation->update([
                'generated_content' => $content,
                'status' => 'completed'
            ]);
        }

        catch(\Exception $e){
            $this->generation->update([
                'status' => 'failed'
            ]);

            \Log::error('Content generation failed: ' . $e->getMessage());
        }
    }
}
