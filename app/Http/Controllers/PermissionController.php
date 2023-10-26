<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DB;
use Auth;

class PermissionController extends Controller
{
    

    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('permission_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $permissions = Permission::orderBy('id', 'ASC')->get();
        $users = User::orderBy('id', 'DESC')->get();

        if ($request->user_id) {
            $user =  User::findOrFail($request->user_id);
            $permission_names = $user->permissions->pluck('name')->toArray();
            

            return view('users.permission', [  'users' => $users,
                                                'permissions' => $permissions,
                                                'permission_names' => collect($permission_names)
                                            ]);
        } else {

            return view('users.permission', [  'users' => $users,
                                                'permissions' => $permissions
                                            ]);
        }
        
    }

    public function store(Request $request){

        $check = Auth::user()->hasPermissionTo('create_permission');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $user =  User::find($request->user);
        if ($user) {
            
            $remove = DB::table('model_has_permissions')
                            ->where('model_id', $user->id)
                            ->delete();

            $requestKeys = collect($request->all())->keys();
            $remove_keys = $requestKeys->except([0, 1])->toArray();
            $values = array_values($remove_keys);
    
            $user->givePermissionTo($values);
            return redirect()->back()->with('success','Permissions Added Successfully');
        }
        return redirect()->back()->with('error','Please Choose User Name');
    }

}
