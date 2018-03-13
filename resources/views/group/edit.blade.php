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
        <div class="col-md-4">
            <h1>Edit Group</h1>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form class="" action="/group" method="post">
                {{ csrf_field() }}
                <input type="hidden" class="form-control" name="id" id="id" placeholder="Group Id" value="{{ $group->id }}">
                <div class="form-group">
                    <label for="name">Group name</label>
                    <input type="name" class="form-control" name="name" id="name" placeholder="Group Name" value="{{ $group->name }}">
                </div>
                <button type="submit" class="btn btn-default">Save</button>
            </form>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Automatic membership</h2>
                </div>
                <div class="panel-body">
                    @foreach ($group->rules as $rule)
                    <div style="margin-top:3px;margin-bottom:3px;">
                        <img style="padding-right:10px;" src="https://image.eveonline.com/{{$rule->entity_type}}/{{$rule->entity->id}}_32.png"/>{{$rule->entity->name}}</div>
                    @endforeach
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#automatic_membership_model">Add Membership Rule</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Manual membership</h2>
                </div>
                <div class="panel-body">
                    <p>Manual membership will be overwritten by any Automatic membership rules you have !</p>
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#manual_membership_model">Add Member</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <h3>Member List</h3>
        @foreach ($group->users()->get() as $groupUser)
        <div style="margin-top:5px;"><img style="padding-right:10px;" src="https://image.eveonline.com/character/{{$groupUser->id}}_32.jpg"/>&nbsp;{{$groupUser->name}} <span class="glyphicon glyphicon-remove" onclick="group_removeMember({{$groupUser->id}})" aria-hidden="true"></span> </div>
        @endforeach
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="automatic_membership_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Membership Rule</h4>
            </div>
            <div class="modal-body">
                <form name="entityeditform" id='entityeditform' method="POST" action="/group/addRule">
                    {{ csrf_field() }}
                    <input type="hidden" name="id">
                    <input type="hidden" name="type">
                    <input type="hidden" name="group_id" value="{{$group->id}}">
                    <div class="form-group">
                        <label>Allow Access</label>
                        <p class="help-block">The name of the character/corporation/alliance you want to be allowed to join your group.</p>
                        <input type="text" class="form-control" name="entityName" id="entityName" placeholder="Name">
                        <p style="margin-top:10px;" id="fieldAddEntity">
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="manual_membership_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Member</h4>
            </div>
            <div class="modal-body">
                <form name="memberaddform" id='memberaddform' method="POST" action="/group/addMember">
                    {{ csrf_field() }}
                    <input type="hidden" name="id">
                    <input type="hidden" name="group_id" value="{{$group->id}}">
                    <div class="form-group">
                        <label>Add Member</label>
                        <p class="help-block">The name of the character you want to add.</p>
                        <input type="text" class="form-control" name="memberName" id="memberName" placeholder="Name">
                        <p style="margin-top:10px;" id="fieldAddMember">
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<form class='hidden' name="memberremoveform" id='memberremoveform' method="POST" action="/group/removeMember">
    {{ csrf_field() }}
    <input type="hidden" name="id">
    <input type="hidden" name="group_id" value="{{$group->id}}">
</form>
@endsection
