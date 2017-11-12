<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Entrust;

class list_users extends Controller
{

    public function list()
    {

        $users = User::all();

        return view('admin.users')->with('users', $users);

    }

    public function edit($id) {


        $user = User::find($id);

        $roles =  $user->roles()->get();

        return view('admin.users_edit')->with('roles', $roles)->with('user', $user);

    }
}
