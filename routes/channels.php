<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Chat channels
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // Check if user is part of the chat
    $chat = Chat::find($chatId);
    
    if (!$chat) {
        return false;
    }
    
    return $chat->users()->where('user_id', $user->id)->exists();
});

// Admin presence channel
Broadcast::channel('admin-presence', function ($user) {
    if ($user->role === 'admin') {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role
        ];
    }
    return false;
});
