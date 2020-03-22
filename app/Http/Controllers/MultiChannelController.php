<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MultiChannelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function createChatRoom(Request $request){
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email'
        ]);
        $data = $request->input();
        $data['token'] = Str::random(64);
        $room = ChatRoom::create($data);
        $this->cekAvailableAgent();
        return response()->json($room);
    }

    public function cekAvailableAgent(){
        $agenAllocation = new AgentAlocationController();
        $agents = $agenAllocation->availableAgent();
        foreach ($agents AS $agent){
            $this->enterAgentToChatRoom($agent->id);
        }
    }

    public function enterAgentToChatRoom($agent_id){
        $room = ChatRoom::where('status','open')->orderBy('created_at')->first();
        if ($room) {
            $room->agent_id = $agent_id;
            $room->status = 'active';
            $room->save();
            $room->messages()->create([
                'message' => 'Welcome to chat room #' . $room->id,
                'actor' => 'room'
            ]);
        }
    }

    public function getChatRoomForAgent($agent_id){
        $room = ChatRoom::where('agent_id',$agent_id)->where('status','active')->get();
        return response()->json($room);
    }

    public function chatRoom(Request $request,$token){
        $datetime = $request->input('datetime');
        if ($datetime == ''){
            $room = ChatRoom::with('messages')->where('token',$token)->first();
        } else {
            $room = ChatRoom::with('messages')->whereHas('messages',function($q)use($datetime){
                $q->where('created_at','>',$datetime);
            })->where('token',$token)->first();
        }
        return response()->json($room);
    }

    public function sendMessage(Request $request,$token){
        $this->validate($request,[
            'message' => 'required',
            'actor' => 'required|in:user,agent'
        ]);
        $data = $request->input();
        $room = ChatRoom::where('token',$token)->first();
        $msg = $room->messages()->create($data);
        return response()->json([
            'room' => $room,
            'msg' => $msg
        ]);
    }

    public function resolveChatRoom($token){
        $room = ChatRoom::where('token',$token)->first();
        $room->messages()->create([
            'message' => 'chat room is resolved',
            'actor' => 'room'
        ]);
        $room->status = 'resolve';
        $room->save();
        $this->cekAvailableAgent();
        return response()->json('ok');
    }

    public function cekRoomStatus($token){
        $this->cekAvailableAgent();
        return response()->json(ChatRoom::where('token',$token)->first());
    }

    public function loginAgent(Request $request){
        $this->validate($request,[
            'username' => 'required'
        ]);
        return response()->json(Agent::where('username',$request->input('username'))->first());
    }

}
