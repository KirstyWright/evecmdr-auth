<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\GroupRule;
use App\Rule;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Eve\Discord $discord)
    {
        $discord->addGroups();
        return view('group.index',['groups'=>Group::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //\
        return view('group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:groups,name|max:191'
        ]);
        if ($request->id) {
            $model = Group::find($request->id);
        } else {
            $model = new Group();
        }
        $model->name = $request->name;
        $model->save();
        return redirect()->action('GroupController@index')->with('message','Group Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = group::find($id);
        return view('group.edit',['group'=>$group]);
    }

    public function addRule(Request $request)
    {
        $request->validate([
            'group_id'=>'required|exists:groups,id',
            'id'=>'required',
            'type'=>'required|in:alliance,corporation,faction'
        ]);
        $group = group::find($request->group_id);

        $rule = new GroupRule;
        $rule->group_id = $group->id;
        $rule->entity_type = $request->type;
        $rule->entity_id = $request->id;
        $rule->save();
        return redirect()->action('GroupController@edit',['id'=>$group->id])->with('message','Rule added');
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'group_id'=>'required|exists:groups,id',
            'id'=>'required|exists:users',
        ]);
        $group = Group::find($request->group_id);
        $group->users()->attach($request->id);
        return redirect()->action('GroupController@edit',['id'=>$group->id])->with('message','Member added');

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|unique:groups,name|max:191',
            'id'=>'required|exists:groups'
        ]);
        $model = Group::find($request->id);
        $model->name = $request->name;
        $model->save();
        return redirect()->action('GroupController@index')->with('message','Group updated');
    }

    public function removeMember(Request $request)
    {
        $request->validate([
            'group_id'=>'required|exists:groups,id',
            'id'=>'required|exists:users',
        ]);
        $group = Group::find($request->group_id);
        $group->users()->detach($request->id);
        return redirect()->action('GroupController@edit',['id'=>$group->id])->with('message','Member removed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
