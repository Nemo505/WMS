<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $check = Auth::user()->hasPermissionTo('customer_list');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $customers = Customer::where(function($query) use ($request){
            if($request->customer){
                return $query->where('id', $request->customer);
            }
            
        })
        ->where(function ($query) use ($request){
            if($request->phone){
                return $query->where('phone', $request->phone);
            }
        })
        ->orderbydesc('id')
        ->get();

        return view('customers/index', [ 'customers' => $customers ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check = Auth::user()->hasPermissionTo('create_customer');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

       return view('customers/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:customers|max:255',
            'phone' => 'required|unique:customers|max:255',
            'address' => 'required',
            'emergency' => 'required',
        ]);
        
        if ($validator->fails())
        {
            return redirect()->route('customers.index')->with('error', 'Please try again. The same names, phones are not allowed.');
        }
       
        $customer = Customer::Create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'emergency' => $request->emergency,
            'created_by' => Auth::user()->id,
        ]);
        if ($request->email) {
            $customer->update([
                'email' => $request->email,
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer was created successfully.');
    }

    public function edit(Request $request, customer $customer)
    {
        $check = Auth::user()->hasPermissionTo('edit_customer');

        if ($check == false) {
            return redirect()->back()->with('error','You do not have permission to access this page.');
        }

        $customer = Customer::findOrFail($request->id);
        return view('customers/edit', [ 'customer' => $customer ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, customer $customer)
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
            return redirect()->route('customers.index')->with('error', 'Please try again.');
        }
        $customer = Customer::findOrFail($request->id);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'emergency' => $request->emergency,
            'email' => $request->email,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer was successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       $customer = Customer::findOrFail($request->del_id);
        if($customer){
            $customer->delete();
            return Redirect::route('customers.index')->with('success','Successfully Deleted a customer');          
        }else{
            return Redirect::route('customers.index')->with('error','Customer Not Found');
        }
    }

}
