<?php

namespace App\Http\Controllers;

use Auth;

use App\Models\Group;
use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupController extends Controller
{
  public function addgroup(Request $req)
  {
    $this->validate($req, [
      'name'=>'required',
      'location'=>'required',
      'description'=>'required',
    ]);
    // $updatestatus = Event::findOrFail($req['id']);
    $newgroup = new Group();
    $newgroup->name = $req['name'];
    $newgroup->location = $req['location'];
    $newgroup->description = $req['description'];
    $newgroup->active = 1;
    $newgroup->save();
    $createlogs = new Logs();
    $createlogs->type = 'Create_Group';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' Created '.$req['name'].' Group.';
    $createlogs->save();
  }
  public function getgroupusers(Request $req)
  {
    if ($req['name']||$req['role']>=0||$req['status']>=0||$req['username']) {
      return User::Where('name', 'like', '%' . $req['name'] . '%')
      ->Where('role', 'like', '%' .  $req['role']. '%')
      ->Where('active', 'like', '%' . $req['status'] . '%')
      ->Where('username', 'like', '%' . $req['username'] . '%')
      ->Where('group_id',  $req['group_id'])
      ->latest()->paginate(10);
    }
    else {
      return User::where('group_id',$req['group_id'])->paginate(10);
    }
  }
  public function getgroupusersall(Request $req)
  {
    return User::where('group_id',$req['group_id'])->get();
  }
  public function getallgroups(Request $req)
  {
    if ($req['id']||$req['name']||$req['location']||$req['description']||$req['active']||$req['group_id']) {
      return Group::with('users')
      ->Where('name', 'like', '%' . $req['name'] . '%')
      ->Where('description', 'like', '%' . $req['description'] . '%')
      ->Where('id', 'like', '%' . $req['id'] . '%')
      ->Where('location', 'like', '%' . $req['location'] . '%')
      ->latest()->paginate(10);
    }else {
      return Group::with('users')->latest()->paginate(10);
    }
  }
  public function getallgroupname()
  {
      return Group::select('name','id')->get();
  }
  public function updategroup(Request $req)
  {
    $this->validate($req, [
      'id'=>'required',
      'name'=>'required',
      'location'=>'required',
      'description'=>'required',
      'active'=>'required',
    ]);
    $newgroup = Group::findOrFail($req['id']);
    $newgroup->name = $req['name'];
    $newgroup->location = $req['location'];
    $newgroup->description = $req['description'];
    $newgroup->active = $req['active'];
    $newgroup->save();

    $createlogs = new Logs();
    $createlogs->type = 'Update_Group_Details';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' Update '.$req['name'].' Group Details.';
    $createlogs->save();
  }
}
