<?php

namespace App\Http\Controllers;
use App\Models\Log;
use App\Models\Notification;
abstract class Controller
{
    
public function logAction($action, $description)
{
    $user = auth()->user();

    Log::create([
        'action' => $action,
        'description' => $description,
        'user_id' => $user->id,
        'secretary_id' => $user->role === 'secretaire' ? $user->id : null,
        'student_id'    => $user->role === 'etudiant' ? $user->student->id ?? null : null,
        'admin_id'      => $user->role === 'admin' ? $user->id : null,
    ]);
}

public function createNotification($message, $type = 'info')
{
    $user = auth()->user();

    Notification::create([
        'type' => $type,
        'message' => $message,
        'read' => false,
        'user_id' => $user->id,
        'admin_id' => $user->role === 'admin' ? $user->id : null,
        'secretary_id' => $user->role === 'secretaire' ? $user->id : null,
        'student_id' => $user->role === 'etudiant' ? ($user->student->id ?? null) : null,
    ]);
}

}
