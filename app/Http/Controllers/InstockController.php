<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\Brand; 
use App\Models\Commodity;
use Auth;

class InstockController extends Controller
{
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('instock_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        $instocks = Code::where(function ($query) use ($request){
            if($request->code_id){
                return $query->where('name', $request->code_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->brand_id){
                return $query->where('brand_id', $request->brand_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->commodity_id){
                return $query->where('commodity_id', $request->commodity_id);
            }
        })
        ->orderbydesc('id')
        ->get();
        
        $codes = Code::distinct()->get(['name']);
        $brands = Brand::get();
        $commodities = Commodity::get();
      
        return view('instocks/index', ['instocks' => $instocks,
                                        'codes' => $codes,
                                        'brands' => $brands,
                                        'commodities' => $commodities,
                                        ]);
    }
}
