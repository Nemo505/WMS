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
                                    <option value=""  selected>Choose Warehouse</option>
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
                            <option value=""  selected>Choose Code</option>
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
                                <option value=""  selected>Choose Brand</option>
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
                    <div class="col-1">
                        <div class="form-group">
                            <label for="commodity_id">Commodity</label>
                            <div>

                            <div> <select id='commodity_id' name="commodity_id" class=" form-control">
                                <option value=""  selected>Choose Commodity</option>
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

                    <div class="col-2 d-flex justify-content-">
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
              </form>
          </div>
          <!-- /.card-header -->
          <div class="card-body" style="overflow-x: scroll;">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Warehouse</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>

                  <th>Total Opening Qty</th>
                  <th>Total Received Qty</th>
                  <th>Total Transfer In</th>
                  <th>Total Transfer Out</th>

                  <th>Total MR Qty (-)</th>
                  <th>Total MRR Qty </th>
                  <th>Total SupplierReturn Qty (-)</th>
                 
                  <th>Total Add Adjustment Qty (+)</th>
                  <th>Total Sub Adjustment Qty (-)</th>
                  
                  <th>Total Balance</th>
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
                        if (!empty($_REQUEST['from_date']) || !empty($_REQUEST['to_date'])) {
                            // Set 'from_date' or fallback to the first received product's date
                            $from_date = !empty($_REQUEST['from_date']) 
                                            ? date('Y-m-d', strtotime($_REQUEST['from_date'])) 
                                            : optional(
                                                \App\Models\Product::where('products.code_id', $instock->code_id)
                                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                    ->orderBy('products.received_date', 'asc')
                                                    ->first()
                                            )->received_date;
                            $from_date = $from_date ? date('Y-m-d', strtotime($from_date)) : null;
                            $to_date = !empty($_REQUEST['to_date']) 
                                        ? date('Y-m-d', strtotime($_REQUEST['to_date'])) 
                                        : date('Y-m-d'); 
                        
                        
                            $received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereBetween('products.received_date', [$from_date, $to_date])
                                                ->where('products.type', 'receive')
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
                        
                            $opening_balance_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
                                                    
                            $opening_received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                    ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                    ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                    ->whereDate('products.received_date', '<=', $opening_balance_date)
                                                    ->where('products.type', 'receive')
                                                    ->sum('products.received_qty');
                                                
                            $opening_transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('products.received_date', '<=', $opening_balance_date)
                                                ->where('products.type', 'transfer')
                                                ->sum('products.received_qty');
                        
                            $opening_transfer_out = \App\Models\Transfer::where('transfers.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('transfers.transfer_date', '<=', $opening_balance_date)
                                                ->sum('transfers.transfer_qty');
                        
                            $opening_mr_qty = \App\Models\Issue::where('issues.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'issues.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('issues.issue_date', '<=', $opening_balance_date)
                                                ->sum('issues.mr_qty');
                        
                            $opening_mrr_qty = \App\Models\IssueReturn::where('issue_returns.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'issue_returns.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('issue_returns.issue_return_date', '<=', $opening_balance_date)
                                                ->sum('issue_returns.mrr_qty');
                        
                            $opening_sup_qty = \App\Models\SupplierReturn::where('supplier_returns.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'supplier_returns.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('supplier_returns.supplier_return_date', '<=', $opening_balance_date)
                                                ->sum('supplier_returns.supplier_return_qty');
                        
                            $opening_add_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('adjustments.adjustment_date', '<=', $opening_balance_date)
                                                ->where('adjustments.type', 'add')
                                                ->sum('adjustments.qty');
                        
                            $opening_sub_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('adjustments.adjustment_date', '<=', $opening_balance_date) 
                                                ->where('adjustments.type', 'sub')
                                                ->sum('adjustments.qty');
                            
                            
                            $opening_qty = ($opening_received_qty + $opening_mrr_qty + $opening_add_adjust + $opening_sup_qty + $opening_transfer_in) - ($opening_mr_qty + $opening_sub_adjust + $opening_transfer_out);
                                                     
                                                
                            $start_received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('products.received_date', '<=', $to_date)
                                                ->where('products.type', 'receive')
                                                ->sum('products.received_qty');
                                                
                            $start_transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('products.received_date', '<=', $to_date)
                                                ->where('products.type', 'transfer')
                                                ->sum('products.received_qty');
                        
                            $start_transfer_out = \App\Models\Transfer::where('transfers.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('transfers.transfer_date', '<=', $to_date)
                                                ->sum('transfers.transfer_qty');
                            
                            $start_mr_qty = \App\Models\Issue::where('issues.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'issues.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('issues.issue_date', '<=', $to_date)
                                                ->sum('issues.mr_qty');
                        
                            $start_mrr_qty = \App\Models\IssueReturn::where('issue_returns.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'issue_returns.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('issue_returns.issue_return_date', '<=', $to_date)
                                                ->sum('issue_returns.mrr_qty');
                        
                            $start_sup_qty = \App\Models\SupplierReturn::where('supplier_returns.code_id', $instock->code_id)
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'supplier_returns.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('supplier_returns.supplier_return_date', '<=', $to_date)
                                                ->sum('supplier_returns.supplier_return_qty');
                        
                            $start_add_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('adjustments.adjustment_date', '<=', $to_date)
                                                ->where('adjustments.type', 'add')
                                                ->sum('adjustments.qty');
                        
                            $start_sub_adjust = \App\Models\Adjustment::where('adjustments.code_id', $instock->code_id)
                                                ->join('products', 'products.id', '=', 'adjustments.product_id')
                                                ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                ->whereDate('adjustments.adjustment_date', '<=', $to_date) 
                                                ->where('adjustments.type', 'sub')
                                                ->sum('adjustments.qty');
                            
                            $balance_qty = ($start_received_qty + $start_mrr_qty + $start_add_adjust + $start_sup_qty + $start_transfer_in) - ($start_mr_qty + $start_sub_adjust + $start_transfer_out);
                        
                        } else {
                            // Default case
                            $received_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->where('products.type', 'receive')
                                            ->sum('products.received_qty');
                                            
                            
                            $from = \App\Models\Product::where('products.code_id', $instock->code_id)
                                                        ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                                        ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                                        ->orderBy('products.received_date', 'asc')
                                                        ->first();
                            
                        
                            $transfer_in = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->where('products.type', 'transfer')
                                            ->sum('products.received_qty');
                        
                            $transfer_out = \App\Models\Transfer::where('code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'transfers.from_shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->sum('transfers.transfer_qty');
                        
                            $balance_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->sum('products.balance_qty');
                        
                            $mr_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->sum('products.mr_qty');
                        
                            $mrr_qty = \App\Models\Product::where('products.code_id', $instock->code_id)
                                            ->join('shelf_numbers', 'shelf_numbers.id', '=', 'products.shelf_number_id')
                                            ->where('shelf_numbers.warehouse_id', optional($warehouse)->id)
                                            ->sum('products.mrr_qty');
                        
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
                            $opening_qty = ($received_qty + $mrr_qty + $add_adjust + $sup_qty + $transfer_in) - ($mr_qty + $sub_adjust+ $transfer_out);
                        }
                    @endphp
                    <tr>
                        <td>{{  $i }}</td>

                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $code->name }}</td>
                        <td>{{ optional($brand)->name }}</td>
                        <td>{{ optional($commodity)->name }}</td>

                        <td>{{ $opening_qty }}</td>
                        <td>{{ $received_qty }}</td>
                        <td>{{ $transfer_in }}</td>
                        <td>{{ $transfer_out }}</td>
                        

                        <td>{{ $mr_qty }}</td>
                        <td>{{ $mrr_qty }}</td>
                        <td>{{ $sup_qty }}</td>
                        <td>{{ $add_adjust }}</td>
                        <td>{{ $sub_adjust }}</td>
                        
                        <td>{{ $balance_qty }}</td>
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

                <th>Total Opening Qty</th>
                <th>Total Received Qty</th>
                <th>Total Transfer In</th>
                <th>Total Transfer Out</th>
                

                <th>Total MR Qty</th>
                <th>Total MRR Qty</th>
                <th>Total SupplierReturn Qty</th>
                <th>Total Add Adjustment Qty</th>
                <th>Total Sub Adjustment Qty</th>
                
                <th>Total Balance</th>
              </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
          
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item {{ $instocks->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $instocks->previousPageUrl() }}">&laquo;</a>
                    </li>
                    @php
                        $numAdjacent = 2; // Number of adjacent page links to display
                        $start = max(1, $instocks->currentPage() - $numAdjacent);
                        $end = min($start + $numAdjacent * 2, $instocks->lastPage());
                    @endphp
                    @if($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $instocks->url(1) }}">1</a>
                        </li>
                        @if($start > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    @for ($i = $start; $i <= $end; $i++)
                        <li class="page-item {{ $i === $instocks->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $instocks->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($end < $instocks->lastPage())
                        @if($end < $instocks->lastPage() - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $instocks->url($instocks->lastPage()) }}">{{ $instocks->lastPage() }}</a>
                        </li>
                    @endif
                    <li class="page-item {{ $instocks->currentPage() === $instocks->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $instocks->nextPageUrl() }}">&raquo;</a>
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