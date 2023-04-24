<?php

namespace App\Http\Controllers;

use App\Models\Prebet;
use App\Models\bet;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Arr;
use App\Models\Event;
use App\Models\Logs;
use App\Models\Potmoney;
use App\Models\arena;
use Carbon\Carbon;


class ArenaController extends Controller
{
  public function addarena(Request $req)
  {
    $this->validate($req, [
      'name' => 'required|max:255',
      'code' => 'required|max:3',
    ]);

    $newarena = new arena();
    $newarena->name = $req['name'];
    $newarena->code = $req['code'];
    $newarena->save();

    $createlogs = new Logs();
    $createlogs->type = 'Create_Arena';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' Created '.$req['name'].' arena.';
    $createlogs->save();
  }
  public function getallarena(Request $req)
  {
    if ($req['id']||$req['name']||$req['code']) {
      return arena::Where('name', 'like', '%' . $req['name'] . '%')
      ->Where('code', 'like', '%' . $req['code'] . '%')
      ->Where('id', 'like', '%' . $req['id'] . '%')
      ->latest()->paginate(10);
    }else {
      return arena::latest()->paginate(10);
      // 'alex binago ko hasdsdasdasdasd
    }

  }
  public function getallarenax(Request $req)
  {
      return arena::latest()->get();
  }
  public function updatearena(Request $req)
  {
    $this->validate($req, [
      'id' => 'required',
      'name' => 'required|max:255',
      'code' => 'required|max:3',
    ]);
    $update = arena::findOrFail($req['id']);
    $update->name = $req['name'];
    $update->code = $req['code'];
    $update->save();

    $createlogs = new Logs();
    $createlogs->type = 'Update_Arena_Details';
    $createlogs->user_id = auth()->user()->id;
    $createlogs->message = auth()->user()->username.' Update '.$req['name'].' arena.';
    $createlogs->save();

  }
}
