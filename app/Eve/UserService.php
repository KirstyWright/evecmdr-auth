<?php
namespace App\Eve;

use \App\Esi\Manager;
use \App\Corporation;
use \App\Alliance;

class UserService {
    private $esi;

    public function __construct(Manager $api)
    {
        $this->esi = $api;
    }

    public function update($user)
    {
        // Get rules that apply to user
        // Get users groups
        $character = $this->esi->getPublicCharacterDetails($user->id);
        $user->corporation_id = $character['corporation_id'];
        $user->name = $character['name'];

        if (!isset($character['faction_id'])) {
            $character['faction_id'] = null;
        }
        $user->faction_id = $character['faction_id'];

        $esiCorporation = $this->esi->getPublicCorporationDetails($user->corporation_id);
        if (!isset($esiCorporation['alliance_id'])) {
            $esiCorporation['alliance_id'] = null;
        }
        $corporation = Corporation::firstOrNew(['id'=>$user->corporation_id]);
        $corporation->id = $user->corporation_id;
        $corporation->name = $esiCorporation['name'];
        $corporation->ticker = $esiCorporation['ticker'];
        $corporation->alliance_id = $esiCorporation['alliance_id'];
        $corporation->ceo_id = $esiCorporation['ceo_id'];
        $corporation->member_count = $esiCorporation['member_count'];

        if ($esiCorporation['alliance_id'] !== null) {
            $esiAlliance = $this->esi->getPublicAllianceDetails($corporation->alliance_id);
            $alliance = Alliance::firstOrNew(['id'=>$corporation->alliance_id]);
            $alliance->id = $corporation->alliance_id;
            $alliance->name = $esiAlliance['name'];
            $alliance->ticker = $esiAlliance['ticker'];
            $user->alliance_id = $alliance->id;
            $alliance->save();
        } else {
            $user->alliance_id = null;
        }

        $user->save();
        $corporation->save();
    }

}
