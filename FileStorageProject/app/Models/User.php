<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uuid',
        'public_key',
        'encrypted_private_key'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'uuid',
        'public_key',
        'encrypted_private_key'

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contacts()
    {

        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'contact_id')
            ->withPivot('state', 'id');
    }
    public function contactsRequest()
    {

        return $this->belongsToMany(User::class, 'contacts', 'contact_id', 'user_id')
            ->withPivot('state', 'id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public static function getPublicKey($id)
    {

        return DB::select(
            "select public_key from users where id = (?)",
            [$id]
        );
    }
    public static function getUuid($id)
    {

        return DB::select(
            "select uuid from users where id = (?)",
            [$id]
        );
    }
}
