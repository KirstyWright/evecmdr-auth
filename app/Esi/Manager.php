<?php
namespace App\Esi;

class Manager{
    private $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function searchEntities($name)
    {
        $output = ['corporation'=>[],'alliance'=>[]];
        $ids = $this->api->call('GET','v2/search',['categories'=>'alliance,corporation','search'=>$name]);
        if (isset($ids['corporation']) && count($ids['corporation']) <= 3) {
            foreach ($ids['corporation'] as $id) {
                $tmp = $this->getPublicCorporationDetails($id);
                $tmp['id'] = $id;
                $output['corporation'][] = $tmp;
            }
        }
        if (isset($ids['alliance']) && count($ids['alliance']) <= 3) {
            foreach ($ids['alliance'] as $id) {
                $tmp = $this->getPublicAllianceDetails($id);
                $tmp['id'] = $id;
                $output['alliance'][] = $tmp;
            }
        }
        return $output;
    }

    public function getPublicCharacterDetails($entityId)
    {
        return $this->api->call('GET','/v4/characters/'.$entityId);
    }

    public function getPublicCorporationDetails($entityId)
    {
        return $this->api->call('GET','/v4/corporations/'.$entityId);
    }

    public function getPublicAllianceDetails($entityId)
    {
        return $this->api->call('GET','/v3/alliances/'.$entityId);
    }

    public function getFactions()
    {
        return $this->api->call('GET','/v2/universe/factions/');
    }

    public function syncFactions()
    {
        $factions = $this->getFactions();
        foreach ($factions as $faction) {
            $factionModel = \App\Faction::firstOrNew(['id'=>$faction['faction_id']]);
            $factionModel->name = $faction['name'];
            $factionModel->save();
        }
    }

    public function sendMail($to,$subject,$body)
    {
        $this->api->setToken(\Auth::user()->token);
        $data = ['subject'=>$subject,'recipients'=>$to,'body'=>$body];
        $response =  $this->api->call('POST','/v1/characters/'.\Auth::user()->id.'/mail',$data);
        return $response;
    }

    public function setUser($user)
    {
        if (strtotime($user->token_expiry) < time()) {
            $options = [
                'form_params'=>[
                    'grant_type'=>'refresh_token',
                    'refresh_token'=>$user->refresh_token
                ],
                'headers'=>[
                    'Authorization'=>'Basic '.base64_encode(env('EVEONLINE_CLIENT_ID').':'.env('EVEONLINE_CLIENT_SECRET'))
                ]
            ];
            $response = $this->api->guzzle()->request('POST', 'https://login.eveonline.com/oauth/token',$options);
            $data = json_decode($response->getBody());
            $time = $data->expires_in + time();
            $user->token = $data->access_token;
            $user->token_expiry = date('Y-m-d H:i:s',$time);
            $user->refresh_token = $data->refresh_token;
            $user->save();
        }
        $this->api->setToken($user->access_token);
    }
}
