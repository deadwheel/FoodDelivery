<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

class roles_create extends Controller
{

    public function create_roles()
    {
        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description = 'User is allowed to manage everything'; // optional
        $admin->save();

        $driver = new Role();
        $driver->name = 'driver';
        $driver->display_name = 'Driver';
        $driver->save();

    }
}
