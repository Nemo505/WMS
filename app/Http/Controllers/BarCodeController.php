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
        $product = Product::where('products.shelf_number_id', $request->shelfnum_id)
                        ->join('codes', 'codes.id', '=', 'products.code_id')
                        ->join('brands', 'brands.id', '=', 'codes.brand_id')
                        ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                        ->where('codes.barcode', $x) 
                        ->first([
                            'products.id',
                            'products.transfer_id',
                            'codes.name',
                            'brands.id as brand_id',
                            'brands.name as brand_name',
                            'commodities.id as commodity_id',
                            'commodities.name as commodity_name',
                            'products.voucher_no',
                            'products.balance_qty',
                            'codes.image',
                            'codes.usage',
                            'codes.barcode', 
                        ]);
        $transfer = Transfer::find(optional($product)->transfer_id);
                         
        return response()->json( ['product' => $product, 'transfer' => $transfer]);
    }
    
    public function storeMR(Request $request)
    {
        $x = substr($request->barcode, 0, -1);

        $product = Product::join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
            ->join('codes', 'codes.id', '=', 'products.code_id')
            ->join('brands', 'brands.id', '=', 'codes.brand_id')
            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
            ->where('codes.barcode', $x) 
            ->first([
                'products.id', 
                'products.shelf_number_id',
                'shelf_numbers.name as shelfnum_name',
                'products.transfer_id',
                'codes.name',
                'brands.id as brand_id',
                'brands.name as brand_name',
                'commodities.id as commodity_id',
                'commodities.name as commodity_name',
                'products.voucher_no',
                'products.balance_qty',
                'codes.image',
                'codes.usage',
                'codes.barcode', 
            ]);

        $transfer = Transfer::find(optional($product)->transfer_id);

        return response()->json(['product' => $product, 'transfer' => $transfer]);
    }


    public function storeMRR(Request $request)
    {
        $x = substr($request->barcode, 0, -1);

        $product = Product::join('codes', 'codes.id', '=', 'products.code_id')
            ->join('brands', 'brands.id', '=', 'codes.brand_id')
            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
            ->where('codes.barcode', $x) 
            ->where('products.shelf_number_id', $request->shelfnum_id)
            ->whereNotNull('products.mr_qty')
            ->where('products.mr_qty', '>', 0)
            ->first([
                'products.id',
                'codes.name',
                'brands.name as brand_name',
                'commodities.name as commodity_name',
                'products.voucher_no',
                'products.balance_qty',
                'codes.image',
                'codes.usage',
                'codes.barcode',
            ]);

        $issues = Issue::where('product_id', optional($product)->id)->get();

        return response()->json(['product' => $product, 'issues' => $issues]);
    }


    public function storeSupplier(Request $request)
    {
        $x = substr($request->barcode, 0, -1);

        $product = Product::join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->join('codes', 'codes.id', '=', 'products.code_id')
            ->join('brands', 'brands.id', '=', 'codes.brand_id')
            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
            ->where('codes.barcode', $x)
            ->where('products.shelf_number_id', $request->shelfnum_id)
            ->where('suppliers.name', $request->supplier)
            ->first([
                'products.id',
                'products.transfer_id',
                'codes.name',
                'brands.name as brand_name',
                'commodities.name as commodity_name',
                'products.voucher_no',
                'codes.image',
                'codes.usage',
                'codes.barcode',
            ]);

        $transfer = Transfer::find(optional($product)->transfer_id);

        return response()->json(['product' => $product, 'transfer' => $transfer]);
    }

 
}
