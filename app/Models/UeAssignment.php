<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UeAssignment extends Model
{
     protected $fillable = [
       'name',
       'coefficient',
       'ue_id',
       'filiere_id',

    ];

    //Une ueAssignment appartient à une ue
    public function ue(){
        return $this->belongsTo(Ue::class);
    }

    //Une ueAssignment appartient à une filière
    public function filiere(){
        return $this->belongsTo(Filiere::class);
    }
}
