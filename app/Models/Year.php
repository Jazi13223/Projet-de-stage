<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
     protected $fillable = [
       'academic_year',
    ];

    //Une année peut avoir plusieurs semestres
    public function semesters(){
        return $this->hasMany(Semester::class);
    }

    //Une année peut avoir plusieurs inscriptions
    public function inscriptions(){
        return $this->hasMany(Inscription::class);
    }

     //Une année peut contenir plusieurs ue
    public function ues(){
        return $this->hasManny(Ue::class);
    }
}
