<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use App\Models\ShelfNumber;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ShelvesImport;
use Redirect;
use Auth;
use Validator;
use DB;

class ShelfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('shelf_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $shelves = Shelf::where(function($query) use ($request){
            if($request->shelf_id){
                return $query->where('id', $request->shelf_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->warehouse_id){
                return $query->where('warehouse_id', $request->warehouse_id);
            }
        })
        ->orderbydesc('id')
        ->get();

        $warehouses = Warehouse::all();

        return view('shelves/index', [ 'shelves' => $shelves, 'warehouses' => $warehouses]);
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
            'name' => 'required|max:255',
            'warehouse_id' => 'required',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->route('shelves.index')->with('error', 'Please try again.');
        }
        $c_warehouse_id = Warehouse::find($request->warehouse_id);
        if ($c_warehouse_id) {
            $check_shelf = Shelf::where('name', $request->name)
                                ->where('warehouse_id', $c_warehouse_id->id)
                                ->first();
            if (!$check_shelf) {
                $shelf = Shelf::Create([
                        'name' => $request->name,
                        'warehouse_id' => $c_warehouse_id->id,
                    'created_by' => Auth::user()->id
                ]);
                if ($request->remarks) {
                    $shelf->update([
                        'remarks' => $request->remarks,
                    ]);
                }
                return redirect()->route('shelves.index')->with('success', 'shelf was created successfully.');
            }else{
                return redirect()->route('shelves.index')->with('error', 'Please try again. The same names are not allowed.');
            }
        }
        return redirect()->route('shelves.index')->with('error', 'Warehouse Not Found');
       
    }

    /**
     * Display the specified resource.
     */
    public function show(shelf $shelf)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(shelf $shelf)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, shelf $shelf)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'e_warehouse_id' => 'required',
            'edit_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('shelves.index')->with('error', 'Please try again.');
        }
        $shelf = Shelf::findOrFail($request->edit_id);

        $shelf->update([
            'name' => $request->edit_name,
            'warehouse_id' => $request->e_warehouse_id,
            'updated_by' => Auth::user()->id,
            'remarks' => $request->edit_remarks,
        ]);

      
        return redirect()->route('shelves.index')->with('success', 'Shelf was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $shelf = Shelf::findOrFail($request->del_id);
        if($shelf){
            $check_shelf_num = ShelfNumber::where('shelf_id', $shelf->id)->first();
            if ($check_shelf_num) {
                return Redirect::route('shelves.index')->with('error'," Please note that Shelf containing active Shelf numbers cannot be deleted!");
            } else {
                $shelf->delete();
                return Redirect::route('shelves.index')->with('success','Successfully Deleted a shelf');          
            }       
        }else{
            return Redirect::route('shelves.index')->with('error','Shelf Not Found');
        }
    }
    
    public function import(Request $request){
        try {
            DB::beginTransaction();
            $arrays = Excel::toArray(new ShelvesImport, $request->file('shelves'));
            
            foreach ($arrays[0] as $rowNumber => $row) {
                $shelfName = trim(preg_replace('/\s+/', ' ', $row['shelf_name']));
                $warehouseName = trim(preg_replace('/\s+/', ' ', $row['warehouse_name']));
                
                if (!empty($row['shelf_name']) && !empty($row['warehouse_name'])) {
                    
                    $checkWarehouse = Warehouse::where('name', $warehouseName)
                                                ->first();
                                                
                                              
                    if($checkWarehouse){
                        $shelf = Shelf::where('name', $shelfName)
                                ->where('warehouse_id', $checkWarehouse->id)
                                ->first();
                                
                        if(!$shelf){
                            Shelf::create([
                                'name' => $shelfName,
                                'warehouse_id' => $checkWarehouse->id,
                                'remarks' => $row['remark'],
                                'created_by' => Auth::user()->id,
                            ]);
                        }else {
                            return redirect()->route('shelves.index')->with('error', "Shelf '$shelfName' already exists. No changes were made.");
                        }
                    }else {
                            return redirect()->route('shelves.index')->with('error', "The '$warehouseName' does not exist.");
                    }
                  
                }else{
                    return redirect()->route('shelves.index')->with('error', "Shelf '$shelfName' already exists. No changes were made.");
                }
            }
            
            DB::commit();
            return redirect()->route('shelves.index')->with('success', 'Shelves Imported successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred during import. No changes were made.');
        }

    }
 

    public function sample(){
        $path = public_path(). "/excel/samples/Shelves.xlsx";
        if(file_exists($path)){
            return response()->download($path);
        }else{
            return redirect()->route('shelves.index')->with('error', 'This cannot be downloaded');
        }
    }

}
