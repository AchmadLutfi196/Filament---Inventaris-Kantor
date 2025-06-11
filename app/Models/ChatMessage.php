<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'message',
        'type',
        'file_path',
        'file_name',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            // Update chat last_message_at when new message is created
            $message->chat->update(['last_message_at' => $message->created_at]);
            
            // Broadcast the event
            broadcast(new \App\Events\NewChatMessage($message->load('user')));
        });
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
