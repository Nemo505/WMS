@extends('layouts.master')
@section('title', 'Adjustment List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("adjustments.history")}}" type="button" class="btn btn-primary" >
      <i class="fas fa-history"></i>
      History
    </a> 
    <a href="{{ route("adjustments.create")}}" type="button" class="btn btn-primary" >
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
                $s_products =  App\Models\Product::distinct()->get(['voucher_no']);
                $s_adjustments =  App\Models\Adjustment::distinct()->get(['adjustment_no']);
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-around">
                {{-- Adjustment No --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="adjust_no">Adjustment No</label>

                      <select id='adjust_no' name="adjust_no" class=" form-control">
                        <option value="" selected>Choose Adjustment No</option>
                        @foreach ($s_adjustments as $s_adjustment)
                          @if (isset($_REQUEST['adjust_no']))
                              @if ($s_adjustment->adjustment_no == $_REQUEST['adjust_no'])
                                  <option value="{{ $s_adjustment->adjustment_no }}" selected>{{ $s_adjustment->adjustment_no }}</option>
                              @else
                                  <option value="{{ $s_adjustment->adjustment_no }}">{{ $s_adjustment->adjustment_no }}</option>
                              @endif
                          @else
                              <option value="{{$s_adjustment->adjustment_no }}">{{ $s_adjustment->adjustment_no }}</option> 
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
                        <option value="" selected>Choose Warehouse</option>
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
                        <option value="" selected>Choose Shelf No</option>

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
                        <option value="" selected>Choose Code</option>
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
                        <option value="" selected>Choose Brand</option>
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
                        <option value="" selected>Choose Commodity</option>
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
                 
                  {{-- Supplier --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="supplier">Supplier</label>

                      <select id='supplier_id' name="supplier_id" class=" form-control">
                        <option value="" selected>Choose Supplier</option>
                        @foreach ($suppliers as $supplier)
                          @if (isset($_REQUEST['supplier_id']))
                              @if ($supplier->id == $_REQUEST['supplier_id'])
                                  <option value="{{ $supplier->id }}" selected>{{ $supplier->name }}</option>
                              @else
                                  <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                              @endif
                          @else
                              <option value="{{ $supplier->id }}">{{ $supplier->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>

                  {{-- VRNO --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="vr_no">VR No</label>

                      <select id='vr_no' name="vr_no" class=" form-control">
                        <option value="" selected>Choose VR No</option>
                        @foreach ($s_products as $s_product)
                          @if (isset($_REQUEST['vr_no']))
                              @if ($s_product->voucher_no == $_REQUEST['vr_no'])
                                  <option value="{{ $s_product->voucher_no }}" selected>{{ $s_product->voucher_no }}</option>
                              @else
                                  <option value="{{ $s_product->voucher_no }}">{{ $s_product->voucher_no }}</option>
                              @endif
                          @else
                              <option value="{{$s_product->voucher_no }}">{{ $s_product->voucher_no }}</option> 
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
                  <div class="col-2">
                  </div>

                  <div class="col-2 d-flex justify-content-center">
                    <div class="col-6">
                      <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" name="search">Search</button>
                      </div>
                    </div>

                    <div class="col-6">
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
                  <th>Adjustment No</th>
                  <th>Warehouse</th>
                  <th>Shelf No</th>
                  <th>Supplier</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Unit</th>
                  <th>Qty</th>
                  <th>Remarks</th>
                  <th>VR No</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($adjustments as $adjustment)
                @php
                  ++$i;

                  $product =  \App\Models\Product::find($adjustment->product_id);
                  
                  $shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', optional($product)->shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]); 

                  $warehouse = \App\Models\Warehouse::find(optional($shelf_no)->warehouse_id);

                  $code = \App\Models\Code::find(optional($product)->code_id);
                  $brand = \App\Models\Brand::find(optional($code)->brand_id); 
                  $commodity = \App\Models\Commodity::find(optional($code)->commodity_id);

                  $unit = \App\Models\Unit::find(optional($product)->unit_id); 
                  $supplier = \App\Models\Supplier::find(optional($product)->supplier_id);
                @endphp
                  <tr>
                        <td>{{  $i }}</td>
                        <td>{{ $adjustment->adjustment_date }}</td>
                        <td>{{ $adjustment->adjustment_no }}</td>
                        
                        <td>{{ optional($warehouse)->name }}</td>
                        <td>{{ optional($shelf_no)->name }} ( {{ optional($shelf_no)->shelf_name }})</td>
                        <td>{{ optional($supplier)->name }}</td>

                        <td>{{ optional($code)->name }}</td>
                        <td>{{ optional($brand)->name }}</td>
                        <td>{{ optional($commodity)->name }}</td>
                        <td>{{ optional($unit)->name }}</td>
                        <td>{{ $adjustment->qty }}</td>

                        <td>{{ $adjustment->remarks }}</td>
                        <td>{{ optional($product)->voucher_no }}</td>
                        <td>
                          <div class="d-flex justify-content-around"> 
                              <a href="{{ route("adjustments.edit", ["id" => $adjustment->id] )}}" >
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
                <th>Adjustment No</th>
                <th>Warehouse</th>
                <th>Shelf No</th>
                <th>Supplier</th>
                <th>Code</th>
                <th>Brand</th>
                <th>Commodity</th>
                <th>Unit</th>
                <th>Qty</th>
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

  <!--import modal -->
  <div class="modal fade" id="import-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Product Lists!</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                <div class="row">
                  <div class="col-8">
                      <div class="form-group ">
                          <label class="form-label" >Excel</label>
                          <input type="file"required class="form-control" name="products" required>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="form-group ">
                          <label class="form-label">Sample File</label>
                            <a href="{{ route("products.sample")}}" type="button"class="btn btn-success">
                                <i class="fas fa-file-download mr-1"></i>Download
                            </a>
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.Import modal -->
 
@endsection
@section('scripts')

<script>
  $( "#adjust_no" ).ready(function() {
      $("#adjust_no").select2();
  });
  $( "#vr_no" ).ready(function() {
      $("#vr_no").select2();
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
  $( "#supplier_id" ).ready(function() {
      $("#supplier_id").select2();
  });
  $( "#commodity_id" ).ready(function() {
      $("#commodity_id").select2();
  });
</script>

@endsection