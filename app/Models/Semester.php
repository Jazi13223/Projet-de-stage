<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
     protected $fillable = [
       'name',
       'year_id'
    
    ];

    //Un semestre appartient à une année
    public function years(){
        return $this->belongsTo(Year::class);
    }

    //Un semestre peut avoir plusieurs inscriptions
    public function inscriptions(){
        return $this->hasMany(Inscription::class);
    }

    //Un semeestre peut contenir plusieurs ue
    public function ues(){
        return $this->hasManny(Ue::class);
    }

    //Dans un semestre on peut avoir plusieurs validations
    public function validation(){
        return $this->hasMany(Validation::class);
    }

    
}
