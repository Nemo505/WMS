@extends('layouts.master')
@section('title', 'Issue Return List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("issue_returns.history")}}" type="button" class="btn btn-primary" >
      <i class="fas fa-history"></i>
      History
    </a> 
    <a href="{{ route("issue_returns.create")}}" type="button" class="btn btn-primary" >
      <i class="fa fa-solid fa-plus" style="color: #ffffff;"></i>
      Add New
    </a>

@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            {{-- form --}}
            @php
                $s_issues =  App\Models\IssueReturn::distinct()->get(['mrr_no']);
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-around">
                {{-- MR NO --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="mrr_no">MRR No</label>

                      <select id='mrr_no' name="mrr_no" class=" form-control">
                        <option value="" disabled selected>Choose MRR No</option>
                        @foreach ($s_issues as $s_issue)
                          @if (isset($_REQUEST['mrr_no']))
                              @if ($s_issue->mrr_no == $_REQUEST['mrr_no'])
                                  <option value="{{ $s_issue->mrr_no }}" selected>{{ $s_issue->mrr_no }}</option>
                              @else
                                  <option value="{{ $s_issue->mrr_no }}">{{ $s_issue->mrr_no }}</option>
                              @endif
                          @else
                              <option value="{{$s_issue->mrr_no }}">{{ $s_issue->mrr_no }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                {{-- Warehouse --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="warehouse_id">Warehouse</label>

                      <select id='warehouse_id' name="warehouse_id" class=" form-control">
                        <option value="" disabled selected>Choose Warehouse</option>
                        @foreach ($warehouses as $warehouse)
                          @if (isset($_REQUEST['warehouse_id']))
                              @if ($warehouse->id == $_REQUEST['warehouse_id'])
                                  <option value="{{ $warehouse->id }}" selected>{{ $warehouse->name }}</option>
                              @else
                                  <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                              @endif
                          @else
                              <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                {{-- Shelf_No --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="shelf_num_id">Shelf Number</label>

                      <select id='shelf_num_id' name="shelf_num_id" class=" form-control">
                        <option value="" disabled selected>Choose Shelf No</option>

                        @foreach ($shelfnums as $shelfnum)
                        @php
                            $shelf = \App\Models\Shelf::find($shelfnum->shelf_id);
                        @endphp
                          @if (isset($_REQUEST['shelf_num_id']))
                              @if ($shelfnum->id == $_REQUEST['shelf_num_id'])
                                  <option value="{{ $shelfnum->id }}" selected>{{ $shelfnum->name }} ({{ $shelf->name }})</option>
                              @else
                                  <option value="{{ $shelfnum->id }}">{{ $shelfnum->name }} ({{ $shelf->name }})</option>
                              @endif
                          @else
                              <option value="{{ $shelfnum->id }}">{{ $shelfnum->name }} ({{ $shelf->name }})</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                
                {{-- Code --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="code_id">Codes</label>

                      <select id='code_id' name="code_id" class=" form-control">
                        <option value="" disabled selected>Choose Code</option>
                        @foreach ($codes as $code)
                          @if (isset($_REQUEST['code_id']))
                              @if ($code->name == $_REQUEST['code_id'])
                                  <option value="{{ $code->name }}" selected>{{ $code->name }}</option>
                              @else
                                  <option value="{{ $code->name }}">{{ $code->name }}</option>
                              @endif
                          @else
                              <option value="{{ $code->name }}">{{ $code->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                
                {{-- BrandName --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="brand_id">Brand</label>

                      <select id='brand_id' name="brand_id" class=" form-control">
                        <option value="" disabled selected>Choose Brand</option>
                        @foreach ($brands as $brand)
                          @if (isset($_REQUEST['brand_id']))
                              @if ($brand->id == $_REQUEST['brand_id'])
                                  <option value="{{ $brand->id }}" selected>{{ $brand->name }}</option>
                              @else
                                  <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                              @endif
                          @else
                              <option value="{{ $brand->id }}">{{ $brand->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                
                {{-- Commodity --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="commodity_id">Commodity</label>

                      <select id='commodity_id' name="commodity_id" class=" form-control">
                        <option value="" disabled selected>Choose Commodity</option>
                        @foreach ($commodities as $commodity)
                          @if (isset($_REQUEST['commodity_id']))
                              @if ($commodity->id == $_REQUEST['commodity_id'])
                                  <option value="{{ $commodity->id }}" selected>{{ $commodity->name }}</option>
                              @else
                                  <option value="{{ $commodity->id }}">{{ $commodity->name }}</option>
                              @endif
                          @else
                              <option value="{{ $commodity->id }}">{{ $commodity->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>

              </div>


                <div class="row d-flex justify-content-around">
                  {{-- VRNO --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="vr_no">VR No</label>

                      <select id='vr_no' name="vr_no" class=" form-control">
                        <option value="" disabled selected>Choose VR No</option>
                        @foreach ($vr_nos as $vr_no)
                          @if (isset($_REQUEST['vr_no']))
                              @if ($vr_no->voucher_no == $_REQUEST['vr_no'])
                                  <option value="{{ $vr_no->voucher_no }}" selected>{{ $vr_no->voucher_no }}</option>
                              @else
                                  <option value="{{ $vr_no->voucher_no }}">{{ $vr_no->voucher_no }}</option>
                              @endif
                          @else
                              <option value="{{$vr_no->voucher_no }}">{{ $vr_no->voucher_no }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                 
                  {{-- customer --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="customer">Customer</label>

                      <select id='customer_id' name="customer_id" class=" form-control">
                        <option value="" disabled selected>Choose Customer</option>
                        @foreach ($customers as $customer)
                          @if (isset($_REQUEST['customer_id']))
                              @if ($customer->id == $_REQUEST['customer_id'])
                                  <option value="{{ $customer->id }}" selected>{{ $customer->name }}</option>
                              @else
                                  <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                              @endif
                          @else
                              <option value="{{ $customer->id }}">{{ $customer->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                
                  {{-- From Date --}}
                  <div class="col-2">
                    <div class="">
                      <label for="from_date">From Date</label>
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
                  {{-- To date --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="to_date">To Date</label>
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

                  <div class="col-4 d-flex justify-content-center">
                    <div class="col-4">
                      <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" name="search">Search</button>
                      </div>
                    </div>

                    <div class="col-4">
                      <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" name="export">Export</button>
                      </div>
                    </div>
                  </div>

                </div>
            </form>

          </div>
          <!-- /.card-header -->
          <div class="card-body" style="overflow-x: scroll;">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>MRR No</th>
                  <th>Warehouse</th>
                  <th>Shelf No</th>
                  <th>Customer</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Unit</th>
                  <th>MRR Qty</th>
                  <th>Remarks</th>
                  <th>VR No</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($mrrs as $mrr)
                
                @php
                  ++$i;
                  $issue = \App\Models\Issue::find($mrr->issue_id);

                  $shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', '=', optional($issue)->shelf_number_id)
                                                ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                ]); 
                  $warehouse = \App\Models\Warehouse::find(optional($shelf_no)->warehouse_id);
                  $product =  \App\Models\Product::find(optional($issue)->product_id);

                  $code = \App\Models\Code::find(optional($product)->code_id);
                  $brand = \App\Models\Brand::find(optional($code)->brand_id); 
                  $commodity = \App\Models\Commodity::find(optional($code)->commodity_id);
                  $unit = \App\Models\Unit::find(optional($product)->unit_id);

                  $customer = \App\Models\customer::find(optional($issue)->customer_id);
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td>{{ $mrr->issue_return_date }}</td>
                      <td>{{ $mrr->mrr_no }}</td>

                      <td>{{ optional($warehouse)->name }}</td>
                      <td>{{ optional($shelf_no)->name }} ( {{ optional($shelf_no)->shelf_name }})</td>
                      <td>{{ optional($customer)->name }}</td>

                      <td>{{ optional($code)->name }}</td>
                      <td>{{ optional($brand)->name }}</td>
                      <td>{{ optional($commodity)->name }}</td>
                      <td>{{ optional($unit)->name }}</td>

                      <td>{{ $mrr->mrr_qty }}</td>

                      <td>{{ $mrr->remarks }}</td>
                      <td>{{ optional($product)->voucher_no }}</td>
                      <td>
                          <div class="d-flex justify-content-around"> 
                              <a href="{{ route("issue_returns.edit", ["id" => $mrr->id] )}}" >
                                  <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                              </a>
                          </div>
                      </td>
                  </tr>
                @endforeach
              </tbody>

              <tfoot>
              <tr>
                <th>No</th>
                <th>Date</th>
                <th>MRR No</th>
                <th>Warehouse</th>
                <th>Shelf No</th>
                <th>Customer</th>
                <th>Code</th>
                <th>Brand</th>
                <th>Commodity</th>
                <th>Unit</th>
                <th>MRR Qty</th>
                <th>Remarks</th>
                <th>VR No</th>
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

<script>
  $( "#vr_no" ).ready(function() {
      $("#vr_no").select2();
  });
  $( "#mrr_no" ).ready(function() {
      $("#mrr_no").select2();
  });
  $( "#warehouse_id" ).ready(function() {
      $("#warehouse_id").select2();
  });
  $( "#shelf_num_id" ).ready(function() {
      $("#shelf_num_id").select2();
  });
  $( "#code_id" ).ready(function() {
      $("#code_id").select2();
  });
  $( "#brand_id" ).ready(function() {
      $("#brand_id").select2();
  });
  $( "#customer_id" ).ready(function() {
      $("#customer_id").select2();
  });
  $( "#commodity_id" ).ready(function() {
      $("#commodity_id").select2();
  });
</script>

@endsection