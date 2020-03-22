<?php

namespace App\Http\Controllers;

use App\Model\Agent;
use App\Model\ChatRoom;

class AgentAlocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function availableAgent(){
        $this->updateStatusAgent();
        return Agent::where('status','available')->get();
    }

    public function updateStatusAgent(){
        $agent_cek = Agent::get();
        foreach ($agent_cek AS $cek){
            if (ChatRoom::where('agent_id',$cek->id)->where('status','active')->count() < 2){
                $cek->status = 'available';
            } else {
                $cek->status = 'busy';
            }
            $cek->save();
        }
    }
}
