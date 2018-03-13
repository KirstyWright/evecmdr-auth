@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h1>Create Group</h1>
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
                <div class="form-group">
                    <label for="name">Group name</label>
                    <input type="name" class="form-control" name="name" id="name" placeholder="Group Name">
                </div>
                <button type="submit" class="btn btn-default">Create</button>
            </form>
        </div>
    </div>
</div>
@endsection
