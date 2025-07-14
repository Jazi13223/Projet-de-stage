<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
     protected $fillable = [
       'motif',
       'status',
       'date_submission',
       'inscription_id',
       'secretary_id',
       'note_id',
    
    ];

    //Une réclamation appartient à une inscription
    public function inscription(){
        return $this->belongsTo(Reclamation::class);
    }

    //Une réclamation appartient à un secrétaire
    public function secretary(){
        return $this->belongsTo(Secretary::class);
    }

    //Une réclamation appartient à une note
    public function note(){
        return $this->belongsTo(Note::class);
    }

    
}
