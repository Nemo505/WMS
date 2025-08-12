<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShelfNumber;
use App\Models\Shelf;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\ShelfNumberImport;
use Redirect;
use Auth;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use DB;

class ShelfNumberController extends Controller
{
    
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('shelf_number_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        
        $shelf_nums = ShelfNumber::where(function($query) use ($request){
            if($request->shelf_num_name){
                return $query->where('name', $request->shelf_num_name);
            }
        })
        ->where(function ($query) use ($request){
            if($request->warehouse_id){
                return $query->where('warehouse_id', $request->warehouse_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->shelf_name){
                $s_shelves = Shelf::where('name', $request->shelf_name)->get();
                $shelf_ids = $s_shelves->pluck('id')->toArray(); 
                return $query->whereIn('shelf_id', $shelf_ids);
            }
        })
        ->orderbydesc('id')
        ->get();

        $warehouses = Warehouse::all();
        $shelves = Shelf::distinct()->pluck('name');

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
            return redirect()->route('shelf_nums.index')->with('error', 'Please try again.');
        }

        $c_warehouse_id = Warehouse::find($request->warehouse_id);

        if ($c_warehouse_id) {
            $c_shelf_id = Shelf::findOrFail($request->shelf_id);
            $check_shelf = ShelfNumber::where('name', $request->name)
                            ->where('warehouse_id', $c_warehouse_id->id)
                            ->where('shelf_id', $c_shelf_id->id)
                            ->first();
            if (!$check_shelf) {

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
            }else{
                return redirect()->route('shelf_nums.index')->with('error', 'Please try again. The same names are not allowed.');
            }
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

       

        return redirect()->route('shelf_nums.index')->with('success', 'ShelfNumber was successfully updated');
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
    
    public function import(Request $request){
        
        try {
            DB::beginTransaction();
            $arrays = Excel::toArray(new ShelfNumberImport, $request->file('shelf-nums'));
          
            foreach ($arrays[0] as $rowNumber => $row) {
                $shelf_number = trim(preg_replace('/\s+/', ' ', $row['shelf_number']));
                $shelf_name = trim(preg_replace('/\s+/', ' ', $row['shelf_name']));
                $warehouse_name = trim(preg_replace('/\s+/', ' ', $row['warehouse_name']));
                
                if (!empty($row['shelf_name']) && !empty($row['warehouse_name'])) {
        
                    $shelf = Shelf::where('name', $shelf_name)->first();
                    $warehouse = Warehouse::where('name', $warehouse_name)->first();
        
                    if($shelf && $warehouse){
                        $shelfnumber = ShelfNumber::where('name', $shelf_number)
                                    ->where('shelf_id', $shelf->id)
                                    ->where('warehouse_id', $warehouse->id)
                                    ->first();
        
                        if(!$shelfnumber){
                            ShelfNumber::create([
                                'name' => $shelf_number,
                                'shelf_id' => $shelf->id,
                                'warehouse_id' => $warehouse->id,
                                'remarks' => $row['remarks'] ?? "-",
                                'created_by' => Auth::user()->id,
                            ]);
                        }else {
                            return redirect()->route('shelf_nums.index')->with('error', "shelf_nums '$shelf_number' already exists. No changes were made.");
                        }
                    }else{
                            return redirect()->route('shelf_nums.index')->with('error', "Row number " . ($rowNumber + 2) . " is empty. No changes were made.");
                        }
                } 
            }
            
            DB::commit();
            return redirect()->route('shelf_nums.index')->with('success', 'shelf_nums Imported successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred during import. No changes were made.');
        }

    }
    
    public function sample(){
        $path = public_path(). "/excel/samples/ShelfNums.xlsx";
        if(file_exists($path)){
            return response()->download($path);
        }else{
            return redirect()->route('shelves.index')->with('error', 'This cannot be downloaded');
        }
    }
    

}