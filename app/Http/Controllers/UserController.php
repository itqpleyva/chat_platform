<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function getConnected(){

        $users = User::where([
            ['connected','=','connected'],
            ['id','!=',Auth::user()->id]
        ])->get();
        return $users;
    }
}
