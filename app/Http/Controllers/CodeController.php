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

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('code_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        
        $query = Code::query();

        if ($request->code_id) {
            $query->where('id', $request->code_id);
        }

        if ($request->brand_id) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->commodity_id) {
            $query->where('commodity_id', $request->commodity_id);
        }

        
        $code_lists = Code::get();
        $brands = Brand::get();
        $commodities = Commodity::get();

        if ($request->has('export')) {
            $exportCodes = $query->orderByDesc('id')->get()->sort(); 
            return $this->export($exportCodes);
        }
        $codes = $query->orderByDesc('id')->paginate(10)->appends(request()->query());

        return view('codes/index', [ 'codes' => $codes,
                                        'code_lists' => $code_lists,
                                        'brands' => $brands,
                                        'commodities' => $commodities
                                     ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:codes|max:255',
            'commodity_id' => 'required',
            'brand_id' => 'required',
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('codes.index')->with('error', 'Please try again. The same names are not allowed.');
        }

        do {
            $number = mt_rand(100000000, 999999999);
        } while (Code::where('barcode', $number)->exists());

        $code = Code::Create([
            'name' => $request->name,
            'commodity_id' => $request->commodity_id,
            'brand_id' => $request->brand_id,
            'created_by' => Auth::user()->id,
            'usage' => $request->usage ?? '-',
            'barcode' => $number,
        ]);

        if ($request->file('image')) {
            $photo = $request->file('image');
            $destinationPath = 'storage/img/code';
            $profileImage = $code->id . "." .$photo->getClientOriginalExtension();
            $photo->move($destinationPath, $profileImage);

            $code->update([
                'image' => "storage/img/code/$profileImage",
            ]);
        }

        return redirect()->route('codes.index')->with('success', 'Code was created successfully');
    }

 
    public function update(Request $request, code $code)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'edit_id' => 'required',
            'e_brand_id' => 'required',
            'e_commodity_id' => 'required',
            
        ]);

        if ($validator->fails())
        {
            return redirect()->route('codes.index')->with('error', 'Please try again.');
        }
        $code = Code::findOrFail($request->edit_id);

        if ($code) {
            
            $code->update([
                'name' => $request->edit_name,
                'brand_id' => $request->e_brand_id,
                'commodity_id' => $request->e_commodity_id,
                'usage' => $request->edit_usage ?? '-',
                'updated_by' => Auth::user()->id
            ]);
            if ($request->file('edit_image')) {
                
                if ($code->image && file_exists($code->image)) {
                     unlink($code->image);
                }
               
                $photo = $request->file('edit_image');
                $destinationPath = 'storage/img/code';
                $profileImage = $code->id . "_" . time() . "." . $photo->getClientOriginalExtension();
                $photo->move($destinationPath, $profileImage);
    
                $code->update([
                    'image' => "storage/img/code/$profileImage",
                ]);
            }
           

        }else{

            return redirect()->route('codes.index')->with('error', 'Code Not Found');
        }
        return redirect()->route('codes.index')->with('success', 'Code was successfully updated');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $code = Code::findOrFail($request->del_id);
        if($code){
            $check_code = Product::where('code_id', $code->id)->first();
            if (!$check_code) {
                # code...
                $code->delete();
                return Redirect::route('codes.index')->with('success','Successfully Deleted a code');          
            }else{
                return Redirect::route('codes.index')->with('error','Please note that Code containing active Product cannot be deleted!');
            }
        }else{
            return Redirect::route('codes.index')->with('error','Code Not Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function printBarcode(Request $request)
    {
        $code = Code::findOrFail($request->id);
        if($code){
            return view('codes.barcode',['code' => $code]);
        }else{
            return Redirect::route('codes.index')->with('error','Code Not Found');
        }
    }

    public function cancel(Request $request)
    {
       $code = Code::findOrFail($request->cancel_id);
        if($code){
            $code->canceled_at = now();
            $code->save();

            return redirect()->route('codes.index')
                     ->with('success', 'Code has been successfully canceled.');
           
        }else{
            return Redirect::route('codes.index')->with('error','Code Not Found');
        }
    }

    #export excel
    public function export($sort_codes)
    {
        $check = Auth::user()->hasPermissionTo('export_code');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

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

        return Excel::download($export,'codes_'.date("Y-m-d").'.xlsx');
    }
    
    public function import(Request $request){
        try {
            DB::beginTransaction();
            $arrays = Excel::toArray(new CodesImport, $request->file('codes'));
          
            foreach ($arrays[0] as $rowNumber => $row) {
                $brandName = trim(preg_replace('/\s+/', ' ', $row['brand_name']));
                $commodityName = trim(preg_replace('/\s+/', ' ', $row['commodity_name']));
                $codeName = trim(preg_replace('/\s+/', ' ', $row['code_name']));
                
                if (!empty($row['brand_name']) && !empty($row['commodity_name'])) {
        
                    $brand = Brand::where('name', $brandName)->first();
                    $commodity = Commodity::where('name', $commodityName)->first();
        
                    if($brand && $commodity){
                        $code = Code::where('name', $codeName)
                                    ->where('brand_id', $brand->id)
                                    ->where('commodity_id', $commodity->id)
                                    ->first();
        
                        if(!$code){
                            do {
                                $number = mt_rand(100000000, 999999999);
                            } while (Code::where('barcode', $number)->exists());

                            Code::create([
                                'name' => $codeName,
                                'brand_id' => $brand->id,
                                'commodity_id' => $commodity->id,
                                'usage' => $row['usage'] ?? "-",
                                'created_by' => Auth::user()->id,
                                'barcode' => $number,
                            ]);
                        }else {
                            return redirect()->route('codes.index')->with('error', "Codes '$codeName' already exists. No changes were made.");
                        }
                    }else{
                            return redirect()->route('codes.index')->with('error', "Row number " . ($rowNumber + 2) . " is empty. No changes were made.");
                        }
                } 
            }
            
            DB::commit();
            return redirect()->route('codes.index')->with('success', 'Codes Imported successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred during import. No changes were made.');
        }

    }

    public function sample(){
        $path = public_path(). "/excel/samples/Codes.xlsx";
        if(file_exists($path)){
            return response()->download($path);
        }else{
            return redirect()->route('codes.index')->with('error', 'This cannot be downloaded');
        }
    }
}
