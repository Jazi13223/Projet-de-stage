<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //
    public function student()
    {
    return $this->hasOne(Student::class);
    }

    public function secretary()
    {
    return $this->hasOne(Secretary::class);
    }

    public function admin()
    {
    return $this->hasOne(Admin::class);
    }

    
public function isAdmin()
{
    // Vérifie si l'utilisateur a le rôle d'admin (supposons que tu as une colonne 'role')
    return $this->role === 'admin'; // Remplace 'admin' par la valeur correspondant à l'admin dans ta base de données
}

public function isSecretary()
{
    // Vérifie si l'utilisateur a le rôle de secrétaire
    return $this->role === 'secretary'; // Remplace 'secretary' par la valeur correspondant à un secrétaire
}

public function notifications()
{
    return $this->hasMany(Notification::class);
}

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

   

}
