@extends('layouts.master')
@section('title', 'Transfer List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route("transfers.history")}}" type="button" class="btn btn-primary" >
      <i class="fas fa-history"></i>
      History
    </a> 
    <a href="{{ route("transfers.create")}}" type="button" class="btn btn-primary" >
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
                $s_transfers =  App\Models\Transfer::distinct()->get(['transfer_no']);;
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-around">
                {{-- TransferNO --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="transfer_no">Transfer No</label>

                      <select id='transfer_no' name="transfer_no" class=" form-control">
                        <option value="" selected>Choose Transfer No</option>
                        @foreach ($s_transfers as $s_transfer)
                          @if (isset($_REQUEST['transfer_no']))
                              @if ($s_transfer->transfer_no == $_REQUEST['transfer_no'])
                                  <option value="{{ $s_transfer->transfer_no }}" selected>{{ $s_transfer->transfer_no }}</option>
                              @else
                                  <option value="{{ $s_transfer->transfer_no }}">{{ $s_transfer->transfer_no }}</option>
                              @endif
                          @else
                              <option value="{{$s_transfer->transfer_no }}">{{ $s_transfer->transfer_no }}</option> 
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
                {{-- Warehouse --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="from_warehouse_id">Warehouse From</label>

                      <select id='from_warehouse_id' name="from_warehouse_id" class=" form-control">
                        <option value="" selected>Choose Warehouse</option>
                        @foreach ($warehouses as $warehouse)
                          @if (isset($_REQUEST['from_warehouse_id']))
                              @if ($warehouse->id == $_REQUEST['from_warehouse_id'])
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
                {{-- From Shelf_No --}}
                 <div class="col-2">
                    <div class="form-group">
                        <label for="from_shelf_num_id">Shelf Number From</label>

                        <select id='from_shelf_num_id' name="from_shelf_num_id" class=" form-control">
                        <option value="" selected>Choose Shelf No</option>

                        @foreach ($shelfnums as $shelfnum)
                        @php
                            $shelf = \App\Models\Shelf::find($shelfnum->shelf_id);
                        @endphp
                            @if (isset($_REQUEST['from_shelf_num_id']))
                                @if ($shelfnum->id == $_REQUEST['from_shelf_num_id'])
                                    <option value="{{ $shelfnum->id }}" selected>{{ $shelfnum->name }}  ({{ $shelf->name }})</option>
                                @else
                                    <option value="{{ $shelfnum->id }}">{{ $shelfnum->name }}  ({{ $shelf->name }})</option>
                                @endif
                            @else
                                <option value="{{ $shelfnum->id }}">{{ $shelfnum->name }}  ({{ $shelf->name }})</option> 
                            @endif
                        @endforeach
                        </select>

                    </div>
                </div>
                {{-- To Warehouse --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="to_warehouse_id">Warehouse To</label>

                      <select id='to_warehouse_id' name="to_warehouse_id" class=" form-control">
                        <option value="" selected>Choose Warehouse</option>
                        @foreach ($warehouses as $warehouse)
                          @if (isset($_REQUEST['to_warehouse_id']))
                              @if ($warehouse->id == $_REQUEST['to_warehouse_id'])
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
                {{-- To Shelf_No --}}
                  <div class="col-2">
                    <div class="form-group">
                      <label for="to_shelf_num_id">Shelf Number To</label>

                      <select id='to_shelf_num_id' name="to_shelf_num_id" class=" form-control">
                        <option value="" selected>Choose Shelf No</option>

                        @foreach ($shelfnums as $shelfnum)
                        @php
                            $shelf = \App\Models\Shelf::find($shelfnum->shelf_id);
                        @endphp
                          @if (isset($_REQUEST['to_shelf_num_id']))
                              @if ($shelfnum->id == $_REQUEST['to_shelf_num_id'])
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
               

                </div>


                <div class="row d-flex justify-content-around">

                    <div class="col-2">
                        <div class="form-group">
                          <label for="code_id">Codes</label>
    
                          <select id='code_id' name="code_id" class=" form-control">
                            <option value="" selected>Choose Code</option>
                            @foreach ($codes as $code)
                              @if (isset($_REQUEST['code_id']))
                                  @if ($code->id == $_REQUEST['code_id'])
                                      <option value="{{ $code->id }}" selected>{{ $code->name }}</option>
                                  @else
                                      <option value="{{ $code->id }}">{{ $code->name }}</option>
                                  @endif
                              @else
                                  <option value="{{ $code->id }}">{{ $code->name }}</option> 
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
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>Tarnsfer No</th>
                  <th>Warehouse From</th>
                  <th>ShelfNumber From</th>
                  <th>Warehouse To</th>
                  <th>ShelfNumber To</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Qty</th>
                  <th>Remarks</th>
                  <th>VR No</th>
                  <th>Created By</th>
                  <th>Updated By</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php
                  $i = $transfers->firstItem() - 1;
                @endphp
                @foreach ($transfers as $transfer)
                @php
                  ++$i;
                  
                  $from_shelf_no = \App\Models\ShelfNumber::find($transfer->from_shelf_number_id); 
                  $to_shelf_no = \App\Models\ShelfNumber::find($transfer->to_shelf_number_id); 

                  $from_warehouse = \App\Models\Warehouse::find(optional($from_shelf_no)->warehouse_id);
                  $to_warehouse = \App\Models\Warehouse::find(optional($to_shelf_no)->warehouse_id);

                  $code = \App\Models\Code::find($transfer->code_id);
                  $brand = \App\Models\Brand::find(optional($code)->brand_id); 
                  $commodity = \App\Models\Commodity::find(optional($code)->commodity_id);

                  $product = \App\Models\Product::find($transfer->product_id); 
                  $created_by = \App\Models\User::find($transfer->created_by); 
                  $updated_by = \App\Models\User::find($transfer->updated_by); 
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td>{{ $transfer->transfer_date }}</td>
                      <td>{{ $transfer->transfer_no }}</td>
                      <td>{{ optional($from_warehouse)->name }}</td>
                      <td>{{ optional($from_shelf_no)->name }}</td>
                      <td>{{ optional($to_warehouse)->name }}</td>
                      <td>{{ optional($to_shelf_no)->name }}</td>

                      <td>{{ optional($code)->name }}</td>
                      <td>{{ optional($brand)->name }}</td>
                      <td>{{ optional($commodity)->name }}</td>
                      
                      <td>{{ $transfer->transfer_qty }}</td>
                      <td>{{ $transfer->remarks }}</td>
                      <td>{{ optional($product)->voucher_no  }}</td>
                      <td>{{ optional($created_by)->user_name  }}</td>
                      <td>{{ optional($updated_by)->user_name  }}</td>
                      <td>
                          <div class="d-flex justify-content-around"> 
                              <a href="{{ route("transfers.edit", ["id" => $transfer->id] )}}" >
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
                  <th>Tarnsfer No</th>
                  <th>Warehouse From</th>
                  <th>ShelfNumber From</th>
                  <th>Warehouse To</th>
                  <th>ShelfNumber To</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Qty</th>
                  <th>Remarks</th>
                  <th>VR No</th>
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
                  <li class="page-item {{ $transfers->onFirstPage() ? 'disabled' : '' }}">
                      <a class="page-link" href="{{ $transfers->previousPageUrl() }}">&laquo;</a>
                  </li>
                    @php
                        $numAdjacent = 2; // Number of adjacent page links to display
                        $start = max(1, $transfers->currentPage() - $numAdjacent);
                        $end = min($start + $numAdjacent * 2, $transfers->lastPage());
                    @endphp
                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $transfers->url(1) }}">1</a>
                        </li>
                        @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i === $transfers->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $transfers->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($end < $transfers->lastPage())
                        @if($end < $transfers->lastPage() - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $transfers->url($transfers->lastPage()) }}">{{ $transfers->lastPage() }}</a>
                        </li>
                    @endif
                    <li class="page-item {{ $transfers->currentPage() === $transfers->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $transfers->nextPageUrl() }}">&raquo;</a>
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


 
@endsection
@section('scripts')

<script>
  $( "#transfer_no" ).ready(function() {
      $("#transfer_no").select2();
  });
  $( "#vr_no" ).ready(function() {
      $("#vr_no").select2();
  });
  $( "#from_warehouse_id" ).ready(function() {
      $("#from_warehouse_id").select2();
  });
  $( "#to_warehouse_id" ).ready(function() {
      $("#to_warehouse_id").select2();
  });
  $( "#form_shelf_num_id" ).ready(function() {
      $("#from_shelf_num_id").select2();
  });
  $( "#to_shelf_num_id" ).ready(function() {
      $("#to_shelf_num_id").select2();
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