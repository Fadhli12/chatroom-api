<?php

use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agents = [
            [
                'username' => 'agent1'
            ],[
                'username' => 'agent2'
            ]
        ];

        \App\Model\Agent::insert($agents);
    }
}
