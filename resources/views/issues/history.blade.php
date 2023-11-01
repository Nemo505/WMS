@extends('layouts.master')
@section('title', 'Issue History List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("issues.index")}}" style="color:rgb(136, 32, 139)">
        <i class="fas fa-reply"></i>
      Back
    </a>        

@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body" style="overflow-x: scroll;">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>New Date</th>

                  <th>MR No</th>
                  <th>New MR No</th>

                  <th>Shelf Number</th>
                  <th>New Shelf Number</th>

                  <th>Code</th>
                  <th>New Code</th>

                  <th>Unit</th>
                  <th>New Unit</th>
                 
                  <th>MR Qty</th>
                  <th>New MR Qty</th>
                  <th>MRR Qty</th>
                  <th>new MRR Qty</th>

                  <th>Customer</th>
                  <th>New Customer</th>

                  <th>Remark</th>
                  <th>New Remark</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($histories as $history)
                @php
                  ++$i;
                  
                  $shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $history->shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]); 
                  $new_shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $history->new_shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]); 

                  $warehouse = \App\Models\Warehouse::find(optional($shelf_no)->warehouse_id);
                  $new_warehouse = \App\Models\Warehouse::find(optional($new_shelf_no)->warehouse_id);

                  $code = \App\Models\Product::where('products.id', $history->product_id)
                                                ->join('codes', 'codes.id', '=', 'products.code_id')
                                                ->join('units', 'units.id', '=', 'products.unit_id')
                                                ->first(['codes.name', 'units.name as unit_name']); 

                  $new_code = \App\Models\Product::where('products.id', $history->new_product_id)
                                                ->join('codes', 'codes.id', '=', 'products.code_id')
                                                ->join('units', 'units.id', '=', 'products.unit_id')
                                                ->first(['codes.name', 'units.name as unit_name']); 

                  $customer = \App\Models\Customer::find($history->customer_id);
                  $new_customer = \App\Models\Customer::find($history->new_customer_id);
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td>{{ $history->issue_date }}</td>
                      <td>{{ $history->new_issue_date }}</td>

                      <td>{{ $history->mr_no }}</td>
                      <td>{{ $history->new_mr_no }}</td>

                      <td>{{ optional($shelf_no)->name }} ( {{ optional($shelf_no)->shelf_name }})</td>
                      <td>{{ optional($new_shelf_no)->name }} ( {{ optional($new_shelf_no)->shelf_name }})</td>

                      <td>{{ optional($code)->name }}</td>
                      <td>{{ optional($new_code)->name }}</td>
                      <td>{{ optional($code)->unit_name }}</td>
                      <td>{{ optional($new_code)->unit_name }}</td>

                      <td>{{ $history->mr_qty }}</td>
                      <td>{{ $history->new_mr_qty }}</td>

                      <td>{{ $history->mrr_qty }}</td>
                      <td>{{ $history->new_mrr_qty }}</td>
                      
                      <td>{{ optional($customer)->name }}</td>
                      <td>{{ optional($new_customer)->name }}</td>

                      <td>{{ $history->remarks }}</td>
                      <td>{{ $history->new_remarks }}</td>
                      <td >
                            @if ($history->method == 'update')
                                <span class="badge badge-pill fs-6 " style="background-color: rgb(127, 255, 180)">{{ $history->method }}</span>
                            @else
                                <span class="badge badge-pill fs-6" style="background-color: rgb(250, 171, 171)" >{{ $history->method }}</span>
                            @endif 
                      </td>
                  </tr>
                @endforeach
              </tbody>

              <tfoot>
              <tr>
                <th>No</th>
                  <th>Date</th>
                  <th>New Date</th>

                  <th>MRR No</th>
                  <th>New MRR No</th>

                  <th>Shelf Number</th>
                  <th>New Shelf Number</th>

                  <th>Code</th>
                  <th>New Code</th>

                  <th>Unit</th>
                  <th>New Unit</th>
                 
                  <th>MR Qty</th>
                  <th>New MR Qty</th>
                  <th>MRR Qty</th>
                  <th>new MRR Qty</th>

                  <th>Customer</th>
                  <th>New Customer</th>

                  <th>Remark</th>
                  <th>New Remark</th>
                  <th>Action</th>
              </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

    
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->


 
@endsection
@section('scripts')

@endsection