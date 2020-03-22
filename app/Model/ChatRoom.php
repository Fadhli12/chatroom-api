<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{

    protected $table = 'chat_room';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','agent_id','status','token'
    ];

    public function messages(){
        return $this->hasMany(ChatRoomMessage::class,'chat_room_id');
    }

    public function agent(){
        return $this->belongsTo(Agent::class,'agent_id');
    }
}
