<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Redirect;
use Auth;
use Validator;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('warehouse_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $warehouses = Warehouse::where(function($query) use ($request){
            if($request->warehouse_id){
                return $query->where('id', $request->warehouse_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->short_id){
                return $query->where('id', $request->short_id);
            }
        })
        ->orderbydesc('id')
        ->get();

        return view('warehouses/index', [ 'warehouses' => $warehouses ]);
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
       $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                Rule::unique('warehouses')->whereNull('deleted_at')
            ],
            'short_term' => [
                'required',
                'max:255',
                Rule::unique('warehouses')->whereNull('deleted_at')
            ],
        ]);
        
        if ($validator->fails())
        {
            return redirect()->route('warehouses.index')->with('error', 'Please try again. The same names are not allowed.');
        }
       
        $warehouse = Warehouse::Create([
            'name' => $request->name,
            'short_term' => $request->short_term,
            'created_by' => Auth::user()->id
        ]);
        if ($request->remarks) {
            $warehouse->update([
                'remarks' => $request->remarks,
            ]);
        }

        return redirect()->route('warehouses.index')->with('success', 'Warehouse was created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, warehouse $warehouse)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'edit_short_term' => 'required',
            'edit_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('warehouses.index')->with('error', 'Please try again.');
        }
        $warehouse = Warehouse::findOrFail($request->edit_id);

        $warehouse->update([
            'name' => $request->edit_name,
            'short_term' => $request->edit_short_term,
            'updated_by' => Auth::user()->id,
            'remarks' => $request->edit_remarks,
        ]);


        return redirect()->route('warehouses.index')->with('success', 'Warehouse was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $warehouse = Warehouse::findOrFail($request->del_id);
        if($warehouse){
            $check_shelf = Shelf::where('warehouse_id', $warehouse->id)->first();
            if ($check_shelf) {
                return Redirect::route('warehouses.index')->with('error'," Please note that warehouses containing active shelves cannot be deleted!");
            } else {
                $warehouse->delete();
                return Redirect::route('warehouses.index')->with('success','Successfully Deleted a warehouse');          
            }
            
        }else{
            return Redirect::route('warehouses.index')->with('error','Warehouse Not Found');
        }
    }

}
