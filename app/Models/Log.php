<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
     protected $fillable = [
        'user_id',
        'action',
        'secretary_id',
        'admin_id',
        'student_id',
        'description',
    ];

    /**
     * Relation : Un log appartient à un utilisateur (secrétaire ou autre)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function secretary()
    {
    return $this->belongsTo(Secretary::class);
    }
}
