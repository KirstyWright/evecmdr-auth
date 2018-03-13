<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use \App\Eve\UserService;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Eve\Groups $group,\App\Eve\Discord $discordService)
    {
        $user = Auth::user();
        $group->runRules($user);
        // $discordService->syncGroups();
        return view('home',['user'=>Auth::user()]);
    }
}
