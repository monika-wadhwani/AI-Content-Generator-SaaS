<?php

namespace App\Models;

use App\Models\ContentGenerator;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BrandProfile extends Model
{
    protected $fillable = [
        'user_id', 'brand_name', 'industry', 'target_audience',  'tone', 'description', 'words_to_avoid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generations()
    {
        return $this->hasMany(ContentGenerator::class);
    }
}
