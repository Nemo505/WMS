<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\Brand; 
use App\Models\Commodity;
use App\Models\Warehouse;
use App\Models\ShelfNumber;
use App\Models\Product;
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
                            ->where(function ($query) use ($request){
                                    if($request->code_id){
                                        return $query->where('codes.name', $request->code_id);
                                    }
                                })
                                ->where(function ($query) use ($request){
                                    if($request->brand_id){
                                        return $query->where('codes.brand_id', $request->brand_id);
                                    }
                                })
                                ->where(function ($query) use ($request){
                                    if($request->commodity_id){
                                        return $query->where('codes.commodity_id', $request->commodity_id);
                                    }
                                })
                                ->where(function ($query) use ($request){
                                    if($request->warehouse_id){
                                        return $query->where('warehouses.id', $request->warehouse_id);
                                    }
                                })
                            ->select('products.code_id', 'warehouses.id')
                            ->groupBy('products.code_id')
                            ->groupBy('warehouses.id')
                            ->get();
       

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
}
