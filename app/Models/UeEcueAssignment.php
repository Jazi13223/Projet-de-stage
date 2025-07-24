<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UeEcueAssignment extends Model
{
     protected $fillable = [
        'ue_assignment_id',
        'ecue_id',
        'coefficient',
    ];

    public function ueAssignment()
    {
        return $this->belongsTo(UeAssignment::class);
    }

    public function ecue()
    {
        return $this->belongsTo(Ecue::class);
    }
}
