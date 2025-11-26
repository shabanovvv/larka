<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Профиль ментора с опытом, ставкой и направлениями.
 */
class MentorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'experience_years',
        'rate',
        'is_active',
    ];
    protected $hidden = [];

    /**
     * Пользователь, которому принадлежит профиль.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Технологии, в которых специализируется ментор.
     */
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'mentor_technology');
    }
}
