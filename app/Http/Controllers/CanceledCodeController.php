<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Brand;
use App\Models\Commodity;
use App\Models\Product;
use App\Models\User;
use App\helpers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CodesExport;
use App\Imports\CodesImport;
use \Milon\Barcode\DNS1D;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Redirect;
use Auth;
use Validator;
use DB;

class CanceledCodeController extends Controller
{

    public function index(Request $request)
    {
        # Only fetch canceled codes
        $query = Code::withoutGlobalScope('notCanceled')
                    ->whereNotNull('canceled_at');
        if ($request->code_id) {
            $query->where('id', $request->code_id);
        }

        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->commodity_id) {
            $query->where('commodity_id', $request->commodity_id);
        }

        $code_lists = Code::withoutGlobalScope('notCanceled')->whereNotNull('canceled_at')->get();
        $brands = Brand::get();
        $commodities = Commodity::get();

        // Export
        if ($request->has('export')) {
            $exportCodes = $query->orderByDesc('id')->get()->sort(); 
            return $this->export($exportCodes);
        }
        $codes = $query->orderByDesc('id')->paginate(10)->appends(request()->query());

        return view('canceled.index', [
            'codes' => $codes,
            'code_lists' => $code_lists,
            'brands' => $brands,
            'commodities' => $commodities
        ]);
    }

    public function show($id)
    {
        $code = Code::withoutGlobalScope('notCanceled')
            ->whereNotNull('canceled_at')
            ->with([
                'transfers' => function ($query) {
                    $query->withoutGlobalScope('activeCode')
                    ->with([
                              'product' => function($q) {
                                  $q->withoutGlobalScope('activeCode');
                              }]);
                },
                'issues' => function ($query) {
                    $query->withoutGlobalScope('activeCode')
                    ->with([
                              'product' => function($q) {
                                  $q->withoutGlobalScope('activeCode');
                              }]);
                },
                'issueReturns' => function ($query) {
                    $query->withoutGlobalScope('activeCode')
                    ->with([
                              'product' => function($q) {
                                  $q->withoutGlobalScope('activeCode');
                              },
                              'issue' => function($q) {
                                  $q->withoutGlobalScope('activeCode');
                              },
                            ]);
                },
                'supplierReturns' => function ($query) {
                    $query->withoutGlobalScope('activeCode')
                    ->with([
                              'product' => function($q) {
                                  $q->withoutGlobalScope('activeCode');
                              }]);
                },
                'adjustments' => function ($query) {
                    $query->withoutGlobalScope('activeCode')
                    ->with([
                              'product' => function($q) {
                                  $q->withoutGlobalScope('activeCode');
                              }]);
                },
                'brand',
                'commodity',
                'products' => function($query) {
                    $query->withoutGlobalScope('activeCode')
                        ->where('type', 'receive');
                },

            ])
            ->findOrFail($id);
       
        return view('canceled.show', compact('code'));
    }

    public function export($sort_codes)
    {

        for ($i = 0; $i < count($sort_codes); $i++) {
            # code...
            $code = $sort_codes[$i];
            $brand = Brand::find($code->brand_id); 
            $commodity = Commodity::find($code->commodity_id); 
            $c_user = User::find($code->created_by); 
            $u_user = User::find($code->updated_by); 
                                
            $sort_codes[$i] = [
                "No" => count($sort_codes) - $i,
                "ImagePath" => $code->image ? basename($code->image) : '',
                'name' => $code->name,
                'Brand Name' => $brand->name,
                'Commodity Name' => $commodity->name,
                'Usage' => $code->usage,
                "created by" => optional($c_user)->name,
                "updated by" => optional($u_user)->name,
                "canceled at" => $code->canceled_at,
                "created at" => $code->created_at,
                "updated at" => $code->updated_at,
            ];
        }
        $export = new CodesExport($sort_codes);

        return Excel::download($export,'canceled_codes_'.date("Y-m-d").'.xlsx');
    }

}

