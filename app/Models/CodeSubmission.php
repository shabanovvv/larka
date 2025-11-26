<?php

namespace App\Models;

use App\Enums\CodeSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Отправка решения студентом для последующего ревью.
 */
class CodeSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'mentor_id',
    ];

    protected $casts = [
        'status' => CodeSubmissionStatus::class,
    ];

    /**
     * Студент, который создал отправку.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ментор, закреплённый за проверкой работы.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Технологии, отмеченные студентом в задаче.
     */
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    /**
     * Файлы, загруженные в рамках работы.
     */
    public function submissionFiles(): HasMany
    {
        return $this->hasMany(SubmissionFile::class);
    }

    /**
     * Выполненные AI-анализы этой работы.
     */
    public function aiAnalyses(): HasMany
    {
        return $this->hasMany(AiAnalysis::class);
    }

    /**
     * Ревью, которые прошла отправка.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
