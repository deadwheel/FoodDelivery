<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Entrust;


class list_users extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $users = User::all();

        return view('admin.users')->with('users', $users);

    }


    public function list()
    {

        $users = User::all();

        return view('admin.users')->with('users', $users);

    }

    public function edit($id) {


        $user = User::find($id);

        $roles =  $user->roles()->pluck('id');

        $roles_ave = Role::whereNotIn('id', $roles)->get();


        return view('admin.users_edit', compact('user','id'))->with('roles_ave', $roles_ave)->with('user', $user);

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
        $user = User::findOrFail($id);

        // TODO Validacja
        /*  $this->validate(request(), [
            'name' => 'required',
            'price' => 'required|numeric'
        ]);*/


        if(!empty($request->add_rols)) {

            $user->attachRoles($request->add_rols);

        }

        if (!empty($request->delete_rols)) {

            $user->detachRoles($user->roles()->whereIn('id', $request->delete_rols)->get());

        }


        return redirect('admin')->with('success','User has been updated');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('admin')->with('success','User has been  deleted');
    }



}
