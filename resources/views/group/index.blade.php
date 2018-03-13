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
    <a href="/group/create" class="btn btn-primary btn-default" role="button">Create Group</a>
    <div class="row">
        <div class="col-md-12">
            <table class='table'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>No Of Members</th>
                        <th>Discord Setup</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                    <tr>
                        <td>{{ $group->name }}</td>
                        <td>0</td>
                        <td>{!! $group->discord_id != null ? '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>' : '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>' !!}</td>
                        <td><a href='/group/{{$group->id}}/edit'>Edit</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
