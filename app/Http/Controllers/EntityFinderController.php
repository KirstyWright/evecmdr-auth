<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alliance;
use App\Corporation;
use App\Faction;
use App\User;

class EntityFinderController extends Controller
{
    public function find(Request $request,\App\Esi\Manager $esi)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $alliances = Alliance::where('name','like',"%{$request->name}%")->get();
        $corporations = Corporation::where('name','like',"%{$request->name}%")->get();
        $factions = Faction::where('name','like',"%{$request->name}%")->get();

        // $entities = $esi->searchEntities($request->name);
        $output = [];
        foreach ($corporations as $member) {
            $member['type'] = 'corporation';
            $output[] = $member;
        }
        foreach ($factions as $member) {
            $member['type'] = 'faction';
            $output[] = $member;
        }
        foreach ($alliances as $member) {
            $member['type'] ='alliance';
            $output[] = $member;
        }
        return response()->json($output);
    }

    public function findUser(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $users = User::where('name','like',"%{$request->name}%")->get();

        foreach ($users as $member) {
            $output[] = [
                'id'=>$member->id,
                'name'=>$member->name,
                'ticker'=>$member->corporation->ticker
            ];
        }
        return response()->json($output);
    }
}
