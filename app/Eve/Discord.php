<?php
namespace App\Eve;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Client;
class Discord {
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client([
            'base_uri'=>"https://discordapp.com/api/",
            "headers"=>[
                "Authorization"=>"Bot ".env("DISCORD_BOT_TOKEN")
            ]
        ]);
    }

    public function addMember($user)
    {
        $discordRoles = [];
        foreach ($user->groups()->where('discord_id','<>',null)->get() as $group) {
            $discordRoles[] = $group->discord_id;
        }
        $data = [
            'access_token'=>$user->discord->token,
            'nick'=>$user->name,
            'roles'=>$discordRoles
        ];
        $result = $this->guzzle->put("guilds/".env('DISCORD_SERVER_ID')."/members/{$user->discord->id}",['json'=>$data]);
        return json_decode($result->getBody(),1);
    }

    public function addGroup($group)
    {
        $data = [
            'name'=>'a_'.snake_case($group->name),
        ];
        $result = $this->guzzle->post("guilds/".env('DISCORD_SERVER_ID')."/roles",['json'=>$data]);
        return json_decode($result->getBody(),1);
    }

    public function addGroups()
    {
        $groups = \App\Group::all();
        foreach ($groups as $group) {
            if ($group->discord_id == null) {
                $discordGroup = $this->addGroup($group);
                $group->discord_id = $discordGroup['id'];
                $group->save();
            }
        }
    }

    public function syncGroups()
    {
        $users = \App\DiscordUser::all();
        foreach ($users as $user) {
            try {
                $this->syncUserGroups($user->user);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                continue;
            }
        }
    }

    public function syncUserGroups($user)
    {
        $discordMember = $this->guzzle->get('guilds/'.env('DISCORD_SERVER_ID').'/members/'.$user->discord->id);
        $discordMember = json_decode($discordMember->getBody(),1);

        $userGroups = $user->groups()->get()->pluck('discord_id')->toArray();
        $diff = array_diff($discordMember['roles'],$userGroups);
        sort($userGroups);
        sort($discordMember['roles']);
        if ($userGroups != $discordMember['roles']) {
            $data = [
                'nick'=>$user->name,
                'roles'=>$userGroups
            ];
            $result = $this->guzzle->patch("guilds/".env('DISCORD_SERVER_ID')."/members/".$user->discord->id,['json'=>$data]);
        }
        // $discordMember['roles']
    }


}
