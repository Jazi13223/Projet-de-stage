<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $fillable = [
       'general_average',
       'ue_validation_rate',
       'decision',
       'session',
       'semester_id',
       'inscription_id',
    
    ];

    //Une validation appartient à un semestre
    public function semester(){
        return $this->belongsTo(Semester::class);
    }

    //Une validation appartient à une inscription
    public function inscription(){
        return $this->belongsTo(Inscription::class);
    }



}
