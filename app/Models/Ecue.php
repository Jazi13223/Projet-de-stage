<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecue extends Model
{
     protected $fillable = [
       'name',
       'coefficient',
       'ue_id',
    ];

    //Une ECUE appartient à une UE
    public function ue(){
        return $this->belongsTo(Ue::class);
    }

    //Une ECUE peut avoir plusieurs EcueResult
    public function ecue_results(){
        return $this->hasMany(EcueResult::class);
    }

    //Une ECUE peut avoir plusieurs notes
    public function notes(){
        return $this->hasMany(Note::class);
    }

    
}
