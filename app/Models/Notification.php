<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
      protected $fillable = ['type', 'message', 'read', 'user_id', 'admin_id', 'secretary_id', 'student_id'];

       // Définir les relations avec User (admin ou secrétaire)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthode pour marquer la notification comme lue
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
