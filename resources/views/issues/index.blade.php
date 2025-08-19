@extends('layouts.master')
@section('title', 'Issue List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("issues.history")}}" type="button" class="btn btn-primary" >
      <i class="fas fa-history"></i>
      History
    </a> 
    <a href="{{ route("issues.create")}}" type="button" class="btn btn-primary" >
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
                $s_issues =  App\Models\Issue::distinct()->get(['mr_no']);
                $job_issues =  App\Models\Issue::where('job_no', '<>', '')
                ->distinct()->get(['job_no']);
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-around">
                {{-- MR NO --}}
                  <div class="col-1">
                    <div class="form-group">
                      <label for="mr_no">MR No</label>
                      <div>

                        <select id='mr_no' name="mr_no" class=" form-control">
                          <option value="" disabled selected>Choose MR No</option>
                          @foreach ($s_issues as $s_issue)
                            @if (isset($_REQUEST['mr_no']))
                                @if ($s_issue->mr_no == $_REQUEST['mr_no'])
                                    <option value="{{ $s_issue->mr_no }}" selected>{{ $s_issue->mr_no }}</option>
                                @else
                                    <option value="{{ $s_issue->mr_no }}">{{ $s_issue->mr_no }}</option>
                                @endif
                            @else
                                <option value="{{$s_issue->mr_no }}">{{ $s_issue->mr_no }}</option> 
                            @endif
                          @endforeach
                        </select>
                      </div>

                    </div>
                  </div>
                {{-- Job NO --}}
                  <div class="col-1">
                    <div class="form-group">
                      <label for="job_no">Job No</label>
                      <div>

                        <select id='job_no' name="job_no" class="form-control">
                          <option value="" disabled selected>Choose Job No</option>
                          @foreach ($job_issues as $job_issue)
                            @if (isset($_REQUEST['job_no']))
                                @if ($job_issue->job_no == $_REQUEST['job_no'])
                                    <option value="{{ $job_issue->job_no }}" selected>{{ $job_issue->job_no }}</option>
                                @else
                                    <option value="{{ $job_issue->job_no }}">{{ $job_issue->job_no }}</option>
                                @endif
                            @else
                                <option value="{{$job_issue->job_no }}">{{ $job_issue->job_no }}</option> 
                            @endif
                          @endforeach
                        </select>
                      </div>

                    </div>
                  </div>
                {{-- Warehouse --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="warehouse_id">Warehouse</label>
                      <div>

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
                  </div>
                {{-- Shelf_No --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="shelf_num_id">Shelf Number</label>
                      <div>

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
                  </div>
                
                {{-- Code --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="code_id">Codes</label>
                      <div>

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
                  </div>
                
                {{-- BrandName --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="brand_id">Brand</label>
                      <div>

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
                  </div>
                
                {{-- Commodity --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="commodity_id">Commodity</label>
                      <div>

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

              </div>


                <div class="row d-flex justify-content-around">

                  {{-- Department --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="department">Department</label>
                    <div>
                      <select id='department_id' name="department_id" class=" form-control">
                        <option value="" disabled selected>Choose Department</option>
                        @foreach ($departments as $department)
                          @if (isset($_REQUEST['department_id']))
                              @if ($department->id == $_REQUEST['department_id'])
                                  <option value="{{ $department->id }}" selected>{{ $department->name }}</option>
                              @else
                                  <option value="{{ $department->id }}">{{ $department->name }}</option>
                              @endif
                          @else
                              <option value="{{ $department->id }}">{{ $department->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                    </div>
                  </div>
                  {{-- VRNO --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="vr_no">VR No</label>
                      <div>

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
                  </div>
                 
                  {{-- customer --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="customer">Customer</label>
                    <div>
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

                  <div class="col-2 d-flex justify-content-around">
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
                  <th>MR No</th>
                  <th>Job No</th>
                  <th>Department</th>
                  <th>Warehouse</th>
                  <th>Shelf No</th>
                  <th>Customer</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Unit</th>
                  <th>MR Qty</th>
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
                @foreach ($issues as $issue)
                @php
                  ++$i;
                  $shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $issue->shelf_number_id)
                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                  ->first([
                    'shelf_numbers.id', 
                    'shelf_numbers.name', 
                    'shelves.name as shelf_name', 
                    'shelf_numbers.warehouse_id'
                  ]); 
                  $department = \App\Models\Department::find(optional($issue)->department_id);
                  $warehouse = \App\Models\Warehouse::find(optional($shelf_no)->warehouse_id);
                  $product =  \App\Models\Product::find($issue->product_id);

                  $code = \App\Models\Code::find(optional($product)->code_id);
                  $brand = \App\Models\Brand::find(optional($code)->brand_id); 
                  $commodity = \App\Models\Commodity::find(optional($code)->commodity_id);
                  $unit = \App\Models\Unit::find(optional($product)->unit_id);

                  $customer = \App\Models\customer::find($issue->customer_id);
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td>{{ $issue->issue_date }}</td>
                      <td>{{ $issue->mr_no }}</td>
                      <td>{{ $issue->job_no }}</td>
                      <td>{{ optional($department)->name }}</td>

                      <td>{{ optional($warehouse)->name }}</td>
                      <td>{{ optional($shelf_no)->name }} ( {{ optional($shelf_no)->shelf_name }})</td>
                      <td>{{ optional($customer)->name }}</td>

                      <td>{{ optional($code)->name }}</td>
                      <td>{{ optional($brand)->name }}</td>
                      <td>{{ optional($commodity)->name }}</td>
                      <td>{{ optional($unit)->name }}</td>

                      <td>{{ $issue->mr_qty }}</td>
                      <td>{{ $issue->mrr_qty }}</td>

                      <td>{{ $issue->remarks }}</td>
                      <td>{{ optional($product)->voucher_no }}</td>
                      <td>
                          <div class="d-flex justify-content-around"> 
                              <a href="{{ route("issues.edit", ["id" => $issue->id] )}}" >
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
                <th>MR No</th>
                <th>Job No</th>
                <th>Department</th>
                <th>Warehouse</th>
                <th>Shelf No</th>
                <th>Customer</th>
                <th>Code</th>
                <th>Brand</th>
                <th>Commodity</th>
                <th>Unit</th>
                <th>MR Qty</th>
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
  $( "#mr_no" ).ready(function() {
      $("#mr_no").select2();
  });
  $( "#job_no" ).ready(function() {
      $("#job_no").select2();
  });
  $( "#department_id" ).ready(function() {
      $("#department_id").select2();
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