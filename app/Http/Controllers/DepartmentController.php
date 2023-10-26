<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('department_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $departments = Department::where(function($query) use ($request){
            if($request->department_id){
                return $query->where('id', $request->department_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->short_id){
                return $query->where('id', $request->short_id);
            }
        })
        ->orderbydesc('id')
        ->get();

        return view('departments/index', [ 'departments' => $departments ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:departments|max:255',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->route('departments.index')->with('error', 'Please try again. The same names are not allowed.');
        }
       
        $department = department::Create([
            'name' => $request->name,
            'created_by' => Auth::user()->id
        ]);
        if ($request->remarks) {
            $department->update([
                'remarks' => $request->remarks,
            ]);
        }

        return redirect()->route('departments.index')->with('success', 'department was created successfully.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, department $department)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'edit_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('departments.index')->with('error', 'Please try again.');
        }
        $department = department::findOrFail($request->edit_id);

        $department->update([
            'name' => $request->edit_name,
            'updated_by' => Auth::user()->id,
            'remarks' => $request->edit_remarks,
        ]);


        return redirect()->route('departments.index')->with('success', 'department was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $department = Department::findOrFail($request->del_id);
        if($department){
            $check_issue = Issue::where('department_id', $department->id)->first();
            if ($check_issue) {
                return Redirect::route('departments.index')->with('error'," Please note that departments containing active Issue cannot be deleted!");
            } else {
                $department->delete();
                return Redirect::route('departments.index')->with('success','Successfully Deleted a Department');          
            }
            
        }else{
            return Redirect::route('departments.index')->with('error','department Not Found');
        }
    }

}
