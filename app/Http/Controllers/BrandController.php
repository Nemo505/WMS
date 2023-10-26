<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BrandsExport;
use App\Imports\BrandsImport;
use Redirect;
use Auth;
use Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('brand_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $brands = Brand::where(function($query) use ($request){
            if($request->brand_id){
                return $query->where('id', $request->brand_id);
            }
        })
        ->where(function ($query) use ($request){
            if($request->from_date){
                $from_date = date('Y-m-d H:i:s', strtotime($request->from_date));

                return $query->where('created_at', '>=',  $from_date);

            }
        })
        ->where(function ($query) use ($request){
            if($request->to_date){
                $to_date = date('Y-m-d H:i:s', strtotime($request->to_date));
                
                return $query->where('created_at', '<=',  $to_date);
            }
        })
        ->orderbydesc('id')->get();

        if ($request->has('export')) {
            $sort_brands = $brands->sort();
            return $this->export($sort_brands);
        }
        return view('brands/index', [ 'brands' => $brands ]);
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:brands|max:255',
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('brands.index')->with('error', 'Please try again. The same names are not allowed.');
        }
       
        $brand = Brand::Create([
            'name' => $request->name,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand was created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'edit_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('brands.index')->with('error', 'Please try again.');
        }
        $brand = Brand::findOrFail($request->edit_id);

        $brand->update([
            'name' => $request->edit_name,
            'updated_by' => Auth::user()->id
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $brand = Brand::findOrFail($request->del_id);
        if($brand){
            $check_code = Code::where('brand_id', $brand->id)->first();
            if ($check_code) {
                return Redirect::route('brands.index')->with('error'," Please note that brand containing active Code cannot be deleted!");
            } else {
                $brand->delete();
                return Redirect::route('brands.index')->with('success','Successfully Deleted a Shelf Number');          
            }         
        }else{
            return Redirect::route('brands.index')->with('error','Shelf Number Not Found');
        }
    }

    #export excel
    public function export($sort_brands)
    {
        $check = Auth::user()->hasPermissionTo('export_brand');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        for ($i = 0; $i < count($sort_brands); $i++) {
            # code...
            $brand = $sort_brands[$i];
            $c_user = User::find($brand->created_by); 
            $u_user = User::find($brand->updated_by); 

            $sort_brands[$i] = [
                "No" =>  count($sort_brands) - $i,
                "Brand ID" => $brand->id,
                'Brand Name' => $brand->name,
                "Created By" => optional($c_user)->name,
                "Updated By" => optional($u_user)->name,
                "Created At" => $brand->created_at,
                "Updated At" => $brand->updated_at,
            ];
        }
        $export = new BrandsExport([$sort_brands]);

        return Excel::download($export, 'brands ' . date("Y-m-d") . '.xlsx');
    }

    public function import(Request $request){
        $arrays = Excel::toArray(new BrandsImport, $request->file('brands'));
      
        foreach ($arrays[0] as $row){
            $brand =  Brand::where('name',  $row['brand_name'])->first();

            if(!$brand){
                Brand::create([
                    'name' => $row['brand_name'],
                    'created_by'=> Auth::user()->id,
                ]);
            }
        }
        return redirect()->route('brands.index')->with('success', 'Brands Imported successfully');

    }

    public function sample(){
        $path = public_path(). "/excel/samples/Brands.xlsx";
        if(file_exists($path)){
            return response()->download($path);
        }else{
            return redirect()->route('brands.index')->with('error', 'This cannot be downloaded');
        }
    }
    
}
