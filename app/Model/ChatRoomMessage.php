<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ChatRoomMessage extends Model
{
    protected $table = 'chat_room_message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_room_id','message','actor'
    ];

    public function chatRoom(){
        return $this->belongsTo(ChatRoom::class,'chat_room_id');
    }
}
