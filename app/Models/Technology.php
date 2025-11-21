<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technology extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function mentorProfiles(): BelongsToMany
    {
        return $this->belongsToMany(MentorProfile::class, 'mentor_technology');
    }

    public function codeSubmissions(): BelongsToMany
    {
        return $this->belongsToMany(CodeSubmission::class, 'code_submission_technology');
    }
}
