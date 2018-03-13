@extends('layouts.app')

@section('content')
<div class="container">
    @if (Session::has('message'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {!! session('message') !!}
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="fl">
                <img class='img-thumbnail' src="https://image.eveonline.com/Character/{{$user->id}}_128.jpg" alt="{{$user->name}} eve icon">
            </div>
            <div class="fl character_pane_text">
                <h3>{{$user->name}}</h3>
                <p>Corporation: {{$user->corporation->name}}</p>
                @isset($user->corporation->alliance)
                <p>Alliance: {{$user->corporation->alliance->name}}</p>
                @else
                <p>Alliance: None</p>
                @endif
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h1>Your services</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4 text-center">
                @if ($user->discord == null)
                <a href="{{ route('discordOAuth') }}">
                    <img style='width:auto%;height:120px;' src="/images/discord.png" alt="Discord SSO button">
                    <p>Click here to setup discord</p>
                </a>
                @else
                <img style='width:auto%;height:120px;' src="/images/discord.png" alt="Discord SSO button">
                <p>You are using discord as {{$user->discord->username}}</p>
                <p><a href="/discord/revoke">Revoke discord logins</a></p>
                <p><a href="{{ route('discordOAuth') }}">Rejoin server</a></p>
                @endif
            </div>
            <div class="col-md-4 text-center">
                <h2>Forums</h2>
                <p><a href="https://forums.delirium.evecmdr.com/">Click here to go to forums</a></p>
            </div>
            <div class="col-md-4 text-center">
                <a href="#">
                    <img style='width:auto%;height:120px;' src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8f/Icons_mumble.svg/400px-Icons_mumble.svg.png" alt="Discord SSO button">
                    <p>Mumble auth is coming soon</p>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12"><h3>Your groups</h3></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class='table'>
                <thead>
                    <tr>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->groups()->get() as $group)
                        <tr>
                            <td>{{$group->name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
