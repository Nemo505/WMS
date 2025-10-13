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
        $product = Product::where('products.shelf_number_id', $request->shelfnum_id)
                        ->join('codes', 'codes.id', '=', 'products.code_id')
                        ->join('brands', 'brands.id', '=', 'codes.brand_id')
                        ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
                        ->where('codes.barcode', $request->barcode) 
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

    public function storeProduct(Request $request)
    {
        $code = Code::where('codes.barcode', $request->barcode)
            ->join('brands', 'brands.id', '=', 'codes.brand_id')
            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
            ->first([
                'codes.name',
                'brands.id as brand_id',
                'brands.name as brand_name',
                'commodities.id as commodity_id',
                'commodities.name as commodity_name',
                'codes.barcode'
            ]);
        return response()->json(['code' => $code]);
    }
    
    public function storeMR(Request $request)
    {
        $code = Code::where('codes.barcode', $request->barcode)
            ->join('brands', 'brands.id', '=', 'codes.brand_id')
            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
            ->first([
                'codes.id',
                'codes.name',
                'brands.id as brand_id',
                'brands.name as brand_name',
                'commodities.id as commodity_id',
                'commodities.name as commodity_name',
                'codes.barcode',
                'codes.image'
            ]);

        $shelf_numbers = Product::where('products.code_id', $code->id)
                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                ->join('warehouses', 'warehouses.id', '=', 'shelf_numbers.warehouse_id') // if needed
                                ->get([
                                    'shelf_numbers.id',
                                    'shelf_numbers.name',
                                    'shelves.name as shelf_name',
                                    'warehouses.name as warehouse_name'
                                ]);

        return response()->json(['code' => $code, 'shelf_numbers' => $shelf_numbers]);
    }


    public function storeMRR(Request $request)
    {
        $product = Product::join('codes', 'codes.id', '=', 'products.code_id')
            ->join('brands', 'brands.id', '=', 'codes.brand_id')
            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
            ->where('codes.barcode', $request->barcode) 
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
        $product = Product::join('suppliers', 'suppliers.id', '=', 'products.supplier_id')
            ->join('codes', 'codes.id', '=', 'products.code_id')
            ->join('brands', 'brands.id', '=', 'codes.brand_id')
            ->join('commodities', 'commodities.id', '=', 'codes.commodity_id')
            ->where('codes.barcode', $request->barcode)
            ->where('products.shelf_number_id', $request->shelfnum_id)
            ->where('suppliers.name', $request->supplier)
            ->first([
                'products.id',
                'products.transfer_id',
                'codes.name',
                'brands.name as brand_name',
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

 
}
