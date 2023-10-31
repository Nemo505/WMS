<?php

namespace App\Http\Controllers;

use App\Models\Unit; 
use App\Models\Product; 
use App\Models\User;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Validator;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $check = Auth::user()->hasPermissionTo('unit_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $units = Unit::where(function($query) use ($request){
            if($request->unit_id){
                return $query->where('id', $request->unit_id);
            }
        })
        ->orderbydesc('id')->get();

        return view('units/index', [ 'units' => $units ]);
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
            'name' => 'required|unique:units|max:255',
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('units.index')->with('error', 'Please try again. The same names are not allowed.');
        }
       
        $unit = unit::Create([
            'name' => $request->name,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('units.index')->with('success', 'Unit was created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, unit $unit)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'edit_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('units.index')->with('error', 'Please try again.');
        }
        $unit = unit::findOrFail($request->edit_id);

        $unit->update([
            'name' => $request->edit_name,
            'updated_by' => Auth::user()->id
        ]);

        return redirect()->route('units.index')->with('success', 'Unit was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $unit = Unit::findOrFail($request->del_id);
        if($unit){
            $check_product = Product::where('unit_id', $unit->id)->first();
            if ($check_product) {
                return Redirect::route('units.index')->with('error'," Please note that Unit containing active products cannot be deleted!");
            } else {
                $unit->delete();
                return Redirect::route('units.index')->with('success','Successfully Deleted a Shelf Number');          
            }         
        }else{
            return Redirect::route('units.index')->with('error','Shelf Number Not Found');
        }
    }

}
