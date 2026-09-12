<?php
namespace App\Services;

use App\Models\BrandProfile;
use Illuminate\Support\Facades\Http;

class ContentGeneratorService
{
    public function generate(BrandProfile $brand, string $topic): string
    {
        $systemPrompt = "You are a content writer for {$brand->brand_name},
        a company in the {$brand->industry} industry. Write content 
        targeted at {$brand->target_audience}. Use a {$brand->tone} tone. 
        Brand description: {$brand->description}.";

        if($brand->words_to_avoid) {
            $systemPrompt .= "Avoid these words/phrases: {$brand->words_to_avoid}";
        }

        $response = Http::withToken(config('services.openai.api_key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => "Write content about: {$topic}"
                    ]
                ],
            ]);
        \Log::info('OpenAI raw response:', $response->json() ?? ['raw_body' => $response->body()]);
        return $response->json()['choices'][0]['message']['content'];
    }
}