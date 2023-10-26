@extends('layouts.master')
@section('title', 'Edit Product')

@section('css')

@endsection

@section('content')
@php
    $i = 0;
@endphp
<div class="container-fluid">
    
      <!-- general form elements -->
      <div class="card card-primary">
        <!-- form start -->
        <form method="POST" action="{{route('products.update')}}">
          @csrf
          <div class="card-body">

              <!-- mainform -->
              <div class="row">
                <div class="col-4">
                  <div class="form-group ">
                    <label for="warehouse">Warehouse Name <span style="color: red">*</span></label>

                    <select id='warehouse' required name="warehouse_id" class="form-control getShelfNum">
                        <option value="" disabled selected>Choose Warehouse</option>
                        @foreach ($warehouses as $warehouse)
                          @if ($warehouse->id == $edit_warehouse->id)
                              <option selected value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                          @else
                              <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-4">
                  <div class="form-group">
                    <div class="form-group ">
                      <label for="shelfnum">Shelf Number <span style="color: red">*</span></label>
  
                      <select id='shelfnum' required name="shelfnum_id" class="form-control">
                        @foreach ($shelfnums as $shelfnum)
                          @if ($shelfnum->id == $edit_shelfnum->id)
                              <option selected value="{{$shelfnum->id}}">{{$shelfnum->shelfnumName}}( {{$shelfnum->shelfName}})</option>
                          @else
                              <option value="{{$shelfnum->id}}">{{$shelfnum->shelfnumName}}( {{$shelfnum->shelfName}})</option>
                          @endif
                        @endforeach
  
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-4">
                  <label for="supplier">Supplier <span style="color: red">*</span></label>
                  <!-- Dropdown --> 
                  <select id='supplier' required name="supplier" class="form-control">
                    <option value="" disabled selected>Choose Supplier</option>
                      @foreach ($suppliers as $supplier)
                        @if ($supplier->id == $edit_supplier->id)
                            <option selected value="{{$supplier->id}}">{{$supplier->name}}</option>
                        @else
                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                        @endif
                      @endforeach
                  </select>
                </div>
              </div>
              
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label for="vr_no">Voucher No <span style="color: red">*</span></label>
                    <input type="text" class="form-control" required id="vr_no" name="vr_no" value="{{$product->voucher_no}}">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="date">Date <span style="color: red">*</span></label>
                    <input type="date" name="date" required class="form-control" id="date" placeholder="" value="{{$product->received_date}}">
                  </div>
                </div>

              </div>
              <!-- end mainform -->

              <div class="row my-3 ml-1">
                  <button type="button" class="btn btn-primary" id="newColumn" >
                    <i class="fa fa-solid fa-plus" style="color: #ffffff;"></i>
                    Add New
                  </button>
                  <div class="form-check mx-3 my-auto">
                      <input type="checkbox" class="form-check-input" id="checkAll">
                      <label class="form-check-label" for="checkAll">Check all</label>
                  </div>
                  <button type="button" class="btn btn-danger" id="deleteColumn" >
                    <i class="fa fa-trash mx-1"  style="color: #ffffff;"></i>
                    Delete
                  </button>
              </div>
              {{-- hidden one product for check --}}
              <input type="hidden" value="{{$product->id}}" name="old_product" >
              <!-- Add New Card -->
              <div class="moreCols">
                @foreach ($choosen_products as $choosen_product)
                @php
                  ++$i;
                @endphp
                  {{-- hidden all products --}}
                  <input type="hidden" value="{{$choosen_product->id}}" name="product_{{$i}}" >
                  <div class="row d-flex justify-content-around deleteRow">
                    @if ($choosen_product->transfer_qty != null  && 
                          $choosen_product->transfer_qty != 0  ||
                          $choosen_product->mr_qty != 0 &&
                          $choosen_product->mr_qty != null ||
                          $choosen_product->supplier_return_qty != null &&
                          $choosen_product->supplier_return_qty != 0 
                        )
                      <div class="my-auto pl-1 text-center">
                          <i class="fas fa-window-close" 
                            style="color: rgb(196, 22, 22)"></i>
                      </div>
                    @else
                      <div class="my-auto pl-4 text-center">
                        <div class="form-group ">
                          <input type="checkbox" class="form-check-input" id="">
                        </div>
                      </div>
                    @endif

                    <div class="col-2">
                      <div class="form-group">
                        <label for="code">Code <span style="color: red">*</span></label>
                        <!-- Dropdown --> 
                          @if ($choosen_product->transfer_qty != null  && 
                              $choosen_product->transfer_qty != 0  ||
                              $choosen_product->mr_qty != 0 &&
                              $choosen_product->mr_qty != null ||
                              $choosen_product->supplier_return_qty != null &&
                              $choosen_product->supplier_return_qty != 0 
                            )
                            <select id='code_{{$i}}' required disabled name="code_{{$i}}" class="form-control getCode">
                                  <option selected value="{{$choosen_product->code_name}}">{{$choosen_product->code_name}}</option>
                            </select>
                          @else
                          <select id='code_{{$i}}' required name="code_{{$i}}" class="form-control getCode">
                              <option selected value="{{$choosen_product->code_name}}">{{$choosen_product->code_name}}</option>
                          </select>
                          @endif
                      </div>
                    </div>

                    <div class="col-2">
                      <div class="form-group">
                        <label for="brand">Brand Name <span style="color: red">*</span></label>
                        <!-- Dropdown --> 
                        @if ($choosen_product->transfer_qty != null  && 
                            $choosen_product->transfer_qty != 0  ||
                            $choosen_product->mr_qty != 0 &&
                            $choosen_product->mr_qty != null ||
                            $choosen_product->supplier_return_qty != null &&
                            $choosen_product->supplier_return_qty != 0 
                          )
                          <select id='brand_{{$i}}' disabled required name="brand_{{$i}}" class=" form-control getBrand ">
                            <option selected value="{{$choosen_product->brand_id}}">{{$choosen_product->brand_name}}</option>
                          </select>
                        @else
                          <select id='brand_{{$i}}' required name="brand_{{$i}}" class=" form-control getBrand ">
                            <option selected value="{{$choosen_product->brand_id}}">{{$choosen_product->brand_name}}</option>
                          </select>
                        @endif
                      </div>
                    </div>

                    <div class="col-2">
                      <div class="form-group">
                        <label for="commodity">Commodity <span style="color: red">*</span></label>
                        <!-- Dropdown --> 
                        @if ($choosen_product->transfer_qty != null  && 
                            $choosen_product->transfer_qty != 0  ||
                            $choosen_product->mr_qty != 0 &&
                            $choosen_product->mr_qty != null ||
                            $choosen_product->supplier_return_qty != null &&
                            $choosen_product->supplier_return_qty != 0 
                          )
                          <select id='commodity_{{$i}}' disabled required name="commodity_{{$i}}" class=" form-control ">
                            <option selected value="{{$choosen_product->commodity_id}}">{{$choosen_product->commodity_name}}</option>
                          </select>
                        @else
                          <select id='commodity_{{$i}}' required name="commodity_{{$i}}" class=" form-control ">
                            <option selected value="{{$choosen_product->commodity_id}}">{{$choosen_product->commodity_name}}</option>
                          </select>
                        @endif

                      </div>
                    </div>

                    <div class="col-1">
                      <div class="form-group">
                        <label for="qty">Qty <span style="color: red">*</span></label>
                        @if ($choosen_product->transfer_qty != null  && 
                              $choosen_product->transfer_qty != 0  ||
                              $choosen_product->mr_qty != 0 &&
                              $choosen_product->mr_qty != null ||
                              $choosen_product->supplier_return_qty != null &&
                              $choosen_product->supplier_return_qty != 0 
                          )
                            @php
                                $sum = $choosen_product->transfer_qty + $choosen_product->mr_qty + $choosen_product->supplier_return_qty;
                            @endphp
                          <input type="number" class="form-control transferQty" 
                                required id="qty_{{$i}}" 
                                name="qty_{{$i}}" 
                                value="{{$choosen_product->received_qty}}" 
                                step=".01" 
                                sum = {{$sum}}
                                oninput="validity.valid||(value='');"
                                >
                        @else
                          <input type="number" class="form-control" 
                                required id="qty_{{$i}}" 
                                name="qty_{{$i}}" 
                                value="{{$choosen_product->received_qty}}" 
                                step=".01" min=0
                                oninput="validity.valid||(value='');"
                          >
                          
                        @endif
                      </div>
                    </div>

                    <div class="col-1">
                      <div class="form-group">
                        <label for="unit_{{$i}}">Unit <span style="color: red">*</span></label>
                        <!-- Dropdown --> 
                        @if ($choosen_product->transfer_qty != null  && 
                          $choosen_product->transfer_qty != 0  ||
                          $choosen_product->mr_qty != 0 &&
                          $choosen_product->mr_qty != null ||
                          $choosen_product->supplier_return_qty != null &&
                          $choosen_product->supplier_return_qty != 0 
                          )
                            <select id='unit_{{$i}}' disabled required name="unit_{{$i}}" class=" form-control ">
                                @foreach ($units as $unit)
                                    @if ($unit->id == $choosen_product->unit_id)
                                        <option selected value="{{$unit->id}}">{{$unit->name}}</option>
                                    @else
                                        <option value="{{$unit->id}}">{{$unit->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                          @else
                          <select id='unit_{{$i}}' required name="unit_{{$i}}" class=" form-control ">
                              @foreach ($units as $unit)
                                  @if ($unit->id == $choosen_product->unit_id)
                                      <option selected value="{{$unit->id}}">{{$unit->name}}</option>
                                  @else
                                      <option value="{{$unit->id}}">{{$unit->name}}</option>
                                  @endif
                              @endforeach
                          </select>
                          @endif
                      </div>
                    </div>
                    
                    <div class="col-2">
                      <div class="form-group">
                        <label for="remark">Remark</label>
                        <input type="text" class="form-control" id="remark_{{$i}}" name="remark_{{$i}}" value="{{$choosen_product->remarks}}">
                      </div>
                    </div>

                  </div>
                @endforeach
              </div>
              <!-- End New Card -->

            </div>
            <!-- /.card-body -->

          <div class="card-footer ">
            <div class="d-flex justify-content-around">

              <a href="{{ route('products.index')}}" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>

        </form>

      </div>
      <!-- /.card -->
      
</div>
  <!-- /.container-fluid -->

@endsection
@section('scripts')

<script>
  $( "#warehouse" ).ready(function() {
      $("#warehouse").select2();
  });
  $( "#shelfnum" ).ready(function() {
      $("#shelfnum").select2();
  });
  $( "#supplier" ).ready(function() {
      $("#supplier").select2();
  });
  $( "#code_{{$i}}" ).ready(function() {
      $("#code_{{$i}}").select2();
  });
  $( "#brand_{{$i}}" ).ready(function() {
      $("#brand_{{$i}}").select2();
  });
  $( "#commodity_{{$i}}" ).ready(function() {
      $("#commodity_{{$i}}").select2();
  });
  $( "#unit_{{$i}}" ).ready(function() {
      $("#unit_{{$i}}").select2();
  });

</script>

<script>
  // accessing self numbers under choosen warehouse
  $('.getShelfNum').on('change', function() {
      var warehouse_id = this.value;
      $.ajax({
          url: "{{ route('products.getShelfNum') }}",
          type: "GET",
          data: {
              "warehouse_id": warehouse_id,
          },
          cache: false,
          success: function(result) {
            console.log(result);
              if (result) {
                  $("#shelfnum").empty();
                  $("#shelfnum").append(
                          `<option value="">Choose Shelf Number</option>`
                      );
                  $.each(result, function(key, value) {
                      $("#shelfnum").append(
                          `<option value="${value.id}">${value.shelfnumName}  (${value.shelfName})</option>`
                      );
                  });
              } else {
                  $("#shelfnum").empty();
              }
          }
      });
  });
     
</script>

<script>
 
  var codes = '<?php echo $codes ?>'
  var i = '<?php echo $i ?>'

  // moreCols
   $( "#newColumn" ).click(function() {
    ++i;
      var moreCols = `
                <div class="row d-flex justify-content-around deleteRow">
                  <div class="my-auto pl-4 text-center">
                    <div class="form-group ">
                      <input type="checkbox" class="form-check-input" id="">
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <label for="code">Code <span style="color: red">*</span></label>
                      <!-- Dropdown --> 
                      <select id='code_${i}' required name="code_${i}" class="form-control getCode">
                        <option value="" disabled selected>Choose Code</option>
                          @foreach ($codes as $code)
                              <option value="{{ $code->name }}">
                                  {{ $code->name }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-2">
                    <div class="form-group">
                      <label for="brand">Brand Name <span style="color: red">*</span></label>
                      <!-- Dropdown --> 
                      <select id='brand_${i}' required name="brand_${i}" class=" form-control getBrand">
                        <option value="" disabled selected>Choose Brand</option>
                       
                      </select>
                    </div>
                  </div>

                  <div class="col-2">
                    <div class="form-group">
                      <label for="commodity">Commodity <span style="color: red">*</span></label>
                      <!-- Dropdown --> 
                      <select id='commodity_${i}' required name="commodity_${i}" class=" form-control ">
                        <option value="" disabled selected>Choose Commodity</option>
                       
                      </select>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="qty">Qty <span style="color: red">*</span></label>
                      <input type="number" 
                        class="form-control" 
                        required id="qty_${i}" 
                        name="qty_${i}" step=".01" 
                        min=0.01 oninput="validity.valid||(value='');" 
                        value="0">
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="unit_${i}">Unit <span style="color: red">*</span></label>
                      <!-- Dropdown --> 
                      <select id='unit_${i}' required name="unit_${i}" class=" form-control">
                        <option value="" disabled selected>Units</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">
                                {{ $unit->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-2">
                    <div class="form-group">
                      <label for="remark">Remark</label>
                      <input type="text" class="form-control" id="remark_${i}" name="remark_${i}" placeholder="">
                    </div>
                  </div>

                </div>
      `
       $( ".moreCols" ).append(moreCols);

        $(".getCode").on('change', function() {
          var code_name = this.value;
          var row_id = this.id.split('_')[1];

            $.ajax({
                url: "{{ route('products.getFromCode') }}",
                type: "GET",
                data: {
                    "code_name": code_name,
                },
                cache: false,
                success: function(result) {
                    if (result) {
                      //selected brand list under code
                        $(`#brand_${row_id}`).empty();
                        $(`#brand_${row_id}`).append(
                                  `<option value="" >Choose Brand</option>`
                          );
                        $.each(result.brands, function(key, value) {
                          $(`#brand_${row_id}`).append(
                                  `<option value="${value.id}" >${value.name}</option>`
                          )
                        });

                    } else {
                        $(`#brand_${row_id}`).empty();
                    }
                }
            });
        });

        // accessing code/Commodity under choosen brand
        $(".getBrand").on('change', function() {
            var brand_id = this.value;
            var row_id = this.id.split('_')[1];
            var commodity_id = $(`#commodity_${row_id}`).val();
            var code_name = $(`#code_${row_id}`).val();
      
              $.ajax({
                  url: "{{ route('products.getFromBrand') }}",
                  type: "GET",
                  data: {
                      "brand_id": brand_id,
                      "code_name": code_name,
                  },
                  cache: false,
                  success: function(result) {
                      if (result) {

                          $(`#commodity_${row_id}`).empty();
                          $(`#commodity_${row_id}`).append(
                                `<option value="" >Choose Commodity</option>`
                            )
                          $.each(result.commodities, function(key, value) {
                            $(`#commodity_${row_id}`).append(
                                `<option value="${value.id}" >${value.name}</option>`
                            )
                          });
                      
                      } else {
                          $(`#commodity_${row_id}`).empty();
                      }
                  }
              });
            
        });

  });

</script>


<script>
      $(".getCode").on('change', function() {
          var code_name = this.value;
          var row_id = this.id.split('_')[1];

            $.ajax({
                url: "{{ route('products.getFromCode') }}",
                type: "GET",
                data: {
                    "code_name": code_name,
                },
                cache: false,
                success: function(result) {
                    if (result) {
                      //selected brand list under code
                        $(`#brand_${row_id}`).empty();
                        $(`#brand_${row_id}`).append(
                                  `<option value="" >Choose Brand</option>`
                          );
                        $.each(result.brands, function(key, value) {
                          $(`#brand_${row_id}`).append(
                                  `<option value="${value.id}" >${value.name}</option>`
                          )
                        });

                    } else {
                        $(`#brand_${row_id}`).empty();
                    }
                }
            });
      });

        // accessing code/Commodity under choosen brand
        $(".getBrand").on('change', function() {
            var brand_id = this.value;
            var row_id = this.id.split('_')[1];
            var commodity_id = $(`#commodity_${row_id}`).val();
            var code_name = $(`#code_${row_id}`).val();
      
              $.ajax({
                  url: "{{ route('products.getFromBrand') }}",
                  type: "GET",
                  data: {
                      "brand_id": brand_id,
                      "code_name": code_name,
                  },
                  cache: false,
                  success: function(result) {
                      if (result) {

                          $(`#commodity_${row_id}`).empty();
                          $(`#commodity_${row_id}`).append(
                                `<option value="" >Choose Commodity</option>`
                            )
                          $.each(result.commodities, function(key, value) {
                            $(`#commodity_${row_id}`).append(
                                `<option value="${value.id}" >${value.name}</option>`
                            )
                          });
                      
                      } else {
                          $(`#commodity_${row_id}`).empty();
                      }
                  }
              });
            
        });
</script>

<script>
  $('#checkAll').click(function(e) {
    $("input:checkbox").prop('checked',$(this).is(':checked'))
  });

  $('#deleteColumn').click(function(e) {
    var deleteRow = $('input:checked').closest('.deleteRow'); 
    deleteRow.empty();
  });


</script>


<script>
  //disabling 
    var transfer_exist = '<?php echo $choosen_products ?>'

    $.each(JSON.parse(transfer_exist), function(key, value) {
      if (value.transfer_qty != null  && 
          value.transfer_qty != 0  ||
          value.mr_qty != 0 &&
          value.mr_qty != null ||
          value.supplier_return_qty != null &&
          value.supplier_return_qty != 0 
          ){
          $("#warehouse").attr("disabled",true);   
          $("#shelfnum").attr("disabled",true);
          $("#supplier").attr("disabled",true);
          $("#vr_no").attr("disabled",true);
          $(".transferQty").hover( function() {

            var row_id = this.id.split('_')[1];
            
            var min = $(`#qty_${row_id}`).attr('sum');
            $(`#qty_${row_id}`).popover({
              content: `Ensure not to less than the transfer, issue, supplier return amount.(${min}).` 
            })
          });
        }
    });

    //limit transfer date 
    var transfer_date = '<?php echo $transfer_date ?>'
    if (transfer_date != '') {
      
      if (JSON.parse(transfer_date).transfer_date != null) {
        $("#date").hover( function() {
          $("#date").popover({
            content: `Ensure not to less than the transfer date.(${JSON.parse(transfer_date).transfer_date}).` 
          })
          
        });
        $("#date").attr({"min" : `${JSON.parse(transfer_date).transfer_date}`});
      };
    }
</script>

@endsection