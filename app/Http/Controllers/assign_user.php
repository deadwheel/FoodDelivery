<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;

class assign_user extends Controller
{

    public function attach_role()
    {

        $user = User::where('id', '=', '1')->first();


        $user->attachRole('1');



    }
}

