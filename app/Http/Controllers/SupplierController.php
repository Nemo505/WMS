<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuppliersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('supplier_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $suppliers = Supplier::where(function($query) use ($request){
            if($request->supplier_id){
                return $query->where('id', $request->supplier_id);
            }
        })
        ->orderbydesc('id')
        ->get();

        if ($request->has('export')) {
            $sort_suppliers = $suppliers->sort();
            return $this->export($sort_suppliers);
        }

        return view('suppliers/index', [ 'suppliers' => $suppliers ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_supplier');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }
       return view('suppliers/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:suppliers|max:255',
            'phone' => 'required|unique:suppliers|max:255',
            'address' => 'required',
            'emergency' => 'required',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->route('suppliers.index')->with('error', 'Please try again. The same names, phones are not allowed.');
        }
       
        $supplier = Supplier::Create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'emergency' => $request->emergency,
            'created_by' => Auth::user()->id,
        ]);
        if ($request->email) {
            $supplier->update([
                'email' => $request->email,
            ]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier was created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Supplier $supplier)
    {
        $check = Auth::user()->hasPermissionTo('edit_supplier');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $supplier = Supplier::findOrFail($request->id);
        return view('suppliers/edit', [ 'supplier' => $supplier ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, supplier $supplier)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'emergency' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->route('suppliers.index')->with('error', 'Please try again.');
        }
        $supplier = Supplier::findOrFail($request->id);

        $supplier->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'emergency' => $request->emergency,
            'email' => $request->email,
            'updated_by' => Auth::user()->id,
        ]);

        if ($request->email) {
            # code...
            $supplier->update([
                'email' => $request->email,
            ]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $supplier = Supplier::findOrFail($request->id);
        if($supplier){
            $supplier->delete();
            return Redirect::route('suppliers.index')->with('success','Successfully Deleted a supplier');          
        }else{
            return Redirect::route('suppliers.index')->with('error','Supplier Not Found');
        }
    }

        //export excel
        public function export($sort_suppliers)
        {
            for ($i = 0; $i < count($sort_suppliers); $i++) {
                # code...
                $supplier = $sort_suppliers[$i];
    
                $c_user = User::find($supplier->created_by); 
                $u_user = User::find($supplier->updated_by); 
                                    
                $sort_suppliers[$i] = [
                    "No" =>  count($sort_suppliers) - $i,
                    "Supplier ID" => $supplier->id,
                    'Supplier Name' => $supplier->name,
                    "Created By" => optional($c_user)->name,
                    "Updated By" => optional($u_user)->name,
                    "Created At" => $supplier->created_at,
                    "Updated At" => $supplier->updated_at,
                ];
            }
            $export = new SuppliersExport([$sort_suppliers]);
    
            return Excel::download($export, 'suppliers ' . date("Y-m-d") . '.xlsx');
        }

}
