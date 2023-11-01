<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\TransferHistory; 
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
use App\Exports\transfersExport;
use Redirect;
use Auth;
use Validator;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('transfer_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $transfers = Transfer::where(function($query) use ($request){
            if($request->transfer_id){
                return $query->where('id', $request->transfer_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->transfer_no){
                return $query->where('transfer_no', $request->transfer_no);
            }
        })
        ->where(function ($query) use ($request){
            if($request->vr_no){
                $voucher_nos = Product::where('voucher_no',$request->vr_no)
                                                ->get();
                foreach($voucher_nos as $voucher_no){
                    return $query->where('product_id', $voucher_no->id);
                }
                
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_warehouse_id){
                $shelf_nos = ShelfNumber::where('warehouse_id',$request->from_warehouse_id)
                                                ->get();
                foreach($shelf_nos as $shelf_no){
                    return $query->where('from_shelf_number_id', $shelf_no->id);
                }
                
            }
        })
        ->where(function ($query) use ($request){
            if($request->to_warehouse_id){
                $shelf_nos = ShelfNumber::where('warehouse_id',$request->to_warehouse_id)
                                                ->get();
                foreach($shelf_nos as $shelf_no){
                    return $query->where('to_shelf_number_id', $shelf_no->id);
                }
                
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_shelf_num_id){
                return $query->where('from_shelf_number_id', $request->from_shelf_num_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->to_shelf_num_id){
                return $query->where('to_shelf_number_id', $request->to_shelf_num_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->code_id){
                return $query->where('code_id', $request->code_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->brand_id){
                $brand_codes = Code::where('brand_id',$request->brand_id)->get();
                //array of codes under brand
                $b_items = [];
                foreach($brand_codes as $b_code){
                    array_push($b_items,$b_code->id);
                }
                return $query->whereIn('code_id', $b_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->commodity_id){
                $com_codes = Code::where('commodity_id',$request->commodity_id)
                                                ->get();
                //array of codes under commodity
                $c_items = [];
                foreach($com_codes as $c_code){
                    array_push($c_items,$c_code->id);
                }
                return $query->whereIn('code_id', $c_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_date){
                $from_date = date('Y-m-d', strtotime($request->from_date));

                return $query->where('transfer_date', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d', strtotime($request->to_date));
                
                return $query->where('transfer_date', '<=',  $to_date);
            }
        })
        ->orderbydesc('transfer_date')
        ->get();
        

        $warehouses = Warehouse::get();
        $codes = Code::get();
        $brands = Brand::get();
        $commodities = Commodity::get();
        $shelfnums = ShelfNumber::get();

        $vr_nos = Product::distinct()->get(['voucher_no']);

        if ($request->has('export')) {
            return $this->export($transfers);
        }

        return view('transfers/index', ['warehouses' => $warehouses,
                                        'vr_nos' => $vr_nos,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'shelfnums' => $shelfnums,
                                        'transfers' => $transfers
                                        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('transfer_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $warehouses = Warehouse::get();
        return view('transfers/create', ['warehouses' => $warehouses]);
    }

    public function getCode(Request $request)
    {
        if ($request->supplier) {
            $codes = Product::where('products.shelf_number_id', $request->shelfnum_id)
                        ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
                        ->where('products.balance_qty', '>', 0)
                        ->where('suppliers.name',  $request->supplier)
                        ->join('codes', 'codes.id', '=', 'products.code_id')
                        ->distinct()
                        ->get(['codes.name']);
        }else{
            $codes = Product::where('products.shelf_number_id', $request->shelfnum_id)
                        ->where('products.balance_qty', '>', 0)
                        ->join('codes', 'codes.id', '=', 'products.code_id')
                        ->distinct()
                        ->get(['codes.name']);
        }
                        
        return response()->json(['codes' => $codes ]);
    }

    public function getVr(Request $request)
    {
        $code = Code::where('name', $request->code_name)
                        ->where('brand_id', $request->brand_id)
                        ->where('commodity_id', $request->commodity_id)
                        ->first();
        if ($code) {
            $vr_nos = Product::where('products.shelf_number_id', $request->shelfnum_id)
                            ->where('products.code_id', '=', $code->id)
                            ->where('products.balance_qty', '>', 0)
                            ->join("codes", 'products.code_id', '=', "codes.id")
                            ->leftJoin('transfers', 'products.transfer_id', '=', 'transfers.id')
                            ->get(['products.id', 
                                'products.voucher_no', 
                                'transfers.transfer_no', 
                                'products.balance_qty',
                                'products.mr_qty',
                                'products.mrr_qty',
                                'codes.image',
                                'codes.usage'
                            ]);
            return response()->json(['vr_nos' => $vr_nos ]);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "from_warehouse_id" => 'required',
            "from_shelfnum_id" => 'required',
            "date" => 'required',
            "to_warehouse_id" => 'required',
            "to_shelfnum_id" => 'required',
            "transfer_no" => 'required|unique:transfers,transfer_no',
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('transfers.index')->with('error', 'Please try again.');
        }
       
        $newRequest = $request->except(['_token',
                                    'from_warehouse_id',
                                    'from_shelfnum_id',
                                    'to_warehouse_id',
                                    'to_shelfnum_id',
                                    'transfer_no',
                                    'date']);

        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'code_')) {
                $explode = explode("_",$key);
                $code = "code_".$explode[1];
                $brand = "brand_".$explode[1];
                $commodity = "commodity_".$explode[1];
                $qty = "qty_".$explode[1];
                $remarks = "remark_".$explode[1];
                $vr_no = "vr_no_".$explode[1];

                //for new 
                $product = Product::find($request->$vr_no);
                
                if ($product) {
                    #find code id
                    $code_id = Code::where('name', $request->$code) 
                                    ->where('brand_id', $request->$brand)
                                    ->where('commodity_id', $request->$commodity)
                                    ->first();

                    $check_to_product = Transfer::where('code_id', $code_id->id)
                                                ->where('transfer_no', $request->transfer_no)
                                                ->where('product_id', $product->id)
                                                ->first();
                    if (!$check_to_product) {
                       
                        do {
                            #random number
                            $number = mt_rand(100000000, 999999999);
                            $check_barcode = Product::where('barcode', $number)->first();

                        } while ($check_barcode);

                        $transfer_product = Product::create([
                            'code_id' => $product->code_id,
                            'unit_id' => $product->unit_id,
                            'type' => 'transfer',
                            'barcode' => $number,
                            'received_qty' => $request->$qty,
                            'balance_qty' => $request->$qty,
        
                            'remarks' => $request->$remarks,
                            'shelf_number_id'=> $request->to_shelfnum_id,
                            'received_date'=> $request->date,
                            'voucher_no'=> $product->voucher_no,
        
                            'supplier_id'=> $product->supplier_id,
                            'created_by'=> Auth::user()->id,
        
                        ]);
        
                        $transfer = Transfer::create([
                            'code_id' => $product->code_id,
                            'transfer_qty' => $request->$qty,
                            'remarks' => $request->$remarks,
                            'product_id' => $product->id,
        
                            'transfer_date'=> $request->date,
                            'transfer_no'=> $request->transfer_no,
        
                            'from_shelf_number_id'=> $request->from_shelfnum_id,
                            'to_shelf_number_id'=> $request->to_shelfnum_id,
                            'created_by'=> Auth::user()->id,
        
                        ]);
                        
                        if ($product->balance_qty >= $request->$qty) {
                            # code...
                            $product->update([
                                'balance_qty' => $product->balance_qty - $request->$qty,
                                'transfer_qty' => $request->$qty,
                            ]);
        
                            $transfer_product->update([
                                'transfer_id' => $transfer->id,
                            ]);
        
                        }else{
                            return redirect()->route('transfers.index')->with('error', 'Please Try again');
                        }
                    } else {
                        return Redirect::back()->withInput()
                                    ->with('error', "$code_id->name is already in Warehouse.");
                    }
                    
                }
            }
        }
        return redirect()->route('transfers.index')->with('success', 'transfer was created successfully');

    }


    public function edit(Request $request, transfer $transfer)
    {
        $check = Auth::user()->hasPermissionTo('edit_transfer');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $warehouses = Warehouse::get(); 
        $transfer = Transfer::findOrFail($request->id);
        //Edit from IDs to show
        $edit_from_shelfnum = ShelfNumber::where('shelf_numbers.id', $transfer->from_shelf_number_id)
                                            ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                            ->first([
                                                'shelf_numbers.id', 
                                                'shelves.name as shelfName', 
                                                'shelf_numbers.name as shelfnumName', 
                                                'shelf_numbers.warehouse_id'
                                            ]);
                                            
        $edit_from_warehouse = Warehouse::findOrFail(optional($edit_from_shelfnum)->warehouse_id);
        $from_shelfnums = ShelfNumber::where('shelf_numbers.warehouse_id', optional($edit_from_warehouse)->id)
                                    ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                    ->get([
                                        'shelf_numbers.id', 
                                        'shelves.name as shelfName', 
                                        'shelf_numbers.name as shelfnumName', 
                                        'shelf_numbers.warehouse_id'
                                    ]);
        
        //Edit to IDs to show
        $edit_to_shelfnum = ShelfNumber::where('shelf_numbers.id', $transfer->to_shelf_number_id)
                                            ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                            ->first([
                                                'shelf_numbers.id', 
                                                'shelves.name as shelfName', 
                                                'shelf_numbers.name as shelfnumName', 
                                                'shelf_numbers.warehouse_id'
                                            ]);
        $edit_to_warehouse = Warehouse::findOrFail(optional($edit_to_shelfnum)->warehouse_id);
        $to_shelfnums = ShelfNumber::where('shelf_numbers.warehouse_id', optional($edit_to_warehouse)->id)
                            ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                            ->get([
                                'shelf_numbers.id', 
                                'shelves.name as shelfName', 
                                'shelf_numbers.name as shelfnumName', 
                                'shelf_numbers.warehouse_id'
                            ]);

        //code list under shelfnumberID &transfer_no 
        $choosen_transfers = Transfer::where('transfers.transfer_no', $transfer->transfer_no)
                            ->where('transfers.from_shelf_number_id', $transfer->from_shelf_number_id)
                            ->where('transfers.to_shelf_number_id', $transfer->to_shelf_number_id)
                            ->join('products', 'products.transfer_id', '=', 'transfers.id')
                            ->join('codes', 'codes.id', '=', 'products.code_id')
                            ->join('brands', 'brands.id', '=', 'codes.brand_id')
                            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                            ->get(['products.id', 
                                    'products.transfer_id', 
                                    'products.transfer_qty as p_transfer_qty',
                                    'products.voucher_no',
                                    'products.add_adjustment',
                                    'products.sub_adjustment',
                                    'products.voucher_no',

                                    'products.transfer_qty',
                                    'products.supplier_return_qty',
                                    'products.mr_qty',
                                    'codes.name as code_name', 
                                    'codes.brand_id',

                                    'codes.usage',
                                    'codes.image',
                                    'codes.commodity_id',
                                    
                                    'brands.name as brand_name',
                                    'commodities.name as commodity_name',


                                    'transfers.remarks',
                                    'transfers.transfer_qty',
                                    'transfers.transfer_no',
                                    'transfers.product_id',
                                ]);

        //Edit Code IDs
        $codes = Product::where('products.shelf_number_id', $transfer->from_shelf_number_id)
                        ->join('codes','codes.id', '=', 'products.code_id')
                        ->distinct()
                        ->get(['codes.name']);

        return view('transfers/edit', ['warehouses' => $warehouses,
                                        'transfer' => $transfer,

                                        'choosen_transfers' => $choosen_transfers,
                                        'edit_from_shelfnum' => $edit_from_shelfnum,
                                        'edit_from_warehouse' => $edit_from_warehouse,
                                        'from_shelfnums' => $from_shelfnums,

                                        'edit_to_shelfnum' => $edit_to_shelfnum,
                                        'edit_to_warehouse' => $edit_to_warehouse,
                                        'to_shelfnums' => $to_shelfnums,

                                        'codes' => $codes,
                                        'qty' => $transfer->transfer_qty,
                                        
                                        ]);
    }

  
    public function update(Request $request, transfer $transfer)
    {
        if ($request->from_warehouse_id) {
            $validator = Validator::make($request->all(),[
                "from_warehouse_id" => 'required',
                "from_shelfnum_id" => 'required',
                "date" => 'required',
                "to_warehouse_id" => 'required',
                "to_shelfnum_id" => 'required',
                "transfer_no" => 'required',
            ]);
        }else{
            $validator = Validator::make($request->all(),[
                "date" => 'required',
            ]);
        }

        if ($validator->fails())
        {
            return Redirect::back()->withInput()
                            ->with('error', 'Please try again.');
        }

        $old_transfer = Transfer::find($request->old_transfer);

        $newRequest = $request->except(['_token',
                                        'from_warehouse_id',
                                        'from_shelfnum_id',
                                        'to_warehouse_id',
                                        'to_shelfnum_id',
                                        'date',
                                        'transfer_no'
                                    ]);
                                    
        foreach ($newRequest as $key => $value){
            
            if (str_contains($key, 'transfer_')) {
                $explode = explode("_",$key);
                $transfer_id = "transfer_".$explode[1];
                $qty = "qty_".$explode[1];

                $transfer = Transfer::find($request->$transfer_id);
                if ($transfer) {
                    #from 
                    $from_transfer_product = Product::find($transfer->product_id);
                    #find to transferred product
                    $to_transfer_product = Product::where('transfer_id', $request->$transfer_id)
                                                ->first();;
                }

                if (!$request->$qty) {
                    $from_transfer_product->update([
                        'transfer_qty' =>  $from_transfer_product->transfer_qty - $transfer->transfer_qty,
                        'balance_qty' => $from_transfer_product->balance_qty + $transfer->transfer_qty,
                    ]);
                    
                    $t_history = TransferHistory::create([
                        'from_shelf_number_id' => $transfer->from_shelf_number_id,
                        'new_from_shelf_number_id' => $transfer->from_shelf_number_id,
                        'to_shelf_number_id' => $transfer->to_shelf_number_id,
                        'new_to_shelf_number_id' => $transfer->to_shelf_number_id,

                        'transfer_date' => $transfer->transfer_date,
                        'new_transfer_date' => $transfer->transfer_date,

                        'transfer_no' => $transfer->transfer_no,
                        'new_transfer_no' => $transfer->transfer_no,
            
                        'method' => "delete",
                        'created_by' => Auth::user()->id,
                        
                        'code_id' => $transfer->code_id,
                        'new_code_id' => $transfer->code_id,
                        
                        #unclear
                        'product_id' => $to_transfer_product->id,
                        'new_product_id' => $to_transfer_product->id,

                        'transfer_qty' => $transfer->transfer_qty,
                        'new_transfer_qty' => $transfer->transfer_qty,
            
                        'remarks' => $transfer->remarks,
                        'new_remarks' => $transfer->remarks,
            
                        'method' => "delete",
                        'created_by' => Auth::user()->id,
                    ]);

                    $to_transfer_product->delete();
                    $transfer->delete();
                }

        }}

        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'qty_')) {
                $explode = explode("_",$key);
                $code = "code_".$explode[1];
                $brand = "brand_".$explode[1];
                $commodity = "commodity_".$explode[1];
                $qty = "qty_".$explode[1];
                $vr_no = "vr_no_".$explode[1];
                $remarks = "remark_".$explode[1];
                $transfer_id = "transfer_".$explode[1];

                $transfer = Transfer::find($request->$transfer_id);
                if ($transfer) {
                    #from 
                    $from_transfer_product = Product::find($transfer->product_id);
                }

                
                # find to transferred product
                $to_transfer_product = Product::where('transfer_id', $request->$transfer_id)
                                        ->first();

                #if from trasnferred product id is new or same?
                $check_from_product = Product::find($request->$vr_no);

                if ($check_from_product && $check_from_product->transfer_id != null ){
                    #product throught transfer.product_id
                    $new_from_transfer_product = Product::where('products.id', $request->$vr_no)
                                                ->join('transfers', 'transfers.id', '=' , 'products.transfer_id')
                                                ->first(['transfers.product_id as id', 
                                                        'products.transfer_qty',
                                                        'products.balance_qty',
                                                        'products.voucher_no',
                                                        'products.supplier_id',
                                                    ]);
                }else{
                    $new_from_transfer_product = Product::where('products.id', $request->$vr_no)
                                            ->first();
                }

                #find code id
                $code_id = Code::where('name', $request->$code) 
                                ->where('brand_id', $request->$brand)
                                ->where('commodity_id', $request->$commodity)
                                ->first();
                
                if ($check_from_product && $code_id && $transfer ) {
                    #if to transferred product is already exist?
                    $check_to_product = Transfer::where('code_id', $code_id->id)
                                                ->where('transfer_no', $transfer->transfer_no)
                                                ->where('id', '!=', $transfer->id)
                                                ->where('product_id', $transfer->product_id)
                                                ->first();
                    if ($check_to_product) {
                        return Redirect::back()->withInput()
                                    ->with('error', "$code_id->name ($check_to_product->transfer_no) is already in Warehouse.");
                    }
                }
                
                #update no transfer product
                if ($transfer && $request->$code ) {

                    if ($request->from_shelfnum_id) {
                        if ($request->from_shelfnum_id != $transfer->from_shelf_number_id || 
                            $request->to_shelfnum_id != $transfer->to_shelf_number_id || 
                            $request->transfer_no != $transfer->transfer_no || 
                            $request->date != $transfer->transfer_date || 

                            $request->$vr_no != $to_transfer_product->id || 
                            $request->$code != $code_id->name || 
                            $request->$qty != $transfer->transfer_qty) {

                            $t_history = TransferHistory::create([

                                'from_shelf_number_id' => $transfer->from_shelf_number_id,
                                'new_from_shelf_number_id' => $request->from_shelfnum_id,
                                'to_shelf_number_id' => $transfer->to_shelf_number_id,
                                'new_to_shelf_number_id' => $request->to_shelfnum_id,

                                'transfer_date' => $transfer->transfer_date,
                                'new_transfer_date' => $request->date,

                                'transfer_no' => $transfer->transfer_no,
                                'new_transfer_no' => $request->transfer_no,
                                
                                'code_id' => $transfer->code_id,
                                'new_code_id' => $code_id->id,
                                
                                #unclear
                                'product_id' => $to_transfer_product->id,
                                'new_product_id' => $request->$vr_no,

                                'transfer_qty' => $transfer->transfer_qty,
                                'new_transfer_qty' => $request->$qty,
                    
                                'remarks' => $transfer->remarks,
                                'new_remarks' => $request->$remarks,
                    
                                'method' => "update",
                                'created_by' => Auth::user()->id,
                            ]);
                        }

                        #if same product id, update OR reduce to old- add to new
                        if ($new_from_transfer_product->id && 
                            $new_from_transfer_product->id == $from_transfer_product->id) {
                            
                            $from_transfer_product->update([
                                'transfer_qty' =>  ($from_transfer_product->transfer_qty- $transfer->transfer_qty) + $request->$qty,
                                'balance_qty' => ($from_transfer_product->balance_qty+ $transfer->transfer_qty)- $request->$qty,
                            ]);
                                
                                
                        } else {
                            $from_transfer_product->update([
                                'transfer_qty' =>  $from_transfer_product->transfer_qty - $transfer->transfer_qty,
                                'balance_qty' => $from_transfer_product->balance_qty+ $transfer->transfer_qty,
                            ]);
                            // dd($new_from_transfer_product->transfer_qty + $request->$qty);
                            $new_from_transfer_product->update([
                                'transfer_qty' =>  $new_from_transfer_product->transfer_qty + $request->$qty,
                                'balance_qty' => $new_from_transfer_product->balance_qty - $request->$qty,
                            ]);
                        }

                        $to_transfer_product->update([
                                'shelf_number_id'=> $request->to_shelfnum_id,
                                'code_id' => $code_id->id,

                                'received_qty' => $request->$qty,
                                'balance_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'received_date'=> $request->date,

                                'voucher_no'=> $new_from_transfer_product->voucher_no,
                                'supplier_id'=> $new_from_transfer_product->supplier_id,
                                
                                'updated_by' => Auth::user()->id
                            ]);

                            $transfer->update([
                                'from_shelf_number_id'=> $request->from_shelfnum_id,
                                'to_shelf_number_id'=> $request->to_shelfnum_id,

                                'code_id' => $code_id->id,
                                'product_id' => $new_from_transfer_product->id,
                                
                                'transfer_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'transfer_date'=> $request->date,

                                'transfer_no'=> $request->transfer_no,
                                'updated_by' => Auth::user()->id
                            ]);

                            
                    }else{

                        if ($request->date != $transfer->transfer_date || 

                            $request->$vr_no != $to_transfer_product->id || 
                            $request->$code != $code_id->name || 
                            $request->$qty != $transfer->transfer_qty) {

                            $t_history = TransferHistory::create([

                                'from_shelf_number_id' => $transfer->from_shelf_number_id,
                                'new_from_shelf_number_id' => $transfer->new_from_shelf_number_id,
                                'to_shelf_number_id' => $transfer->to_shelf_number_id,
                                'new_to_shelf_number_id' => $transfer->new_to_shelf_number_id,

                                'transfer_date' => $transfer->transfer_date,
                                'new_transfer_date' => $request->date,

                                'transfer_no' => $transfer->transfer_no,
                                'new_transfer_no' => $transfer->transfer_no,
                                
                                'code_id' => $transfer->code_id,
                                'new_code_id' => $code_id->id,
                                
                                #unclear
                                'product_id' => $to_transfer_product->id,
                                'new_product_id' => $request->$vr_no,

                                'transfer_qty' => $transfer->transfer_qty,
                                'new_transfer_qty' => $request->$qty,
                    
                                'remarks' => $transfer->remarks,
                                'new_remarks' => $request->$remarks,
                    
                                'method' => "update",
                                'created_by' => Auth::user()->id,
                            ]);
                        }

                        
                        #if same product id, update OR reduce to old- add to new
                        if ($new_from_transfer_product->id && 
                            $new_from_transfer_product->id == $from_transfer_product->id) {
                            
                            $from_transfer_product->update([
                                'transfer_qty' =>  ($from_transfer_product->transfer_qty- $transfer->transfer_qty) + $request->$qty,
                                'balance_qty' => ($from_transfer_product->balance_qty+ $transfer->transfer_qty)- $request->$qty,
                            ]);
                                
                                
                        } else {
                            $from_transfer_product->update([
                                'transfer_qty' =>  $from_transfer_product->transfer_qty - $transfer->transfer_qty,
                                'balance_qty' => $from_transfer_product->balance_qty+ $transfer->transfer_qty,
                            ]);
                            // dd($new_from_transfer_product->transfer_qty + $request->$qty);
                            $new_from_transfer_product->update([
                                'transfer_qty' =>  $new_from_transfer_product->transfer_qty + $request->$qty,
                                'balance_qty' => $new_from_transfer_product->balance_qty - $request->$qty,
                            ]);
                        }


                        $to_transfer_product->update([
                            'code_id' => $code_id->id,

                            'received_qty' => $request->$qty,
                            'balance_qty' => $request->$qty,
                            'remarks' => $request->$remarks,
                            'received_date'=> $request->date,

                            'voucher_no'=> $new_from_transfer_product->voucher_no,
                            'supplier_id'=> $new_from_transfer_product->supplier_id,
                            
                            'updated_by' => Auth::user()->id
                        ]);

                        $transfer->update([
                            'code_id' => $code_id->id,
                            'product_id' => $new_from_transfer_product->id,
                            
                            'transfer_qty' => $request->$qty,
                            'remarks' => $request->$remarks,
                            'transfer_date'=> $request->date,

                            'updated_by' => Auth::user()->id
                        ]);
                    }
                
                }elseif ($transfer &&  $request->$qty && !$request->$code) {
                    //update disabled transfers(no code_id)
                    $sum = $to_transfer_product->transfer_qty + 
                            $to_transfer_product->mr_qty + 
                            $to_transfer_product->supplier_return_qty;

                        if ($sum <= $request->$qty) {

                            if ($request->$qty != $transfer->transfer_qty || 
                            $request->date != $transfer->transfer_date ) {
    
                            $t_history = TransferHistory::create([
    
                                    'from_shelf_number_id' => $transfer->from_shelf_number_id,
                                    'new_from_shelf_number_id' => $transfer->from_shelf_number_id,
                                    'to_shelf_number_id' => $transfer->to_shelf_number_id,
                                    'new_to_shelf_number_id' => $transfer->to_shelf_number_id,
    
                                    'transfer_date' => $transfer->transfer_date,
                                    'new_transfer_date' => $request->date,
    
                                    'transfer_no' => $transfer->transfer_no,
                                    'new_transfer_no' => $transfer->transfer_no,
                                    
                                    'code_id' => $transfer->code_id,
                                    'new_code_id' => $transfer->code_id,
                                    
                                    #unclear
                                    'product_id' => $to_transfer_product->id,
                                    'new_product_id' => $to_transfer_product->id,
    
                                    'transfer_qty' => $transfer->transfer_qty,
                                    'new_transfer_qty' => $request->$qty,
                        
                                    'remarks' => $transfer->remarks,
                                    'new_remarks' => $request->$remarks,
                        
                                    'method' => "update",
                                    'created_by' => Auth::user()->id,
                                ]);
                        }
                          
                            $from_transfer_product->update([
                                'transfer_qty' =>  ($from_transfer_product->transfer_qty- $transfer->transfer_qty) + $request->$qty,
                                'balance_qty' => ($from_transfer_product->balance_qty+ $transfer->transfer_qty)- $request->$qty,
                            ]);
    
                            $to_transfer_product->update([
                                'received_qty' => $request->$qty,
                                'balance_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'received_date'=> $request->date,
    
                                'voucher_no'=> $from_transfer_product->voucher_no,
                                'supplier_id'=> $from_transfer_product->supplier_id,
                                
                                'updated_by' => Auth::user()->id
                            ]);
    
                            $transfer->update([
                                'transfer_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'transfer_date'=> $request->date,
    
                                'updated_by' => Auth::user()->id
                            ]);
                        }


                    
                }else{
                    #no transfer product and new product
                    $check_transfer = Transfer::where('code_id', $code_id->id)
                                            ->where('transfer_no', $old_transfer->transfer_no)
                                            ->where('product_id', $check_from_product->id)
                                            ->first();


                    if (!$check_transfer) {
                        if ($request->shelfnum_id) {
                            
                            do {
                                #random number
                                $number = mt_rand(100000000, 999999999);
                                $check_barcode = Product::where('barcode', $number)->first();
    
                            } while ($check_barcode);

                            $transfer_product = Product::create([
                                'code_id' => $check_from_product->code_id,
                                'unit_id' => $check_from_product->unit_id,
                                'type' => 'transfer',
                                'barcode' => $number,
                                'received_qty' => $request->$qty,
                                'balance_qty' => $request->$qty,
        
                                'remarks' => $request->$remarks,
                                'shelf_number_id'=> $request->to_shelfnum_id,
                                'received_date'=> $request->date,
                                'voucher_no'=> $check_from_product->voucher_no,
        
                                'supplier_id'=> $check_from_product->supplier_id,
                                'created_by'=> Auth::user()->id,
            
                            ]);
                            $new_transfer = Transfer::create([
                                    'from_shelf_number_id'=> $request->from_shelfnum_id,
                                    'to_shelf_number_id'=> $request->to_shelfnum_id,
    
                                    'code_id' => $code_id->id,
                                    'product_id' => $request->$vr_no,
                                    
                                    'transfer_qty' => $request->$qty,
                                    'remarks' => $request->$remarks,
                                    'transfer_date'=> $request->date,
    
                                    'transfer_no'=> $request->transfer_no,
                                    'updated_by' => Auth::user()->id
            
                            ]);
                                
                            if ($check_from_product->balance_qty >= $request->$qty) {
                                # code...
                                $check_from_product->update([
                                    'balance_qty' => $check_from_product->balance_qty - $request->$qty,
                                    'transfer_qty' => $request->$qty,
                                ]);
        
                                $transfer_product->update([
                                    'transfer_id' => $transfer->id,
                                ]);
        
                            }else{
                                return Redirect::back()->withInput()
                                    ->with('error', "$code_name->name'balance($check_from_product->balance_qty) is lower than Request Qty.");
                            }

                        }else{

                            do {
                                #random number
                                $number = mt_rand(100000000, 999999999);
                                $check_barcode = Product::where('barcode', $number)->first();
    
                            } while ($check_barcode);

                            $transfer_product = Product::create([
                                'code_id' => $check_from_product->code_id,
                                'unit_id' => $check_from_product->unit_id,
                                'type' => 'transfer',
                                'barcode' => $number,
                                'received_qty' => $request->$qty,
                                'balance_qty' => $request->$qty,
        
                                'remarks' => $request->$remarks,
                                'shelf_number_id'=> $old_transfer->to_shelf_number_id,
                                'received_date'=> $request->date,
                                'voucher_no'=> $check_from_product->voucher_no,
        
                                'supplier_id'=> $check_from_product->supplier_id,
                                'created_by'=> Auth::user()->id,
            
                            ]);
                            $new_transfer = Transfer::create([
    
                                    'code_id' => $code_id->id,
                                    'product_id' => $request->$vr_no,
                                    
                                    'transfer_qty' => $request->$qty,
                                    'remarks' => $request->$remarks,
                                    'transfer_date'=> $request->date,
                                    'from_shelf_number_id'=> $old_transfer->from_shelf_number_id,
                                    'to_shelf_number_id'=> $old_transfer->to_shelf_number_id,
    
                                    'transfer_no'=> $request->transfer_no,
                                    'created_by' => Auth::user()->id
            
                            ]);
                                
                            if ($check_from_product->balance_qty >= $request->$qty) {
                                $check_from_product->update([
                                    'balance_qty' => $check_from_product->balance_qty - $request->$qty,
                                    'transfer_qty' => $request->$qty,
                                ]);
        
                                $transfer_product->update([
                                    'transfer_id' => $new_transfer->id,
                                ]);
        
                            }else{
                                return Redirect::back()->withInput()
                                    ->with('error', "$code_id->name'balance($check_from_product->balance_qty) is lower than Request Qty.");
                            }
                        }

                    }

                }
        }};

        return redirect()->route('transfers.index')->with('success', 'Transfer was successfully updated');
    }


    public function history(Request $request)  {
        $check = Auth::user()->hasPermissionTo('transfer_history');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $histories = TransferHistory::orderby('id', 'desc')->get();;
        return view('transfers/history', ['histories' => $histories ]);
    }


    //export excel
    public function export($transfers)
    {
        $check = Auth::user()->hasPermissionTo('export_transfer');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        for ($i = 0; $i < count($transfers); $i++) {
            # code...
            $transfer = $transfers[$i];
            
            $from_shelfnum = ShelfNumber::find($transfer->from_shelf_number_id); 
            $from_warehouse = Warehouse::find(optional($from_shelfnum)->warehouse_id); 

            $to_shelfnum = ShelfNumber::find($transfer->to_shelf_number_id); 
            $to_warehouse = Warehouse::find(optional($to_shelfnum)->warehouse_id); 

            $code = Code::find($transfer->code_id); 
            $brand = Brand::find(optional($code)->brand_id); 
            $commodity = Commodity::find(optional($code)->commodity_id); 
                                
            $transfers[$i] = [
                "No" => $i + 1,
                "Date" => $transfer->received_date,
                "Transfer_No" => $transfer->transfer_no,
                "Warehouse From"  => optional($from_warehouse)->name,
                "Warehouse To"  => optional($to_warehouse)->name,

                "Code" => optional($code)->name,
                "Brand" => optional($brand)->name,
                "Commodity" => optional($commodity)->name,

                "Transfer Qty" => $transfer->transfer_qty,
                "Transfer No"  => $transfer->transfer_no,
                "Remarks" => $transfer->remarks,
                
            ];
        }
        $export = new TransfersExport([$transfers]);

        return Excel::download($export, 'transfers ' . date("Y-m-d") . '.xlsx');
    }

}
