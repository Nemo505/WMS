@extends('layouts.master')
@section('title', 'Instock List')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            {{-- form --}}
            <form action="" method="GET">

                <div class="row d-flex justify-content-around">
                    {{-- warehouse --}}
                    <div class="col-2">
                        <div class="form-group">
                            <label for="warehouse_id">Warehouse</label>
                            <div>

                                <div> <select id='warehouse_id' name="warehouse_id" class=" form-control">
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
                    </div>

                    {{-- Code --}}
                    <div class="col-1">
                    <div class="form-group">
                        <label for="code_id">Code </label>
                        <div>

                        <div> <select id='code_id' name="code_id" class=" form-control">
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
                                </select> </div> 
                            </div>

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

                    <div class="col-1 ">
                        <div class="form-group mt-4">
                            <button class="btn btn-primary" type="submit" name="search">Search</button>
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
                  <th>Warehouse</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>

                  <th>Total Received Qty</th>
                  <th>Total Transfer In</th>
                  <th>Total Transfer Out</th>
                  <th>Total Balance</th>

                  <th>Total MR Qty (-)</th>
                  <th>Total MRR Qty </th>
                  <th>Total SupplierReturn Qty (-)</th>
                 
                  <th>Total Add Adjustment Qty (+)</th>
                  <th>Total Sub Adjustment Qty (-)</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($instocks as $instock)
                    @php
                    ++$i;

                    $code = \App\Models\Code::find($instock->code_id); 
                    $brand = \App\Models\Brand::find(optional($code)->brand_id); 
                    $commodity = \App\Models\Commodity::find(optional($code)->commodity_id);
                    $warehouse = \App\Models\Warehouse::find($instock->id);

                    #fromdate to today
                    if (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']) && 
                            isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']) ) 
                        {
                    
                            $from_date = date('Y-m-d', strtotime($_REQUEST['from_date']));
                            $to_date = date('Y-m-d', strtotime($_REQUEST['to_date']));

                            $from = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                            ->orderBy('products.received_date', 'asc')
                                            ->first();
                           
                            $received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->whereBetween('products.received_date', [$from->received_date, $to_date])
                                            ->sum('products.received_qty');

                            $transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('products.received_date', [$from_date, $to_date])
                                                ->where('products.type', 'transfer')
                                                ->sum('products.received_qty');

                            $transfer_out = \App\Models\Transfer::where('transfers.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('transfers.transfer_date', [$from_date, $to_date])
                                                ->sum('transfers.transfer_qty');

                            $balance_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('products.received_date', [$from->received_date, $to_date])
                                                ->sum('products.balance_qty');

                            $mr_qty = \App\Models\Issue::where('issues.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'issues.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('issues.issue_date', [$from_date, $to_date])
                                                ->sum('issues.mr_qty');

                            $mrr_qty = \App\Models\IssueReturn::where('issue_returns.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'issue_returns.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('issue_returns.issue_return_date', [$from_date, $to_date])
                                                ->sum('issue_returns.mrr_qty');
            
                            $sup_qty = \App\Models\SupplierReturn::where('supplier_returns.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'supplier_returns.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('supplier_returns.supplier_return_date', [$from_date, $to_date])                 
                                                ->sum('supplier_returns.supplier_return_qty');

                            $add_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('adjustments.adjustment_date', [$from_date, $to_date])
                                                ->where('adjustments.type', 'add')
                                                ->sum('adjustments.qty');
            
                            $sub_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('adjustments.adjustment_date', [$from_date, $to_date])
                                                ->where('adjustments.type', 'sub')
                                                ->sum('adjustments.qty');

                    }elseif (isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date'])) {
                            $from_date = date('Y-m-d', strtotime($_REQUEST['from_date']));
                            $to_date = date('Y-m-d');

                            $received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                                ->sum('products.received_qty');

                        
                            $transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('products.received_date', [$from_date, $to_date])
                                                ->where('products.type', 'transfer')
                                                ->sum('products.received_qty');

                            $transfer_out = \App\Models\Transfer::where('transfers.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('transfers.transfer_date', [$from_date, $to_date])
                                                ->sum('transfers.transfer_qty');

                            $balance_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->sum('products.balance_qty');

                            $mr_qty = \App\Models\Issue::where('issues.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'issues.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('issues.issue_date', [$from_date, $to_date])
                                                ->sum('issues.mr_qty');

                            $mrr_qty = \App\Models\IssueReturn::where('issue_returns.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'issue_returns.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('issue_returns.issue_return_date', [$from_date, $to_date])
                                                ->sum('issue_returns.mrr_qty');
            
                            $sup_qty = \App\Models\SupplierReturn::where('supplier_returns.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'supplier_returns.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('supplier_returns.supplier_return_date', [$from_date, $to_date])                 
                                                ->sum('supplier_returns.supplier_return_qty');

                            $add_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('adjustments.adjustment_date', [$from_date, $to_date])
                                                ->where('adjustments.type', 'add')
                                                ->sum('adjustments.qty');
            
                            $sub_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('adjustments.adjustment_date', [$from_date, $to_date])
                                                ->where('adjustments.type', 'sub')
                                                ->sum('adjustments.qty');

                    }else{
                            $received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                                ->sum('products.received_qty');

                            $transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->where('products.type', 'transfer')   
                                                ->sum('products.transfer_qty');

                            $transfer_out = \App\Models\Transfer::where('code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)  
                                                ->sum('transfers.transfer_qty');

                            $balance_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                                ->sum('products.balance_qty');

                            $mrr_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                                ->sum('products.mrr_qty');

                            $mr_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                                ->sum('products.mr_qty');
            
                            $sup_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                                ->sum('products.supplier_return_qty');

                            $add_adjust = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)                      
                                                ->sum('products.add_adjustment');

                            $sub_adjust = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id') 
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)   
                                                ->sum('products.sub_adjustment');
                    }

                    @endphp
                    <tr>
                        <td>{{  $i }}</td>

                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $code->name }}</td>
                        <td>{{ optional($brand)->name }}</td>
                        <td>{{ optional($commodity)->name }}</td>

                        <td>{{ $received_qty }}</td>
                        <td>{{ $transfer_in }}</td>
                        <td>{{ $transfer_out }}</td>
                        <td>{{ $balance_qty }}</td>

                        <td>{{ $mr_qty }}</td>
                        <td>{{ $mrr_qty }}</td>
                        <td>{{ $sup_qty }}</td>
                        <td>{{ $add_adjust }}</td>
                        <td>{{ $sub_adjust }}</td>
                    </tr>
                @endforeach
              </tbody>

              <tfoot>
              <tr>
                <th>No</th>
                <th>Warehouse</th>
                <th>Code</th>
                <th>Brand</th>
                <th>Commodity</th>

                <th>Total Received Qty</th>
                <th>Total Transfer In</th>
                <th>Total Transfer Out</th>
                <th>Total Balance</th>

                <th>Total MR Qty</th>
                <th>Total MRR Qty</th>
                <th>Total SupplierReturn Qty</th>
                <th>Total Add Adjustment Qty</th>
                <th>Total Sub Adjustment Qty</th>
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
  
  $( "#code_id" ).ready(function() {
      $("#code_id").select2();
  });
  $( "#brand_id" ).ready(function() {
      $("#brand_id").select2();
  });
  $( "#commodity_id" ).ready(function() {
      $("#commodity_id").select2();
  });
  $( "#warehouse_id" ).ready(function() {
      $("#warehouse_id").select2();
  });
</script>

@endsection