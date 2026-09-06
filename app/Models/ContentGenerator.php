<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentGenerator extends Model
{
    protected $fillable = [
        'user_id', 'brand_profile_id', 'topic', 'generated_content', 'status'
    ];

    public function brandProfile()
    {
        return $this->belongsTo(BrandProfile::class);
    }
}
