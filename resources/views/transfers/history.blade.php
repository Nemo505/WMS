@extends('layouts.master')
@section('title', 'Transfer History List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("transfers.index")}}" style="color:rgb(136, 32, 139)">
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
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>New Date</th>
                  
                  <th>Transfer No</th>
                  <th>New Transfer No</th>

                  <th>Shelf Number From</th>
                  <th>New Shelf Number From</th>

                  <th>Shelf Number To</th>
                  <th>New Shelf Number To</th>

                  <th>Code</th>
                  <th>New Code</th>

                  <th>Unit</th>
                  <th>New Unit</th>

                  <th>Transfer Qty</th>
                  <th>New Transfer Qty</th>

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
                  
                  $from_shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $history->from_shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]); 

                  $new_from_shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $history->new_from_shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]);

                  $to_shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $history->to_shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]); 
                   

                  $new_to_shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $history->new_to_shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]); 

                  $from_warehouse = \App\Models\Warehouse::find(optional($from_shelf_no)->warehouse_id);
                  $new_from_warehouse = \App\Models\Warehouse::find(optional($new_from_shelf_no)->warehouse_id);

                  $to_warehouse = \App\Models\Warehouse::find(optional($to_shelf_no)->warehouse_id);
                  $new_to_warehouse = \App\Models\Warehouse::find(optional($new_to_shelf_no)->warehouse_id);

                  $code = \App\Models\Product::where('products.id', $history->product_id)
                                                ->join('codes', 'codes.id', '=', 'products.code_id')
                                                ->join('units', 'units.id', '=', 'products.unit_id')
                                                ->first(['codes.name', 'units.name as unit_name']); 

                  $new_code = \App\Models\Product::where('products.id', $history->new_product_id)
                                                ->join('codes', 'codes.id', '=', 'products.code_id')
                                                ->join('units', 'units.id', '=', 'products.unit_id')
                                                ->first(['codes.name', 'units.name as unit_name']); 

                  $unit = \App\Models\Unit::find($history->unit_id); 
                  $new_unit = \App\Models\Unit::find($history->new_unit_id); 

                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td>{{ $history->transfer_date }}</td>
                      <td>{{ $history->new_transfer_date }}</td>

                      <td>{{ $history->transfer_no }}</td>
                      <td>{{ $history->new_transfer_no }}</td>

                      <td>{{ optional($from_shelf_no)->name }} ( {{ optional($from_shelf_no)->shelf_name }})</td>
                      <td>{{ optional($new_from_shelf_no)->name }} ( {{ optional($new_from_shelf_no)->shelf_name }})</td>
                      
                      <td>{{ optional($to_shelf_no)->name }} ( {{ optional($to_shelf_no)->shelf_name }})</td>
                      <td>{{ optional($new_to_shelf_no)->name }} ( {{ optional($new_to_shelf_no)->shelf_name }})</td>

                      <td>{{ optional($code)->name }}</td>
                      <td>{{ optional($new_code)->name }}</td>
                      <td>{{ optional($code)->unit_name }}</td>
                      <td>{{ optional($new_code)->unit_name }}</td>

                      <td>{{ $history->transfer_qty }}</td>
                      <td>{{ $history->new_transfer_qty }}</td>

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
                  
                  <th>Transfer No</th>
                  <th>New Transfer No</th>

                  <th>Shelf Number From</th>
                  <th>New Shelf Number From</th>

                  <th>Shelf Number To</th>
                  <th>New Shelf Number To</th>

                  <th>Code</th>
                  <th>New Code</th>

                  <th>Unit</th>
                  <th>New Unit</th>

                  <th>Transfer Qty</th>
                  <th>New Transfer Qty</th>

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