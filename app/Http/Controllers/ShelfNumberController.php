<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShelfNumber;
use App\Models\Shelf;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Validator;

class ShelfNumberController extends Controller
{
    
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('shelf_number_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        
        $shelf_nums = ShelfNumber::where(function($query) use ($request){
            if($request->shelf_num_id){
                return $query->where('id', $request->shelf_num_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->warehouse_id){
                return $query->where('warehouse_id', $request->warehouse_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->shelf_id){
                return $query->where('shelf_id', $request->shelf_id);
            }
        })
        ->orderbydesc('id')
        ->get();

        $warehouses = Warehouse::all();
        $shelves = Shelf::all();

        return view('shelf_numbers/index', [ 
                                            'shelf_nums' => $shelf_nums, 
                                            'warehouses' => $warehouses,
                                            'shelves' => $shelves,
                                        ]);
    }

    /**
     * Show the form for shelves under warehouse
     */
    public function warehouseShelves(Request $request)
    {
        $shelves = Shelf::where('warehouse_id', $request->warehouse_id)
                    ->get();
        return response()->json($shelves);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'warehouse_id' => 'required',
            'shelf_id' => 'required',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->route('shelf_nums.index')->with('error', 'Please try again. The same names are not allowed.');
        }

        $c_warehouse_id = Warehouse::find($request->warehouse_id);

        if ($c_warehouse_id) {
            $c_shelf_id = Shelf::findOrFail($request->shelf_id);
            $shelf_num = ShelfNumber::Create([
                'name' => $request->name,
                'warehouse_id' => $c_warehouse_id->id,
                'shelf_id' => $c_shelf_id->id,
                'created_by' => Auth::user()->id
            ]);
            if ($request->remarks) {
                $shelf_num->update([
                    'remarks' => $request->remarks,
                ]);
            }
           return redirect()->route('shelf_nums.index')->with('success', 'Shelf Number was successfully Created');
        }
        return redirect()->route('shelf_nums.index')->with('error', 'Warehouse Not Found');
        
       
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShelfNumber $shelfNumber)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'e_warehouse_id' => 'required',
            'e_shelf_id' => 'required',
            'edit_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('shelf_nums.index')->with('error', 'Please try again.');
        }
        $shelf_num = ShelfNumber::findOrFail($request->edit_id);

        $shelf_num->update([
            'name' => $request->edit_name,
            'warehouse_id' => $request->e_warehouse_id,
            'shelf_id' => $request->e_shelf_id,
            'updated_by' => Auth::user()->id,
            'remarks' => $request->edit_remarks,
        ]);

       

        return redirect()->route('shelf_nums.index')->with('success', 'shelf_num was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $shelf_num = ShelfNumber::findOrFail($request->del_id);
        if($shelf_num){
            $check_product = Product::where('shelf_number_id', $shelf_num->id)->first();
            if ($check_product) {
                return Redirect::route('shelf_nums.index')->with('error'," Please note that ShelfNumber containing active products cannot be deleted!");
            } else {
                $shelf_num->delete();
                return Redirect::route('shelf_nums.index')->with('success','Successfully Deleted a Shelf Number');          
            }         
        }else{
            return Redirect::route('shelf_nums.index')->with('error','Shelf Number Not Found');
        }
    }

}