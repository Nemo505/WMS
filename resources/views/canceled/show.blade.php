@extends('layouts.master')
@section('title', 'Canceled Code Detail')

@section('css')
@endsection

@section('buttons')
    <a href="{{ route("canceled.index")}}" style="color:rgb(136, 32, 139)">
        <i class="fas fa-reply"></i>
      Back
@endsection

@section('content')
<div class="container-fluid" style="color: black;">
  <div class="row">
    <div class="col-12">
      
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                
                {{-- Left side: code info --}}
                <div>
                    <h2 class="mb-2">{{ $code->name }} (Canceled)</h2>
                    <p class="mb-1">Brand: {{ $code->brand?->name ?? '-' }}</p>
                    <p class="mb-1">Commodity: {{ $code->commodity?->name ?? '-' }}</p>
                    <p class="mb-0">Canceled At: {{ $code->canceled_at?->format('Y-m-d H:i') ?? '-' }}</p>
                </div>
                
                {{-- Right side: image --}}
                <div>
                    @if ($code->image == null)
                        <img src="/storage/img/code/no-img.jpg"
                             class="img_{{ $code->id }} rounded border"
                             alt="code"
                             width="180">
                    @else
                        <img src="{{ URL::asset($code->image) }}"
                             class="img_{{ $code->id }} rounded border"
                             alt="code"
                             width="180">
                    @endif
                </div>
                
            </div>
        </div>

      {{-- Products Section --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Products</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="overflow-x: auto;">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Date</th>
                  <th>VR No</th>
                  <th>Warehouse</th>
                  <th>Shelf No</th>
                  <th>Supplier</th>
                  <th>Code</th>
                  <th>Brand</th>
                  <th>Commodity</th>
                  <th>Unit</th>
                  <th>Received Qty</th>
                  <th>Remarks</th>
                  <th>Created By</th>
                  <th>Updated By</th>
                </tr>
            </thead>
            <tbody>
              @forelse($code->products as $i => $product)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $product->received_date }}</td>
                <td>{{ $product->voucher_no }}</td>
                <td>{{ $product->shelfNum?->warehouse?->name }}</td>
                <td>{{ $product->shelfNum?->name }}</td>
                <td>{{ $product->supplier?->name }}</td>

                <td>{{ $code->name }}</td>
                <td>{{ $code->brand?->name }}</td>
                <td>{{ $code->commodity?->name }}</td>

                <td>{{ $product->unit?->name }}</td>
                <td>{{ $product->received_qty }}</td>
                <td>{{ $product->remarks }}</td> 
                <td>{{ $product->creator?->name }}</td>
                <td>{{ $product->updater?->name }}</td>
              </tr>
              @empty
              <tr><td colspan="3" class="text-center">No Received products</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Transfers Section --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Transfers</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="overflow-x: auto;">
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
                </tr>
            </thead>
            <tbody>
              @forelse($code->transfers as $i => $transfer)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $transfer->transfer_date }}</td>
                <td>{{ $transfer->transfer_no }}</td>
                <td>{{ $transfer->fromShelf?->warehouse?->name }}</td>
                <td>{{ $transfer->fromShelf?->name }}</td>
                <td>{{ $transfer->toShelf?->warehouse->name }}</td>
                <td>{{ $transfer->toShelf?->name }}</td>
                <td>{{ $code->name }}</td>
                <td>{{ $code->brand?->name }}</td>
                <td>{{ $code->commodity?->name }}</td>
                
                <td>{{ $transfer->transfer_qty }}</td>
                <td>{{ $transfer->remarks }}</td> 
                <td>{{ $transfer->product?->voucher_no }}</td>
                <td>{{ $transfer->creator?->name }}</td>
                <td>{{ $transfer->updater?->name }}</td>
              </tr>
              @empty
              <tr><td colspan="3" class="text-center">No transfers</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Issues Section --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Issues</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="overflow-x: auto;">
          <table class="table table-bordered table-hover">
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
                <th>Created By</th>
                <th>Updated By</th>
              </tr>
            </thead>
            <tbody>
              @forelse($code->issues as $i => $issue)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $issue->issue_date }}</td>
                <td>{{ $issue->mr_no }}</td>
                <td>{{ $issue->job_no }}</td>
                <td>{{ $issue->department?->name }}</td>
                <td>{{ $issue->shelfNum?->warehouse?->name }}</td>
                <td>{{ $issue->shelfNum?->name }}</td>
                <td>{{ $issue->customer?->name }}</td>

                <td>{{ $code->name }}</td>
                <td>{{ $code->brand?->name }}</td>
                <td>{{ $code->commodity?->name }}</td>
                <td>{{ $issue->product?->unit?->name }}</td>

                <td>{{ $issue->mr_qty }}</td>
                <td>{{ $issue->mrr_qty }}</td>
                <td>{{ $issue->remarks }}</td> 
                <td>{{ $issue->product?->voucher_no }}</td>
                <td>{{ $issue->creator?->name }}</td>
                <td>{{ $issue->updater?->name }}</td>
              </tr>
              @empty
              <tr><td colspan="3" class="text-center">No issues</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Issue Returns Section --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Issue Returns</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="overflow-x: auto;">
          <table class="table table-bordered table-hover">
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
                  <th>Created By</th>
                  <th>Updated By</th>
              </tr>
                </tr>
            </thead>
            <tbody>
              @forelse($code->issueReturns as $i => $return)
              <tr>
                
                <td>{{ $i + 1 }}</td>
                <td>{{ $return->issue_return_date }}</td>
                <td>{{ $return->mrr_no }}</td>
                <td>{{ $return->issue?->shelfNum?->warehouse?->name }}</td>
                <td>{{ $return->issue?->shelfNum?->name }}</td>
                <td>{{ $return->issue?->customer?->name }}</td>

                <td>{{ $code->name }}</td>
                <td>{{ $code->brand?->name }}</td>
                <td>{{ $code->commodity?->name }}</td>
                <td>{{ $return->product?->unit?->name }}</td>

                <td>{{ $return->mrr_qty }}</td>
                <td>{{ $return->remarks }}</td> 
                <td>{{ $return->product?->voucher_no }}</td>
                <td>{{ $return->creator?->name }}</td>
                <td>{{ $return->updater?->name }}</td>
              </tr>
              @empty
              <tr><td colspan="3" class="text-center">No issue returns</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Supplier Returns Section --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Supplier Returns</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="overflow-x: auto;">
          <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Supplier Return No</th>
                    <th>Warehouse</th>
                    <th>Shelf No</th>
                    <th>Supplier</th>
                    <th>Code</th>
                    <th>Brand</th>
                    <th>Commodity</th>
                    <th>Unit</th>
                    <th>Supplier Return Qty</th>
                    <th>Remarks</th>
                    <th>VR No</th>
                    <th>Created By</th>
                    <th>Updated By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($code->supplierReturns as $i => $supplierReturn)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $supplierReturn->supplier_return_date }}</td>
                        <td>{{ $supplierReturn->supplier_return_no }}</td>
                        <td>{{ $supplierReturn->shelfNum?->warehouse?->name }}</td>
                        <td>{{ $supplierReturn->shelfNum?->name }}</td>
                        <td>{{ $supplierReturn->supplier?->name }}</td>
                        <td>{{ $code->name }}</td>
                        <td>{{ $code->brand?->name }}</td>
                        <td>{{ $code->commodity?->name }}</td>
                        <td>{{ $supplierReturn->product?->unit?->name }}</td>
                        <td>{{ $supplierReturn->supplier_return_qty }}</td>
                        <td>{{ $supplierReturn->remarks }}</td>
                        <td>{{ $supplierReturn->product?->voucher_no }}</td>
                        <td>{{ $supplierReturn->creator?->name }}</td>
                        <td>{{ $supplierReturn->updater?->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center">No supplier returns found</td>
                    </tr>
                @endforelse
            </tbody>

          </table>
        </div>
      </div>

      {{-- Adjustment Section --}}
      <div class="card mb-3">
        <div class="card-header">
          <h3 class="card-title">Adjustment</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="overflow-x: auto;">
          <table class="table table-bordered table-hover">
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
                <th>Created By</th>
                <th>Updated By</th>
              </tr>
            </thead>
            <tbody>
              @forelse($code->adjustments as $i => $adjustment)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $adjustment->adjustment_date }}</td>
                    <td>{{ $adjustment->adjustment_no }}</td>
                    <td>{{ $adjustment->product?->shelfNum?->warehouse?->name }}</td>
                    <td>{{ $adjustment->product?->shelfNum?->name }}</td>
                    <td>{{ $adjustment->product?->supplier?->name }}</td>
                    <td>{{ $code?->name }}</td>
                    <td>{{ $code?->brand?->name }}</td>
                    <td>{{ $code?->commodity?->name }}</td>
                    <td>{{ $adjustment->product?->unit?->name }}</td>
                    <td>{{ $adjustment->qty }}</td>
                    <td>{{ $adjustment->remarks }}</td>
                    <td>{{ $adjustment->product?->voucher_no }}</td>
                    <td>{{ $adjustment->creator?->name }}</td>
                    <td>{{ $adjustment->updater?->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="15" class="text-center">No adjustments found</td>
                </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@section('scripts')

@endsection