<?php

namespace App\Models;

use App\Enums\AiProvider;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * AI-анализ кода с сохранённым ответом и метаданными.
 *
 * @property int $id
 * @property int $code_submission_id
 * @property AiProvider $provider
 * @property string $summary
 * @property string $suggestions
 * @property int $score
 * @property string $raw_response
 * @property Carbon $created_at
 */
class AiAnalysis extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $fillable = [
        'code_submission_id',
        'provider',
        'summary',
        'suggestions',
        'score',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'suggestions' => 'array',
        'provider' => AiProvider::class,
    ];

    /**
     * Исходная отправка кода, для которой выполнен анализ.
     *
     * @return BelongsTo<CodeSubmission, $this>
     */
    public function codeSubmission(): BelongsTo
    {
        return $this->belongsTo(CodeSubmission::class);
    }
}
