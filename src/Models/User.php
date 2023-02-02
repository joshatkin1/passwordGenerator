<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Auth
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $connection = 'mongodb';

    protected $db = 'pixle';

    protected $collection = 'users';

    protected $primaryKey = '_id';

    protected $keyType = 'string';

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * All attributes are mass assignable
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fetch($userID)
    {

    }
}
