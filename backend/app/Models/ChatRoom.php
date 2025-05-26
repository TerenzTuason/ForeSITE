<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_rooms';
    protected $primaryKey = 'room_id';
    
    protected $fillable = [
        'learning_style_id',
        'room_name'
    ];

    /**
     * Get the learning style associated with the chat room
     */
    public function learningStyle(): BelongsTo
    {
        return $this->belongsTo(LearningStyle::class, 'learning_style_id', 'style_id');
    }

    /**
     * Get the messages for this chat room
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id', 'room_id');
    }
}
