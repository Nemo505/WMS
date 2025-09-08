@extends('layouts.master')
@section('title', 'Receive History List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("products.index")}}" style="color:rgb(136, 32, 139)">
        <i class="fas fa-reply"></i>
      Back
    </a>        

@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">

           <div class="card-header">
            {{-- form --}}
           
            <form action="" method="GET">

              <div class="row d-flex justify-content-around">
                 
                  {{-- From Date --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="from_date">From Date</label>

                      <div style="width: 80%">
                          @if(isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))
                            @php
                                $new_from_date = date('Y-m-d', strtotime($_REQUEST['from_date']));
                            @endphp
                            <input type="date" value={{$new_from_date}} name="from_date" class="form-control"  id="from_date">
                          @else
                            <input type="date" name="from_date" class="form-control"  id="from_date">
                          @endif
                      </div>

                    </div>
                  </div>
                  {{-- To date --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="to_date">To Date</label>

                      <div style="width: 80%">
                        @if(isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))
                          @php
                              $new_to_date = date('Y-m-d', strtotime($_REQUEST['to_date']));
                          @endphp
                          <input type="date" value={{$new_to_date}} name="to_date" class="form-control"  id="to_date">
                        @else
                          <input type="date" name="to_date" class="form-control"  id="to_date">
                        @endif
                      </div>

                    </div>
                  </div>

                  <div class="col-1">
                      <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" name="search">Search</button>
                      </div>
                  </div>
                  
                  <div class="col"></div>

              </div>
            </form>

          </div>

          <!-- /.card-header -->
          <div class="card-body" style="overflow-x: scroll;">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Action</th>
                  
                  <th>Date</th>
                  <th>New Date</th>

                  <th>Shelf Number</th>
                  <th>New Shelf Number</th>

                  <th>Code</th>
                  <th>New Code</th>

                  <th>Unit</th>
                  <th>New Unit</th>

                  <th>Received Qty</th>
                  <th>New Received Qty</th>

                  <th>Balance</th>
                  <th>Transfer Qty</th>
                  <th>MR Qty</th>
                  <th>MRR Qty</th>
                  <th>SupplierReturn Qty</th>

                  <th>Sub Adjustment</th>
                  <th>Add Adjustment</th>
                  <th>Transfer No</th>

                  <th>Supplier</th>
                  <th>New Supplier</th>

                  <th>Remark</th>
                  <th>New Remark</th>

                  <th>Created By</th>
                  <th>Updated By</th>
                  
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

                  $code = \App\Models\Code::find($history->code_id);
                  $new_code = \App\Models\Code::find($history->new_code_id);

                  $unit = \App\Models\Unit::find($history->unit_id); 
                  $new_unit = \App\Models\Unit::find($history->new_unit_id); 

                  $transfer = \App\Models\Transfer::find($history->transfer_id); 

                  $supplier = \App\Models\Supplier::find($history->supplier_id);
                  $new_supplier = \App\Models\Supplier::find($history->new_supplier_id);

                  $created_by = \App\Models\User::find($history->created_by); 
                  $updated_by = \App\Models\User::find($history->updated_by); 
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td >
                            @if ($history->method == 'update')
                                <span class="badge badge-pill fs-6 " style="background-color: rgb(127, 255, 180)">{{ $history->method }}</span>
                            @else
                                <span class="badge badge-pill fs-6" style="background-color: rgb(250, 171, 171)" >{{ $history->method }}</span>
                            @endif 
                      </td>
                      <td>{{ $history->received_date }}</td>
                      <td>{{ $history->new_received_date }}</td>
                      <td>{{ optional($shelf_no)->name }} ( {{ optional($shelf_no)->shelf_name }})</td>
                      <td>{{ optional($new_shelf_no)->name }} ( {{ optional($new_shelf_no)->shelf_name }})</td>

                      <td>{{ optional($code)->name }}</td>
                      <td>{{ optional($new_code)->name }}</td>
                      <td>{{ optional($unit)->name }}</td>
                      <td>{{ optional($new_unit)->name }}</td>

                      <td>{{ $history->received_qty }}</td>
                      <td>{{ $history->new_received_qty }}</td>
                      <td>{{ $history->balance_qty }}</td>
                      <td>{{ $history->transfer_qty }}</td>

                      <td>{{ $history->mr_qty }}</td>
                      <td>{{ $history->mrr_qty }}</td>
                      <td>{{ $history->supplier_return_qty }}</td>
                      <td>{{ $history->sub_adjustment }}</td>
                      <td>{{ $history->add_adjustment }}</td>

                      <td>{{ optional($transfer)->transfer_no }}</td>
                      <td>{{ optional($supplier)->name }}</td>
                      <td>{{ optional($new_supplier)->name }}</td>

                      <td>{{ $history->remarks }}</td>
                      <td>{{ $history->new_remark }}</td>
                      <td>{{ optional($created_by)->user_name  }}</td>
                      <td>{{ optional($updated_by)->user_name  }}</td>
                  </tr>
                @endforeach
              </tbody>

              <tfoot>
              <tr>
                <th>No</th>
                <th>Action</th>
                
                <th>Date</th>
                <th>New Date</th>
                <th>Shelf Number</th>
                <th>New Shelf Number</th>

                <th>Code</th>
                <th>New Code</th>

                <th>Unit</th>
                <th>New Unit</th>

                <th>Received Qty</th>
                <th>New Received Qty</th>

                <th>Balance</th>
                <th>Transfer Qty</th>
                <th>MR Qty</th>
                <th>MRR Qty</th>
                <th>SupplierReturn Qty</th>

                <th>Sub Adjustment</th>
                <th>Add Adjustment</th>
                <th>Transfer No</th>

                <th>Supplier</th>
                <th>New Supplier</th>

                <th>Remark</th>
                <th>New Remark</th>
                
                <th>Created By</th>
                <th>Updated By</th>
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