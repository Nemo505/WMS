<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueHistory;
use App\Models\IssueReturn;
use App\Models\Product;
use App\Models\Department;
use App\Models\Unit; 
use App\Models\Brand; 
use App\Models\Transfer; 
use App\Models\Commodity; 
use App\Models\Warehouse; 
use App\Models\Code; 
use App\Models\Customer; 
use App\Models\Shelf; 
use App\Models\ShelfNumber; 
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IssuesExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Redirect;
use Auth;
use Validator;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('mr_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $issues = Issue::where(function($query) use ($request){
            if($request->issue_id){
                return $query->where('id', $request->issue_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->mr_no){
                return $query->where('mr_no', $request->mr_no);
            }
        })
        ->where(function ($query) use ($request){
            if($request->department_id){
                return $query->where('department_id', $request->department_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->vr_no){
                $products = Product::where('products.voucher_no', $request->vr_no)
                                    ->join('issues', 'issues.product_id', 'products.id')
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

                $issue_shelves = ShelfNumber::where('warehouse_id',$request->warehouse_id)
                                        ->join('issues', 'issues.shelf_number_id', 'shelf_numbers.id')
                                        ->get('shelf_numbers.id as shelf_number_id');

                $s_items = [];
                foreach($issue_shelves as $issue_shelf){
                    array_push($s_items, optional($issue_shelf)->shelf_number_id);
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
                                    ->join('products', 'products.code_id', 'codes.id')
                                    ->join('issues', 'issues.product_id', 'products.id')
                                    ->get(['products.id as product_id']);
                $c_items = [];
                foreach($codes as $code){
                    array_push($c_items, optional($code)->product_id);
                }
                                            
                return $query->whereIn('product_id', $c_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->brand_id){
             
                $brands = Code::where('brand_id',$request->brand_id)
                            ->join('products', 'products.code_id', 'codes.id')
                            ->join('issues', 'issues.product_id', 'products.id')
                            ->get(['products.id as product_id']);

                $b_items = [];
                foreach($brands as $brand){
                    array_push($b_items, optional($brand)->product_id);
                }
                return $query->whereIn('product_id', $b_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->commodity_id){

                $commodities = Code::where('commodity_id',$request->commodity_id)
                            ->join('products', 'products.code_id', 'codes.id')
                            ->join('issues', 'issues.product_id', 'products.id')
                            ->get(['products.id as product_id']);

                $com_items = [];
                foreach($commodities as $commodity){
                    array_push($com_items, optional($commodity)->product_id);
                }
                                            
                return $query->whereIn('product_id', $com_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->customer_id){
                return $query->where('customer_id', $request->customer_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_date){
                $from_date = date('Y-m-d', strtotime($request->from_date));

                return $query->where('issue_date', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d', strtotime($request->to_date));
                
                return $query->where('issue_date', '<=',  $to_date);
            }
        })
        ->orderbydesc('issue_date')
        ->get();

        $warehouses = Warehouse::get();
        $customers = Customer::get();
        $codes = Code::distinct()->get(['name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
        $shelfnums = ShelfNumber::get();
        $departments = Department::get();

        $vr_nos = Issue::join('products', 'products.id', 'issues.product_id')
                        ->distinct()
                        ->get(['products.voucher_no']);

        if ($request->has('export')) {
            $sort_issues = $issues->sort();
            return $this->export($sort_issues);
        }

        return view('issues/index', ['warehouses' => $warehouses,
                                        'customers' => $customers,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'shelfnums' => $shelfnums,
                                        'issues' => $issues,
                                        'departments' => $departments,
                                        'vr_nos' => $vr_nos
                                        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_mr');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $warehouses = Warehouse::get();
        $customers = Customer::get();
        $departments = Department::get();
        return view('issues/create', ['warehouses' => $warehouses,
                                     'customers' => $customers,
                                     'departments' => $departments
                                        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            "warehouse_id" => 'required',
            "department_id" => 'required',
            "shelfnum_id" => 'required',
            "date" => 'required',
            "mr_no" => 'required',
            "customer" => 'required',
        ]); 
        
        if ($validator->fails())
        {
            return Redirect::back()->withInput()
                                    ->with('error', 'Please try again.');
        }
       
        $newRequest = $request->except(['_token',
                                    'warehouse_id',
                                    'shelfnum_id',
                                    'department_id',
                                    'mr_no',
                                    'date',
                                    'customer',
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
                    # same issue no, product exist?
                    $check_issue = Issue::where('product_id', $product->id)
                                        ->where('mr_no', $request->mr_no)
                                        ->where('shelf_number_id', $request->shelfnum_id)
                                        ->where('customer_id', $request->customer)
                                        ->first();
                    if (!$check_issue) {
                        
                        if ($product->balance_qty >= $request->$qty) {
                            # code...
                            $product->update([
                                'balance_qty' => $product->balance_qty - $request->$qty,
                                'mr_qty' => $product->mr_qty + $request->$qty,
                            ]);
                            $issue = Issue::create([
                                'mr_no'=> $request->mr_no,
                                'mr_qty'=> $request->$qty,
                                'shelf_number_id'=> $request->shelfnum_id,
                                'department_id'=> $request->department_id,
                                'issue_date'=> $request->date,
                                
                                'remarks' => $request->$remarks,
                                'product_id' => $product->id,
                                'code_id' => $product->code_id,
        
                                'created_by'=> Auth::user()->id,
                                'customer_id'=>  $request->customer,
                            ]);
                            
                        }
                    }

                }
            }
        }
        return redirect()->route('issues.index')->with('success', 'New Issue was created successfully');

    }
    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Issue $issue)
    {
        $check = Auth::user()->hasPermissionTo('edit_mr');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $warehouses = Warehouse::get();
        $customers = Customer::get();
        $departments = Department::get();

        # Edit IDs to show
        $issue = Issue::find($request->id);
        
        if ($issue) {
            # code...
            $edit_shelfnum = ShelfNumber::where('shelf_numbers.id', $issue->shelf_number_id)
                                            ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                            ->first([
                                                'shelf_numbers.id', 
                                                'shelves.name as shelfName', 
                                                'shelf_numbers.name as shelfnumName', 
                                                'shelf_numbers.warehouse_id'
                                            ]);
    
            $edit_warehouse = Warehouse::findOrFail(optional($edit_shelfnum)->warehouse_id);
            $edit_customer = Customer::findOrFail($issue->customer_id);
            $edit_department = Department::findOrFail($issue->department_id);
    
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
            $choosen_issues = Issue::where('issues.mr_no', $issue->mr_no)
                                    ->where('issues.shelf_number_id', $issue->shelf_number_id)
                                    ->where('issues.customer_id', $issue->customer_id)
                                    ->join('products', 'products.id', '=', 'issues.product_id')
                                    ->join('codes', 'codes.id', '=', 'products.code_id')
                                    ->join('brands', 'brands.id', '=', 'codes.brand_id')
                                    ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                                    ->get(['products.id as product_id',
                                            'products.voucher_no', 
                                            'issues.id as issue_id', 
                                            'issues.issue_date', 

                                            'codes.name as code_name', 
                                            'codes.brand_id',
                                            'brands.name as brand_name',
                                            'codes.commodity_id',
                                            'commodities.name as commodity_name',
                                            'codes.usage',
                                            'codes.image',

                                            'issues.mrr_qty',
                                            'issues.mr_qty',
                                            'issues.remarks',
                                            'products.balance_qty',
                                            'products.transfer_id',
                                        ]);

            # all issure_return dates under issues
            $issue_return_date = Issue::where('issues.mr_no', $issue->mr_no)
                                ->where('issues.shelf_number_id', $issue->shelf_number_id)
                                ->where('issues.customer_id', $issue->customer_id)
                                ->join('issue_returns', 'issue_returns.issue_id', '=', 'issues.id')
                                ->orderBy('issue_returns.issue_return_date', 'ASC')
                                ->first(['issues.id', 
                                        'issue_returns.issue_return_date',
                                    ]);

            # Edit Code IDs
            $codes = Product::where('products.shelf_number_id', $issue->shelf_number_id)
                        ->join('codes','codes.id', '=', 'products.code_id')
                        ->distinct()
                        ->get(['codes.name']);

            return view('issues/edit', ['warehouses' => $warehouses,
                                            'customers' => $customers,
                                            'issue' => $issue,
                                            'codes' => $codes,
                                            'departments' => $departments,
    
                                            'edit_warehouse' => $edit_warehouse,
                                            'edit_shelfnum' => $edit_shelfnum,
                                            'edit_customer' => $edit_customer,
    
                                            'shelfnums' => $shelfnums,
                                            'choosen_issues' => $choosen_issues,
                                            'edit_department' => $edit_department,
                                            'issue_return_date' => $issue_return_date,
                                            ]);
        }else{
            return Redirect::back()->withInput()
                            ->with('error', 'Issue Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue)
    {
        if ($request->from_warehouse_id) {
            $validator = Validator::make($request->all(),[
                "warehouse_id" => 'required',
                "shelfnum_id" => 'required',
                "date" => 'required',
                "mr_no" => 'required',
                "department_id" => 'required',
                "customer" => 'required',
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

        $old_issue = Issue::find($request->old_issue);

        $newRequest = $request->except(['_token',
                                        'warehouse_id',
                                        'shelfnum_id',
                                        'date',
                                        'customer',
                                        'mr_no',
                                        'old_issue'
                                    ]);
                                    
        foreach ($newRequest as $key => $value){
            if (str_contains($key, 'issue_')) {
                $explode = explode("_",$key);
                $issue_id = "issue_".$explode[1];
                $qty = "qty_".$explode[1];

                $issue = Issue::find($request->$issue_id);
                if ($issue) {

                    $issue_product = Product::find($issue->product_id);
                    if ($issue_product){

                        $issue_code = Code::find($issue_product->code_id);
                    }
                }

                if (!$request->$qty) {

                    $mr_history = IssueHistory::create([
                        'mr_no' => $issue->mr_no,
                        'new_mr_no' => $issue->mr_no,

                        'mr_qty' => $issue->mr_qty,
                        'new_mr_qty' => $issue->mr_qty,

                        'mrr_qty' => $issue->mrr_qty,
                        'new_mrr_qty' => $issue->mrr_qty,

                        'shelf_number_id' => $issue->shelf_number_id,
                        'new_shelf_number_id' => $issue->shelf_number_id,

                        'department_id' => $issue->department_id,
                        'new_department_id' => $issue->department_id,

                        'issue_date' => $issue->issue_date,
                        'new_issue_date' => $issue->issue_date,
                        
                        'code_id' => $issue_code->id,
                        'new_code_id' => $issue_code->id,
                        
                        #unclear
                        'product_id' => $issue->product_id,
                        'new_product_id' => $issue->product_id,

                        'customer_id' => $issue->customer_id,
                        'new_customer_id' => $issue->customer_id,
            
                        'remarks' => $issue->remarks,
                        'new_remarks' => $issue->remarks,
            
                        'method' => "delete",
                        'created_by' => Auth::user()->id,
                    ]);

                    $issue_product->update([
                        'mr_qty' =>  $issue_product->mr_qty - $issue->mr_qty,
                        'balance_qty' => $issue_product->balance_qty + $issue->mr_qty,
                    ]);

                    $issue->delete();
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
                $issue_id = "issue_".$explode[1];

                $issue = Issue::find($request->$issue_id);
                if ($issue) {
                    $issue_product = Product::find($issue->product_id);
                    if ($issue_product){
                        $issue_code = Code::find($issue_product->code_id);
                    }
                }
                #if issue product id is new or same?
                $issue_from_vr = Product::find($request->$vr_no);
                
                if ($issue_from_vr) {
                    #find code id
                    $code_id = Code::where('name', $request->$code) 
                                ->where('brand_id', $request->$brand)
                                ->where('commodity_id', $request->$commodity)
                                ->first();
                }

                #update no mrr product
                if ($issue && $request->$code ) {
                    $check_issue = Issue::where('product_id', $issue_from_vr->id)
                                    ->where('mr_no', $old_issue->mr_no)
                                    ->where('shelf_number_id', $old_issue->shelf_number_id)
                                    ->where('customer_id', $old_issue->customer_id)
                                    ->where('id', '!=',  $request->$issue_id)
                                    ->first();
                     
                    if (!$check_issue) {                
                        if ($request->shelfnum_id) {

                            if ($request->shelfnum_id != $issue->shelf_number_id || 
                                $request->mr_no != $issue->mr_no || 
                                $request->department_id != $issue->department_id || 
                                $request->date != $issue->issue_date || 

                                $request->$vr_no != $issue_product->id || 
                                $request->$code != $code_id->name || 
                                $request->$qty != $issue->mr_qty) {

                                $mr_history = IssueHistory::create([
                                    'mr_no' => $issue->mr_no,
                                    'new_mr_no' => $request->mr_no,
            
                                    'mr_qty' => $issue->mr_qty,
                                    'new_mr_qty' => $request->$qty,
            
                                    'mrr_qty' => $issue->mrr_qty,
                                    'new_mrr_qty' => $issue->mrr_qty,
            
                                    'shelf_number_id' => $issue->shelf_number_id,
                                    'new_shelf_number_id' => $request->shelfnum_id,
            
                                    'department_id' => $issue->department_id,
                                    'new_department_id' => $request->department_id,
            
                                    'issue_date' => $issue->issue_date,
                                    'new_issue_date' => $request->date,
                                    
                                    'code_id' => $issue_code->id,
                                    'new_code_id' => $code_id->id,
                                    
                                    #unclear
                                    'product_id' => $issue->product_id,
                                    'new_product_id' => $request->$vr_no,
            
                                    'customer_id' => $issue->customer_id,
                                    'new_customer_id' => $request->customer_id,
                        
                                    'remarks' => $issue->remarks,
                                    'new_remarks' => $request->$remarks,
                        
                                    'method' => "update",
                                    'created_by' => Auth::user()->id,
                                ]);
                                
                            }
                            $issue_product->update([
                                'mr_qty' =>  $issue_product->mr_qty - $issue->mr_qty,
                                'balance_qty' => $issue_product->balance_qty + $issue->mr_qty,
                            ]);

                            #if same product id, update OR reduce to old- add to new
                            if ($issue->product_id == $request->$vr_no) {
                                $issue_product->update([
                                    'mr_qty' =>  $issue_product->mr_qty + $request->$qty,
                                    'balance_qty' => $issue_product->balance_qty - $request->$qty,
                                ]);
                            }else{
                                $issue_from_vr->update([
                                    'mr_qty' =>  $issue_from_vr->mr_qty + $request->$qty,
                                    'balance_qty' => $issue_from_vr->balance_qty - $request->$qty,
                                ]);
                            }

                            $issue->update([
                                'shelf_number_id'=> $request->shelfnum_id,
                                'department_id'=> $request->department_id,
                                'product_id' => $issue_from_vr->id,
                                'code_id' => $issue_from_vr->code_id,

                                'mr_qty' => $request->$qty,
                                'remarks' => $request->$remarks,
                                'issue_date'=> $request->date,

                                'mr_no'=> $request->mr_no,
                                'customer_id'=> $request->customer_id,
                                
                                'updated_by' => Auth::user()->id
                            ]);

                                
                        }else{

                            if ($request->date != $issue->issue_date || 

                                $request->$vr_no != $issue_product->id || 
                                $request->$code != $code_id->name || 
                                $request->$qty != $issue->mr_qty) {

                                $mr_history = IssueHistory::create([
                                    'mr_no' => $issue->mr_no,
                                    'new_mr_no' => $issue->mr_no,
            
                                    'mr_qty' => $issue->mr_qty,
                                    'new_mr_qty' => $request->$qty,
            
                                    'mrr_qty' => $issue->mrr_qty,
                                    'new_mrr_qty' => $issue->mrr_qty,
            
                                    'shelf_number_id' => $issue->shelf_number_id,
                                    'new_shelf_number_id' => $issue->shelf_number_id,

                                    'department_id' => $issue->department_id,
                                    'new_department_id' => $issue->department_id,
            
                                    'issue_date' => $issue->issue_date,
                                    'new_issue_date' => $request->date,
                                    
                                    'code_id' => $issue_code->id,
                                    'new_code_id' => $code_id->id,
                                    
                                    #unclear
                                    'product_id' => $issue->product_id,
                                    'new_product_id' => $request->$vr_no,
            
                                    'customer_id' => $issue->customer_id,
                                    'new_customer_id' => $issue->customer_id,
                        
                                    'remarks' => $issue->remarks,
                                    'new_remarks' => $request->$remarks,
                        
                                    'method' => "update",
                                    'created_by' => Auth::user()->id,
                                ]);
                            }

                            
                            #if same product id, update OR reduce to old- add to new
                            $issue_product->update([
                                'mr_qty' =>  $issue_product->mr_qty - $issue->mr_qty,
                                'balance_qty' => $issue_product->balance_qty + $issue->mr_qty,
                            ]);

                            #if same product id, update OR reduce to old- add to new
                            if ($issue->product_id == $request->$vr_no) {
                                $issue_product->update([
                                    'mr_qty' =>  $issue_product->mr_qty + $request->$qty,
                                    'balance_qty' => $issue_product->balance_qty - $request->$qty,
                                ]);
                            }else{
                                $issue_from_vr->update([
                                    'mr_qty' =>  $issue_product->mr_qty - $request->$qtyy,
                                    'balance_qty' => $issue_product->balance_qty + $request->$qty,
                                ]);
                            }

                                $issue->update([
                                    'shelf_number_id'=> $issue->shelfnum_id,
                                    'product_id' => $issue_from_vr->id,
                                    'code_id' => $issue_from_vr->code_id,

                                    'mr_qty' => $request->$qty,
                                    'remarks' => $request->$remarks,
                                    'issue_date'=> $request->date,

                                    'mr_no'=> $issue->mr_no,
                                    'customer_id'=> $issue->customer_id,
                                    
                                    'updated_by' => Auth::user()->id
                                ]);

                        }
                    }
                }elseif ($issue &&  $request->$qty && !$request->$code) {
                    #update disabled issue(no code_id)
                    if ($request->$qty != $issue->mr_qty || 
                        $request->$remarks != $issue->remarks || 
                        $request->date != $issue->issue_date ) {

                            $mr_history = IssueHistory::create([
                                'mr_no' => $issue->mr_no,
                                'new_mr_no' => $issue->mr_no,
        
                                'mr_qty' => $issue->mr_qty,
                                'new_mr_qty' => $request->$qty,
        
                                'mrr_qty' => $issue->mrr_qty,
                                'new_mrr_qty' => $issue->mrr_qty,
        
                                'shelf_number_id' => $issue->shelf_number_id,
                                'new_shelf_number_id' => $issue->shelf_number_id,

                                'department_id' => $issue->department_id,
                                'new_department_id' => $issue->department_id,
        
                                'issue_date' => $issue->issue_date,
                                'new_issue_date' => $request->date,
                                
                                'code_id' => $issue_code->id,
                                'new_code_id' => $issue_code->id,
                                
                                #unclear
                                'product_id' => $issue->product_id,
                                'new_product_id' => $issue->product_id,
        
                                'customer_id' => $issue->customer_id,
                                'new_customer_id' => $issue->customer_id,
                    
                                'remarks' => $issue->remarks,
                                'new_remarks' => $request->$remarks,
                    
                                'method' => "update",
                                'created_by' => Auth::user()->id,
                            ]);
                    }

                        $issue_product->update([
                            'mr_qty' =>  ($issue_product->mr_qty - $issue->mr_qty) + $request->$qty,
                            'balance_qty' => ($issue_product->balance_qty + $issue->mr_qty) - $request->$qty,
                        ]);

                        $issue->update([
                            'mr_qty' => $request->$qty,
                            'remarks' => $request->$remarks,
                            'issue_date'=> $request->date,

                            'updated_by' => Auth::user()->id
                        ]);
                    
                }else{
                    $check_issue = Issue::where('product_id', $issue_from_vr->id)
                                        ->where('mr_no', $old_issue->mr_no)
                                        ->where('shelf_number_id', $old_issue->shelf_number_id)
                                        ->where('customer_id', $old_issue->customer_id)
                                        ->where('id', '!=',  $request->$issue_id)
                                        ->first();
                     if (!$check_issue) {     
                        #new issue update
                        if ($request->shelfnum_id) {
                            # code...
                           
                            
                            if ($issue_from_vr->balance_qty >= $request->$qty) {
                                # code...
                                $issue_from_vr->update([
                                    'balance_qty' => $issue_from_vr->balance_qty - $request->$qty,
                                    'mr_qty' => $issue_from_vr->mr_qty + $request->$qty,
                                ]);

                                $issue = Issue::create([
                                    'mr_no'=> $request->mr_no,
                                    'mr_qty'=> $request->$qty,
                                    'shelf_number_id'=> $request->shelfnum_id,
                                    'department_id'=> $request->department_id,
                                    'issue_date'=> $request->date,
                                    
                                    'remarks' => $request->$remarks,
                                    'product_id' => $issue_from_vr->id,
                                    'code_id' => $issue_from_vr->code_id,
            
                                    'created_by'=> Auth::user()->id,
                                    'customer_id'=>  $request->customer_id,
                                ]);
        
                            }

                        }else{


                            if ($issue_from_vr->balance_qty >= $request->$qty) {
                                # code...
                                $issue_from_vr->update([
                                    'balance_qty' => $issue_from_vr->balance_qty - $request->$qty,
                                    'mr_qty' => $issue_from_vr->mr_qty + $request->$qty,
                                ]);

                                
                                $issue = Issue::create([
                                    'mr_no'=> $old_issue->mr_no,
                                    'mr_qty'=> $request->$qty,
                                    'shelf_number_id'=> $issue_from_vr->shelf_number_id,
                                    'department_id'=> $issue_from_vr->department_id,
                                    'issue_date'=> $request->date,
                                    
                                    'remarks' => $request->$remarks,
                                    'product_id' => $issue_from_vr->id,
                                    'code_id' => $issue_from_vr->code_id,
            
                                    'created_by'=> Auth::user()->id,
                                    'customer_id'=>  $old_issue->customer_id,
                                ]);
        
                            }
                        }
                    }
                }
                
        }};

        return redirect()->route('issues.index')->with('success', 'Issue was successfully updated');
    }

    public function history(Request $request)  {
        $check = Auth::user()->hasPermissionTo('mr_history');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $histories = IssueHistory::orderby('id', 'desc')->get();;
        return view('issues/history', ['histories' => $histories ]);
    }


    //export excel
    public function export($sort_issues)
    {
        $check = Auth::user()->hasPermissionTo('export_mr');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        for ($i = 0; $i < count($sort_issues); $i++) {
            # code...
            $issue = $sort_issues[$i];
            $product = Product::find($issue->product_id); 
            $unit = Unit::find(optional($product)->unit_id); 
            $customer = Customer::find($issue->customer_id); 

            $shelf_number = ShelfNumber::find($issue->shelf_number_id); 
            $warehouse = Warehouse::find(optional($shelf_number)->warehouse_id); 

            $code = Code::find($product->code_id); 
            $brand = Brand::find(optional($code)->brand_id); 
            $commodity = Brand::find(optional($code)->commodity_id); 

            $c_user = User::find($issue->created_by); 
            $u_user = User::find($issue->updated_by); 

            $sort_issues[$i] = [
                "No" =>  count($sort_issues) - $i,
                "Date" => $issue->issue_date,
                "MR No" => $issue->mr_no,
                'Warehouse' => optional($warehouse)->name,
                "Shelf No" => optional($shelf_number)->name,
                "Customer" => optional($customer)->name,

                "Code" => optional($code)->name,
                "Brand" => optional($brand)->name,
                "Commodity" => optional($commodity)->name,
                "Unit" => optional($unit)->name,
                "MR Qty" => $issue->mr_qty,
                "MRR Qty" => $issue->mrr_qty ?? 0,
                "Remarks" => $issue->remarks,
                "Voucher No" => optional($product)->voucher_no,

                "Create By" => optional($c_user)->name,
                "Updated By" => optional($u_user)->name,
                
                "Created At" => $issue->created_at,
                "Updated At" => $issue->updated_at,
            ];
        }
        $export = new IssuesExport([$sort_issues]);

        return Excel::download($export, 'issues ' . date("Y-m-d") . '.xlsx');
    }

}
