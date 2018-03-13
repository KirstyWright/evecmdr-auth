<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

class ForumController extends Controller
{
    public function sso(Request $request)
    {
        $sso = new \Cviebrock\DiscoursePHP\SSOHelper();
        $secret = 'super_secret_sso_key';
        $sso->setSecret(env('FORUM_SSO_KEY'));

        // load the payload passed in by Discourse
        $payload = $request->sso;
        $signature = $request->sig;

        if (!($sso->validatePayload($payload,$signature))) {
            abort(403);
        }

        $nonce = $sso->getNonce($payload);
        $user = \Auth::user();
        $groupList = '';
        $removeGroupList = '';
        foreach ($user->groups()->where('forum','=',true)->get() as $group) {
            $groupList .= $group->name.',';
        }
        $groupList = trim($groupList,',');
        $userId = $user->id;
        $userEmail = $user->id.'eve@evecmdr.com';
        $extraParameters = array(
            'username' => $user->name,
            'name' => $user->name,
            'require_activation'=>true,
            'avatar_url' => 'https://image.eveonline.com/Character/'.$user->id.'_128.jpg',
            'add_groups' => $groupList
        );
        $query = $sso->getSignInString($nonce, $userId, $userEmail, $extraParameters);
        return redirect('https://forums.delirium.evecmdr.com/session/sso_login?' . $query);

    }
}
