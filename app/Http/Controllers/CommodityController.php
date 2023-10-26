<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Commodity;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CommoditiesExport;
use App\Imports\CommoditiesImport;
use Redirect;
use Auth;
use Validator;

class CommodityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('commodity_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $commodities = Commodity::where(function($query) use ($request){
            if($request->commodity_id){
                return $query->where('id', $request->commodity_id);
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
            $sort_commodities = $commodities->sort();
            return $this->export($sort_commodities);
        }
        return view('commodities/index', [ 'commodities' => $commodities ]);
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
            'name' => 'required|unique:commodities|max:255',
        ]); 
        
        if ($validator->fails())
        {
            return redirect()->route('commodities.index')->with('error', 'Please try again. The same names are not allowed.');
        }
       
        $commodity = commodity::Create([
            'name' => $request->name,
            'created_by' => Auth::user()->id
        ]);

        return redirect()->route('commodities.index')->with('success', 'commodity was created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(commodity $commodity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(commodity $commodity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commodity $commodity)
    {
        $validator = Validator::make($request->all(),[
            'edit_name' => 'required',
            'edit_id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('commodities.index')->with('error', 'Please try again.');
        }
        $commodity = Commodity::findOrFail($request->edit_id);

        $commodity->update([
            'name' => $request->edit_name,
            'updated_by' => Auth::user()->id
        ]);

        return redirect()->route('commodities.index')->with('success', 'Commodity was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $commodity = Commodity::findOrFail($request->del_id);
        if($commodity){
            $check_code = Code::where('commodity_id', $commodity->id)->first();
            if ($check_code) {
                return Redirect::route('commoditys.index')->with('error'," Please note that commodity containing active Codes cannot be deleted!");
            } else {
                $commodity->delete();
                return Redirect::route('commoditys.index')->with('success','Successfully Deleted a Shelf Number');          
            }         
        }else{
            return Redirect::route('commoditys.index')->with('error','Shelf Number Not Found');
        }
    }


    //export excel
    public function export($sort_commodities)
    {
        $check = Auth::user()->hasPermissionTo('export_commodity');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
        
        for ($i = 0; $i < count($sort_commodities); $i++) {
            # code...
            $commodity = $sort_commodities[$i];
            $c_user = User::find($commodity->created_by); 
            $u_user = User::find($commodity->updated_by); 
                                
            $sort_commodities[$i] = [
                "No" =>  count($sort_commodities) - $i,
                "Commodity ID" => $commodity->id,
                'Commodity Name' => $commodity->name,
                "Created By" => optional($c_user)->name,
                "Updated By" => optional($u_user)->name,
                "Created At" => $commodity->created_at,
                "Updated At" => $commodity->updated_at,
            ];
        }
        $export = new CommoditiesExport([$sort_commodities]);

        return Excel::download($export, 'commodities ' . date("Y-m-d") . '.xlsx');
    }

    public function import(Request $request){
        $arrays = Excel::toArray(new CommoditiesImport, $request->file('commodities'));
      
        foreach ($arrays[0] as $row){
            $commodity =  Commodity::where('name',  $row['commodity_name'])->first();

            if(!$commodity){
                Commodity::create([
                    'name' => $row['commodity_name'],
                    'created_by'=> Auth::user()->id,
                ]);
            }
        }
        return redirect()->route('commodities.index')->with('success', 'Commodity Imported successfully');

    }

    public function sample(){
        $path = public_path(). "/excel/samples/Commodities.xlsx";
        if(file_exists($path)){
            return response()->download($path);
        }else{
            return redirect()->route('commodities.index')->with('error', 'This cannot be downloaded');
        }
    }
}
