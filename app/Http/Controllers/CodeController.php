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

        if ($request->from_date) {
            $from_date = date('Y-m-d H:i:s', strtotime($request->from_date));
            $query->where('created_at', '>=',  $from_date);
        }

        if ($request->to_date) {
            $to_date = date('Y-m-d H:i:s', strtotime($request->to_date));
            $query->where('created_at', '<=',  $to_date);
        }

        $codes = $query->orderByDesc('id')->paginate(10);
        
        $code_lists = Code::get();
        $brands = Brand::get();
        $commodities = Commodity::get();

        if ($request->has('export')) {
            $hasFilters = $request->code_id || $request->brand_id || $request->commodity_id || $request->from_date || $request->to_date;
            if(!$hasFilters){
                $query = Code::query();
                $unsort_codes = $query->orderBy('id', 'desc')->get();
                $sort_codes = $unsort_codes->sort();
                return $this->export($sort_codes);
            }else{
                $sort_codes = $codes->sort();
                return $this->export($sort_codes);
            }
        }
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
       
        $code = Code::Create([
            'name' => $request->name,
            'commodity_id' => $request->commodity_id,
            'brand_id' => $request->brand_id,
            'created_by' => Auth::user()->id,
            'usage' => $request->usage ?? '-',
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


    //export excel
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
                "created at" => $code->created_at,
                "updated at" => $code->updated_at,
            ];
        }
        $export = new CodesExport($sort_codes);

        return Excel::download($export,'codes'.date("Y-m-d").'.xlsx');
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
                            Code::create([
                                'name' => $codeName,
                                'brand_id' => $brand->id,
                                'commodity_id' => $commodity->id,
                                'usage' => $row['usage'] ?? "-",
                                'created_by' => Auth::user()->id,
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

    public function oldimport(Request $request){
        $arrays = Excel::toArray(new CodesImport, $request->file('codes'));
        $spreadsheet = IOFactory::load(request()->file('codes'));
      
        foreach ($arrays[0] as $row){
            Code::create([
                'name' => $row['code_name'],
                'brand_id' => $row['brand_id'],
                'commodity_id' => $row['commodity_id'],
                'usage' => $row['usage'] ?? "-",
                'created_by'=> Auth::user()->id,
            ]);
            
        }
        
        $i = 0;
        $last_code = Code::orderby('id', 'desc')->first();
        $first_id = $last_code->id - count($arrays[0]);

        foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
            
            if ($drawing instanceof MemoryDrawing) {
                ob_start();
                call_user_func(
                    $drawing->getRenderingFunction(),
                    $drawing->getImageResource()
                );
                $imageContents = ob_get_contents();
                ob_end_clean();
                switch ($drawing->getMimeType()) {
                    case MemoryDrawing::MIMETYPE_PNG :
                        $extension = 'png';
                        break;
                    case MemoryDrawing::MIMETYPE_GIF:
                        $extension = 'gif';
                        break;
                    case MemoryDrawing::MIMETYPE_JPEG :
                        $extension = 'jpg';
                        break;
                }
            } else {
                if ($drawing->getPath()) {
                    // Check if the source is a URL or a file path
                    if ($drawing->getIsURL()) {
                        $imageContents = file_get_contents($drawing->getPath());
                        $filePath = tempnam(sys_get_temp_dir(), 'Drawing');
                        file_put_contents($filePath , $imageContents);
                        $mimeType = mime_content_type($filePath);
                        // You could use the below to find the extension from mime type.
                        // https://gist.github.com/alexcorvi/df8faecb59e86bee93411f6a7967df2c#gistcomment-2722664
                        $extension = File::mime2ext($mimeType);
                        unlink($filePath);            
                    }
                    else {
                        $zipReader = fopen($drawing->getPath(),'r');
                        $imageContents = '';
                        while (!feof($zipReader)) {
                            $imageContents .= fread($zipReader,1024);
                        }
                        fclose($zipReader);
                        $extension = $drawing->getExtension();            
                    }
                }
            }
            $myFileName = time() .++$i. '.' . $extension;
            file_put_contents('storage/img/code/' .$myFileName,$imageContents);

            Code::where('id', ++$first_id)->update([
                'image' => "storage/img/code/$myFileName",
            ]);
        }
        return redirect()->route('codes.index')->with('success', 'Codes Imported successfully');

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
