<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Socialite;
use Auth;

class MailController extends Controller
{
    private $esi;

    public function __construct()
    {
        $this->esi = new \Seat\Eseye\Eseye(new \Seat\Eseye\Containers\EsiAuthentication([
            'client_id'=>env('EVEONLINE_CLIENT_ID'),
            'secret'=>env('EVEONLINE_CLIENT_SECRET'),
            'refresh_token'=>\App\User::find(96559018)->refresh_token
        ]));
    }

    public function list(Request $request)
    {

        $this->esi->setVersion('v1');
        $call = $this->esi->invoke('get','/alliances/{alliance_id}/corporations/', [
            'alliance_id'=>$request->id
        ]);
        $corps = [];
        $this->esi->setVersion('v4');
        foreach ($call as $id) {
            if (!Cache::has("esi.corporation.{$id}")) {
                $raw = $this->esi->invoke('get','/corporations/{corporation_id}/', [
                    'corporation_id'=>$id
                ]);
                Cache::put("esi.corporation.{$id}",$raw,60);
            } else {
                $raw = Cache::get("esi.corporation.{$id}");
            }

            if ($raw->member_count < 6) {
                continue;
            }
            $corps[] = [
                'id'=>$id,
                'name'=>$raw->name,
                'description'=>$raw->description
            ];
        }


        return response()->json([
            'corporations'=>$corps
        ]);
    }

    public function index(Request $request)
    {
        die;
        $content = "o/

On behalf of my corp and alliance we would like to offer yourself and your members a place to engage in pvp combat and have blasts of fun creating explosions alongside us.

We would be willing to accept members directly into the corp or corporations into the alliance. We are not asking you to give up your rental ground or move your main characters. You are able to create alpha clones and move them straight into our entities, we have skills and ships for you to come have fun in from the get go.

What we offer:
- Content heavy EU & US timezones.
- Small gang fleets throughout both timezones.
- Mainline combat fleets every few days and on weekends.
- Newbro support team.
- Corp funded ships & books for newbies.


What we look for:
- People who want to pvp, be it for the first time or somthing you do on a regular basis.
- People who are a bit bored with mining / ratting all day.
- People who want to shake things up a bit.

o7

Kirsty (Vamps)
--
Discord: Kirsty#0340";

        $to = [
            [
                'recipient_type'=>'character',
                'recipient_id'=>90792652
            ]
        ];

        $this->esi->setVersion('v1');
        $this->esi->setBody([
            'subject'=>'A chance to pew pew',
            'recipients'=>$to,
            'body'=>$content
        ])->invoke('post','/characters/{character_id}/mail/', [
            'character_id'=>\Auth::user()->id
        ]);
        // $response = $this->esi->sendMail($to,'PVP opportunity',$content);
        return response()->json([
            'id'=>$request->id
        ]);
    }
}
