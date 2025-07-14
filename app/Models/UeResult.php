<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UeResult extends Model
{
      protected $fillable = [
       'initial_average',
       'resit_average',
       'final_average',
       'validated',
       'recomposed',
       'ue_id',
       'inscription_id',
    
    ];

    //Une ueResult appartient à une UE
    public function ues(){
        return $this->belongsTo(Ue::class);
    }

    //Une ueResult peut avoir plusieurs ecueresult
    public function ecue_results(){
        return $this->hasMany(EcueResult::class);
    }

    //Une ueResult appartient à une Inscrption
    public function inscriptions(){
        return $this->belongsTo(Inscription::class);
    }

    
}
