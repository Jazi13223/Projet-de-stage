<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
     protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    /**
     * Relation : Un log appartient à un utilisateur (secrétaire ou autre)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
