<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_type',
        'message',
        'metadata',
        'is_audio',
        'audio_file_path',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_audio' => 'boolean',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    // Scope for user messages
    public function scopeFromUser($query)
    {
        return $query->where('sender_type', 'user');
    }

    // Scope for AI messages
    public function scopeFromAI($query)
    {
        return $query->where('sender_type', 'ai');
    }

    // Scope for audio messages
    public function scopeAudio($query)
    {
        return $query->where('is_audio', true);
    }
}
