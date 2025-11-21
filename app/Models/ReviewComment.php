<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'file_id',
        'line_number',
        'comment',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function submissionFile(): BelongsTo
    {
        return $this->belongsTo(SubmissionFile::class, 'file_id');
    }
}
