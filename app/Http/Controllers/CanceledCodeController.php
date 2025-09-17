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
        // Only fetch canceled codes
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

        if ($request->from_date) {
            $from_date = date('Y-m-d H:i:s', strtotime($request->from_date));
            $query->where('created_at', '>=', $from_date);
        }

        if ($request->to_date) {
            $to_date = date('Y-m-d H:i:s', strtotime($request->to_date));
            $query->where('created_at', '<=', $to_date);
        }

        $code_lists = Code::withoutGlobalScope('notCanceled')->whereNotNull('canceled_at')->get();
        $brands = Brand::get();
        $commodities = Commodity::get();

        // Export
        if ($request->has('export')) {
            $exportCodes = $query->orderByDesc('id')->get(); 
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


    // Show details of a single canceled code
    public function show(Code $code)
    {
        $code->load(['transfers', 'issues']); // eager load related transactions

        return view('canceled.show', compact('code'));
    }
}

