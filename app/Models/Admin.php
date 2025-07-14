<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
     protected $fillable = ['user_id'];
    
    // Définir la relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
