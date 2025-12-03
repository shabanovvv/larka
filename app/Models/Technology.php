<?php

namespace App\Models;

use Database\Factories\TechnologyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Технология/стек, который привязывается к менторам и работам.
 */
class Technology extends Model
{
    /** @use HasFactory<TechnologyFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Менторы, владеющие данной технологией.
     *
     * @return BelongsToMany<MentorProfile, $this>
     */
    public function mentorProfiles(): BelongsToMany
    {
        return $this->belongsToMany(MentorProfile::class, 'mentor_technology');
    }

    /**
     * Отправки кода, в которых указана технология.
     *
     * @return BelongsToMany<CodeSubmission, $this>
     */
    public function codeSubmissions(): BelongsToMany
    {
        return $this->belongsToMany(CodeSubmission::class, 'code_submission_technology');
    }
}
