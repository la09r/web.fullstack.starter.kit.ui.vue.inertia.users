<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends \App\Models\User
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
