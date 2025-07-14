<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
     protected $fillable = [
       'value',
       'type',
       'date_evaluation',
       'session',
       'inscription_id',
       'ue_id',
       'ecue_id',
    
    ];

    //Une note appartient à une inscription
    public function inscriptions(){
        return $this->belongsTo(Inscription::class);
    }

    //Une note appartient à un UE
    public function ue(){
        return $this->belongsTo(Ue::class);
    }

    //Une note appartient à une ECUE
    public function ecue(){
        return $this->belongsTo(Ecue::class);
    }

    //Une note peut faire l'objet de plusieurs réclamations
    public function reclamations(){
        return $this->hasMany(Reclamation::class);
    }

    
}
