<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    protected $fillable = [
        'user_id', 'headline', 'location', 'summary', 'skills', 'education',
        'experience', 'profile_picture', 'resume_path',
    ];

    protected $casts = [
        'skills' => 'array',
        'education' => 'array',
        'experience' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
