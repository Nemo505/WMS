<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\ProductHistory; 
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
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Redirect;
use Auth;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('product_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $exportQuery = Product::where(function($query) use ($request){
            if($request->product_id){
                return $query->where('id', $request->product_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->vr_no){
                return $query->where('voucher_no', $request->vr_no);
            }
        })
       ->where(function ($query) use ($request) {
            if ($request->warehouse_id) {
                $shelf_nos = ShelfNumber::where('warehouse_id', $request->warehouse_id)->pluck('id');
                $query->whereIn('shelf_number_id', $shelf_nos); 
            }
        })
        ->where(function ($query) use ($request){
            if($request->shelf_num_id){
                return $query->where('shelf_number_id', $request->shelf_num_id);
            }
        })
       ->where(function ($query) use ($request){
            if($request->code_id){
                $codes = Code::where('name',$request->code_id)->get();

                $code_items = [];
                foreach($codes as $code){
                    array_push($code_items,$code->id);
                }
                return $query->whereIn('code_id', $code_items);
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
            if($request->supplier_id){
                return $query->where('supplier_id', $request->supplier_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_date){
            $from_date = date('Y-m-d', strtotime($request->from_date));

                return $query->where('received_date', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d', strtotime($request->to_date));
                
                return $query->where('received_date', '<=',  $to_date);
            }
        })
        ->where('type', 'receive')
        ->orderbydesc('id');
        $warehouses = Warehouse::get();
        $suppliers = Supplier::get();
        $codes = Code::distinct()->get(['name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
        $shelfnums = ShelfNumber::get();

        if ($request->has('export')) {
            
            $hasFilters = $request->product_id || $request->code_id || 
                            $request->vr_no || $request->warehouse_id || 
                            $request->shelf_num_id || 
                            $request->brand_id || $request->commodity_id || 
                            $request->from_date || $request->to_date;
            if(!$hasFilters){
                $query = Product::query();
                $unsort_products = $query->orderBy('id', 'desc')->get();
                $sort_products = $unsort_products->sort();
                return $this->export($sort_products);
            }else{
                $sort_products = $exportQuery->get()->sort();
                return $this->export($sort_products);
            }
            
        }
        $products = $exportQuery->paginate(10);
        return view('products/index', ['warehouses' => $warehouses,
                                        'suppliers' => $suppliers,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'shelfnums' => $shelfnums,
                                        'products' => $products
                                        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_product');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }


        $shelfnums = ShelfNumber::join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                ->join('warehouses', 'warehouses.id', '=', 'shelf_numbers.warehouse_id')
                                ->select('shelf_numbers.id', 'shelf_numbers.name', 'shelves.name as shelf_name', 'warehouses.name as warehouse_name')
                                ->get();

        $suppliers = Supplier::get();
        $codes = Code::distinct()
                    ->get(['codes.name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
        $units = Unit::get();
        return view('products/create', ['shelfnums' => $shelfnums,
                                        'suppliers' => $suppliers,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'units' => $units,
                                        ]);
    }

    

    public function getShelfNum(Request $request)
    {
        $shelfnums = ShelfNumber::where('shelf_numbers.warehouse_id', $request->warehouse_id)
                    ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                    ->get(['shelf_numbers.id', 'shelves.name as shelfName', 'shelf_numbers.name as shelfnumName']);
        return response()->json($shelfnums);
    }

    //search brand and commodity from code
    public function getFromCode(Request $request)
    {
        //brands under code
        $brands = Code::where('codes.name', $request->code_name)
                    ->join('brands', 'brands.id', '=', 'codes.brand_id')
                    ->distinct()
                    ->get(['brands.id', 'brands.name']);

        if ($request->shelfnum_id) {
            $brands = Product::where('products.shelf_number_id', $request->shelfnum_id)
                    ->join('codes', 'codes.id',  '=', 'products.code_id')
                    ->where('codes.name', '=', $request->code_name)
                    ->join('brands', 'brands.id', '=', 'codes.brand_id')
                    ->distinct()
                    ->get(['brands.id', 'brands.name']);
        }

        return response()->json(['brands' => $brands]);
    }

    //commodity under code and brand
    public function getFromBrand(Request $request)
    {
        $brand = Brand::findOrFail($request->brand_id);
       
        $commodities = Code::where('codes.name', $request->code_name)
                        ->where('codes.brand_id', $request->brand_id)
                        ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                        ->distinct()
                        ->get(['commodities.id', 'commodities.name']);
        
        if ($request->shelfnum_id) {
            $commodities = Product::where('products.shelf_number_id', $request->shelfnum_id)
                    ->join('codes', 'codes.id',  '=', 'products.code_id')
                    ->where('codes.name', '=', $request->code_name)
                    ->where('codes.brand_id', $request->brand_id)
                    ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                    ->distinct()
                    ->get(['commodities.id', 'commodities.name']);
        }

        return response()->json(['commodities' => $commodities]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'supplier' => 'required',
            'vr_no' => 'required|unique:products,voucher_no',
            'date' => 'required',
        ]); 

        
        if ($validator->fails())
        {
            return Redirect::back()->withInput()
                                    ->with('error', 'Please try again.');
        }

        $newRequest = $request->except(['_token','vr_no','supplier','date']);

        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'code_')) {
                $explode = explode("_",$key);
                $shelfnum = "shelfnum_".$explode[1];
                $code = "code_".$explode[1];
                $brand = "brand_".$explode[1];
                $commodity = "commodity_".$explode[1];
                $unit = "unit_".$explode[1];
                $qty = "qty_".$explode[1];
                $remarks = "remark_".$explode[1];

                #find code id
                $code_id = Code::where('name', $request->$code)
                                ->where('brand_id', $request->$brand)
                                ->where('commodity_id', $request->$commodity)
                                ->first();

                if ($code_id) {
                    # to check the same voucher same product exist
                    $check_product = Product::where('supplier_id', $request->supplier)
                                        ->where('code_id', $code_id->id)
                                        ->where('shelf_number_id', $request->$shelfnum)
                                        ->where('voucher_no', $request->vr_no)
                                        ->where('type', 'receive')
                                        ->first();
                    if (!$check_product) {

                        $product = Product::create([
                            'code_id' => $code_id->id,
                            'unit_id' => $request->$unit,
                            'type' => 'receive',
                            'received_qty' => $request->$qty,
                            'balance_qty' => $request->$qty,
                            'remarks' => $request->$remarks,
                            'shelf_number_id'=> $request->$shelfnum,
                            'received_date'=> $request->date,
                            'voucher_no'=> $request->vr_no,
                            'supplier_id'=> $request->supplier,
                            'created_by'=> Auth::user()->id,
                        ]);
                    }
                }else{
                    return redirect()->route('products.index')->with('error', 'Check again the code number');
                }
                
            }
        }

        return redirect()->route('products.index')->with('success', 'Product was created successfully');
    }


    public function edit(Request $request, Product $product)
    {
        $check = Auth::user()->hasPermissionTo('edit_product');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $shelfnums = ShelfNumber::join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                ->join('warehouses', 'warehouses.id', '=', 'shelf_numbers.warehouse_id')
                                ->select('shelf_numbers.id', 'shelf_numbers.name', 'shelves.name as shelf_name', 'warehouses.name as warehouse_name')
                                ->get();
        $suppliers = Supplier::get();
        $codes = Code::distinct()->get(['name']);
        $units = Unit::get();

        //Edit IDs to show
        $product = Product::findOrFail($request->id);
        if ($product) {
            # code...
            $edit_supplier = Supplier::findOrFail($product->supplier_id);
            
            //code list under shelfnumberID & type(receive) &supplierID
            $choosen_products = Product::where('products.voucher_no', $product->voucher_no)
                                    ->where('products.supplier_id', $product->supplier_id)
                                    ->join('codes', 'codes.id', '=', 'products.code_id')
                                    ->join('brands', 'brands.id', '=', 'codes.brand_id')
                                    ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                                    ->join('units', 'units.id', '=', 'products.unit_id')
                                    ->get(['products.id', 
                                            'products.shelf_number_id', 
                                            'codes.name as code_name', 
                                            'codes.brand_id',
                                            'brands.name as brand_name',
                                            'codes.commodity_id',
                                            'commodities.name as commodity_name',
                                            'products.received_qty',
                                            'products.unit_id',
                                            'units.name as unit_name',
                                            'products.remarks',
                                            'products.transfer_qty',
                                            'products.supplier_return_qty',
                                            'products.mr_qty',
                                            'products.sub_adjustment',
                                            'products.add_adjustment',
                                        ]);

            //all transfer dates under productIDs
            $transfer_date = Product::where('products.voucher_no', $product->voucher_no)
                                    ->where('products.shelf_number_id', $product->shelf_number_id)
                                    ->where('products.supplier_id', $product->supplier_id)
                                    ->join('transfers', 'transfers.product_id', '=', 'products.id')
                                    ->orderBy('transfers.transfer_date', 'ASC')
                                    ->first(['products.id', 
                                            'transfers.transfer_date',
                                        ]);

            return view('products/edit', ['shelfnums' => $shelfnums,
                                            'suppliers' => $suppliers,
                                            'codes' => $codes,
                                            'units' => $units,
                                            'product' => $product,
    
                                            'edit_supplier' => $edit_supplier,
                                            
                                            'choosen_products' => $choosen_products,
                                            'transfer_date' => $transfer_date,
                                            ]);
        }else{
            return redirect()->route('products.index')->with('error', 'Product Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        if ($request->supplier) {
            # code..
            $validator = Validator::make($request->all(),[
                'supplier' => 'required',
                'vr_no' => 'required',
                'date' => 'required',
            ]);
            
        }else{
            $validator = Validator::make($request->all(),[
                'date' => 'required',
            ]);
        }
        
        if ($validator->fails())
        {
            return Redirect::back()->withInput()
                            ->with('error', 'Please try again.');
        }

        $old_product = Product::find($request->old_product);
        
        $newRequest = $request->except(['_token',
                                        'supplier',
                                        'vr_no',
                                        'date'
                                    ]);
                                    
        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'product_')) {
                $explode = explode("_",$key);
                $product_id = "product_".$explode[1];
                $qty = "qty_".$explode[1];

                $product = Product::find($request->$product_id);
                if (!$request->$qty) {
                    $p_history = ProductHistory::create([
                        'shelf_number_id' => $product->shelf_number_id,
                        'received_date' => $product->received_date,
                        'new_received_date' => $request->date,
            
                        'code_id' => $product->code_id,
                        'unit_id' => $product->unit_id,
            
                        'type' => $product->type,
                        'received_qty' => $product->received_qty,
                        
                        'balance_qty' => $product->balance_qty,
                        'transfer_qty' => $product->transfer_qty,
                        'mr_qty' => $product->mr_qty,
                        'mrr_qty' => $product->mrr_qty,
                        'supplier_return_qty' => $product->supplier_return_qty,
            
                        'sub_adjustment' => $product->sub_adjustment,
                        'add_adjustment' => $product->add_adjustment,
                        'remarks' => $product->remarks,
                        
                        'supplier_id'=> $product->supplier_id,
            
                        'method' => "delete",
                        'created_by' => Auth::user()->id,
                    ]);
            
                    $product->delete();
                }

        }}
        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'qty_')) {
                $explode = explode("_",$key);
                $shelfnum = "shelfnum_".$explode[1];
                $code = "code_".$explode[1];
                $brand = "brand_".$explode[1];
                $commodity = "commodity_".$explode[1];
                $unit = "unit_".$explode[1];
                $qty = "qty_".$explode[1];
                $remarks = "remark_".$explode[1];
                $product_id = "product_".$explode[1];

                $product = Product::find($request->$product_id);

                #find code id
                $code_id = Code::where('name', $request->$code)
                                ->where('brand_id', $request->$brand)
                                ->where('commodity_id', $request->$commodity)
                                ->first();

                # update no transfer products 
                if ($product && $request->$code ) {
                    $shelf_check_product = Product::where('products.supplier_id', $product->supplier_id)
                                            ->where('products.code_id', $code_id->id)
                                            ->where('products.voucher_no', $request->vr_no)
                                            ->where('products.shelf_number_id', $request->$shelfnum)
                                            ->where('id', '!=',  $product->id)
                                            ->whereIn('type', ['receive', 'opening'])
                                            ->first();

                    if (!$shelf_check_product) {
                        # code...
                        if ($request->vr_no) {
                            if ($request->$shelfnum != $product->shelf_number_id || 
                                $request->$code != $code_id->name || 
                                $request->date != $product->received_date || 
                                $request->vr_no != $product->voucher_no || 
                                $request->$qty != $product->received_qty) {
                                # code...
                                $p_history = ProductHistory::create([
                                    'shelf_number_id' => $product->shelf_number_id,
                                    'new_shelf_number_id' => $request->$shelfnum,
                                    'received_date' => $product->received_date,
                                    'new_received_date' => $request->date,
                        
                                    'code_id' => $product->code_id,
                                    'new_code_id' => $code_id->id,
                                    'unit_id' => $product->unit_id,
                                    'new_unit_id' => $request->$unit,
                        
                                    'type' => $product->type,
                                    'received_qty' => $product->received_qty,
                                    'new_received_qty' => $request->$qty,
                                    
                                    'balance_qty' => $request->$qty,
                                    'transfer_qty' => $product->transfer_qty,
                                    'mr_qty' => $product->mr_qty,
                                    'mrr_qty' => $product->mrr_qty,
                                    'supplier_return_qty' => $product->supplier_return_qty,
                        
                                    'sub_adjustment' => $product->sub_adjustment,
                                    'add_adjustment' => $product->add_adjustment,
                                    'remarks' => $product->remarks,
                                    'new_remark' => $request->$remarks,
                        
                                    'transfer_id'=> $product->transfer_id,
                                    'supplier_id'=> $product->supplier_id,
                                    'new_supplier_id'=> $request->supplier,
                        
                                    'method' => "update",
                                    'created_by' => Auth::user()->id,
                                ]);
                            }
    
    
                                $product->update([
                                    'code_id' => $code_id->id,
                                    'unit_id' => $request->$unit,
                                    
                                    'received_qty' => $request->$qty,
                                    'balance_qty' => $request->$qty,
                                    'remarks' => $request->$remarks,
                                    'received_date'=> $request->date,
    
                                    'shelf_number_id'=> $request->$shelfnum,
                                    'voucher_no'=> $request->vr_no,
                                    'supplier_id'=> $request->supplier,
                                   
                                    'updated_by' => Auth::user()->id
                                ]);
                        }else{
    
                            if ($request->$shelfnum != $product->shelf_number_id || $request->$code != $code_id->name || 
                                $request->date != $product->received_date ||
                                $request->$qty != $product->received_qty) {
    
                                    $p_history = ProductHistory::create([
                                        'shelf_number_id' => $product->shelf_number_id,
                                        'new_shelf_number_id' => $request->$shelfnum,
                                        'received_date' => $product->received_date,
                                        'new_received_date' => $request->date,
                            
                                        'code_id' => $product->code_id,
                                        'new_code_id' => $request->$code,
                                        'unit_id' => $product->unit_id,
                                        'new_unit_id' => $request->$unit,
                            
                                        'type' => $product->type,
                                        'received_qty' => $product->received_qty,
                                        'new_received_qty' => $request->$qty,
                                        
                                        'balance_qty' => $request->$qty,
                                        'transfer_qty' => $product->transfer_qty,
                                        'mr_qty' => $product->mr_qty,
                                        'mrr_qty' => $product->mrr_qty,
                                        'supplier_return_qty' => $product->supplier_return_qty,
                            
                                        'sub_adjustment' => $product->sub_adjustment,
                                        'add_adjustment' => $product->add_adjustment,
                                        'remarks' => $product->remarks,
                                        'new_remark' => $request->$remarks,
                            
                                        'transfer_id'=> $product->transfer_id,
                                        'supplier_id'=> $product->supplier_id,
                            
                                        'method' => "update",
                                        'created_by' => Auth::user()->id,
                                    ]);
                                }
                               
    
                            $product->update([
                                'code_id' => $code_id->id,
                                'unit_id' => $request->$unit,
                                'shelf_number_id' =>  $request->$shelfnum,
                                'received_qty' => $request->$qty,
                                'balance_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'received_date'=> $request->date,
                                
                                'updated_by' => Auth::user()->id
                            ]);
                        }
                    }                     
                
                }elseif ($product &&  $request->$qty && !$request->$code) {
            
                    //update disabled products(no code_id)
                    if ($request->$qty != $product->received_qty || 
                        $request->date != $product->received_date ) {

                            $p_history = ProductHistory::create([
                                'shelf_number_id' => $product->shelf_number_id,
                                'new_shelf_number_id' => $product->shelf_number_id,
                                'received_date' => $product->received_date,
                                'new_received_date' => $request->date,
                    
                                'code_id' => $product->code_id,
                                'new_code_id' => $product->code_id,
                                'unit_id' => $product->unit_id,
                                'new_unit_id' => $product->unit_id,
                    
                                'type' => $product->type,
                                'received_qty' => $product->received_qty,
                                'new_received_qty' => $request->$qty,
                                
                                'balance_qty' => $request->$qty - $product->transfer_qty,
                                'transfer_qty' => $product->transfer_qty,
                                'mr_qty' => $product->mr_qty,
                                'mrr_qty' => $product->mrr_qty,
                                'supplier_return_qty' => $product->supplier_return_qty,
                    
                                'sub_adjustment' => $product->sub_adjustment,
                                'add_adjustment' => $product->add_adjustment,
                                'remarks' => $product->remarks,
                                'new_remark' => $request->$remarks,
                    
                                'transfer_id'=> $product->transfer_id,
                                'supplier_id'=> $product->supplier_id,
                    
                                'method' => "update",
                                'created_by' => Auth::user()->id,
                            ]);
                        }
                    $sum =  $product->transfer_qty + $product->mr_qty +  $product->supplier_return_qty;
                    if ($request->$qty >= $sum) {
                       
                        $product->update([
                            'received_qty' => $request->$qty,
                            'balance_qty' => $request->$qty - $sum,
                            'remarks' => $request->$remarks,
                            'received_date'=> $request->date,
                            'updated_by' => Auth::user()->id
                        ]);
                    }
                    
                   
                }else{
                    if ($request->vr_no) {
                        //no transfer product and new product
                        $shelf_check_product = Product::where('products.supplier_id', $request->supplier)
                                            ->where('products.code_id', $code_id->id)
                                            ->join('codes', 'codes.id', '=', 'products.code_id')
                                            ->where('products.shelf_number_id', $request->$shelfnum)
                                            ->where('products.voucher_no', $request->vr_no)
                                            ->where('products.type', 'receive')
                                            ->first();
                                            
    
                        if (!$shelf_check_product) {
                            $new_product = Product::Create([
                                'code_id' => $code_id->id,
                                'unit_id' => $request->$unit,
                                'type' => 'receive',
                                'received_qty' => $request->$qty,
                                'balance_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'received_date'=> $request->date,

                                'shelf_number_id'=> $request->$shelfnum,
                                'voucher_no'=> $request->vr_no,
                                'supplier_id'=> $request->supplier,
                                'created_by'=> Auth::user()->id,
    
                            ]);
                        }
                    }else{

                        $check_product = Product::where('products.supplier_id', $old_product->supplier_id)
                                                    ->where('products.code_id', $code_id->id)
                                                    ->join('codes', 'codes.id', '=', 'products.code_id')
                                                    ->where('products.shelf_number_id', $request->$shelfnum)
                                                    ->where('products.voucher_no', $old_product->voucher_no)
                                                    ->where('products.type', 'receive')
                                                    ->first();
                                                    

                        if (!$check_product) {
                          

                            $new_product = Product::Create([
                                'code_id' => $code_id->id,
                                'unit_id' => $request->$unit,
                                'type' => 'receive',
                                'received_qty' => $request->$qty,
                                'balance_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'received_date'=> $request->date,

                                'shelf_number_id'=> $request->$shelfnum,
                                'voucher_no'=> $old_product->voucher_no,
                                'supplier_id'=> $old_product->supplier_id,
                                'created_by'=> Auth::user()->id,

                            ]);
                        }
            }}            
        }};


        return redirect()->route('products.index')->with('success', 'Product was successfully updated');
    }

    public function history(Request $request)  {
        $check = Auth::user()->hasPermissionTo('product_history');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $histories = ProductHistory::orderBy('id', 'desc')
        ->where(function ($query) use ($request){
            if($request->from_date){
                $from_date = date('Y-m-d', strtotime($request->from_date));
                return $query->where('received_date', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d', strtotime($request->to_date));
                return $query->where('received_date', '<=',  $to_date);
            }
        })->orderbydesc('id')
        ->get();

        return view('products/history', ['histories' => $histories ]);
    }
   

    //export excel
    public function export($sort_products)
    {
        $check = Auth::user()->hasPermissionTo('export_product');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        for ($i = 0; $i < count($sort_products); $i++) {
            # code...
            $product = $sort_products[$i];
            
            $shelfnum = ShelfNumber::find($product->shelf_number_id); 
            $warehouse = Warehouse::find(optional($shelfnum)->warehouse_id); 
            $shelf = Shelf::find(optional($shelfnum)->shelf_id); 

            $supplier = Supplier::find($product->supplier_id); 
            $code = Code::find($product->code_id); 
            $brand = Brand::find(optional($code)->brand_id); 
            $commodity = Commodity::find(optional($code)->commodity_id); 
            $unit = Unit::find($product->unit_id); 
            $transfer = Transfer::find($product->transfer_id); 

            $c_user = User::find($product->created_by); 
            $u_user = User::find($product->updated_by); 
                                
            $sort_products[$i] = [
                "No" =>  count($sort_products) - $i,
                "Date" => $product->received_date,
                "Voucher No"  => $product->voucher_no,
                "Warehouse"  => optional($warehouse)->name,
                "Shelf" => optional($shelf)->name,
                "Shelf No" => optional($shelfnum)->name,
                "Supplier" => optional($supplier)->name,
                "Code" => optional($code)->name,
                "Brand" => optional($brand)->name,
                "Commodity" => optional($commodity)->name,
                "Unit" => optional($unit)->name,
                "Receive_Qty" => $product->received_qty,
                "Transfer_Qty" => $product->transfer_qty,
                "MR_Qty" => $product->mr_qty,
                "MRR_Qty" => $product->mrr_qty,
                "SupplierReturn_Qty" => $product->supplier_return_qty,
                "Balance_Qty" => $product->balance_qty,
                "Type" => $product->type,
                "Transfer_No" => optional($transfer)->transfer_no,
                "Remarks" => $product->remarks,
                "Created By"  => optional($c_user)->user_name,
                "Updated By"  => optional($u_user)->user_name,
            ];
        }
        $export = new ProductsExport([$sort_products]);

        return Excel::download($export, 'products ' . date("Y-m-d") . '.xlsx');
    }


    public function import(Request $request){
        
        $arrays = Excel::toArray(new ProductsImport, $request->file('products'));
        foreach ($arrays[0] as $row){
            $product =  Product::where('shelf_number_id',  $row['shelfno_id'])
                                ->where('voucher_no',  $row['voucher_no'])
                                ->where('supplier_id',  $row['supplier_id'])
                                ->where('code_id',  $row['code_id'])
                                ->where('type',  'opening')
                                ->first();

            if(!$product){
                $check_voucher = Product::where('voucher_no', $row['voucher_no'])
                                            ->first();
                if (!$check_voucher) {

                    Product::create([
                        'received_date' => date('Y-m-d', strtotime($row['date'])),
                        'voucher_no' => $row['voucher_no'],
                        'shelf_number_id' => $row['shelfno_id'],
                        'supplier_id' => $row['supplier_id'],
                        'code_id' => $row['code_id'],
                        'unit_id' => $row['unit_id'],
                        'remarks' => $row['remark'],
                        'received_qty' => $row['qty'],
                        'balance_qty' => $row['qty'],
                        'type' => 'opening',
                        'created_by'=> Auth::user()->id,
                    ]);
                }else{
                    Product::create([
                        'received_date' => date('Y-m-d', strtotime($row['date'])),
                        'voucher_no' => $row['voucher_no'],
                        'shelf_number_id' => $row['shelfno_id'],
                        'supplier_id' => $row['supplier_id'],
                        'code_id' => $row['code_id'],
                        'unit_id' => $row['unit_id'],
                        'remarks' => $row['remark'],
                        'received_qty' => $row['qty'],
                        'balance_qty' => $row['qty'],
                        'type' => 'opening',
                        'created_by'=> Auth::user()->id,
                    ]);
                }


            }
        }
        return redirect()->route('products.index')->with('success', 'Products Imported successfully');

    }

    public function sample(){
        $path = public_path(). "/excel/samples/Products.xlsx";
        if(file_exists($path)){
            return response()->download($path);
        }else{
            return redirect()->route('products.index')->with('error', 'This cannot be downloaded');
        }
    }

    public function backup()
    {
        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $fileName = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $filePath = storage_path('app/' . $fileName);

        // Run mysqldump command
        $mysqldumpPath = "C:\\xampp\\mysql\\bin\\mysqldump.exe";
        $command = "\"$mysqldumpPath\" -h$dbHost -u$dbUser -p\"$dbPass\" $dbName > \"$filePath\"";


        system($command);

        // Return file as download
        return response()->download($filePath)->deleteFileAfterSend(true);
    }


}
