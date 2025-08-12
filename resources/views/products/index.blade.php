@extends('layouts.master')
@section('title', 'Receive List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("products.history")}}" type="button" class="btn btn-primary" >
      <i class="fas fa-history"></i>
      History
    </a>        

    <a href="{{ route("products.create")}}" type="button" class="btn btn-primary" >
      <i class="fa fa-solid fa-plus" style="color: #ffffff;"></i>
      Add New
    </a>

    @php
        $check = Auth::user()->hasPermissionTo('import_product');
    @endphp 
    
    @if ($check == true)
      <a href="" type="button" class="btn btn-success" data-toggle="modal" data-target="#import-modal" >
        <i class="fas fa-upload" style="color: #ffffff;"></i>
        Import
      </a>
    @endif

   

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
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-around">
                {{-- VRNO --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="vr_no">VR No</label>

                         <div> <select id='vr_no' name="vr_no" class=" form-control">
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
                          </select> </div> 


                    </div>
                  </div>
                {{-- Warehouse --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="warehouse_id">Warehouse</label>

                       <div> <select id='warehouse_id' name="warehouse_id" class=" form-control">
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
                        </select> </div> 

                    </div>
                  </div>
                {{-- Shelf_No --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="shelf_num_id">Shelf Number</label>

                       <div> <select id='shelf_num_id' name="shelf_num_id" class=" form-control">
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
                        </select> </div> 

                    </div>
                  </div>
                
                {{-- Code --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="code_id">Code </label>
                      <div>

                         <div> <select id='code_id' name="code_id" class=" form-control">
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
                          </select> </div> 
                      </div>

                    </div>
                  </div>
                
                {{-- BrandName --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="brand_id">Brand</label>

                      <div>
                         <div> <select id='brand_id' name="brand_id" class=" form-control">
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
                          </select> </div> 
                      </div>

                    </div>
                  </div>
                
                {{-- Commodity --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="commodity_id">Commodity</label>
                      <div>

                         <div> <select id='commodity_id' name="commodity_id" class=" form-control">
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
                          </select> </div> 
                      </div>

                    </div>
                  </div>

                </div>


                <div class="row d-flex justify-content-around">
                 
                  {{-- Supplier --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="supplier">Supplier</label>

                       <div> <select id='supplier_id' name="supplier_id" class=" form-control">
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
                        </select> </div> 

                    </div>
                  </div>
                
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
                  <div class="col-2">
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
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>VR No</th>
                  <th>BarCode</th>
                  <th>Warehouse</th>
                  <th>Shelf No</th>
                  <th>Supplier</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Unit</th>
                  <th>Received Qty</th>
                  <th>Remarks</th>
                  <th>Transfer No</th>
                  <th>Created By</th>
                  <th>Updated By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($products as $product)
                @php
                  ++$i;
                  
                  $shelf_no = \App\Models\ShelfNumber::where('shelf_numbers.id', $product->shelf_number_id)
                                                  ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                                  ->first([
                                                    'shelf_numbers.id', 
                                                    'shelf_numbers.name', 
                                                    'shelves.name as shelf_name', 
                                                    'shelf_numbers.warehouse_id'
                                                  ]); 
                  $warehouse = \App\Models\Warehouse::find(optional($shelf_no)->warehouse_id);

                  $code = \App\Models\Code::find($product->code_id);
                  $brand = \App\Models\Brand::find(optional($code)->brand_id); 
                  $commodity = \App\Models\Commodity::find(optional($code)->commodity_id);

                  $unit = \App\Models\Unit::find($product->unit_id); 
                  $transfer = \App\Models\Transfer::find($product->transfer_id); 

                  
                  $supplier = \App\Models\Supplier::find($product->supplier_id);
                  $created_by = \App\Models\User::find($product->created_by); 
                  $updated_by = \App\Models\User::find($product->updated_by); 
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td>{{ $product->received_date }}</td>
                      <td>{{ $product->voucher_no }}</td>
                      <td class="text-center">
                        <a href="{{ route('products.printBarcode', ['id' => $product->id]) }}"
                            target="_blank">
                            {!!DNS1D::getBarcodeSVG($product->barcode, 'C39+',1,55,'black', true) !!}
                        </a>
                      </td>
                      <td>{{ optional($warehouse)->name }}</td>
                      <td>{{ optional($shelf_no)->name }} ( {{ optional($shelf_no)->shelf_name }})</td>
                      <td>{{ optional($supplier)->name }}</td>

                      <td>{{ optional($code)->name }}</td>
                      <td>{{ optional($brand)->name }}</td>
                      <td>{{ optional($commodity)->name }}</td>
                      <td>{{ optional($unit)->name }}</td>
                      <td>{{ $product->received_qty }}</td>
                      
                      <td>{{ $product->remarks }}</td>
                      <td>{{ optional($transfer)->transfer_no }}</td>
                       <td>{{ optional($created_by)->user_name  }}</td>
                      <td>{{ optional($updated_by)->user_name  }}</td>
                      <td>
                          <div class="d-flex justify-content-around"> 
                            @if (!$product->transfer_id)
                              <a href="{{ route("products.edit", ["id" => $product->id] )}}" >
                                  <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                              </a>
                            @else
                                  <i class="fas fa-ban" style="color: rgb(221, 142, 40)"></i>
                            @endif
                          </div>
                        
                      </td>
                  </tr>
                @endforeach
              </tbody>

              <tfoot>
              <tr>
                <th>No</th>
                  <th>Date</th>
                  <th>VR No</th>
                  <th>BarCode</th>
                  <th>Warehouse</th>
                  <th>Shelf No</th>
                  <th>Supplier</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Unit</th>
                  <th>Received Qty</th>
                  <th>Remarks</th>
                  <th>Transfer No</th>
                  <th>Created By</th>
                  <th>Updated By</th>
                  <th>Action</th>
              </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
          
            <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}">&laquo;</a>
                    </li>
                    @php
                        $numAdjacent = 2; // Number of adjacent page links to display
                        $start = max(1, $products->currentPage() - $numAdjacent);
                        $end = min($start + $numAdjacent * 2, $products->lastPage());
                    @endphp
                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->url(1) }}">1</a>
                        </li>
                        @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i === $products->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($end < $products->lastPage())
                        @if($end < $products->lastPage() - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a>
                        </li>
                    @endif
                    <li class="page-item {{ $products->currentPage() === $products->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}">&raquo;</a>
                    </li>
                </ul>
            </div>
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
  $( "#product" ).ready(function() {
      $("#product").select2();
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