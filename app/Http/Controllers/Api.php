<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Api extends Controller
{
    public function getGroups()
    {
        $groups = \App\Group::all();
        return response()->json([$groups]);
    }

    public function getUsers()
    {
        $output = [];
        $users = \App\User::has('discord')->get();
        foreach ($users as $user) {
            $userOutput = [
                'characterId'=>$user->id,
                'name'=>'['.$user->corporation->ticker.'] '.$user->name,
                'discordId'=>$user->discord->id,
                'groups'=>[]
            ];
            foreach ($user->groups()->where('discord_id','<>',null)->get() as $group) {
                $userOutput['groups'][] = $group->discord_id;
            }
            $output[] = $userOutput;
        }
        return response()->json([$output]);
    }

}
