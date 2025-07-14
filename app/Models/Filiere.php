<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected $fillable = [
       'name',
    
    ];

    //Une filière peut avoir plusieurs inscriptions
    public function inscriptions(){
        return $this->hasMany(Inscription::class);
    }

    //Une filière peut avoir plusieurs UeAssignment
    public function ue_assignments(){
        return $this->hasMany(UeAssignment::class);
    }

    // Et via ces affectations, elle peut accéder aux UE
    public function ues()
    {
    return $this->hasManyThrough(
       Ue::class, 
        UeAssignment::class,
        'filiere_id',  // clé étrangère dans "ue_assignments"
        'id',  // clé primaire dans "ues"
        'id',  // clé primaire dans "filieres"
        'ue_id'  // clé étrangère dans "ue_assignments"
    );
    }

}
