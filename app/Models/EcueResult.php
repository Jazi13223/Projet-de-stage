<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcueResult extends Model
{
     protected $fillable = [
       'initial_grade',
       'resit_grade',
       'final_grade',
       'validated',
       'ecue_id',
       'ue_result_id',
       'inscription_id',
    
    ];

    //Une EcueResult appartient à une ECUE
    public function ecue(){
        return $this->belongsTo(Ecue::class);
    }

    //Une EcueResult appartient à une UeResult
    public function ue_result(){
        return $this->belongsTo(UeResult::class);
    }

    //Une EcueResult appartient à une Inscrption
    public function inscription(){
        return $this->belongsTo(Inscription::class);
    }

    
}
