<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
     protected $fillable = [
       'name',
       'coefficient',
       'secretary_id',
       'year_id',
       'semester_id',
    ];

    //Une UE contient plusieurs ECUE
    public function ecues(){
        return $this->hasMany(Ecue::class);
    }

    //Une UE peut avoir plusieurs ueResult
    public function ue_results(){
        return $this->hasMany(EcueResult::class);
    }

    //Une UE peut avoir plusieurs notes
    public function notes(){
        return $this->hasMany(Note::class);
    }

    //Une UE appartient à un semestre
    public function semester(){
        return $this->belongsTo(Semester::class);
    }

    //Une UE appartient à une année
    public function year(){
        return $this->belongsTo(Year::class);
    }

    //Une ue est gérer pas un secrétaire
     public function secretary(){
        return $this->belongsTo(Secretary::class);
    }

    //Une ue peut avoir plusieurs ueAssignment
    public function ue_assignments(){
        return $this->hasMany(UeAssignment::class);
    }
}


