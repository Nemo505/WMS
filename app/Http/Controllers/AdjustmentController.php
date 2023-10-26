<?php

namespace App\Http\Controllers;

use App\Models\Adjustment;
use App\Models\AdjustmentHistory;
use App\Models\Product;
use App\Models\Unit; 
use App\Models\Brand; 
use App\Models\Commodity; 
use App\Models\Warehouse; 
use App\Models\Code; 
use App\Models\Supplier; 
use App\Models\Shelf; 
use App\Models\ShelfNumber; 
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdjustmentsExport;
use Redirect;
use Auth;
use Validator;

class AdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('adjustment_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $adjustments = Adjustment::where(function ($query) use ($request){
            if($request->vr_no){
                $adjusts = Product::where('products.voucher_no', $request->vr_no)
                                ->join('adjustments', 'adjustments.product_id', '=', 'products.id')
                                ->get(['products.id']);
                $adjust_arr = [];
                foreach($adjusts as $adjust){
                    array_push($adjust_arr, $adjust->id);
                }
                return $query->whereIn('product_id', $adjust_arr);
            }
        })
        ->where(function ($query) use ($request){
            if($request->adjust_no){

                return $query->where('adjustment_no', $request->adjust_no);
            }
        })
        ->where(function ($query) use ($request){
            if($request->warehouse_id){
                $w_adjusts = Shelfnumber::where('shelf_numbers.warehouse_id', $request->warehouse_id)
                                ->join('products', 'products.shelf_number_id', '=', 'shelf_numbers.id')
                                ->join('adjustments', 'adjustments.product_id', '=', 'products.id')
                                ->get(['products.id']);
                $w_adjust_arr = [];
                foreach($w_adjusts as $w_adjust){
                    array_push($w_adjust_arr, $w_adjust->id);
                }
                return $query->whereIn('product_id', $w_adjust_arr);
            }
        })
        ->where(function ($query) use ($request){
            if($request->shelf_num_id){
                $s_adjusts = Product::where('products.shelf_number_id', $request->shelf_num_id)
                                ->join('adjustments', 'adjustments.product_id', '=', 'products.id')
                                ->get(['products.id']);
                $s_adjust_arr = [];
                foreach($s_adjusts as $s_adjust){
                    array_push($s_adjust_arr, $s_adjust->id);
                }
                return $query->whereIn('product_id', $s_adjust_arr);
            }
        })
        ->where(function ($query) use ($request){
            if($request->code_id){
                $c_adjusts = Code::where('codes.name', $request->code_id)
                                    ->join('products', 'products.code_id', '=', 'codes.id')
                                    ->join('adjustments', 'adjustments.product_id', '=', 'products.id')
                                    ->get(['products.id']);
                $c_adjust_arr = [];
                foreach($c_adjusts as $c_adjust){
                    array_push($c_adjust_arr, $c_adjust->id);
                }
                return $query->whereIn('product_id', $c_adjust_arr);
            }
        })
        ->where(function ($query) use ($request){
            if($request->brand_id){
                $b_adjusts = Code::where('codes.brand_id', $request->brand_id)
                                    ->join('products', 'products.code_id', '=', 'codes.id')
                                    ->join('adjustments', 'adjustments.product_id', '=', 'products.id')
                                    ->get(['products.id']);
                $b_adjust_arr = [];
                foreach($b_adjusts as $b_adjust){
                    array_push($b_adjust_arr, $b_adjust->id);
                }
                return $query->whereIn('product_id', $b_adjust_arr);
            }
        })
        ->where(function ($query) use ($request){
            if($request->commodity_id){
                $com_adjusts = Code::where('codes.commodity_id', $request->commodity_id)
                                    ->join('products', 'products.code_id', '=', 'codes.id')
                                    ->join('adjustments', 'adjustments.product_id', '=', 'products.id')
                                    ->get(['products.id']);
                $com_adjust_arr = [];
                foreach($com_adjusts as $com_adjust){
                    array_push($com_adjust_arr, $com_adjust->id);
                }
                return $query->whereIn('product_id', $com_adjust_arr);
            }
        })
        ->where(function ($query) use ($request){
            if($request->supplier_id){
                $sup_adjusts = Product::where('products.supplier_id', $request->supplier_id)
                                    ->join('adjustments', 'adjustments.product_id', '=', 'products.id')
                                    ->get(['products.id']);
                $sup_adjust_arr = [];
                foreach($sup_adjusts as $sup_adjust){
                    array_push($sup_adjust_arr, $sup_adjust->id);
                }
                return $query->whereIn('product_id', $sup_adjust_arr);
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_date){
                $from_date = date('Y-m-d', strtotime($request->from_date));

                return $query->where('adjustment_date', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d ', strtotime($request->to_date));
                
                return $query->where('adjustment_date', '<=',  $to_date);
            }
        })
        ->orderbydesc('adjustment_date')
        ->get();

        if ($request->has('export')) {
            $sort_adjustments = $adjustments->sort();
            return $this->export($sort_adjustments);
        }

        $warehouses = Warehouse::get();
        $suppliers = Supplier::get();
        $codes = Code::distinct()->get(['name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
        $shelfnums = ShelfNumber::get();

     
        return view('adjustments/index', ['warehouses' => $warehouses,
                                        'suppliers' => $suppliers,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'shelfnums' => $shelfnums,
                                        'adjustments' => $adjustments
                                        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_adjustment');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $warehouses = Warehouse::get();
        return view('adjustments/create', ['warehouses' => $warehouses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "warehouse_id" => 'required',
            "shelfnum_id" => 'required',
            "date" => 'required',
            "adjustment_no" => 'required|',
        ]); 
        
        if ($validator->fails())
        {
            return Redirect::back()->withInput()
                            ->with('error', 'Please try again.');
        }

        $newRequest = $request->except(['_token',
                                        'warehouse_id',
                                        'shelfnum_id',
                                        'adjustment_no',
                                        'date']);

        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'code_')) {
                $explode = explode("_",$key);
                $code = "code_".$explode[1];
                $brand = "brand_".$explode[1];
                $commodity = "commodity_".$explode[1];
                $qty = "qty_".$explode[1];
                $remarks = "remark_".$explode[1];
                $type = "type_".$explode[1];
                $vr_no = "vr_no_".$explode[1];

                //for new 
                $product = Product::find($request->$vr_no);
                
                if ($product) {
                    
                    $check_adjustment = Adjustment::where('product_id', $product->id)
                                        ->where('adjustment_no', $request->adjustment_no)
                                        ->first();

                    if (!$check_adjustment) {
                        if ($request->$type == 'sub') {
                            if ($product->balance_qty > $request->$qty) {
                                $product->update([
                                    'balance_qty' => $product->balance_qty - $request->$qty,
                                    'sub_adjustment' => $request->$qty,
                                ]);
                                $adjustment = Adjustment::create([
                                    'product_id' => $product->id,
                                    'code_id' => $product->code_id,
                                    'qty' => $request->$qty,
                                    'type' => $request->$type,
                                    'remarks' => $request->$remarks,
                                    'adjustment_no'=> $request->adjustment_no,
                                    'adjustment_date'=> $request->date,
                                    'created_by'=> Auth::user()->id,
                
                                ]);
                            }

                        }else{
                            $product->update([
                                'balance_qty' => $product->balance_qty + $request->$qty,
                                'add_adjustment' => $request->$qty,
                            ]);
                            $adjustment = Adjustment::create([
                                'product_id' => $product->id,
                                'code_id' => $product->code_id,
                                'qty' => $request->$qty,
                                'type' => $request->$type,
                                'remarks' => $request->$remarks,
                                'adjustment_no'=> $request->adjustment_no,
                                'adjustment_date'=> $request->date,
                                'created_by'=> Auth::user()->id,
            
                            ]);
                        }
                    }
                }
            }
        }
        return redirect()->route('adjustments.index')->with('success', 'Adjusment was created successfully');


    }

  
    public function edit(Request $request, Adjustment $adjustment)
    {
        $check = Auth::user()->hasPermissionTo('edit_adjustment');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $warehouses = Warehouse::get();

        # Edit IDs to show
        $adjustment = Adjustment::find($request->id);
        if ($adjustment) {
            # code...
            $product = Product::find($adjustment->product_id);
            $edit_shelfnum = ShelfNumber::where('shelf_numbers.id', $product->shelf_number_id)
                                            ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                            ->first([
                                                'shelf_numbers.id', 
                                                'shelves.name as shelfName', 
                                                'shelf_numbers.name as shelfnumName', 
                                                'shelf_numbers.warehouse_id'
                                            ]);
    
            $edit_warehouse = Warehouse::findOrFail(optional($edit_shelfnum)->warehouse_id);
    
     
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
            $choosen_adjustments = Adjustment::where('adjustments.adjustment_no', $adjustment->adjustment_no)
                                    ->join('products', 'products.id', '=', 'adjustments.product_id')
                                    ->join('codes', 'codes.id', '=', 'products.code_id')
                                    ->join('brands', 'brands.id', '=', 'codes.brand_id')
                                    ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                                    ->get(['products.id as product_id',
                                            'products.voucher_no', 
                                            'adjustments.id as adjustment_id', 
                                            'adjustments.adjustment_date', 

                                            'codes.name as code_name', 
                                            'codes.brand_id',
                                            'brands.name as brand_name',
                                            'codes.commodity_id',
                                            'commodities.name as commodity_name',
                                            'codes.usage',
                                            'codes.image',

                                            'adjustments.qty',
                                            'adjustments.type',
                                            'adjustments.remarks',
                                            'products.balance_qty',
                                            'products.transfer_id',
                                        ]);               

            # Edit Code IDs
            $codes = Product::where('products.shelf_number_id', $product->shelf_number_id)
                        ->join('codes','codes.id', '=', 'products.code_id')
                        ->get(['codes.name']);

            return view('adjustments/edit', ['warehouses' => $warehouses,
                                            'adjustment' => $adjustment,
                                            'codes' => $codes,
    
                                            'edit_warehouse' => $edit_warehouse,
                                            'edit_shelfnum' => $edit_shelfnum,
    
                                            'shelfnums' => $shelfnums,
                                            'choosen_adjustments' => $choosen_adjustments,
                                            ]);
        }else{
            return Redirect::back()->withInput()
                            ->with('error', 'Issue Not Found');
        }
    }

    
    public function update(Request $request, Adjustment $adjustment)
    {
        $validator = Validator::make($request->all(),[
            "date" => 'required',
        ]);

        if ($validator->fails())
        {
            return Redirect::back()->withInput()
                            ->with('error', 'Please try again.');
        }

        $old_adjustment = Adjustment::find($request->old_adjustment);

        $newRequest = $request->except(['_token',
                                        'warehouse_id',
                                        'shelfnum_id',
                                        'date',
                                        'adjustment_no',
                                        'old_adjustment'
                                    ]);
        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'adjustment_')) {
                $explode = explode("_",$key);
                $qty = "qty_".$explode[1];
                $adjustment_id = "adjustment_".$explode[1];

                #adjustment_id
                $adjustment = Adjustment::find($request->$adjustment_id);

                if ($adjustment) {

                    $adjustment_product = Product::find($adjustment->product_id);
                    if ($adjustment_product){

                        $adjustment_code = Code::find($adjustment_product->code_id);
                    }
                }
                if (!$request->$qty) {

                    $adjustment_history = AdjustmentHistory::create([

                        'shelf_number_id' => $adjustment_product->shelf_number_id,
                        'new_shelf_number_id' => $adjustment_product->shelf_number_id,

                        'product_id' => $adjustment->product_id,
                        'new_product_id' => $adjustment->product_id,

                        'code_id' => $adjustment_code->id,
                        'new_code_id' => $adjustment_code->id,

                        'adjustment_no' => $adjustment->adjustment_no,
                        'new_adjustment_no' => $adjustment->adjustment_no,

                        'qty' => $adjustment->qty,
                        'new_qty' => $adjustment->qty,

                        'type' => $adjustment->type,
                        'new_type' => $adjustment->type,
                        
                        'adjustment_date' => $adjustment->adjustment_date,
                        'new_adjustment_date' => $adjustment->adjustment_date,
            
                        'remarks' => $adjustment->remarks,
                        'new_remarks' => $adjustment->remarks,
            
                        'method' => "delete",
                        'created_by' => Auth::user()->id,
                    ]);

                    if ($adjustment->type == 'sub') {
                    # code...
                        $adjustment_product->update([
                            'balance_qty' => $adjustment_product->balance_qty + $adjustment->qty,
                            'sub_adjustment' => $adjustment_product->sub_adjustment - $adjustment->qty,
                        ]);

                    }else{
                        $adjustment_product->update([
                            'balance_qty' => $adjustment_product->balance_qty - $adjustment->qty,
                            'add_adjustment' =>  $adjustment_product->add_adjustment - $adjustment->qty,
                        ]);
                    }

                    $adjustment->delete();
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
                $type = "type_".$explode[1];
                $remarks = "remark_".$explode[1];
                $vr_no = "vr_no_".$explode[1];
                $adjustment_id = "adjustment_".$explode[1];

                #adjustment_id
                $adjustment = Adjustment::find($request->$adjustment_id);
                $new_product = Product::find($request->$vr_no);

                if ($adjustment) {

                    $adjustment_product = Product::find($adjustment->product_id);
                    $adjustment_code = Code::find($adjustment_product->code_id);
                    
                    if ($adjustment_product && $new_product){
                        #check if changed product in existing adjustment 
                        $check_adjustment = Adjustment::where('adjustment_no', $request->adjustment_no)
                                            ->where('product_id', $new_product->id)
                                            ->where('id', '!=' , $adjustment->id)
                                            ->first();
                    }
                }

                #update return product
                if ($adjustment && $request->$code && !$check_adjustment) {
                  
                    if ($request->shelfnum_id) {
                        # code...
                        if ($request->shelfnum_id != $adjustment_product->shelf_number_id || 
                            $request->adjustment_no != $adjustment->adjustment_no || 
                            $request->date != $adjustment->adjustment_date || 
    
                            $request->$vr_no != $adjustment->product_id || 
                            $request->$qty != $adjustment->qty) {
    
                                $adjustment_history = AdjustmentHistory::create([
    
                                    'shelf_number_id' => $adjustment_product->shelf_number_id,
                                    'new_shelf_number_id' => $request->shelfnum_id,
            
                                    'product_id' => $adjustment->product_id,
                                    'new_product_id' => $request->$vr_no,
            
                                    'code_id' => $adjustment_code->id,
                                    'new_code_id' => $new_product->code_id,
            
                                    'adjustment_no' => $adjustment->adjustment_no,
                                    'new_adjustment_no' => $request->adjustment_no,
            
                                    'qty' => $adjustment->qty,
                                    'new_qty' => $request->$qty,
    
                                    'type' => $adjustment->type,
                                    'new_type' => $request->$type,
                            
            
                                    'adjustment_date' => $adjustment->adjustment_date,
                                    'new_adjustment_date' => $request->date,
                        
                                    'remarks' => $adjustment->remarks,
                                    'new_remarks' => $request->$remarks,
                        
                                    'method' => "update",
                                    'created_by' => Auth::user()->id,
                                ]);
                            
                        }
    
                    } else {

                        if ($request->$vr_no != $adjustment->product_id || 
                            $request->date != $adjustment->adjustment_date || 
                            $request->$qty != $adjustment->qty) {

                            $adjustment_history = AdjustmentHistory::create([

                                'shelf_number_id' => $adjustment_product->shelf_number_id,
                                'new_shelf_number_id' => $adjustment_product->shelf_number_id,
        
                                'product_id' => $adjustment->product_id,
                                'new_product_id' => $request->$vr_no,
        
                                'code_id' => $adjustment_code->id,
                                'new_code_id' => $new_product->code_id,
        
                                'adjustment_no' => $adjustment->adjustment_no,
                                'new_adjustment_no' => $adjustment->adjustment_no,
        
                                'qty' => $adjustment->qty,
                                'new_qty' => $request->$qty,

                                'type' => $adjustment->type,
                                'new_type' => $adjustment->type,
                        
        
                                'adjustment_date' => $adjustment->adjustment_date,
                                'new_adjustment_date' => $request->date,
                    
                                'remarks' => $adjustment->remarks,
                                'new_remarks' => $request->$remarks,
                    
                                'method' => "update",
                                'created_by' => Auth::user()->id,
                            ]);
                        
                        }
                    }

                    if ($adjustment->type == 'sub') {
                        # code...
                        $adjustment_product->update([
                            'balance_qty' => $adjustment_product->balance_qty + $adjustment->qty,
                            'sub_adjustment' => $adjustment_product->sub_adjustment - $adjustment->qty,
                        ]);

                    }else{
                        $adjustment_product->update([
                            'balance_qty' => $adjustment_product->balance_qty - $adjustment->qty,
                            'add_adjustment' =>  $adjustment_product->add_adjustment - $adjustment->qty,
                        ]);
                    }

                    #if same product id, update OR reduce to old- add to new
                    if ($adjustment->product_id == $request->$vr_no) {

                        if ($request->$type == 'sub') {
                            # code...
                            $adjustment_product->update([
                                'balance_qty' => $adjustment_product->balance_qty - $request->$qty,
                                'sub_adjustment' => $adjustment_product->sub_adjustment + $request->$qty,
                            ]);
    
                        }else{
                            $adjustment_product->update([
                                'balance_qty' => $adjustment_product->balance_qty + $request->$qty,
                                'add_adjustment' =>  $adjustment_product->add_adjustment + $request->$qty,
                            ]);
                        }

                    }else{

                        if ($request->$type == 'sub') {
                            # code...
                            $new_product->update([
                                'balance_qty' => $new_product->balance_qty - $request->$qty,
                                'sub_adjustment' => $new_product->sub_adjustment + $request->$qty,
                            ]);
    
                        }else{
                            $new_product->update([
                                'balance_qty' => $new_product->balance_qty + $request->$qty,
                                'add_adjustment' =>  $new_product->add_adjustment + $request->$qty,
                            ]);
                        }
                    }


                    if ($request->shelfnum_id) {
                        $adjustment->update([
                            'adjustment_no'=> $request->adjustment_no,
                            'qty'=> $request->$qty,
                            'adjustment_date'=> $request->date,
                            'type' => $request->$type,
                            'remarks' => $request->$remarks,
                            'product_id' => $new_product->id,
                            'code_id' => $new_product->code_id,
                            
                            'updated_by'=> Auth::user()->id,
                        ]);
                    }else{
                        $adjustment->update([
                            'qty'=> $request->$qty,
                            'adjustment_date'=> $request->date,
                            'remarks' => $request->$remarks,
                            'product_id' => $new_product->id,
                            'code_id' => $new_product->code_id,
                            'type' => $request->$type,
                            'updated_by'=> Auth::user()->id,
                        ]);
                    }
                         
                }else if($adjustment && !$request->$code ){
                    if ( $request->date != $adjustment->adjustment_date || 
                            $request->$qty != $adjustment->qty) {

                            $adjustment_history = AdjustmentHistory::create([

                                'shelf_number_id' => $adjustment_product->shelf_number_id,
                                'new_shelf_number_id' => $adjustment_product->shelf_number_id,
        
                                'product_id' => $adjustment->product_id,
                                'new_product_id' => $adjustment->product_id,
        
                                'code_id' => $adjustment_code->id,
                                'new_code_id' => $adjustment_code->id,
        
                                'adjustment_no' => $adjustment->adjustment_no,
                                'new_adjustment_no' => $adjustment->adjustment_no,
        
                                'qty' => $adjustment->qty,
                                'new_qty' => $request->$qty,

                                'type' => $adjustment->type,
                                'new_type' => $adjustment->type,
                        
        
                                'adjustment_date' => $adjustment->adjustment_date,
                                'new_adjustment_date' => $request->date,
                    
                                'remarks' => $adjustment->remarks,
                                'new_remarks' => $request->$remarks,
                    
                                'method' => "update",
                                'created_by' => Auth::user()->id,
                            ]);
                        
                        }

                        if ($adjustment->type == 'sub') {
                            $adjustment_product->update([
                                'balance_qty' => ($adjustment_product->balance_qty + $adjustment->qty) - $request->$qty,
                                'sub_adjustment' => ($adjustment_product->sub_adjustment - $adjustment->qty) + $request->$qty,
                            ]);
    
                        }else{
                            $adjustment_product->update([
                                'balance_qty' => ($adjustment_product->balance_qty - $adjustment->qty) + $request->$qty,
                                'add_adjustment' =>  ($adjustment_product->add_adjustment - $adjustment->qty)  + $request->$qty,
                            ]);
                        }

                        $adjustment->update([
                            'qty'=> $request->$qty,
                            'adjustment_date'=> $request->date,
                            'remarks' => $request->$remarks,
                            
                            'updated_by'=> Auth::user()->id,
                        ]);

                }else if(!$adjustment && $request->$code){

                    if ($request->shelfnum_id) {
                        #new return update
                        if ($new_product) {
                            # same return no, product exist?
                            $check_adjustments = Adjustment::where('product_id', $new_product->id)
                                                ->where('adjustment_no', $request->adjustment_no)
                                                ->first();
    
                            if (!$check_adjustments) {
                              
                                $adjustment = Adjustment::create([
                                    'product_id' => $new_product->id,
                                    'code_id' => $new_product->code_id,
                                    'qty' => $request->$qty,
                                    'type' => $request->$type,
                                    'remarks' => $request->$remarks,
                                    'adjustment_no'=> $request->adjustment_no,
                                    'adjustment_date'=> $request->date,
                                    'created_by'=> Auth::user()->id,
                
                                ]);
                                
                            
                                if ($request->$type == 'sub' && $new_product->balance_qty >= $request->$qty) {
                                # code...
                                    $new_product->update([
                                        'balance_qty' => $new_product->balance_qty - $request->$qty,
                                        'sub_adjustment' => $request->$qty,
                                    ]);
    
                                }else if($request->$type == 'add'){
                                    $new_product->update([
                                        'balance_qty' => $new_product->balance_qty + $request->$qty,
                                        'add_adjustment' => $request->$qty,
                                    ]);
                                } 
                            }
        
                        }
                    
                    }else{
                        if ($new_product) {
                            # same return no, product exist?
                            $check_adjustments = Adjustment::where('product_id', $new_product->id)
                                                ->where('adjustment_no', $old_adjustment->adjustment_no)
                                                ->first();
    
                            if (!$check_adjustments) {
                              
                                $adjustment = Adjustment::create([
                                    'product_id' => $new_product->id,
                                    'code_id' => $new_product->code_id,
                                    'qty' => $request->$qty,
                                    'type' => $request->$type,
                                    'remarks' => $request->$remarks,
                                    'adjustment_no'=> $old_adjustment->adjustment_no,
                                    'adjustment_date'=> $request->date,
                                    'created_by'=> Auth::user()->id,
                
                                ]);
                                
                            
                                if ($request->$type == 'sub' && $new_product->balance_qty >= $request->$qty) {
                                # code...
                                    $new_product->update([
                                        'balance_qty' => $new_product->balance_qty - $request->$qty,
                                        'sub_adjustment' => $request->$qty,
                                    ]);
    
                                }else if($request->$type == 'add'){
                                    $new_product->update([
                                        'balance_qty' => $new_product->balance_qty + $request->$qty,
                                        'add_adjustment' => $request->$qty,
                                    ]);
                                }
                            }
        
                        }
                    }
                     
                }
                
        }};


        return redirect()->route('adjustments.index')->with('success', 'Adjustment was successfully updated');
    }

    public function history()
    {
        $check = Auth::user()->hasPermissionTo('adjustment_history');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $histories = AdjustmentHistory::orderby('id', 'desc')->get();;
        return view('adjustments/history', ['histories' => $histories ]);
    }

    //export excel
    public function export($sort_adjustments)
    {
        $check = Auth::user()->hasPermissionTo('export_adjustment');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        for ($i = 0; $i < count($sort_adjustments); $i++) {
            # code...
            $adjustment = $sort_adjustments[$i];
            $product = Product::find($adjustment->product_id); 
            $unit = Unit::find(optional($product)->unit_id); 

            $shelf_number = ShelfNumber::find(optional($product)->shelf_number_id); 
            $warehouse = Warehouse::find(optional($shelf_number)->warehouse_id); 
            $supplier = Supplier::find($product->supplier_id); 

            $code = Code::find($product->code_id); 
            $brand = Brand::find(optional($code)->brand_id); 
            $commodity = Brand::find(optional($code)->commodity_id); 

            $c_user = User::find($adjustment->created_by); 
            $u_user = User::find($adjustment->updated_by); 

            $sort_adjustments[$i] = [
                "No" =>  count($sort_adjustments) - $i,
                "Date" => $adjustment->adjustment_date,
                'Warehouse' => optional($warehouse)->name,
                "Shelf No" => optional($shelf_number)->name,
                "Supplier" => optional($supplier)->name,

                "Code" => optional($code)->name,
                "Brand" => optional($brand)->name,
                "Commodity" => optional($commodity)->name,
                "Unit" => optional($unit)->name,
                "Qty" => optional($adjustment)->qty,
                "Remarks" => $adjustment->remarks,
                "Voucher No" => optional($product)->voucher_no,

                "Create By" => optional($c_user)->name,
                "Updated By" => optional($u_user)->name,
                
                "Created At" => $adjustment->created_at,
                "Updated At" => $adjustment->updated_at,
            ];
        }
        $export = new AdjustmentsExport([$sort_adjustments]);

        return Excel::download($export, 'adjustments ' . date("Y-m-d") . '.xlsx');
    }

}
