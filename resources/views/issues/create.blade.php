@extends('layouts.master')
@section('title', 'New Issue')

@section('css')

@endsection

@section('content')
@php
    $i = 1;
@endphp
<div class="container-fluid">
    
      <!-- general form elements -->
      <div class="card card-primary">
        <!-- form start -->
        <form method="POST" id="myForm" action="{{route('issues.store')}}">
          @csrf
          <div class="card-body">

              <!-- mainform -->
              <div class="row">

                <div class="col-3">
                  <label for="customer">Customer<span style="color: red">*</span> </label> 
                  <div>
                      <select id='customer' required name="customer" class="form-control">
                        <option value="" disabled selected>Choose Customer</option>
                          @foreach ($customers as $customer)
                              <option value="{{ $customer->id }}">
                                  {{ $customer->name }}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
              
                <div class="col-2">
                  <div class="form-group">
                    <label for="department_id">Department<span style="color: red">*</span> </label> 

                    <div>
                      <select id='department_id' required name="department_id" class="form-control">
                        <option value="" disabled selected>Choose Department</option>
                          @foreach ($departments as $department)
                              <option value="{{ $department->id }}">
                                  {{ $department->name }}</option>
                          @endforeach
                      </select>
                    </div>

                  </div>
                </div>

                <div class="col-2">
                  <div class="form-group">
                    <label for="do_no">Do No<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="do_no" name="do_no" placeholder="Enter Do number">

                  </div>
                </div>

                <div class="col-2">
                  <div class="form-group">
                    <label for="mr_no">MR No<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="mr_no" name="mr_no" placeholder="Enter issue number">
                  </div>
                </div>

                <div class="col-3">
                  <div class="form-group">
                    <label for="date">MR Date<span style="color: red">*</span> </label> 
                    <input type="date" name="date" required class="form-control" id="date" placeholder="">
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

                <div class="col-8 text-end">
                  <button  type="button"  class="btn float-right text-white useScanner" style="background-color: rgb(121, 77, 163);">
                    <i class="fas fa-barcode mx-1" style="color: #ffffff;"></i>
                    Use Scanner
                  </button>

                  <div class="d-flex align-items-center" >
                    <span class="form-control" id="text_scan" style="border: none; box-shadow: none; display: none; color: #dc3545; font-weight: 500; font-size: 13px">
                      {{-- ⚠️ Please enter Supplier and Voucher No before scanning. --}}
                    </span>
                    <input type="text" id="scanner" name="scanner" placeholder="scan..." tabindex="1" autofocus class="form-control mr-3" style="display: none; " >
                  </div>
                </div>
                
              </div>
      
              <!-- Add New Card -->
              <div class="moreCols">
                <div class="row d-flex justify-content-around deleteRow">
                  <div class="my-auto pl-4 pb-3 text-center">
                    <div class="form-group ">
                      <input type="checkbox" class="form-check-input" id="">
                    </div>
                  </div>

                  <div class="col-1 justify-content-center align-items-center">
                    <div class="form-group">
                          <img src="/storage/img/code/no-img.jpg" id="img_{{$i}}" 
                          class="isImg"
                          alt="code" height="100" 
                          width="100%" 
                          style="object-fit: contain" >
                    </div>
                  </div>
                  
                  <div class="col-2">
                      <div class="form-group">
                          <label for="shelfnum">ShelfNumber <span style="color: red">*</span></label>
                            <div>
                              <select id='shelfnum_{{$i}}' required name="shelfnum_{{$i}}" class="form-control">
                                  <option value="" disabled selected>Choose Shelf Number</option>
                                 @foreach ($shelfnums as $shelfnum)
                                    <option value="{{ $shelfnum->id }}" >{{ $shelfnum->name }} - {{ $shelfnum->shelf_name }} - {{ $shelfnum->warehouse_name }}</option>
                                 @endforeach
                              </select>
                            </div>
                        </div>
                    </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="code">Code<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <div>
                          <select id='code_{{$i}}' required name="code_{{$i}}" class="form-control getCode">
                            <option value="" disabled selected>Choose Code</option>
                              
                          </select>
                      </div>
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="brand">Brand<span style="color: red">*</span> </label> 
                      <div> 
                          <select id='brand_{{$i}}' required name="brand_{{$i}}" class=" form-control getBrand ">
                            <option value="" disabled selected>Choose Brand</option>
                            
                          </select>
                      </div>
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <div>
                          <select id='commodity_{{$i}}' required name="commodity_{{$i}}" class=" form-control getVr">
                            <option value="" disabled selected>Choose Commodity</option>
                            
                          </select>
                      </div>
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="commodity">Voucher_No<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <div>
                          <select id='vr_no_{{$i}}' required name="vr_no_{{$i}}" class=" form-control getQty">
                            <option value="" disabled selected>Choose Voucher</option>
                            
                          </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-1">
                    <div class="form-group">
                      <label for="job_{{$i}}">Job No</label>
                      <input type="text" class="form-control" id="job_{{$i}}" name="job_{{$i}}" placeholder="">
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="qty_{{$i}}" class="labelQty">__Qty</label> 
                      <input type="number" class="form-control isQty" required id="qty_{{$i}}" name="qty_{{$i}}" placeholder="" step=".01" min=0.01 oninput="validity.valid||(value='');">
                      
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="usage">Usage </label> 
                      <p id="usage_{{$i}}" 
                          name="usage_{{$i}}" 
                          style="color: rgb(149, 155, 155)"
                          class="isUsage"
                          >Detailed Description
                        </p>
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="remark">Remark</label>
                      <input type="text" class="form-control" id="remark_{{$i}}" name="remark_{{$i}}" placeholder="">
                    </div>
                  </div>
      
                </div>
              </div>
              <!-- End New Card -->
        </div>
        <!-- /.card-body -->
      </div>

        <div class="card-footer ">
          <div class="d-flex justify-content-around">
            <a type="" href="{{route('issues.index')}}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>

      </form>
</div>
  <!-- /.container-fluid -->

@endsection
@section('scripts')

<script>
  $( "#department_id" ).ready(function() {
      $("#department_id").select2();
  });
 
  $( "#shelfnum_{{$i}}" ).ready(function() {
      $("#shelfnum_{{$i}}").select2();
  });
  
  $( "#customer" ).ready(function() {
      $("#customer").select2();
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
  
  $( "#vr_no_{{$i}}" ).ready(function() {
      $("#vr_no_{{$i}}").select2();
  });

</script>

<script>
  // moreCols
   $( "#newColumn" ).click(function() {
       $( `#shelfnum_${i}` ).ready(function() {
          $(`#shelfnum_${i}`).select2();
      });
       $( `#code_${i}` ).ready(function() {
          $(`#code_${i}`).select2();
      });
    ++i;
      var moreCols = `
                <div class="row d-flex justify-content-around deleteRow">
                  <div class="my-auto pl-4 text-center">
                    <div class="form-group ">
                      <input type="checkbox" class="form-check-input" id="">
                    </div>
                  </div>

                  <div class="col-1 justify-content-center align-items-center">
                    <div class="form-group">
                          <img src="/storage/img/code/no-img.jpg" id="img_${i}" 
                          class="isImg"
                          alt="code" height="100" 
                          width="100%" 
                          style="object-fit: contain" >
                    </div>
                  </div>
                  
                <div class="col-2">
                    <div class="form-group">
                      <label for="shelfnum">ShelfNumber <span style="color: red">*</span></label>
                        <div>
                          <select id='shelfnum_${i}' required name="shelfnum_${i}" class="form-control">
                              <option value="" disabled selected>Choose Shelf Number</option>
                              @foreach ($shelfnums as $shelfnum)
                                 <option value="{{ $shelfnum->id }}" >{{ $shelfnum->name }} - {{ $shelfnum->shelf_name }} - {{ $shelfnum->warehouse_name }}</option>
                              @endforeach
                          </select>
                        </div>
                    </div>
                </div>
                
                  <div class="col-1">
                    <div class="form-group">
                      <label for="code">Code<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <div>
                          <select id='code_${i}' required name="code_${i}" class="form-control getCode">
                            <option value="" disabled selected>Choose Code</option>
                             
                          </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="brand">Brand <span style="color: red">*</span> </label> 
                      <div>
                          <select id='brand_${i}' required name="brand_${i}" class=" form-control getBrand">
                            <option value="" disabled selected>Choose Brand</option>
                            
                          </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                      <div>
                          <select id='commodity_${i}' required name="commodity_${i}" class=" form-control getVr">
                            <option value="" disabled selected>Choose Commodity</option>
                            
                          </select>
                      </div>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="vr_no">Voucher_No<span style="color: red">*</span> </label> 
                      <div>
                          <select id='vr_no_${i}' required name="vr_no_${i}" class=" form-control getQty">
                            <option value="" disabled selected>Choose Voucher</option>
                            
                          </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-1">
                    <div class="form-group">
                      <label for='job_${i}'>Job No</label>
                      <input type="text" class="form-control" id='job_${i}' name='job_${i}' placeholder="">
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="qty_${i}" class="labelQty">__Qty </label> 
                      <input type="number" class="form-control isQty" required id="qty_${i}" name="qty_${i}" step=".01" min=0.01 oninput="validity.valid||(value='');" placeholder="">
                     
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="usage">Usage</label> 
                      <p id="usage_${i}" 
                          name="usage_${i}" 
                          style="color: rgb(149, 155, 155)"
                          class="isUsage"
                          >Detailed Description
                        </p>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="remark">Remark</label> 
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
          var shelfnum_id = $(`#shelfnum_${row_id}`).val();

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
            var shelfnum_id = $(`#shelfnum_${row_id}`).val();
      
           
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

        
       $(`#shelfnum_${i}`).on('change', function() {
          var shelfnum_id = this.value;
           var row_id = this.id.split('_')[1];
          $.ajax({
              url: "{{ route('transfers.getCode') }}",
              type: "GET",
              data: {
                  "shelfnum_id": shelfnum_id,
              },
              cache: false,
              success: function(result) {
                
                  if (result) {
                      $(`#code_${row_id}`).empty();
                      $(`#code_${row_id}`).append(
                              '<option value="" >Choose Code</option>'
                          );
                      $(`#brand_${row_id}`).empty();
                      $(`#brand_${row_id}`).append(
                              '<option value="" >Choose Brand</option>'
                          );
                      $(`#commodity_${row_id}`).empty();
                      $(`#commodity_${row_id}`).append(
                              '<option value="" >Choose Commodity</option>'
                          );
                      
                      $(`#vr_no_${row_id}`).empty();
                      $(`#vr_no_${row_id}`).append(
                              '<option value="" >Choose Voucher</option>'
                          );
                      $(`#usage_${row_id}`).html("Detailed Description");
                      $(`#img_${row_id}`).attr("src", "/storage/img/code/no-img.jpg");

                      $(`label[for=qty_${row_id}]`).text(`__Qty`);
                      $(`#qty_${row_id}`).prop({
                                            "max" : ` `,
                                            "value" : 0,
                                        });


                      $.each(result.codes, function(key, value) {
                              $(`#code_${row_id}`).append(
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
        });

          //accessing commodity lists under code and brand
        $('.getVr').on('change', function() {
            var commodity_id = this.value;
            var row_id = this.id.split('_')[1];

            var brand_id = $(`#brand_${row_id}`).val();
            var code_name = $(`#code_${row_id}`).val();
            var shelfnum_id = $(`#shelfnum_${row_id}`).val();

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

                                $(`#qty_${row_id}`).prop({
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

  });

    //from to shelfnumber id to codes, brands, commodities
    $(document).on('change', '[id^="shelfnum_"]', function() {
      var shelfnum_id = $(this).val();
      var index = $(this).attr('id').split('_')[1];
      $.ajax({
          url: "{{ route('transfers.getCode') }}",
          type: "GET",
          data: {
              "shelfnum_id": shelfnum_id,
          },
          cache: false,
          success: function(result) {
              if (result) {

                // If code/brand/commodity are already filled (scanner mode)
                var code_name = $('#code_' + index).val();
                var brand_id = $('#brand_' + index).val();
                var commodity_id = $('#commodity_' + index).val();

                if (code_name && brand_id && commodity_id) {
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
                        success: function(vrResult) {
                          console.log(vrResult);
                          
                          if (vrResult && vrResult.vr_nos) {
                              $('#vr_no_' + index).empty().append('<option value="">Choose Voucher</option>');
                              $.each(vrResult.vr_nos, function(key, value) {
                                let text = value.transfer_no
                                    ? `${value.voucher_no} (${value.transfer_no})`
                                    : `${value.voucher_no}`;
                                $("#vr_no_" + index).append(
                                    `<option value="${value.id}">${text}</option>`
                                );
                              });

                              $(`#vr_no_${index}`).off('change').on('change', function() {
                                  var vr_no = this.value;
                                  $.each(vrResult.vr_nos, function(key, value) {
                                      if (value.id == vr_no) {
                                          $(`#qty_${index}`).prop({
                                              "max": value.balance_qty,
                                              "value": 0,
                                          });
                                          $(`label[for='qty_${index}']`).text(`${value.balance_qty} Qty`);
                                          $(`#usage_${index}`).text(`${value.usage}`);
                                          $(`#img_${index}`).attr("src", `{{ URL::asset('${value.image}') }}`);
                                      }
                                  });
                              });
                          }
                        }
                    });
                }else{
                    $('#code_' + index).empty().append('<option value="">Choose Code</option>');
                    $('#brand_' + index).empty().append('<option value="">Choose Brand</option>');
                    $('#commodity_' + index).empty().append('<option value="">Choose Commodity</option>');
                    $('#vr_no_' + index).empty().append('<option value="">Choose Voucher</option>');
                    $("#usage_" + index).html("Detailed Description");
                    $("#img_" + index).attr("src", "/storage/img/code/no-img.jpg");

                    $(`#qty_${index}`).prop({ "max": ``, "value": 0 });
                    $(`.labelQty`).text(`__Qty`);

                    $.each(result.codes, function(key, value) {
                        $("#code_" + index).append('<option value="' + value.name + '">' + value.name + '</option>');
                    });
                }

              } else {
                  $(".getCode, .getBrand, .getVr, .getQty").empty();
              }
          }
      });
    });

</script>

<script>
  var i = '<?php echo $i ?>'
  // accessing brand/Commodity under choosen code
  $('#code_{{$i}}').on('change', function() {
      var code_name = this.value;
      var shelfnum_id = $("#shelfnum_{{$i}}").val();

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
                  $("#brand_{{$i}}").empty();
                  $("#brand_{{$i}}").append(
                            `<option value="" >Choose Brand</option>`
                    );
                  $.each(result.brands, function(key, value) {
                    $("#brand_{{$i}}").append(
                            `<option value="${value.id}" >${value.name}</option>`
                    )
                  });

              } else {
                  $("#brand_{{$i}}").empty();
              }
          }
      });
  });

  //accessing commodity lists under code and brand
  $('#brand_{{$i}}').on('change', function() {
      var brand_id = this.value;
      var commodity_id = $("#commodity_{{$i}}").val();
      var code_name = $("#code_{{$i}}").val();
      var shelfnum_id = $("#shelfnum_{{$i}}").val();

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
                  $("#commodity_{{$i}}").empty();
                  $("#commodity_{{$i}}").append(
                        `<option value="" >Choose Commodity</option>`
                    )
                  $.each(result.commodities, function(key, value) {
                    $("#commodity_{{$i}}").append(
                        `<option value="${value.id}" >${value.name}</option>`
                    )
                  });
              
              } else {
                  $("#commodity_{{$i}}").empty();
              }
          }
      });
  });

  //accessing commodity lists under code and brand
  $('#commodity_{{$i}}').on('change', function() {
      var commodity_id = this.value;
      var brand_id = $("#brand_{{$i}}").val();
      var code_name = $("#code_{{$i}}").val();
      var shelfnum_id = $("#shelfnum_{{$i}}").val();

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
                  $("#vr_no_{{$i}}").empty();
                  $("#vr_no_{{$i}}").append(
                        `<option value="" >Choose Voucher</option>`
                    )
                  $.each(result.vr_nos, function(key, value) {
                    if (value.transfer_no == null) {
                      $("#vr_no_{{$i}}").append(
                        `<option value="${value.id}" >${value.voucher_no}</option>`
                      )
                    }else{
                        $("#vr_no_{{$i}}").append(
                          `<option value="${value.id}" >${value.voucher_no} (${value.transfer_no})</option>`
                      )};
                        
                    })

                    //qty under vr no
                    $(`#vr_no_{{$i}}`).on('change', function() {
                      var vr_no = this.value;
                      
                      $.each(result.vr_nos, function(key, value) {
                        if (value.id == vr_no) {

                          $("#qty_{{$i}}").prop({
                                      "max" : `${value.balance_qty}`,
                                      "value" : 0,
                                  });
                          $("label[for='qty_{{$i}}']").text(`${value.balance_qty} Qty`);
                          $(`#usage_{{$i}}`).text(`${value.usage}`);
                          $(`#img_{{$i}}`).attr("src", `{{ URL::asset('${value.image}') }}`);
                        }
                        
                        });
                    });
              
              } else {
                  $("#vr_no_{{$i}}").empty();
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
                  if ($('#scanner').val() != null && $('#scanner').val() != '') {
                    ++i;
                        $.ajax({
                            url: "{{ route('scanners.storeMR') }}",
                            type: "GET",
                            data: {
                              "barcode": $('#scanner').val(),
                            },
                            cache: false,
                            success: function (result) {
                              if (result !== null) {
            
                                $('#scanner').val('');
                                var img = result['code'].image;
                                let shelfOptions = '<option value="" disabled selected>Choose Shelf Number</option>';

                                result.shelf_numbers.forEach(function(shelfnum) {
                                    shelfOptions += `
                                        <option value="${shelfnum.id}">
                                            ${shelfnum.name} - ${shelfnum.shelf_name} - ${shelfnum.warehouse_name}
                                        </option>
                                    `;
                                });

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
                                    
                                    <div class="col-2">
                                        <div class="form-group">
                                          <label for="shelfnum">ShelfNumber <span style="color: red">*</span></label>
                                            <div>
                                              <select id='shelfnum_${i}' required name="shelfnum_${i}" class="form-control">
                                                   ${shelfOptions}
                                              </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="code">Code<span style="color: red">*</span> </label> 
                                          <div>
                                            <select id='code_${i}' required name="code_${i}" class="form-control">
                                              <option value="${result['code'].name}" >${result['code'].name}</option>
                                            </select>
                                          </div>
                                      </div>
                                    </div>
                
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="brand">Brand <span style="color: red">*</span> </label> 
                                        <div> 
                                            <select id='brand_${i}' required name="brand_${i}" class=" form-control getBrand">
                                              <option value="${result['code'].brand_id}" >${result['code'].brand_name}</option>
                                            </select>
                                        </div>
                                      </div>
                                    </div>
                                    
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                                        <div>
                                            <select id='commodity_${i}' required name="commodity_${i}" class=" form-control getVr">
                                              <option value="${result['code'].commodity_id}" >${result['code'].commodity_name}</option>
                                            </select>
                                        </div>
                                      </div>
                                    </div>
                
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="vr_no">Voucher_No<span style="color: red">*</span> </label> 
                                        <div>
                                            <select id='vr_no_${i}' required name="vr_no_${i}" class=" form-control getQty">
                                               <option value="" >Choose Voucher</option>
                                            </select>
                                        </div>
                                      </div>
                                    </div>
                                    
                                    <div class="col-1">
                                        <div class="form-group">
                                          <label for='job_${i}'>Job No</label>
                                          <input type="text" class="form-control" id='job_${i}' name='job_${i}' placeholder="">
                                        </div>
                                    </div>
                
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="qty_${i}"> Qty </label> 
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
                                        <label for="usage">Usage</label> 
                                        <p id="usage_${i}" 
                                            name="usage_${i}" 
                                            style="color: rgb(149, 155, 155)"
                                            class="isUsage"
                                            >
                                          </p>
                                      </div>
                                    </div>
                
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="remark">Remark</label> 
                                        <input type="text" class="form-control" id="remark_${i}" name="remark_${i}" placeholder="">
                                      </div>
                                    </div>
                
                                    
                                  </div>
                                  `
                                )
                              }
                            }
                        });
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
    $('#text_scan').css('display','block');
    $('#scanner').css('display','block');
    $('#scanner').focus();
  })
</script>

@endsection