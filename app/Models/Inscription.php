<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = [
        'date_inscription',
        'student_id',
        'filiere_id',
        'semester_id',
        'year_id',
    ];


    //Une inscription appartient à un étudiant
    public function student(){
        return $this->belongsTo(Student::class);
    }

    //Une inscription appartient à une filière
    public function filiere(){
        return $this->belongsTo(Filiere::class);
    }

    //Une inscription appartient à un semestre
    public function semester(){
        return $this->belongsTo(Semester::class);
    }

    //Une inscription appartient à une année
    public function year(){
        return $this->belongsTo(Year::class);
    }

    //Une inscription peut avoir plusieurs validations
    public function validations(){
        return $this->hasMany(Validation::class);
    }

    //Une inscription peut avoir plusieurs résultatsUE
    public function ue_results(){
        return $this->hasMany(UeResult::class);
    }

    
    //Une inscription peut avoir plusieurs résultatsECUE
    public function ecue_results(){
        return $this->hasMany(EcueResult::class);
    }

    //Une inscription peut avoir plusieurs réclamations
    public function reclamations(){
        return $this->hasMany(Reclamation::class);
    }

    //Une inscription peut avoir plusieurs notes
    public function notes(){
        return $this->hasMany(Note::class);
    }

    
}
