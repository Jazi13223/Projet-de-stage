<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secretary extends Model
{
     protected $fillable = [
       'user_id',
    
    ];

    //Une secrétaire appartient à un utilisateur
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Un secrétaire peut gérer plusieurs réclamations
    public function reclamations(){
        return $this->hasMany(Reclamation::class);
    }

    //Un secrétaire peut gérer plusieurs UE
    public function ues(){
        return $this->hasMany(Ue::class);
    }

    
}
