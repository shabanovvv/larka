<?php

namespace App\Models;

use App\Enums\CodeSubmissionStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Отправка решения студентом для последующего ревью.
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $mentor_id
 * @property string|null $code
 * @property CodeSubmissionStatus $status
 */
class CodeSubmission extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $attributes = [
        'status' => CodeSubmissionStatus::WAITING->value,
    ];

    protected $fillable = [
        'user_id',
        'code',
        'description',
        'status',
        'mentor_id',
    ];

    protected $casts = [
        'status' => CodeSubmissionStatus::class,
    ];

    /**
     * Студент, который создал отправку.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ментор, закреплённый за проверкой работы.
     *
     * @return BelongsTo<User, $this>
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Технологии, отмеченные студентом в задаче.
     *
     * @return BelongsToMany<Technology, $this>
     */
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    /**
     * Выполненные AI-анализы этой работы.
     *
     * @return HasMany<AiAnalysis, $this>
     */
    public function aiAnalyses(): HasMany
    {
        return $this->hasMany(AiAnalysis::class);
    }

    /**
     * Ревью, которые прошла отправка.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
