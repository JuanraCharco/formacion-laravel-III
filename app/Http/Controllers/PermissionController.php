<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        if (!Gate::allows('Permission management'))
            return abort(401);

        return view('admin.permissions.index');
    }

    public function getPermissions() {
        if (!Gate::allows('Permission management'))
            return abort(401);

        $permissions = Permission::all();

        return DataTables::of($permissions)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $edit_html = '<a class="btn btn-xs text-xs btn-primary mr-2" href="permissions/'.$data->id.'/edit">Edit</a>';
                $delete_html = '<form action="permissions/'.$data->id.'" method="POST" onsubmit="return confirm(\'¿ Está seguro ?\');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="'.csrf_token().'">
                                    <input type="submit" class="btn btn-xs text-xs btn-danger" value="Delete">
                                </form>';
                return $edit_html.$delete_html;
            })
            ->make();
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Permission management'))
            return abort(401);

        return view('admin.permissions.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Permission management'))
            return abort(401);

        Permission::create($request->all());

        return redirect()->route('permissions.index');
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        if (!Gate::allows('Permission management'))
            return abort(401);

        return view('admin.permissions.edit',['layout' => 'simple-menu'], compact('permission'));
    }

    /**
     * Update Permission in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if (!Gate::allows('Permission management'))
            return abort(401);

        $permission->update($request->all());

        return redirect()->route('permissions.index');
    }


    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if (!Gate::allows('Permission management'))
            return abort(401);

        $permission->delete();

        return redirect()->route('permissions.index');
    }

    public function show(Permission $permission)
    {
        if (!Gate::allows('Permission management'))
            return abort(401);

        return redirect()->route('permissions.index');
    }

}
