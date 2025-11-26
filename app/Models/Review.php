<?php

namespace App\Models;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ревью ментора для конкретной отправки кода.
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_submission_id',
        'mentor_id',
        'status',
        'comment',
        'rating',
    ];

    protected $casts = [
        'status' => ReviewStatus::class,
    ];

    /**
     * Работа, которую проверяет ментор.
     */
    public function codeSubmission(): BelongsTo
    {
        return $this->belongsTo(CodeSubmission::class);
    }

    /**
     * Ментор, выполняющий ревью.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Комментарии ментора по конкретным файлам/строкам.
     */
    public function reviewComments(): HasMany
    {
        return $this->hasMany(ReviewComment::class);
    }
}
