<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Загруженный файл в рамках отправки студентом.
 */
class SubmissionFile extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $fillable = [
        'code_submission_id',
        'filename',
        'content',
    ];

    /**
     * Отправка кода, которой принадлежит файл.
     *
     * @return BelongsTo<CodeSubmission, $this>
     */
    public function codeSubmission(): BelongsTo
    {
        return $this->belongsTo(CodeSubmission::class);
    }

    /**
     * Комментарии ревью, оставленные на этот файл.
     *
     * @return HasMany<ReviewComment, $this>
     */
    public function reviewComments(): HasMany
    {
        return $this->hasMany(ReviewComment::class);
    }
}
