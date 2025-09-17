<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\Brand; 
use App\Models\Commodity;
use App\Models\Warehouse;
use App\Models\ShelfNumber;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InstocksExport;
use Auth;

class InstockController extends Controller
{
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('instock_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
       
       $instocks = Warehouse::join('shelf_numbers', 'warehouses.id', '=', 'shelf_numbers.warehouse_id')
                            ->join('products', 'products.shelf_number_id', '=', 'shelf_numbers.id')
                            ->join('codes', 'codes.id', '=', 'products.code_id')
                            ->whereNull('warehouses.deleted_at')
                            ->whereNull('shelf_numbers.deleted_at')
                            ->whereNull('products.deleted_at')
                            ->whereNull('codes.deleted_at')
                            ->where(function ($query) use ($request) {
                                if ($request->code_id) {
                                    $query->where('codes.name', $request->code_id);
                                }
                            })
                            ->where(function ($query) use ($request) {
                                if ($request->brand_id) {
                                    $query->where('codes.brand_id', $request->brand_id);
                                }
                            })
                            ->where(function ($query) use ($request) {
                                if ($request->commodity_id) {
                                    $query->where('codes.commodity_id', $request->commodity_id);
                                }
                            })
                            ->where(function ($query) use ($request) {
                                if ($request->warehouse_id) {
                                    $query->where('warehouses.id', $request->warehouse_id);
                                }
                            })
                            ->select('products.code_id', 'warehouses.id')
                            ->groupBy('products.code_id', 'warehouses.id');
                            
        if ($request->has('export')) {
            return $this->export($instocks->get(), $request->from_date, $request->to_date);
        }
        
        $instocks = $instocks->paginate(10)->appends(request()->query());

        $codes = Code::distinct()->get(['name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
        $warehouses = Warehouse::get();
        
        return view('instocks/index', ['instocks' => $instocks,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        'warehouses' => $warehouses,
                                        ]);
    }
    
    //export excel
    public function export($instocks, $request_from, $request_to)
    {
        for ($i = 0; $i < count($instocks); $i++) {
            # code...
            $instock = $instocks[$i];
            
            $code = \App\Models\Code::find($instock->code_id); 
            
            $brand = \App\Models\Brand::find(optional($code)->brand_id); 
            $commodity = \App\Models\Commodity::find(optional($code)->commodity_id);
            $warehouse = \App\Models\Warehouse::find($instock->id);
    
            #fromdate to today
            

            if ($request_from) {
                    $from_date = !empty($request_from) 
                                    ? date('Y-m-d', strtotime($request_from)) 
                                    : optional(
                                        \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->orderBy('products.received_date', 'asc')
                                            ->first()
                                    )->received_date;
                    $from_date = $from_date ? date('Y-m-d', strtotime($from_date)) : null;
                    $to_date = !empty($request_to) 
                                ? date('Y-m-d', strtotime($request_to)) 
                                : date('Y-m-d'); 
                
                    // from or request_from to today or request_to
                    $received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('products.received_date', [$from_date, $to_date])
                                        ->where('products.type', 'receive')
                                        ->sum('products.received_qty');
                                        
                    $transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('products.received_date', [$from_date, $to_date])
                                        ->where('products.type', 'transfer')
                                        ->sum('products.received_qty');
                
                    $transfer_out = \App\Models\Transfer::where('transfers.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('transfers.transfer_date', [$from_date, $to_date])
                                        ->sum('transfers.transfer_qty');
                                            
                    $mr_qty = \App\Models\Issue::where('issues.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'issues.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('issues.issue_date', [$from_date, $to_date])
                                        ->sum('issues.mr_qty');
                
                    $mrr_qty = \App\Models\IssueReturn::where('issue_returns.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'issue_returns.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('issue_returns.issue_return_date', [$from_date, $to_date])
                                        ->sum('issue_returns.mrr_qty');
                
                    $sup_qty = \App\Models\SupplierReturn::where('supplier_returns.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'supplier_returns.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('supplier_returns.supplier_return_date', [$from_date, $to_date])
                                        ->sum('supplier_returns.supplier_return_qty');
                
                    $add_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'adjustments.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('adjustments.adjustment_date', [$from_date, $to_date])
                                        ->where('adjustments.type', 'add')
                                        ->sum('adjustments.qty');
                
                    $sub_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'adjustments.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereBetween('adjustments.adjustment_date', [$from_date, $to_date])
                                        ->where('adjustments.type', 'sub')
                                        ->sum('adjustments.qty');
                
                    $opening_balance_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
                    //  start to before request_to                       
                    $opening_received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->whereDate('products.received_date', '<=', $opening_balance_date)
                                            ->where('products.type', 'receive')
                                            ->sum('products.received_qty');
                                        
                    $opening_transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('products.received_date', '<=', $opening_balance_date)
                                        ->where('products.type', 'transfer')
                                        ->sum('products.received_qty');
                
                    $opening_transfer_out = \App\Models\Transfer::where('transfers.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('transfers.transfer_date', '<=', $opening_balance_date)
                                        ->sum('transfers.transfer_qty');
                
                    $opening_mr_qty = \App\Models\Issue::where('issues.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'issues.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('issues.issue_date', '<=', $opening_balance_date)
                                        ->sum('issues.mr_qty');
                
                    $opening_mrr_qty = \App\Models\IssueReturn::where('issue_returns.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'issue_returns.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('issue_returns.issue_return_date', '<=', $opening_balance_date)
                                        ->sum('issue_returns.mrr_qty');
                
                    $opening_sup_qty = \App\Models\SupplierReturn::where('supplier_returns.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'supplier_returns.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('supplier_returns.supplier_return_date', '<=', $opening_balance_date)
                                        ->sum('supplier_returns.supplier_return_qty');
                
                    $opening_add_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'adjustments.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('adjustments.adjustment_date', '<=', $opening_balance_date)
                                        ->where('adjustments.type', 'add')
                                        ->sum('adjustments.qty');
                
                    $opening_sub_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'adjustments.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('adjustments.adjustment_date', '<=', $opening_balance_date) 
                                        ->where('adjustments.type', 'sub')
                                        ->sum('adjustments.qty');
                    
                    
                    $opening_qty = ($opening_received_qty + $opening_mrr_qty + $opening_add_adjust + $opening_sup_qty + $opening_transfer_in) - ($opening_mr_qty + $opening_sub_adjust + $opening_transfer_out);
                                                
                    // start to request_to                    
                    $start_received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('products.received_date', '<=', $to_date)
                                        ->where('products.type', 'receive')
                                        ->sum('products.received_qty');
                                        
                    $start_transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('products.received_date', '<=', $to_date)
                                        ->where('products.type', 'transfer')
                                        ->sum('products.received_qty');
                
                    $start_transfer_out = \App\Models\Transfer::where('transfers.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('transfers.transfer_date', '<=', $to_date)
                                        ->sum('transfers.transfer_qty');
                    
                    $start_mr_qty = \App\Models\Issue::where('issues.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'issues.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('issues.issue_date', '<=', $to_date)
                                        ->sum('issues.mr_qty');
                
                    $start_mrr_qty = \App\Models\IssueReturn::where('issue_returns.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'issue_returns.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('issue_returns.issue_return_date', '<=', $to_date)
                                        ->sum('issue_returns.mrr_qty');
                
                    $start_sup_qty = \App\Models\SupplierReturn::where('supplier_returns.code_id', $instock->code_id)
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'supplier_returns.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('supplier_returns.supplier_return_date', '<=', $to_date)
                                        ->sum('supplier_returns.supplier_return_qty');
                
                    $start_add_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'adjustments.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('adjustments.adjustment_date', '<=', $to_date)
                                        ->where('adjustments.type', 'add')
                                        ->sum('adjustments.qty');
                
                    $start_sub_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                        ->join('products', 'products.id', '=', 'adjustments.product_id')
                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                        ->whereDate('adjustments.adjustment_date', '<=', $to_date) 
                                        ->where('adjustments.type', 'sub')
                                        ->sum('adjustments.qty');
                    
                    $balance_qty = ($start_received_qty + $start_mrr_qty + $start_add_adjust + $start_sup_qty + $start_transfer_in) - ($start_mr_qty + $start_sub_adjust + $start_transfer_out);
                
                } else {
                     // Default case
                     $received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->where('products.type', 'receive')
                                    ->sum('products.received_qty');
                                    
                    
                    $from = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->orderBy('products.received_date', 'asc')
                                                ->first();
                    
                
                    $transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->where('products.type', 'transfer')
                                    ->sum('products.received_qty');
                
                    $transfer_out = \App\Models\Transfer::where('code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->sum('transfers.transfer_qty');
                
                    $balance_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->sum('products.balance_qty');
                
                    $mr_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->sum('products.mr_qty');
                
                    $mrr_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->sum('products.mrr_qty');
                
                    $sup_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->sum('products.supplier_return_qty');
                
                    $add_adjust = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->sum('products.add_adjustment');
                
                    $sub_adjust = \App\Models\Product::where('products.code_id', $instock->code_id)
                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                    ->sum('products.sub_adjustment');
                    $opening_qty = ($received_qty + $mrr_qty + $add_adjust + $sup_qty + $transfer_in) - ($mr_qty + $sub_adjust+ $transfer_out);
                }


          
            $instocks[$i] = [
                "No" => $i,
                'Warehouse' => $warehouse->name,
    
                "Code" => optional($code)->name,
                "Brand" => optional($brand)->name,
                "Commodity"  => optional($commodity)->name,
                
                "Total Opening Qty"  => $opening_qty,
                "Total Received Qty"  => $received_qty,
                "Total Transfer In"  => $transfer_in,
                "Total Transfer Out"  => $transfer_out,
                
                "Total MR Qty"  => $mr_qty,
                "Total MRR Qty"  => $mrr_qty,
                "Total Supplier Return"  => $sup_qty,
                
                "Total Add Adjustment"  => $add_adjust,
                "Total Sub Adjustment "  => $sub_adjust,
                "Total Balance "  => $balance_qty
            ];
        }
        $export = new InstocksExport([$instocks]);

        return Excel::download($export, 'instocks ' . date("Y-m-d") . '.xlsx');
    }
}
