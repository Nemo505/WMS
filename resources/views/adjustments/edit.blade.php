@extends('layouts.master')
@section('title', 'Edit Adjustment')

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
        <form method="POST" id="myForm" action="{{route('adjustments.update')}}">
          @csrf
          <div class="card-body">

              <!-- mainform -->
              <div class="row">
                <div class="col-6">
                  <div class="form-group ">
                    <label for="warehouse_id">Warehouse Name<span style="color: red">*</span> </label> 
                    <div>

                      <select id='warehouse_id' required name="warehouse_id" class="form-control getShelfNum">
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
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <div class="form-group ">
                      <label for="shelfnum_id">Shelf Number<span style="color: red">*</span> </label> 
                      <div>

                        <select id='shelfnum_id' required name="shelfnum_id" class="form-control">
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
                </div>

              </div>
              
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="adjustment_no">Adjustment No<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="adjustment_no" name="adjustment_no" value="{{$adjustment->adjustment_no}}">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="date">Date<span style="color: red">*</span> </label> 
                    <input type="date" name="date" required class="form-control" id="date" placeholder="" value="{{$adjustment->adjustment_date}}">
                  </div>
                </div>

              </div>
              <!-- end mainform -->

              <div class="row my-3 ml-1 d-flex justify-content-between">
                <div class="col-4 d-flex">
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

                <div class="col-5 text-end ">
                  <button type="button" class="btn float-right text-white useScanner" style="background-color: rgb(121, 77, 163)">
                    <i class="fas fa-barcode  mx-1"  style="color: #ffffff;"></i>
                    Use Scanner
                  </button>

                  <div class="d-flex">
                   <button type="button" class="btn btn-outline-primary	submit_barcode" style=" opacity:0 " value="divide" >
                     Submit
                   </button>
                  <input  placeholder="scan..." class="form-control mr-3" 
                          type="text" tabindex="1" name="scanner" 
                          id="scanner" autofocus  style="display: none">
                  </div>
                </div>
              </div>

              {{-- hidden one adjustment for check --}}
              <input type="hidden" value="{{$adjustment->id}}" name="old_adjustment" >
      
              <!-- Add New Card -->
              <div class="moreCols">
               
                @foreach ($choosen_adjustments as $choosen_adjustment)
                    @php
                      ++$i;
                      $choosen_transfer = App\Models\Transfer::find(optional($choosen_adjustment)->transfer_id);
                    @endphp
                    {{-- hidden all products --}}
                    <input type="hidden" value="{{$choosen_adjustment->adjustment_id}}" name="adjustment_{{$i}}" >
                    <div class="row d-flex justify-content-around deleteRow">
                      
                      @if ( $choosen_adjustment->type == 'add' && 
                            $choosen_adjustment->qty > $choosen_adjustment->balance_qty
                          )
                        <div class="my-auto pl-1 text-center">
                            <i class="fas fa-window-close" 
                              style="color: rgb(196, 22, 22)"></i>
                        </div>
                      @else
                        <div class="my-auto pl-4 pb-3 text-center ">
                          <div class="form-group ">
                            <input type="checkbox" class="form-check-input" id="">
                          </div>
                        </div>
                      @endif

                    <div class="col-1 justify-content-center  align-items-center">
                  
                      @if ($choosen_adjustment->image != null )
                        <div class="form-group">
                            <img src="{{URL::asset($choosen_adjustment->image)}}" id="img_{{$i}}" 
                                  class="isImg"
                                  alt="code" height="100" 
                                  width="100%" 
                                  style="object-fit: contain" >
                        </div>
                      @else
                        <div class="form-group">
                            <img src="/storage/img/code/no-img.jpg" id="img_{{$i}}" 
                                  class="isImg"
                                  alt="code" height="100" 
                                  width="100%" 
                                  style="object-fit: contain" >
                        </div>
                      @endif
                    </div>

                    <div class="col-1">
                        <div class="form-group">
                        <label for="code">Code<span style="color: red">*</span> </label> 
                        @if ( $choosen_adjustment->type == 'add' && 
                            $choosen_adjustment->qty > $choosen_adjustment->balance_qty
                          )
                          <select disabled id='code_{{$i}}' required name="code_{{$i}}" class="form-control getCode">
                              <option selected value="{{$choosen_adjustment->code_name}}">{{$choosen_adjustment->code_name}}</option>
                          </select>
                        @else

                          <select id='code_{{$i}}' required name="code_{{$i}}" class="form-control getCode">
                            <option selected value="{{$choosen_adjustment->code_name}}">{{$choosen_adjustment->code_name}}</option>
                          </select>

                        @endif
                        </div>
                    </div>
        
                    <div class="col-1">
                        <div class="form-group">
                        <label for="brand">Brand<span style="color: red">*</span> </label> 
                        @if ( $choosen_adjustment->type == 'add' && 
                            $choosen_adjustment->qty > $choosen_adjustment->balance_qty
                          )
                          <select disabled id='brand_{{$i}}' required name="brand_{{$i}}" class=" form-control getBrand ">
                            <option selected value="{{$choosen_adjustment->brand_id}}">{{$choosen_adjustment->brand_name}}</option>
                          </select>
                        @else
                          <select id='brand_{{$i}}' required name="brand_{{$i}}" class=" form-control getBrand ">
                            <option selected value="{{$choosen_adjustment->brand_id}}">{{$choosen_adjustment->brand_name}}</option>
                          </select>
                        @endif
                        </div>
                    </div>
        
                    <div class="col-1">
                        <div class="form-group">
                        <label for="commodity">Commodity<span style="color: red">*</span> </label> 

                        @if ( $choosen_adjustment->type == 'add' && 
                              $choosen_adjustment->qty > $choosen_adjustment->balance_qty
                            )
                            <select disabled id='commodity_{{$i}}' required name="commodity_{{$i}}" class=" form-control getVr">
                              <option selected value="{{$choosen_adjustment->commodity_id}}">{{$choosen_adjustment->commodity_name}}</option>
                            </select>
                          @else
                            <select id='commodity_{{$i}}' required name="commodity_{{$i}}" class=" form-control getVr">
                              <option selected value="{{$choosen_adjustment->commodity_id}}">{{$choosen_adjustment->commodity_name}}</option>
                            </select>
                          @endif
                        </div>
                    </div>
        
                    <div class="col-1">
                        <div class="form-group">
                        <label for="voucher">Voucher_No<span style="color: red">*</span> </label> 
                       
                        @if ( $choosen_adjustment->type == 'add' && 
                            $choosen_adjustment->qty > $choosen_adjustment->balance_qty
                          )
                          <select disabled id='vr_no_{{$i}}' required name="vr_no_{{$i}}" class=" form-control getQty">
                            <option selected value="{{$choosen_adjustment->product_id}}">
                              {{$choosen_adjustment->voucher_no}} 
                              {{$choosen_transfer != null ? $choosen_transfer->transfer_no : ''}}
                              </option>
                          </select>
                        @else
                          <select id='vr_no_{{$i}}' required name="vr_no_{{$i}}" class=" form-control getQty">
                            <option selected value="{{$choosen_adjustment->product_id}}">
                              {{$choosen_adjustment->voucher_no}} 
                              {{$choosen_transfer != null ? $choosen_transfer->transfer_no : ''}}
                              </option>
                          </select>
                        @endif
                           
                        </div>
                    </div>

                    <div class="col-1">
                        <div class="form-group">
                          @php
                              $limit_qty = $choosen_adjustment->qty - $choosen_adjustment->balance_qty;
                          @endphp
                          
                          @if ($choosen_adjustment->type == 'sub')
                            <label for="qty_{{$i}}" class="labelQty">{{$choosen_adjustment->balance_qty }} Qty</label> 
                                <input type="number" class="form-control isQty" 
                                        required id="qty_{{$i}}" 
                                        name="qty_{{$i}}"
                                        step=".01" min='0.01'
                                        max={{$choosen_adjustment->balance_qty + $choosen_adjustment->qty }}
                                        oninput="validity.valid||(value='');"
                                        value="{{$choosen_adjustment->qty}}"
                                > 
                          @elseif ( $choosen_adjustment->type == 'add' && 
                                    $choosen_adjustment->qty > $choosen_adjustment->balance_qty
                                  )
                            <label for="qty_{{$i}}" class="labelQty">{{$choosen_adjustment->balance_qty}} Qty</label> 
                            <input type="number" class="form-control isQty" 
                                    required id="qty_{{$i}}" 
                                    name="qty_{{$i}}"
                                    step=".01" min={{$choosen_adjustment->qty - $choosen_adjustment->balance_qty }} 
                                    max=''
                                    oninput="validity.valid||(value='')"
                                    value="{{$choosen_adjustment->qty}}"
                            >   
                          @else
                            <label for="qty_{{$i}}" class="labelQty">{{$choosen_adjustment->balance_qty }} Qty</label> 
                            <input type="number" class="form-control" 
                                    required id="qty_{{$i}}" 
                                    name="qty_{{$i}}"
                                    step=".01" 
                                    max=''
                                    oninput="validity.valid||(value='')"
                                    value="{{$choosen_adjustment->qty}}"
                            >   
                          @endif

                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group">
                          <label for="type_{{$i}}">Add or Sub<span style="color: red">*</span> </label> 
                          
                          @if ($choosen_adjustment->type == 'sub')
                            <select id="type_{{$i}}" required name="type_{{$i}}" class=" form-control getAdBalance">
                              <option value="{{ $choosen_adjustment->type}}" selected>{{ $choosen_adjustment->type}}</option>
                              <option value="add" >add</option>
                            </select>
                          @elseif ( $choosen_adjustment->type == 'add' && 
                                $choosen_adjustment->qty > $choosen_adjustment->balance_qty
                              )
                              <select disabled id="type_{{$i}}" required name="type_{{$i}}" class=" form-control getAdBalance">
                                <option  value="{{ $choosen_adjustment->type}}" >{{ $choosen_adjustment->type}}</option>
                                <option value="sub" >sub</option>
                              </select>
                          @else 
                              <select id="type_{{$i}}" required name="type_{{$i}}" class=" form-control getAdBalance">
                                <option value="{{ $choosen_adjustment->type}}" >{{ $choosen_adjustment->type}}</option>
                                <option value="sub" >sub</option>
                              </select>
                          @endif
                          
                        </div>
                    </div>
        
                    <div class="col-2">
                        <div class="form-group">
                        <label for="usage">Usage </label> 
                        <p id="usage_{{$i}}" 
                            name="usage_{{$i}}" 
                            style="color: rgb(149, 155, 155)"
                            class="isUsage"
                            >{{$choosen_adjustment->usage }}
                        </p>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-group">
                        <label for="remark">Remark </label> 
                        <input type="text" class="form-control" id="remark_{{$i}}" name="remark_{{$i}}" value="{{$choosen_adjustment->remarks}}">
                        </div>
                    </div>
        
                    </div>
                @endforeach
              </div>
              <!-- End New Card -->
        </div>
        <!-- /.card-body -->
      </div>

        <div class="card-footer ">
          <div class="d-flex justify-content-around">

            <a href="{{ route('adjustments.index')}}" type="" class="btn btn-secondary">Cancel</a>
             <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>

      </form>
</div>
  <!-- /.container-fluid -->

@endsection
@section('scripts')

<script>
  $( "#warehouse_id" ).ready(function() {
      $("#warehouse_id").select2();
  });

  $( "#shelfnum_id" ).ready(function() {
      $("#shelfnum_id").select2();
  });

  $( "#customer_id" ).ready(function() {
      $("#customer_id").select2();
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

  $( "#type_{{$i}}" ).ready(function() {
      $("#type_{{$i}}").select2();
  });
  $( "#vr_no_{{$i}}" ).ready(function() {
      // Initialize select2
      $("#vr_no_{{$i}}").select2();
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
              if (result) {
                  $("#shelfnum_id").empty();
                  $("#shelfnum_id").append(
                          `<option value="">Choose Shelf Number</option>`
                      );
                  $.each(result, function(key, value) {
                      $("#shelfnum_id").append(
                          `<option value="${value.id}">${value.shelfnumName}  (${value.shelfName})</option>`
                      );
                  });
              } else {
                  $("#shelfnum_id").empty();
              }
          }
      });
  });

  $('.getToShelfNum').on('change', function() {
      var warehouse_id = this.value;
      $.ajax({
          url: "{{ route('products.getShelfNum') }}",
          type: "GET",
          data: {
              "warehouse_id": warehouse_id,
          },
          cache: false,
          success: function(result) {
              if (result) {
                  $("#to_shelfnum_id").empty();
                  $("#to_shelfnum_id").append(
                          `<option value="">Choose Shelf Number</option>`
                      );
                  $.each(result, function(key, value) {
                      $("#to_shelfnum_id").append(
                          `<option value="${value.id}">${value.shelfnumName}  (${value.shelfName})</option>`
                      );
                  });
              } else {
                  $("#to_shelfnum_id").empty();
              }
          }
      });
  });
  

</script>


<script>
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

                  <div class="col-1 justify-content-center  align-items-center">
                    <div class="form-group">
                          <img src="/storage/img/code/no-img.jpg" 
                          id='img_${i}' 
                          class="isImg"
                          alt="code" height="100" 
                          width="100%" style="object-fit: contain"
                           >
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="code">Code<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='code_${i}' required name="code_${i}" class="form-control getCode">
                        <option value="" disabled selected>Choose Code</option>
                         
                      </select>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="brand">Brand<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='brand_${i}' required name="brand_${i}" class=" form-control getBrand">
                        <option value="" disabled selected>Choose Brand</option>
                        
                      </select>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='commodity_${i}' required name="commodity_${i}" class=" form-control getVr">
                        <option value="" disabled selected>Choose Commodity</option>
                        
                      </select>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="vr_no">Voucher_No<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='vr_no_${i}' required name="vr_no_${i}" class=" form-control getQty">
                        <option value="" disabled selected>Choose Voucher</option>
                        
                      </select>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="qty_${i}" class="labelQty">__Qty</label> 
                      <input type="number" 
                        class="form-control isQty" required 
                        id="qty_${i}" name="qty_${i}" 
                        step=".01" min=0.01 
                        oninput="validity.valid||(value='');" 
                        >
                     
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="type_${i}">Add or Sub<span style="color: red">*</span> </label> 
                      <select id='type_${i}' required name="type_${i}" class=" form-control getBalance">
                        <option value="" disabled selected>Choose Type</option>
                        <option value="add"> add</option>
                        <option value="sub"> sub </option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-2">
                    <div class="form-group">
                      <label for="usage">Usage<span style="color: red">*</span> </label> 
                      <p id="usage_${i}" name="usage_${i}"  style="color: rgb(149, 155, 155)" class="isUsage">Detailed Description</p>
                    </div>
                  </div>

                  <div class="col-2">
                    <div class="form-group">
                      <label for="remark">Remark<span style="color: red">*</span> </label> 
                      <input type="text" class="form-control" id="remark_${i}" name="remark_${i}" placeholder="">
                    </div>
                  </div>

                </div>
      `
       $( ".moreCols" ).append(moreCols);

         // accessing brand/Commodity under choosen code
         $(".getCode").on('change', function() {
          var code_name = this.value;
          var row_id = this.id.split('_')[1];
          var shelfnum_id = $("#shelfnum_id").val();

            $.ajax({
                url: "{{ route('products.getFromCode') }}",
                type: "GET",
                data: {
                    "code_name": code_name,
                    "shelfnum_id": shelfnum_id,
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
            var shelfnum_id = $("#shelfnum_id").val();
            
              $.ajax({
                  url: "{{ route('products.getFromBrand') }}",
                  type: "GET",
                  data: {
                      "brand_id": brand_id,
                      "code_name": code_name,
                      "shelfnum_id": shelfnum_id,
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

        var shelfnum_id = $('#shelfnum_id').val();
        if (shelfnum_id !== undefined) {
          $.ajax({
              url: "{{ route('transfers.getCode') }}",
              type: "GET",
              data: {
                  "shelfnum_id": shelfnum_id,
              },
              cache: false,
              success: function(result) {
                  if (result) {
                      $(`#code_${i}`).empty();
                      $(`#code_${i}`).append(
                              '<option value="" >Choose Code</option>'
                          );
                      $(`#brand_${i}`).empty();
                      $(`#brand_${i}`).append(
                              '<option value="" >Choose Brand</option>'
                          );
                      $(`#commodity_${i}`).empty();
                      $(`#commodity_${i}`).append(
                              '<option value="" >Choose Commodity</option>'
                          );
                      
                      $(`#vr_no_${i}`).empty();
                      $(`#vr_no_${i}`).append(
                              '<option value="" >Choose Voucher</option>'
                          );
                      $(`label[for=qty_${i}]`).text(`__Qty`);
                      $(`#qty_${i}`).attr({
                                            "max" : `0`,
                                            "value" : 0,
                                        });
                      $(`#usage_${i}`).html("Detailed Description");
                      $(`#img_${i}`).attr("src", "/storage/img/code/no-img.jpg");

                      $.each(result.codes, function(key, value) {
                              $(`#code_${i}`).append(
                                '<option value="' + value.name + '">' + value.name +'</option>'
                              );
                        });
                      
      
                  } else {
                      $(`#code_${i}`).empty();
                      $(`#brand_${i}`).empty();
                      $(`#commodity_${i}`).empty();
                      $(`#vr_no_${i}`).empty();
                  }
              }
          });
        }

        //accessing commodity lists under code and brand
        $('.getVr').on('change', function() {
            var commodity_id = this.value;
            var row_id = this.id.split('_')[1];

            var brand_id = $(`#brand_${row_id}`).val();
            var code_name = $(`#code_${row_id}`).val();
            var shelfnum_id = $("#shelfnum_id").val();

            $.ajax({
                url: "{{ route('transfers.getVr') }}",
                type: "GET",
                data: {
                    "brand_id": brand_id,
                    "commodity_id": commodity_id,
                    "code_name": code_name,
                    "shelfnum_id": shelfnum_id,
                },
                cache: false,
                success: function(result) {
                    if (result) {
                        $(`#vr_no_${row_id}`).empty();
                        $(`#vr_no_${row_id}`).append(
                              `<option value="" >Choose Voucher</option>`
                          )
                        $.each(result.vr_nos, function(key, value) {
                          if (value.transfer_no == null) {
                            $(`#vr_no_${row_id}`).append(
                              `<option value="${value.id}" >${value.voucher_no}</option>`
                            )
                          }else{
                              $(`#vr_no_${row_id}`).append(
                                `<option value="${value.id}" >${value.voucher_no} || ${value.transfer_no}</option>`
                            )};
                              
                          })

                          //qty under vr no
                          $(`#vr_no_${row_id}`).on('change', function() {
                            var vr_no = this.value;
                            
                            $.each(result.vr_nos, function(key, value) {
                              if (value.id == vr_no) {

                                $(`#qty_${row_id}`).attr({
                                            "max" : `${value.balance_qty}`,
                                            "value" : 0,
                                        });
                                $(`label[for=qty_${row_id}]`).text(`${value.balance_qty} Qty`);
                                $(`#usage_${row_id}`).text(`${value.usage}`);
                                $(`#img_${row_id}`).attr("src", `{{ URL::asset('${value.image}') }}`);
                              }
                              
                              });
                          });
                    
                    } else {
                        $(`#vr_no_${row_id}`).empty();
                    }
                }
            });
        });

        $('.getBalance').on('change', function() {
            var type = this.value;
            var row_id = this.id.split('_')[1];

            var balance = $(`label[for=qty_${row_id}]`).text().split(" ");
            var input = $(`#qty_${row_id}`).val();
            if (type == 'sub') {

                if (parseInt(balance[0]) < parseInt(input)) {
                  $(`#qty_${row_id}`).attr({
                                        "max" : balance[0] ,
                                        "value" : balance[0] ,
                                    });
                  $(`#qty_${row_id}`).val(balance[0]);
                }
              }else{
                $(`#qty_${row_id}`).attr({
                                          "max" : ``,
                                          "value" : input ,
                                      });
              }

        });

  });

    //from to shelfnumber id to codes, brands, commodities
    $('#shelfnum_id').on('change', function() {
      var shelfnum_id = this.value;
      $.ajax({
          url: "{{ route('transfers.getCode') }}",
          type: "GET",
          data: {
              "shelfnum_id": shelfnum_id,
          },
          cache: false,
          success: function(result) {
              if (result) {
                  $(".getCode").empty();
                  $(".getCode").append(
                          '<option value="" >Choose Code</option>'
                      );
                  $(".getBrand").empty();
                  $(".getBrand").append(
                          '<option value="" >Choose Brand</option>'
                      );
                  $(".getVr").empty();
                  $(".getVr").append(
                          '<option value="" >Choose Commodity</option>'
                      );
                  $(".getQty").empty();
                  $(".getQty").append(
                    '<option value="" >Choose Voucher</option>'
                    );
                  $(".isUsage").text("Detailed Description");
                  $(`.labelQty`).text(`__Qty`);
                  $(`.isImg`).attr("src", `/storage/img/code/no-img.jpg`);
                  $(".isQty").attr({
                                      "max" : `0`,
                                      "value" : 0,
                                   });

                  $.each(result.codes, function(key, value) {
                          $(".getCode").append(
                            '<option value="' + value.name + '">' + value.name +'</option>'
                          );
                    });
  
              } else {
                  $(".getCode").empty();
                  $(".getBrand").empty();
                  $(".getVr").empty();
                  $(".getQty").empty();
              }
          }
      });
    });

   
</script>

<script>
  var i = '<?php echo $i ?>'
  $(".getCode").on('change', function() {
    var code_name = this.value;
    var row_id = this.id.split('_')[1];
    var shelfnum_id = $("#shelfnum_id").val();

      $.ajax({
          url: "{{ route('products.getFromCode') }}",
          type: "GET",
          data: {
              "code_name": code_name,
              "shelfnum_id": shelfnum_id,
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
      var shelfnum_id = $("#shelfnum_id").val();
      
        $.ajax({
            url: "{{ route('products.getFromBrand') }}",
            type: "GET",
            data: {
                "brand_id": brand_id,
                "code_name": code_name,
                "shelfnum_id": shelfnum_id,
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

  //accessing commodity lists under code and brand
  $('.getVr').on('change', function() {
        var commodity_id = this.value;
        var row_id = this.id.split('_')[1];

        var brand_id = $(`#brand_${row_id}`).val();
        var code_name = $(`#code_${row_id}`).val();
        var shelfnum_id = $("#shelfnum_id").val();

        $.ajax({
            url: "{{ route('transfers.getVr') }}",
            type: "GET",
            data: {
                "brand_id": brand_id,
                "commodity_id": commodity_id,
                "code_name": code_name,
                "shelfnum_id": shelfnum_id,
            },
            cache: false,
            success: function(result) {
                if (result) {
                    $(`#vr_no_${row_id}`).empty();
                    $(`#vr_no_${row_id}`).append(
                          `<option value="" >Choose Voucher</option>`
                      )
                    $.each(result.vr_nos, function(key, value) {
                      if (value.transfer_no == null) {
                        $(`#vr_no_${row_id}`).append(
                          `<option value="${value.id}" >${value.voucher_no}</option>`
                        )
                      }else{
                          $(`#vr_no_${row_id}`).append(
                            `<option value="${value.id}" >${value.voucher_no} || ${value.transfer_no}</option>`
                        )};
                          
                      })

                      //qty under vr no
                      $(`#vr_no_${row_id}`).on('change', function() {
                        var vr_no = this.value;
                        
                        $.each(result.vr_nos, function(key, value) {
                          if (value.id == vr_no) {

                            $(`#qty_${row_id}`).attr({
                                        "max" : `${value.balance_qty}`,
                                        "value" : 0,
                                    });
                            $(`label[for=qty_${row_id}]`).text(`${value.balance_qty} Qty`);
                            $(`#usage_${row_id}`).text(`${value.usage }`);
                            $(`#img_${row_id}`).attr("src", `{{ URL::asset('${value.image}') }}`);
                          }
                          
                          });
                      });
                
                } else {
                    $(`#vr_no_${row_id}`).empty();
                }
            }
        });
  });

  $('.getAdBalance').on('change', function() {
      var type = this.value;
      var row_id = this.id.split('_')[1];
      var balance = $(`label[for=qty_${row_id}]`).text().split(" ");
      var input = $(`#qty_${row_id}`).val();

      if (type == 'sub') {

        if (parseInt(balance[0]) < parseInt(input)) {
          $(`#qty_${row_id}`).attr({
                                  "max" : balance[0],
                                  "value" : balance[0] ,
                              });
          $(`#qty_${row_id}`).val(balance[0]);
        }
      }else{
        $(`#qty_${row_id}`).attr({
                                  "max" : ``,
                                  "value" : input ,
                              });
      }

  });

</script>

<script>
  //disabling 
    var adjustment = '<?php echo $choosen_adjustments ?>'

    $.each(JSON.parse(adjustment), function(key, value) {

        $(".isQty").hover( function() {
            var row_id = this.id.split('_')[1];
            var min = $(`#qty_${row_id}`).attr('min');
            var max = $(`#qty_${row_id}`).attr('max');
            if (max == '') {
              $(`#qty_${row_id}`).popover({
                content: `Please Ensure that the value is between (${min}) and above.` 
              })
            }else {
              $(`#qty_${row_id}`).popover({
                content: `Please Ensure that the value is between (${min}) and (${max}).` 
              })
            }
          });

          if ( value.type == 'add' && parseInt(value.balance_qty) < parseInt(value.qty) ) {
              $("#warehouse_id").attr("disabled",true); 
              $("#shelfnum_id").attr("disabled",true);
              $("#adjustment_no").attr("disabled",true);
          }
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
    let isScannerInput = '';
    
  $('#scanner').keyup(function() {
      var value = $('#scanner').val();
      if (value.length === 9 || value.length === 10) {
        if (value.length === 10) {
            value = value.substring(0, 9);
        }
          if (isScannerInput === value) {
              $('#scanner').val('');
               
            }else{
                if ($('#shelfnum_id').val() != null && $('#shelfnum_id').val() != '') {
                      if ($('#scanner').val() != null && $('#scanner').val() != '') {
                        ++i;
                            $.ajax({
                                url: "{{ route('scanners.store') }}",
                                type: "GET",
                                data: {
                                  "barcode": $('#scanner').val(),
                                  "shelfnum_id": $("#shelfnum_id").val(),
                                },
                                cache: false,
                                success: function (result) {
                                  if (result !== null) {
                
                                    $('#scanner').val('');
                                    var img = result['product'].image;
                                    $( ".moreCols" ).append(
                                      `
                                      <div class="row d-flex justify-content-around deleteRow">
                                        <div class="my-auto pl-4 text-center">
                                          <div class="form-group ">
                                            <input type="checkbox" class="form-check-input" id="">
                                          </div>
                                        </div>
                      
                                        <div class="col-1 justify-content-center align-items-center">
                                          <div class="form-group">
                                            <img src={{ URL::asset('${img}')}} id="img_${i}"
                                                    class="isImg"
                                                    alt="code" height="100" 
                                                    width="100%" 
                                                    style="object-fit: contain" >
                      
                                                
                                          </div>
                                        </div>
                    
                                        <div class="col-1">
                                          <div class="form-group">
                                            <label for="code">Code<span style="color: red">*</span> </label> 
                                            <!-- Dropdown --> 
                                            <select id='code_${i}' required name="code_${i}" class="form-control getCode">
                                              <option value="${result['product'].name}" >${result['product'].name}</option>
                                            </select>
                      
                                          </div>
                                        </div>
                    
                                        <div class="col-1">
                                          <div class="form-group">
                                            <label for="brand">Brand <span style="color: red">*</span> </label> 
                                            <!-- Dropdown --> 
                                            <select id='brand_${i}' required name="brand_${i}" class=" form-control getBrand">
                                              <option value="${result['product'].brand_name}" >${result['product'].brand_name}</option>
                                              
                                            </select>
                                          </div>
                                        </div>
                    
                                        <div class="col-1">
                                          <div class="form-group">
                                            <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                                            <!-- Dropdown --> 
                                            <select id='commodity_${i}' required name="commodity_${i}" class=" form-control getVr">
                                              <option value="${result['product'].commodity_name}" >${result['product'].commodity_name}</option>
                                              
                                            </select>
                                          </div>
                                        </div>
                    
                                        <div class="col-1">
                                          <div class="form-group">
                                            <label for="vr_no">Voucher_No<span style="color: red">*</span> </label> 
                                            <!-- Dropdown --> 
                                            <select id='vr_no_${i}' required name="vr_no_${i}" class=" form-control getQty">
                
                                              ${result['transfer'] !== null ?
                                              
                                                `<option value="${result['product'].id}" >${result['product'].voucher_no} || ${result['transfer'].transfer_no}</option>`
                                              :
                                                `<option value="${result['product'].id}" >${result['product'].voucher_no}</option>`
                                              
                                             }
                                              
                                            </select>
                                          </div>
                                        </div>
                    
                                        <div class="col-1">
                                          <div class="form-group">
                                            <label for="qty_${i}" class="labelQty">${result['product'].balance_qty} Qty </label> 
                                            <input type="number" class="form-control"
                                             required id="qty_${i}" 
                                             name="qty_${i}" step=".01" 
                                             min=0.01 oninput="validity.valid||(value='');" 
                                             value=0
                                             placeholder="">
                                           
                                          </div>
                                        </div>
                    
                                        <div class="col-1">
                                          <div class="form-group">
                                            <label for="type_${i}">Add or Sub<span style="color: red">*</span> </label> 
                                            <select id='type_${i}' required name="type_${i}" class=" form-control getBalance">
                                              <option value="" disabled selected>Choose Type</option>
                                              <option value="add"> add </option>
                                              <option value="sub"> sub </option>
                                            </select>
                                          </div>
                                        </div>
                                        
                                      
                                        <div class="col-2">
                                          <div class="form-group">
                                            <label for="usage">Usage</label> 
                                            <p id="usage_${i}" 
                                                name="usage_${i}" 
                                                style="color: rgb(149, 155, 155)"
                                                class="isUsage"
                                                >${result['product'].usage}
                                              </p>
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
                                    )
                
                                    $(`#type_${i}`).on('change', function() {
                                        var type = this.value;
                                        var row_id = this.id.split('_')[1];
                  
                                        var balance = $(`label[for=qty_${row_id}]`).text().split(" ");
                                        var input = $(`#qty_${row_id}`).val();
                                        if (type == 'sub') {
                  
                                          if (input > balance[0]) {
                                            $(`#qty_${row_id}`).prop({
                                                                    "max" : balance[0],
                                                                    "value" : balance[0] ,
                                                                });
                                            $(`#qty_${row_id}`).val(balance[0]);
                                          }
                                        }else{
                                          $(`#qty_${row_id}`).prop({
                                                                    "max" : ``,
                                                                    "value" : input ,
                                                                });
                                        }
                  
                                    });
                
                                  }
                
                                }
                            });
                
                      }else{
                        $('#scanner').val('');
                      }
                    }else{
                      $('#scanner').val('');
                    }
                    isScannerInput = value;
            }
      }
  });

</script>

<script>

  $('.useScanner').click(function () {
    $(this).css('display','none');
    $('#scanner').css('display','block');
    $('#scanner').focus();
    $('.submit_barcode').css('display','block');
  })
</script>

@endsection