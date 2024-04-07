<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
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
        if (!Gate::allows('User management'))
            return abort(401);

        $users = User::all();

        return view('admin.users.index',['layout' => 'simple-menu'], compact('users'));
    }

    public function getUsers() {
        if (!Gate::allows('User management'))
            return abort(401);

        $users = User::all();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('roles', function ($data) {
                $roles_html = '';
                $user = User::find($data->id);
                foreach($user->roles()->pluck('name') as $role)
                    $roles_html .= '<span class="badge badge-primary mr-2">'. $role .'</span>';

                return $roles_html;
            })
            ->addColumn('action', function ($data) {
                $edit_html = '<a class="btn btn-xs text-xs btn-primary mr-2" href="users/'.$data->id.'/edit">Edit</a>';
                $delete_html = '<form action="users/'.$data->id.'" method="POST" onsubmit="return confirm(\'¿ Está seguro ?\');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="'.csrf_token().'">
                                    <input type="submit" class="btn btn-xs text-xs btn-danger" value="Delete">
                                </form>';
                return $edit_html.$delete_html;
            })
            ->rawColumns(['roles','action'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('User management'))
            return abort(401);

        $roles = Role::pluck('name','name')->all();
        return view('admin.users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('User management'))
            return abort(401);

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required'
        ]);

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('User management'))
            return abort(401);

        return redirect()->route('users.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (!Gate::allows('User management'))
            return abort(401);

        $roles = Role::get()->pluck('name', 'name');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!Gate::allows('User management'))
            return abort(401);

        $id = $user->id;

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);

        $user->update($input);

        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('User management'))
            return abort(401);

        User::find($id)->delete();

        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

}
