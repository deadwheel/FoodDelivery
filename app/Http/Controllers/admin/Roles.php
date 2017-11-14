<?php

namespace App\Http\Controllers\admin;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Roles extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('admin.roles')->with('roles',$roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO validation name=required
        $role = new Role();
        $role->name = $request->input_name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        return redirect('admin/roles')->with('success','Role has been added');
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
        $role = Role::findOrFail($id);

        return view('admin.roles_edit')->with('role',$role);
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

        $role = Role::findOrFail($id);
        if(!empty($request->display_name) && $role->display_name != $request->display_name || !empty($request->description) && $role->description != $request->description) {

            if(!empty($request->display_name)) {

                $role->display_name = $request->display_name;
            }

            if(!empty($request->description)) {

                $role->description = $request->description;
            }


            $role->save();
        }

        return redirect('admin/roles')->with('success','Role has been  updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        $role->users()->sync([]);
        $role->perms()->sync([]);
        $role->forceDelete();

        return redirect('admin/roles')->with('success','Role has been  deleted');
    }
}
