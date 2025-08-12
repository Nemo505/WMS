<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\IssueReturn;
use App\Models\IssueReturnHistory;
use App\Models\Product;
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
use App\Exports\IssueReturnsExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Redirect;
use Auth;
use Validator;

class IssueReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('mrr_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $mrrs = IssueReturn::where(function($query) use ($request){
            if($request->mrr_id){
                return $query->where('id', $request->mrr_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->mrr_no){
                return $query->where('mrr_no', $request->mrr_no);
            }
        })
        ->where(function ($query) use ($request){
            if($request->warehouse_id){
               
                $shelf_nos = ShelfNumber::where('shelf_numbers.warehouse_id',$request->warehouse_id)
                                            ->join('issues', 'issues.shelf_number_id', '=', 'shelf_numbers.id')
                                            ->get(['issues.id']);
                $w_items = [];
                foreach($shelf_nos as $shelf_no){
                    array_push($w_items, optional($shelf_no)->id);
                }
                                            
                return $query->whereIn('issue_id', $w_items);

            }
        })
        ->where(function ($query) use ($request){
            if($request->shelf_num_id){
                $issue_shelves = Issue::where('issues.shelf_number_id', $request->shelf_num_id)
                                    ->get();

                $s_items = [];
                foreach($issue_shelves as $issue_shelf){
                    array_push($s_items, optional($issue_shelf)->id);
                }
                                            
                return $query->whereIn('issue_id', $s_items);

            }
        })
        ->where(function ($query) use ($request){
            if($request->code_id){
                $codes = Code::where('codes.name', $request->code_id)
                                    ->join('products', 'products.code_id', 'codes.id')
                                    ->join('issues', 'issues.product_id', 'products.id')
                                    ->get(['issues.id']);

                $c_items = [];
                foreach($codes as $code){
                    array_push($c_items, optional($code)->id);
                }
                                            
                return $query->whereIn('issue_id', $c_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->brand_id){
             
                $brands = Code::where('brand_id',$request->brand_id)
                            ->join('products', 'products.code_id', 'codes.id')
                            ->join('issues', 'issues.product_id', 'products.id')
                            ->get(['issues.id']);

                $b_items = [];
                foreach($brands as $brand){
                    array_push($b_items, optional($brand)->id);
                }
                                            
                return $query->whereIn('issue_id', $b_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->commodity_id){

                $commodities = Code::where('commodity_id',$request->commodity_id)
                            ->join('products', 'products.code_id', 'codes.id')
                            ->join('issues', 'issues.product_id', 'products.id')
                            ->get(['issues.id']);

                $com_items = [];
                foreach($commodities as $commodity){
                    array_push($com_items, optional($commodity)->id);
                }
                                            
                return $query->whereIn('issue_id', $com_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->customer_id){
                $issue_customers = Issue::where('issues.customer_id', $request->customer_id)
                                    ->get();

                $cu_items = [];
                foreach($issue_customers as $issue_customer){
                    array_push($cu_items, optional($issue_customer)->id);
                }
                                            
                return $query->whereIn('issue_id', $cu_items);
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_date){
                $from_date = date('Y-m-d', strtotime($request->from_date));

                return $query->where('issue_return_date', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d', strtotime($request->to_date));
                
                return $query->where('issue_return_date', '<=',  $to_date);
            }
        })
        ->orderbydesc('issue_return_date')
        ->get();

        $warehouses = Warehouse::get();
        $customers = Customer::get();
        $codes = Code::distinct()->get(['name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
        $shelfnums = ShelfNumber::get();

        $vr_nos = Issue::join('products', 'products.id', 'issues.product_id')
                        ->distinct()
                        ->get(['products.voucher_no']);

        if ($request->has('export')) {
            $sort_mrrs = $mrrs->sort();
            return $this->export($sort_mrrs);
        }

        return view('issue_returns/index', ['warehouses' => $warehouses,
                                        'customers' => $customers,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'shelfnums' => $shelfnums,
                                        'mrrs' => $mrrs,
                                        'vr_nos' => $vr_nos
                                        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_mrr');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $warehouses = Warehouse::get();
        $customers = Customer::get();
        return view('issue_returns/create', ['warehouses' => $warehouses,
                                     'customers' => $customers
                                        ]);
    }

    public function getCode(Request $request)
    {
        $codes = Product::where('products.shelf_number_id', $request->shelfnum_id)
                        ->where('products.mr_qty', '!=', null)
                        ->where('products.mr_qty', '>', 0)
                        ->join('codes', 'codes.id', '=', 'products.code_id')
                        ->distinct()
                        ->get(['codes.name']);


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
                            ->where('products.mr_qty', '!=', null)
                            ->where('products.mr_qty', '>', 0)
                            ->join("codes", 'products.code_id', '=', "codes.id")
                            ->leftJoin('issues', 'issues.product_id', '=', 'products.id')
                            ->get(['issues.id', 
                                'products.voucher_no', 
                                'issues.mr_no', 
                                'products.balance_qty',
                                'issues.mr_qty',
                                'issues.mrr_qty',
                                'codes.image',
                                'codes.usage'
                            ]);
            return response()->json(['vr_nos' => $vr_nos ]);
        }
        
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request)  {
            $validator = Validator::make($request->all(),[
                "warehouse_id" => 'required',
                "shelfnum_id" => 'required',
                "date" => 'required',
                "mrr_no" => 'required|unique:issue_returns,mrr_no',
            ]); 
            
            if ($validator->fails())
            {
                return Redirect::back()->withInput()
                                        ->with('error', 'Please try again.');
            }
           
            $newRequest = $request->except(['_token',
                                        'warehouse_id',
                                        'shelfnum_id',
                                        'mrr_no',
                                        'date'
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
                    $issue = Issue::find($request->$vr_no);
                    $code_id = Code::where('name', $request->$code)
                                        ->where('brand_id', $request->$brand)
                                        ->where('commodity_id', $request->$commodity)
                                        ->first();

                    if ($issue) {
                        # same issue_return no, product exist?
                        $check_mrr = IssueReturn::where('mrr_no', $request->mrr_no)
                                            ->where('issue_id', $issue->id)
                                            ->first();

                        if (!$check_mrr) {
                            
                            $product = Product::find($issue->product_id);
        
                            $check_mr_balance = $issue->mr_qty - $issue->mrr_qty;
                           
                            if ($check_mr_balance >= $request->$qty) {
                                $mrr = IssueReturn::create([
                                    'mrr_no'=> $request->mrr_no,
                                    'mrr_qty'=> $request->$qty,
                                    'issue_return_date'=> $request->date,
                                    'issue_id'=> $issue->id,
                                    'product_id'=> $issue->product_id,
                                    'code_id'=> $issue->code_id,
                                    'remarks' => $request->$remarks,
                                    'created_by'=> Auth::user()->id,
                                ]);
    
                                $issue->update([
                                    'mrr_qty'=> $issue->mrr_qty + $request->$qty,
                                ]);
                                $product->update([
                                    'mrr_qty'=> $product->mrr_qty + $request->$qty,
                                    'balance_qty'=> $product->balance_qty + $request->$qty,
                                ]);
        
                            }
                        }
    
                    }
                }
            }
            
        });
        return redirect()->route('issue_returns.index')->with('success', 'New Issue Return was created successfully');

    }
 
    public function edit(Request $request, Issue $issue)
    {
        $check = Auth::user()->hasPermissionTo('edit_mrr');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $warehouses = Warehouse::get();
        $customers = Customer::get();

        # Edit IDs to show
        $mrr = IssueReturn::find($request->id);
        if ($mrr) {
            $issue = Issue::find($mrr->issue_id);
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
            $choosen_mrrs = IssueReturn::where('issue_returns.mrr_no', $mrr->mrr_no)
                                    ->join('issues','issues.id',  '=', 'issue_returns.issue_id')
                                    ->where('issues.shelf_number_id', $issue->shelf_number_id)
                                    ->join('products', 'products.id', '=', 'issues.product_id')
                                    ->join('codes', 'codes.id', '=', 'products.code_id')
                                    ->join('brands', 'brands.id', '=', 'codes.brand_id')
                                    ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                                    ->get(['products.id as product_id',
                                            'products.voucher_no', 
                                            'issues.id as issue_id', 
                                            'issues.mr_qty', 
                                            'issues.mr_no', 
                                            'issue_returns.id as issue_return_id', 
                                            'issue_returns.issue_return_date', 

                                            'codes.name as code_name', 
                                            'codes.brand_id',
                                            'brands.name as brand_name',
                                            'codes.commodity_id',
                                            'commodities.name as commodity_name',
                                            'codes.usage',
                                            'codes.image',

                                            'issue_returns.mrr_qty',
                                            'issue_returns.remarks',
                                            'products.balance_qty',
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
                                    ->where('products.mr_qty', '!=', null)
                                    ->where('products.mr_qty', '>', 0)
                                    ->join('codes', 'codes.id', '=', 'products.code_id')
                                    ->distinct()
                                    ->get(['codes.name']);

            return view('issue_returns/edit', ['warehouses' => $warehouses,
                                            'customers' => $customers,
                                            'mrr' => $mrr,
                                            'codes' => $codes,
    
                                            'edit_warehouse' => $edit_warehouse,
                                            'edit_shelfnum' => $edit_shelfnum,
                                            'edit_customer' => $edit_customer,
    
                                            'shelfnums' => $shelfnums,
                                            'choosen_mrrs' => $choosen_mrrs,
                                            'issue_return_date' => $issue_return_date,
                                            ]);
        }else{
            return Redirect::back()->withInput()
                            ->with('error', 'Issue Return Not Found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue)
    {
         DB::transaction(function () use ($request)  {
            if ($request->from_warehouse_id) {
                $validator = Validator::make($request->all(),[
                    "warehouse_id" => 'required',
                    "shelfnum_id" => 'required',
                    "date" => 'required',
                    "mrr_no" => 'required',
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
    
            $old_mrr = IssueReturn::find($request->old_mrr);
    
            $newRequest = $request->except(['_token',
                                            'warehouse_id',
                                            'shelfnum_id',
                                            'date',
                                            'mrr_no',
                                            'old_mrr'
                                        ]);
                                        
            foreach ($newRequest as $key => $value){
                if (str_contains($key, 'mrr_')) {
                    $explode = explode("_",$key);
                    $mrr_id = "mrr_".$explode[1];
                    $qty = "qty_".$explode[1];
    
                    $mrr = IssueReturn::find($request->$mrr_id);
                    if ($mrr) {
                        $issue = Issue::find($mrr->issue_id);
                        $mrr_product = Product::find($issue->product_id);
    
                        if ($mrr_product){
    
                            $mrr_code = Code::find($mrr_product->code_id);
                        }
                    }
    
                    if (!$request->$qty) {
                        
                        if ( $mrr_product->balance_qty > $mrr->mrr_qty) {
                                $mrr_history = IssueReturnHistory::create([
                                    'mrr_no' => $mrr->mrr_no,
                                    'new_mrr_no' => $mrr->mrr_no,
            
                                    'mrr_qty' => $mrr->mrr_qty,
                                    'new_mrr_qty' => $mrr->mrr_qty,
            
                                    'shelf_number_id' => $issue->shelf_number_id,
                                    'new_shelf_number_id' => $issue->shelf_number_id,
            
                                    'issue_id' => $mrr->issue_id,
                                    'new_issue_id' => $mrr->issue_id,
            
                                    'issue_return_date' => $mrr->issue_return_date,
                                    'new_issue_return_date' => $mrr->issue_return_date,
            
                                    'customer_id' => $issue->customer_id,
                                    'new_customer_id' => $issue->customer_id,
                        
                                    'remarks' => $mrr->remarks,
                                    'new_remarks' => $mrr->remarks,
                        
                                    'method' => "delete",
                                    'created_by' => Auth::user()->id,
                                ]);
            
                                $issue->update([
                                    'mrr_qty' =>  $issue->mrr_qty - $mrr->mrr_qty,
                                ]);
                          
                                $mrr_product->update([
                                    'mrr_qty' =>   $mrr_product->mrr_qty - $mrr->mrr_qty,
                                    'balance_qty'=> $mrr_product->balance_qty - $mrr->mrr_qty,
                                ]);
            
                                $mrr->delete();
                        }
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
                    $mrr_id = "mrr_".$explode[1];
    
    
                    $mrr = IssueReturn::find($request->$mrr_id);
                    $new_issue = Issue::find($request->$vr_no);
                    $new_mrr_product = Product::find($new_issue->product_id);
    
                    #update no mrr product
                    if ($mrr && $request->$code ) {
                        $issue = Issue::find($mrr->issue_id);
                        $mrr_product = Product::find($issue->product_id);
    
                        if ($mrr_product){
                            $mrr_code = Code::find($mrr_product->code_id);
                            $check_mrr = IssueReturn::where('mrr_no', $request->mrr_no)
                                                ->where('issue_id', $new_issue->id)
                                                ->where('id', '!=' , $mrr->id)
                                                ->first();
                    
                            if (!$check_mrr) {

                                if ($request->shelfnum_id != $issue->shelf_number_id || 
                                    $request->mrr_no != $mrr->mrr_no || 
                                    $request->date != $mrr->issue_return_date || 
            
                                    $request->$vr_no != $mrr->issue_id || 
                                    $request->$code != $mrr_code->name || 
                                    $request->$qty != $mrr->mrr_qty) {
            
                                        $mrr_history = IssueReturnHistory::create([
                                            'mrr_no' => $mrr->mrr_no,
                                            'new_mrr_no' => $request->mrr_no,
                    
                                            'mrr_qty' => $mrr->mrr_qty,
                                            'new_mrr_qty' => $request->$qty,
                    
                                            'shelf_number_id' => $issue->shelf_number_id,
                                            'new_shelf_number_id' => $request->shelfnum_id,
                    
                                            'issue_id' => $mrr->issue_id,
                                            'new_issue_id' => $new_issue->id,
                    
                                            'issue_return_date' => $mrr->issue_return_date,
                                            'new_issue_return_date' => $request->date,
                    
                                            'customer_id' => $issue->customer_id,
                                            'new_customer_id' => $issue->customer_id,
                                
                                            'remarks' => $mrr->remarks,
                                            'new_remarks' => $request->$remarks,
                                
                                            'method' => "update",
                                            'created_by' => Auth::user()->id,
                                        ]);
                                    
                                }
                
                                    #if same product id, update OR reduce to old- add to new
                                    if ($issue->id == $request->$vr_no) {
                                        
                                        if ($mrr_product->balance_qty > $request->$qty) {
    
                                            $mrr_product->update([
                                                'mrr_qty' =>  $mrr_product->mrr_qty - $mrr->mrr_qty,
                                                'balance_qty' => $mrr_product->balance_qty - $mrr->mrr_qty,
                                            ]);
                                            $issue->update([
                                                'mrr_qty' =>  $issue->mrr_qty - $mrr->mrr_qty,
                                            ]);
                                            
                                            $mrr_product->update([
                                                'mrr_qty' =>  $mrr_product->mrr_qty + $request->$qty,
                                                'balance_qty' => $mrr_product->balance_qty + $request->$qty,
                                            ]);
                                            $issue->update([
                                                'mrr_qty' =>  $issue->mrr_qty + $request->$qty,
                                            ]);
        
                                            $mrr->update([
                                                'mrr_no'=> $request->mrr_no,
                                                'mrr_qty'=> $request->$qty,
                                                'issue_return_date'=> $request->date,
                        
                                                'issue_id'=> $new_issue->id,
                                                'product_id'=> $new_issue->product_id,
                                                'code_id'=> $new_issue->code_id,
                                                
                                                'remarks' => $request->$remarks,
                                                'created_by'=> Auth::user()->id,
                                            ]);
                                        }
                                        
    
                                    }else{
                                        #enough balace in old product for changing mrr qty
                                        if ($mrr_product->balance_qty > $mrr->mrr_qty) {
    
                                            $mrr_product->update([
                                                'mrr_qty' =>  $mrr_product->mrr_qty - $mrr->mrr_qty,
                                                'balance_qty' => $mrr_product->balance_qty - $mrr->mrr_qty,
                                            ]);
                                            $issue->update([
                                                'mrr_qty' =>  $issue->mrr_qty - $mrr->mrr_qty,
                                            ]);
    
                                            $new_mrr_product->update([
                                                'mrr_qty' =>  $new_mrr_product->mrr_qty + $request->$qty,
                                                'balance_qty' => $new_mrr_product->balance_qty + $request->$qty,
                                            ]);
                                            $new_issue->update([
                                                'mrr_qty' =>  $new_issue->mrr_qty + $request->$qty,
                                            ]);
        
                                            $mrr->update([
                                                'mrr_no'=> $request->mrr_no,
                                                'mrr_qty'=> $request->$qty,
                                                'issue_return_date'=> $request->date,
                        
                                                'issue_id'=> $new_issue->id,
                                                'product_id'=> $new_issue->product_id,
                                                'code_id'=> $new_issue->code_id,
                                                
                                                'remarks' => $request->$remarks,
                                                'created_by'=> Auth::user()->id,
                                            ]);
                                        }
                                    }
                                
                                 
                            }
                        }
                    
                    }else{
    
                        $check_mrr = IssueReturn::where('mrr_no', $request->mrr_no)
                                                ->where('issue_id', $new_issue->id)
                                                ->first();
                        if (!$check_mrr) {
                             #new issue return update
                            if ($new_mrr_product->mr_qty >= $request->$qty) {
                                $new_mrr_product->update([
                                    'mrr_qty' =>  $new_mrr_product->mrr_qty + $request->$qty,
                                    'balance_qty' => $new_mrr_product->balance_qty + $request->$qty,
                                ]);
                                $new_issue->update([
                                    'mrr_qty' =>  $new_issue->mrr_qty + $request->$qty,
                                ]);
        
                                $mrr = IssueReturn::create([
                                    'mrr_no'=> $request->mrr_no,
                                    'mrr_qty'=> $request->$qty,
                                    'issue_return_date'=> $request->date,
            
                                    'issue_id'=> $new_issue->id,
                                    'product_id'=> $new_issue->product_id,
                                    'code_id'=> $new_issue->code_id,
            
                                    'remarks' => $request->$remarks,
                                    'created_by'=> Auth::user()->id,
                                ]);
                                
                            }
                        }
                         
                    }
                    
            }};
         });
        return redirect()->route('issue_returns.index')->with('success', 'Issue Return was successfully updated');
    }


    public function history(Request $request)  {
        $check = Auth::user()->hasPermissionTo('mrr_history');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $histories = IssueReturnHistory::orderby('id', 'desc')->get();;
        return view('issue_returns/history', ['histories' => $histories ]);
    }

      //export excel
      public function export($sort_mrrs)
      {
        $check = Auth::user()->hasPermissionTo('export_mrr');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
          for ($i = 0; $i < count($sort_mrrs); $i++) {
              # code...
              $mrr = $sort_mrrs[$i];
              $issue = Issue::find($mrr->issue_id); 

              $product = Product::find($issue->product_id); 
              $unit = Unit::find(optional($product)->unit_id); 
              $customer = Customer::find(optional($issue)->customer_id); 
  
              $shelf_number = ShelfNumber::find(optional($issue)->shelf_number_id); 
              $warehouse = Warehouse::find(optional($shelf_number)->warehouse_id); 
  
              $code = Code::find($product->code_id); 
              $brand = Brand::find(optional($code)->brand_id); 
              $commodity = Commodity::find(optional($code)->commodity_id); 
  
              $c_user = User::find($mrr->created_by); 
              $u_user = User::find($mrr->updated_by); 
  
              $sort_mrrs[$i] = [
                  "No" =>  count($sort_mrrs) - $i,
                  "Date" => $mrr->issue_return_date,
                  "MRR No" => $mrr->mrr_no,
                  'Warehouse' => optional($warehouse)->name,
                  "Shelf No" => optional($shelf_number)->name,
                  "Customer" => optional($customer)->name,
  
                  "Code" => optional($code)->name,
                  "Brand" => optional($brand)->name,
                  "Commodity" => optional($commodity)->name,
                  "Unit" => optional($unit)->name,
                  "MR Qty" => optional($issue)->mr_qty,
                  "MRR Qty" => $mrr->mrr_qty ?? 0,
                  "Remarks" => $mrr->remarks,
                  "Voucher No" => optional($product)->voucher_no,
  
                  "Create By" => optional($c_user)->name,
                  "Updated By" => optional($u_user)->name,
                  
                  "Created At" => $mrr->created_at,
                  "Updated At" => $mrr->updated_at,
              ];
          }
          $export = new IssueReturnsExport([$sort_mrrs]);
  
          return Excel::download($export, 'mrrs ' . date("Y-m-d") . '.xlsx');
      }
}
