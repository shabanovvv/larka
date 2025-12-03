<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Роль пользователя в системе (admin, mentor, ...).
 */
class Role extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Пользователи, которым присвоена роль.
     *
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
