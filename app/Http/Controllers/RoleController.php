<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Role management'))
            return abort(401);

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    public function getRoles() {
        if (!Gate::allows('Role management'))
            return abort(401);

        $roles = Role::all();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('permisos', function ($data) {
                $permisos_html = '';
                $role = Role::find($data->id);
                foreach($role->permissions()->pluck('name') as $permission)
                    $permisos_html .= '<span class="badge badge-primary mr-2">'. $permission .'</span>';

                return $permisos_html;
            })
            ->addColumn('action', function ($data) {
                $edit_html = '<a class="btn btn-xs text-xs btn-primary mr-2" href="roles/'.$data->id.'/edit">Edit</a>';
                $delete_html = '<form action="roles/'.$data->id.'" method="POST" onsubmit="return confirm(\'¿ Está seguro ?\');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="'.csrf_token().'">
                                    <input type="submit" class="btn btn-xs text-xs btn-danger" value="Delete">
                                </form>';
                return $edit_html.$delete_html;
            })
            ->rawColumns(['permisos','action'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Role management'))
            return abort(401);

        $permissions = Permission::get()->pluck('name', 'name');
        return view('admin.roles.create',['layout' => 'simple-menu'],compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Role management'))
            return abort(401);

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Role management'))
            return abort(401);

        return redirect()->route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if (!Gate::allows('Role management'))
            return abort(401);

        $permissions = Permission::get()->pluck('name', 'name');

        return view('admin.roles.edit',['layout' => 'simple-menu'], compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if (!Gate::allows('Role management'))
            return abort(401);

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role->update($request->except('permission'));
        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('Role management'))
            return abort(401);

        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
