<?php

namespace App\Http\Controllers;

use App\Models\SupplierReturn;
use App\Models\SupplierReturnHistory;
use App\Models\Product;
use App\Models\Unit; 
use App\Models\Brand; 
use App\Models\Transfer; 
use App\Models\Commodity; 
use App\Models\Warehouse; 
use App\Models\Code; 
use App\Models\Supplier; 
use App\Models\Shelf; 
use App\Models\ShelfNumber; 
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierReturnsExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Redirect;
use Auth;
use Validator;

class SupplierReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('supplier_return_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $sup_returns = SupplierReturn::where(function($query) use ($request){
           
            if($request->sup_no){
                return $query->where('supplier_return_no', $request->sup_no);
            }
        })
        ->where(function ($query) use ($request){
            if($request->vr_no){
                $products = Product::where('products.voucher_no', $request->vr_no)
                                    ->join('supplier_returns', 'supplier_returns.product_id', 'products.id')
                                    ->get(['products.id as product_id']);
                                            
                $p_items = [];
                foreach($products as $product){
                    array_push($p_items, optional($product)->product_id);
                }
                                        
                return $query->whereIn('product_id', $p_items);
                
            }
        })
        ->where(function ($query) use ($request){
            if($request->warehouse_id){
                $shelf_nos = ShelfNumber::where('warehouse_id',$request->warehouse_id)
                                        ->join('issues', 'issues.shelf_number_id', 'shelf_numbers.id')
                                        ->get('shelf_numbers.id as shelf_number_id');
                                                
                $s_items = [];
                foreach($shelf_nos as $shelf_no){
                    array_push($s_items, optional($shelf_no)->shelf_number_id);
                }
                                            
                return $query->whereIn('shelf_number_id', $s_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->shelf_num_id){
                return $query->where('shelf_number_id', $request->shelf_num_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->code_id){
                $codes = Code::where('codes.name', $request->code_id)
                                    ->join('supplier_returns', 'supplier_returns.code_id', 'codes.id')
                                    ->get(['codes.id as code_id']);

                $c_items = [];
                foreach($codes as $code){
                    array_push($c_items, optional($code)->code_id);
                }
                                            
                return $query->whereIn('code_id', $c_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->brand_id){
             
                $brands = Code::where('brand_id',$request->brand_id)
                                ->join('supplier_returns', 'supplier_returns.code_id', 'codes.id')
                                ->get(['codes.id as code_id']);

                $b_items = [];
                foreach($brands as $brand){
                    array_push($b_items, optional($brand)->code_id);
                }
                return $query->whereIn('code_id', $b_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->commodity_id){
              
                $commodities = Code::where('commodity_id',$request->commodity_id)
                                    ->join('supplier_returns', 'supplier_returns.code_id', 'codes.id')
                                    ->get(['codes.id as code_id']);


                $com_items = [];
                foreach($commodities as $commodity){
                    array_push($com_items, optional($commodity)->code_id);
                }
                                            
                return $query->whereIn('code_id', $com_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->supplier_id){
                return $query->where('supplier_id', $request->supplier_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_date){
                $from_date = date('Y-m-d', strtotime($request->from_date));

                return $query->where('supplier_return_date', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d', strtotime($request->to_date));
                
                return $query->where('supplier_return_date', '<=',  $to_date);
            }
        })
        ->orderbydesc('supplier_return_date')
        ->get();

        $warehouses = Warehouse::get();
        $suppliers = Supplier::get();
        $codes = Code::distinct()->get(['name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
        $shelfnums = ShelfNumber::get();

        $vr_nos = SupplierReturn::join('products', 'products.id', 'supplier_returns.product_id')
                        ->distinct()
                        ->get(['products.voucher_no']);

        if ($request->has('export')) {
            $sort_sup_returns = $sup_returns->sort();
            return $this->export($sort_sup_returns);
        }

        return view('supplier_returns/index', ['warehouses' => $warehouses,
                                        'suppliers' => $suppliers,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'shelfnums' => $shelfnums,
                                        'sup_returns' => $sup_returns,
                                        'vr_nos' => $vr_nos
                                        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_supplier_return');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $warehouses = Warehouse::get();
        $suppliers = Supplier::get();
        return view('supplier_returns/create', ['warehouses' => $warehouses,
                                     'suppliers' => $suppliers
                                        ]);
    }

    public function getSupplier(Request $request)
    {
        $suppliers = Product::where('products.shelf_number_id', $request->shelfnum_id)
                        ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
                        ->distinct()
                        ->get(['name']);
                        
        return response()->json(['suppliers' => $suppliers ]);
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request)  {
            $validator = Validator::make($request->all(),[
                "warehouse_id" => 'required',
                "shelfnum_id" => 'required',
                "date" => 'required',
                "sup_no" => 'required',
                "supplier" => 'required',
            ]); 
    
            if ($validator->fails())
            {
                return Redirect::back()->withInput()
                                        ->with('error', 'Please try again.');
            }
    
            $supplier = Supplier::where('name',$request->supplier)->first();
           
            $newRequest = $request->except(['_token',
                                        'warehouse_id',
                                        'shelfnum_id',
                                        'sup_no',
                                        'date',
                                        'supplier',
                                    ]);
    
            foreach ($newRequest as $key => $value){
                if (str_contains($key, 'code_')) {
                    $explode = explode("_",$key);
                    $code = "code_".$explode[1];
                    $brand = "brand_".$explode[1];
                    $commodity = "commodity_".$explode[1];
                    $qty = "qty_".$explode[1];
                    $remarks = "remark_".$explode[1];
                    $vr_no = "vr_no_".$explode[1];
    
                    #for new 
                    $product = Product::find($request->$vr_no);
                    
                    if ($product) {
                        # same sup no, product exist?
                        $check_sup_returns = SupplierReturn::where('product_id', $product->id)
                                            ->where('supplier_return_no', $request->sup_no)
                                            ->where('shelf_number_id', $request->shelfnum_id)
                                            ->where('supplier_id', $supplier->id)
                                            ->first();
                                            // dd($check_sup_returns, $product->id, $request->sup_no, $supplier->id);
    
                        if (!$check_sup_returns) {
                            
                            if ($product->balance_qty >= $request->$qty) {
                                # code...
                                $sup_return = SupplierReturn::create([
                                    'supplier_return_no'=> $request->sup_no,
                                    'supplier_return_qty'=> $request->$qty,
                                    'shelf_number_id'=> $request->shelfnum_id,
                                    'supplier_return_date'=> $request->date,
                                    
                                    'remarks' => $request->$remarks,
                                    'product_id' => $product->id,
                                    'code_id' => $product->code_id,
            
                                    'created_by'=> Auth::user()->id,
                                    'supplier_id'=> $supplier->id,
                                ]);
                                $product->update([
                                    'balance_qty' => $product->balance_qty - $request->$qty,
                                    'supplier_return_qty' => $product->supplier_return_qty + $request->$qty,
                                ]);
        
                            }
                        }
                        
                    }
                }
            }
        });
        return redirect()->route('supplier_returns.index')->with('success', 'New Supplier Return was created successfully');

    }
   
    public function edit(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('edit_supplier_return');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $warehouses = Warehouse::get();

        # Edit IDs to show
        $sup_return = SupplierReturn::find($request->id);
        if ($sup_return) {
            # code...
            $edit_shelfnum = ShelfNumber::where('shelf_numbers.id', $sup_return->shelf_number_id)
                                            ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                            ->first([
                                                'shelf_numbers.id', 
                                                'shelves.name as shelfName', 
                                                'shelf_numbers.name as shelfnumName', 
                                                'shelf_numbers.warehouse_id'
                                            ]);
    
            $edit_warehouse = Warehouse::findOrFail(optional($edit_shelfnum)->warehouse_id);
            $edit_supplier = Supplier::findOrFail($sup_return->supplier_id);
    
            #supplier list
            $suppliers = Product::where('products.shelf_number_id', $sup_return->shelf_number_id)
                        ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
                        ->distinct()
                        ->get(['name']);
                        
            # selected list under warehouseID
            $shelfnums = ShelfNumber::where('shelf_numbers.warehouse_id', optional($edit_warehouse)->id)
                                    ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                    ->get([
                                        'shelf_numbers.id', 
                                        'shelves.name as shelfName', 
                                        'shelf_numbers.name as shelfnumName', 
                                        'shelf_numbers.warehouse_id'
                                    ]);
    
            # code list under shelfnumberID & type(receive) &supplierID
            $choosen_sup_returns = SupplierReturn::where('supplier_returns.supplier_return_no', $sup_return->supplier_return_no)
                                    ->where('supplier_returns.shelf_number_id', $sup_return->shelf_number_id)
                                    ->where('supplier_returns.supplier_id', $sup_return->supplier_id)
                                    ->join('products', 'products.id', '=', 'supplier_returns.product_id')
                                    ->join('codes', 'codes.id', '=', 'products.code_id')
                                    ->join('brands', 'brands.id', '=', 'codes.brand_id')
                                    ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                                    ->get(['products.id as product_id',
                                            'products.voucher_no', 
                                            'supplier_returns.id as sup_return_id', 
                                            'supplier_returns.supplier_return_date', 

                                            'codes.name as code_name', 
                                            'codes.brand_id',
                                            'brands.name as brand_name',
                                            'codes.commodity_id',
                                            'commodities.name as commodity_name',
                                            'codes.usage',
                                            'codes.image',

                                            'supplier_returns.supplier_return_qty',
                                            'supplier_returns.remarks',
                                            'products.balance_qty',
                                            'products.transfer_id',
                                        ]);
                                      
            # Edit Code IDs
            $codes = Product::where('products.shelf_number_id', $sup_return->shelf_number_id)
                        ->join('codes','codes.id', '=', 'products.code_id')
                        ->get(['codes.name']);

            return view('supplier_returns/edit', ['warehouses' => $warehouses,
                                            'suppliers' => $suppliers,
                                            'sup_return' => $sup_return,
                                            'codes' => $codes,
    
                                            'edit_warehouse' => $edit_warehouse,
                                            'edit_shelfnum' => $edit_shelfnum,
                                            'edit_supplier' => $edit_supplier,
    
                                            'shelfnums' => $shelfnums,
                                            'choosen_sup_returns' => $choosen_sup_returns,
                                            ]);
        }else{
            return Redirect::back()->withInput()
                            ->with('error', 'Issue Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        DB::transaction(function () use ($request)  {
            $validator = Validator::make($request->all(),[
                "warehouse_id" => 'required',
                "shelfnum_id" => 'required',
                "date" => 'required',
                "sup_no" => 'required',
                "supplier" => 'required',
            ]);
    
            if ($validator->fails())
            {
                return Redirect::back()->withInput()
                                ->with('error', 'Please try again.');
            }
    
            $old_sup_return = SupplierReturn::find($request->old_sup_return);
            $supplier = Supplier::where('name',$request->supplier)->first();
    
            $newRequest = $request->except(['_token',
                                            'warehouse_id',
                                            'shelfnum_id',
                                            'date',
                                            'supplier',
                                            'sup_no',
                                            'old_sup_return'
                                        ]);
            foreach ($newRequest as $key => $value){
                if (str_contains($key, 'supreturn_')) {
                    $explode = explode("_",$key);
                    $qty = "qty_".$explode[1];
                    $sup_return_id = "supreturn_".$explode[1];
    
                    #sup_return_id
                    $sup_return = SupplierReturn::find($request->$sup_return_id);
    
                    if ($sup_return) {
    
                        $sup_return_product = Product::find($sup_return->product_id);
                        if ($sup_return_product){
    
                            $sup_return_code = Code::find($sup_return_product->code_id);
                        }
                    }
                    if (!$request->$qty) {
    
                        $sup_return_history = SupplierReturnHistory::create([
    
                            'shelf_number_id' => $sup_return->shelf_number_id,
                            'new_shelf_number_id' => $sup_return->shelf_number_id,
    
                            'product_id' => $sup_return->product_id,
                            'new_product_id' => $sup_return->product_id,
    
                            'code_id' => $sup_return->code_id,
                            'new_code_id' => $sup_return->code_id,
    
                            'supplier_return_no' => $sup_return->supplier_return_no,
                            'new_supplier_return_no' => $sup_return->supplier_return_no,
    
                            'supplier_return_qty' => $sup_return->supplier_return_qty,
                            'new_supplier_return_qty' => $sup_return->supplier_return_qty,
                            
                            'supplier_id' => $sup_return->supplier_id,
                            'new_supplier_id' => $sup_return->supplier_id,
    
                            'supplier_return_date' => $sup_return->supplier_return_date,
                            'new_supplier_return_date' => $sup_return->supplier_return_date,
                
                            'remarks' => $sup_return->remarks,
                            'new_remarks' => $sup_return->remarks,
                
                            'method' => "delete",
                            'created_by' => Auth::user()->id,
                        ]);
    
                        $sup_return_product->update([
                            'supplier_return_qty' =>  $sup_return_product->supplier_return_qty - $sup_return->supplier_return_qty,
                            'balance_qty' => $sup_return_product->balance_qty + $sup_return->supplier_return_qty,
                        ]);
    
                        $sup_return->delete();
                    }
                    
                }
            }
    
    
            foreach ($newRequest as $key => $value){
                if (str_contains($key, 'qty_')) {
                    $explode = explode("_",$key);
                    $code = "code_".$explode[1];
                    $brand = "brand_".$explode[1];
                    $commodity = "commodity_".$explode[1];
                    $qty = "qty_".$explode[1];
                    $remarks = "remark_".$explode[1];
                    $vr_no = "vr_no_".$explode[1];
                    $sup_return_id = "supreturn_".$explode[1];
    
                    #sup_return_id
                    $sup_return = SupplierReturn::find($request->$sup_return_id);
                    $new_product = Product::find($request->$vr_no);
    
                    if ($sup_return) {
    
                        $sup_return_product = Product::find($sup_return->product_id);
                        $sup_return_code = Code::find($sup_return->code_id);
                        
                        if ($sup_return_product && $new_product){
                            #check if changed product in existing sup_return 
                            $check_return = SupplierReturn::where('supplier_return_no', $request->supplier_return_no)
                                                ->where('product_id', $new_product->id)
                                                ->where('id', '!=' , $sup_return->id)
                                                ->first();
    
                        }
                    }
    
    
                    #update return product
                    if ($sup_return && $request->$code ) {
                        if ($request->shelfnum_id != $sup_return->shelf_number_id || 
                            $request->sup_no != $sup_return->supplier_return_no || 
                            $request->date != $sup_return->supplier_return_date || 
                            $supplier->id != $sup_return->supplier_id || 
    
                            $request->$vr_no != $sup_return->product_id || 
                            $request->$code != $sup_return_code->name || 
                            $request->$qty != $sup_return->supplier_return_qty) {
    
                                $sup_return_history = SupplierReturnHistory::create([
    
                                    'shelf_number_id' => $sup_return->shelf_number_id,
                                    'new_shelf_number_id' => $request->shelfnum_id,
            
                                    'product_id' => $sup_return->product_id,
                                    'new_product_id' => $request->$vr_no,
            
                                    'code_id' => $sup_return->code_id,
                                    'new_code_id' => $new_product->code_id,
            
                                    'supplier_return_no' => $sup_return->supplier_return_no,
                                    'new_supplier_return_no' => $request->sup_no,
            
                                    'supplier_return_qty' => $sup_return->supplier_return_qty,
                                    'new_supplier_return_qty' => $request->$qty,
                                    
                                    'supplier_id' => $sup_return->supplier_id,
                                    'new_supplier_id' => $supplier->id,
            
                                    'supplier_return_date' => $sup_return->supplier_return_date,
                                    'new_supplier_return_date' => $request->date,
                        
                                    'remarks' => $sup_return->remarks,
                                    'new_remarks' => $request->$remarks,
                        
                                    'method' => "update",
                                    'created_by' => Auth::user()->id,
                                ]);
                            
                        }
    
                        $sup_return_product->update([
                            'supplier_return_qty' =>  $sup_return_product->supplier_return_qty - $sup_return->supplier_return_qty,
                            'balance_qty' => $sup_return_product->balance_qty + $sup_return->supplier_return_qty,
                        ]);
    
                        #if same product id, update OR reduce to old- add to new
                        if ($sup_return->product_id == $request->$vr_no) {
    
                            $sup_return_product->update([
                                'supplier_return_qty' =>  $sup_return_product->supplier_return_qty + $request->$qty,
                                'balance_qty' => $sup_return_product->balance_qty - $request->$qty,
                            ]);
    
                        }else{
                            $new_product->update([
                                'supplier_return_qty' =>  $new_product->supplier_return_qty + $request->$qty,
                                'balance_qty' => $new_product->balance_qty - $request->$qty,
                            ]);
                        }
    
                        $sup_return->update([
                            'supplier_return_no'=> $request->sup_no,
                            'supplier_return_qty'=> $request->$qty,
                            'shelf_number_id'=> $request->shelfnum_id,
                            'supplier_return_date'=> $request->date,
                            
                            'remarks' => $request->$remarks,
                            'product_id' => $new_product->id,
                            'code_id' => $new_product->code_id,
    
                            'created_by'=> Auth::user()->id,
                            'supplier_id'=>  $supplier->id,
                        ]);
                    
                    }else{
                        #new return update
                        if ($new_product) {
                            # same return no, product exist?
                            $check_sup_returns = SupplierReturn::where('product_id', $new_product->id)
                                                ->where('supplier_return_no', $request->sup_no)
                                                ->where('shelf_number_id', $request->shelfnum_id)
                                                ->where('supplier_id', $supplier->id)
                                                ->first();
                            if (!$check_sup_returns) {
                               
                                $sup_return = SupplierReturn::create([
                                    'supplier_return_no'=> $request->sup_no,
                                    'supplier_return_qty'=> $request->$qty,
                                    'shelf_number_id'=> $request->shelfnum_id,
                                    'supplier_return_date'=> $request->date,
                                    
                                    'remarks' => $request->$remarks,
                                    'product_id' => $new_product->id,
                                    'code_id' => $new_product->code_id,
            
                                    'created_by'=> Auth::user()->id,
                                    'supplier_id'=>  $supplier->id,
                                ]);
                                
                                if ($new_product->balance_qty >= $request->$qty) {
                                    # code...
                                    $new_product->update([
                                        'balance_qty' => $new_product->balance_qty - $request->$qty,
                                        'supplier_return_qty' => $new_product->supplier_return_qty + $request->$qty,
                                    ]);
            
                                }
                            }
        
                        }
                    
                         
                    }
                    
            }};
        });

        return redirect()->route('supplier_returns.index')->with('success', 'Supplier Return was successfully updated');
    }

    public function history(Request $request)  {
        $check = Auth::user()->hasPermissionTo('supplier_return_history');
        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $histories = SupplierReturnHistory::orderby('id', 'desc')->get();;
        return view('supplier_returns/history', ['histories' => $histories ]);
    }

     //export excel
     public function export($sort_sup_returns)
     {
        $check = Auth::user()->hasPermissionTo('export_supplier_return');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

         for ($i = 0; $i < count($sort_sup_returns); $i++) {
             # code...
             $sup_return = $sort_sup_returns[$i];

             $product = Product::find($sup_return->product_id); 
             $unit = Unit::find(optional($product)->unit_id); 
             $supplier = Supplier::find($sup_return->supplier_id); 
 
             $shelf_number = ShelfNumber::find($sup_return->shelf_number_id); 
             $warehouse = Warehouse::find(optional($shelf_number)->warehouse_id); 
 
             $code = Code::find($sup_return->code_id); 
             $brand = Brand::find(optional($code)->brand_id); 
             $commodity = Commodity::find(optional($code)->commodity_id); 
 
             $c_user = User::find($sup_return->created_by); 
             $u_user = User::find($sup_return->updated_by); 
 
             $sort_sup_returns[$i] = [
                 "No" =>  count($sort_sup_returns) - $i,
                 "Date" => $sup_return->supplier_return_date,
                 "Supplier Return No" => $sup_return->supplier_return_no,
                 'Warehouse' => optional($warehouse)->name,
                 "Shelf No" => optional($shelf_number)->name,
                 "Supplier" => optional($supplier)->name,
 
                 "Code" => optional($code)->name,
                 "Brand" => optional($brand)->name,
                 "Commodity" => optional($commodity)->name,
                 "Unit" => optional($unit)->name,
                 "Supplier Return Qty" => $sup_return->supplier_return_qty,
                 "Remarks" => $sup_return->remarks,
                 "Voucher No" => optional($product)->voucher_no,
 
                 "Create By" => optional($c_user)->name,
                 "Updated By" => optional($u_user)->name,
                 
                 "Created At" => $sup_return->created_at,
                 "Updated At" => $sup_return->updated_at,
             ];
         }
         $export = new SupplierReturnsExport([$sort_sup_returns]);
 
         return Excel::download($export, 'supplier_returns ' . date("Y-m-d") . '.xlsx');
     }

}
