<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Hash;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('user_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $users = User::where(function($query) use ($request){
            if($request->user){
                return $query->where('id', $request->user);
            }
            
        })
        ->where(function ($query) use ($request){
            if($request->phone){
                return $query->where('phone', $request->phone);
            }
        })
        ->where('id', '!=', Auth::user()->id)
        ->orderbydesc('id')
        ->get();

        return view('users/index', [ 'users' => $users ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_user');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
       return view('users/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:users|max:255',
            'user_name' => ['required', 'string', 'max:255','unique:users'],
            'phone' => 'required|unique:users|max:255',
            'address' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        if ($validator->fails())
        {
            
        return Redirect::back()->withInput()
                                 ->with('error', 'Please try again');

        }
       
        $user = User::Create([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'created_by' => Auth::user()->id,
        ]);
        if ($request->email) {
            $user->update([
                'email' => $request->email,
            ]);
        }
        if ($request->emergency) {
            $user->update([
                'emergency' => $request->emergency,
            ]);
        }
        return redirect()->route('users.index')->with('success', 'User was created successfully.');
    }

  
    public function edit(Request $request, user $user)
    {
        $check = Auth::user()->hasPermissionTo('edit_user');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $user = User::findOrFail($request->id);
        return view('users/edit', [ 'user' => $user ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, user $user)
    {
       
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'id' => 'required',
            'user_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails())
        {
            return Redirect::back()->withInput()
                                     ->with('error', 'Please try again');
    
        }
        $user = user::findOrFail($request->id);

        if ($request->password) {
            $validator = Validator::make($request->all(),[
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        $user->update([
            'name' => $request->name,
            'user_name' => $request->user_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_by' => Auth::user()->id,
        ]);
        
         if ($request->email) {
            $user->update([
                'email' => $request->email,
            ]);
        }
        if ($request->emergency) {
            $user->update([
                'emergency' => $request->emergency,
            ]);
        }
        
        if (Auth::user()->id == $request->id) {
            return Redirect::back()->withInput()
                                 ->with('success', 'User was successfully updated');
        } else {
            return redirect()->route('users.index')->with('success', 'User was successfully updated');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $user = user::findOrFail($request->del_id);
        if($user){
            $user->delete();
            return Redirect::route('users.index')->with('success','Successfully Deleted a user');          
        }else{
            return Redirect::route('users.index')->with('error','user Not Found');
        }
    }

    
#profile
    public function profile(Request $request, user $user)
    {
        $user = User::findOrFail(Auth::user()->id);
        return view('users/profile', [ 'user' => $user ]);
    }
}
