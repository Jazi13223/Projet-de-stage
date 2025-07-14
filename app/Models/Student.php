<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
     protected $fillable = [
       'matricule',
       'user_id',
    ];

    //Un etudiant appartient à un user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Un etudiant peut faire plusieurs inscriptions
     public function inscriptions(){
        return $this->hasMany(Inscription::class);
    }

    public function derniereInscription()
{
    return $this->hasOne(Inscription::class)->latestOfMany();
}


}
