<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Технология/стек, который привязывается к менторам и работам.
 */
class Technology extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'slug'];

    /**
     * Менторы, владеющие данной технологией.
     */
    public function mentorProfiles(): BelongsToMany
    {
        return $this->belongsToMany(MentorProfile::class, 'mentor_technology');
    }

    /**
     * Отправки кода, в которых указана технология.
     */
    public function codeSubmissions(): BelongsToMany
    {
        return $this->belongsToMany(CodeSubmission::class, 'code_submission_technology');
    }
}
