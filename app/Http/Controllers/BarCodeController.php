<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\Issue;
use App\Models\Code;
use App\Models\Commodity;
use App\Models\Brand;
use Auth;

class BarCodeController extends Controller
{
   
    public function store(Request $request)
    {
        $x = substr ($request->barcode,0,-1);
        $product = Product::where('products.barcode', $x)
                            ->where('products.shelf_number_id', $request->shelfnum_id)
                            ->join('codes', 'codes.id', '=', 'products.code_id')
                            ->join('brands', 'brands.id', '=', 'codes.brand_id')
                            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                            ->first(['products.id', 
                                    'products.transfer_id', 
                                    'codes.name', 
                                    'brands.name as brand_name', 
                                    'commodities.name as commodity_name',
                                    'products.voucher_no',
                                    'products.balance_qty',
                                    'codes.image',
                                    'codes.usage',
                                ]);

        $transfer = Transfer::find(optional($product)->transfer_id);
                         
        return response()->json( ['product' => $product, 'transfer' => $transfer]);
    }

    public function storeMRR(Request $request)
    {
        $x = substr ($request->barcode,0,-1);

        $product = Product::where('products.barcode', $x)
                            ->where('products.shelf_number_id', $request->shelfnum_id)
                            ->where('products.mr_qty', '!=', null)
                            ->where('products.mr_qty', '>', 0)
                            ->join('codes', 'codes.id', '=', 'products.code_id')
                            ->join('brands', 'brands.id', '=', 'codes.brand_id')
                            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                            ->first(['products.id', 
                                    'codes.name', 
                                    'brands.name as brand_name', 
                                    'commodities.name as commodity_name',
                                    'products.voucher_no',
                                    'products.balance_qty',
                                    'codes.image',
                                    'codes.usage',
                                ]);

        $issues = Issue::where('product_id', optional($product)->id)->get();
                         
         return response()->json( ['product' => $product, 'issues' => $issues]);
    }

    public function storeSupplier(Request $request)
    {
        $x = substr ($request->barcode,0,-1);

        $product = Product::where('products.barcode', $x)
                            ->where('products.shelf_number_id', $request->shelfnum_id)
                            ->join('suppliers', 'suppliers.id', '=', 'products.supplier_id' )
                            ->where('suppliers.name', $request->supplier)
                            ->join('codes', 'codes.id', '=', 'products.code_id')
                            ->join('brands', 'brands.id', '=', 'codes.brand_id')
                            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                            ->first(['products.id', 
                                    'products.transfer_id', 
                                    'codes.name', 
                                    'brands.name as brand_name', 
                                    'commodities.name as commodity_name',
                                    'products.voucher_no',
                                    'codes.image',
                                    'codes.usage',
                                ]);

            $transfer = Transfer::find(optional($product)->transfer_id);
        
            return response()->json( ['product' => $product, 'transfer' => $transfer]);
    }

 
}
