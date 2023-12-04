<?php

namespace LA09R\StarterKit\UI\Vue\Inertia\Users\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LA09R\StarterKit\UI\Vue\Inertia\App\Models\HasCompositePrimaryKey;

class UsersAuthService extends Model
{
    use HasFactory, HasCompositePrimaryKey;

    protected $table = 'users_auth_services';

    protected $primaryKey = ['user_id', 'service_id'];
//    protected $keyType = 'array';

    public $incrementing  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_id',
        'login',
        'secrets',
        'data',
        'name',
        'email',
        'avatar',
    ];

    public const SERVICES_SECRETS_TEMPALTE = [
//        [
//            'selected'      => false,
//            'name'          => 'Google',
//            'service_id'    => 'google',
//            'json'          => '',  // resources/json/template/auth.google.json
//        ],
        [
            'selected'      => false,
            'name'          => 'Github',
            'service_id'    => 'github',
            'json'          => '',
        ],
    ];
}
