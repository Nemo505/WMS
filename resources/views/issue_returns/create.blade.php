@extends('layouts.master')
@section('title', 'New Issue Return')

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
        <form method="POST" id="myForm" action="{{route('issue_returns.store')}}">
          @csrf
          <div class="card-body">

              <!-- mainform -->
              <div class="row">
                <div class="col-6">
                  <div class="form-group ">
                    <label for="warehouse_id">Warehouse <span style="color: red">*</span> </label> 
                    <div>
                      <select id='warehouse_id' required name="warehouse_id" class="form-control getShelfNum">
                          <option value="" disabled selected>Choose Warehouse</option>
                          @foreach ($warehouses as $warehouse)
                              <option value="{{ $warehouse->id }}">
                                  {{ $warehouse->name }}</option>
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
                            <option value="" disabled selected>Choose Warehouse First</option>
                            {{-- AjaxData --}}
    
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                
              </div>

              <div class="row">

                <div class="col-6">
                  <div class="form-group">
                    <label for="mrr_no">MRR No<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="mrr_no" name="mrr_no" placeholder="Enter issue number">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="date">MRR Date<span style="color: red">*</span> </label> 
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
                      ⚠️ Please enter Warehouse and Shelf No before scanning.
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
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="code">Code<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='code_{{$i}}' required name="code_{{$i}}" class="form-control getCode">
                        <option value="" disabled selected>Choose Code</option>
                          
                      </select>
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="brand">Brand<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='brand_{{$i}}' required name="brand_{{$i}}" class=" form-control getBrand ">
                        <option value="" disabled selected>Choose Brand</option>
                        
                      </select>
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='commodity_{{$i}}' required name="commodity_{{$i}}" class=" form-control getVr">
                        <option value="" disabled selected>Choose Commodity</option>
                        
                      </select>
                    </div>
                  </div>
      
                  <div class="col-2">
                    <div class="form-group">
                      <label for="commodity">MR No<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='vr_no_{{$i}}' required name="vr_no_{{$i}}" class=" form-control getQty">
                        <option value="" disabled selected>Choose MR No</option>
                        
                      </select>
                    </div>
                  </div>
      
                  <div class="col-1">
                    <div class="form-group">
                      <label for="qty_{{$i}}" class="labelQty">__Qty</label> 
                      <input type="number" class="form-control isQty" required id="qty_{{$i}}" name="qty_{{$i}}" placeholder="" step=".01" min=0.01 oninput="validity.valid||(value='');">
                      
                    </div>
                  </div>

                  <div class="col-2">
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
      
                  <div class="col-2">
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

            <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
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
      // Initialize select2
      $("#shelfnum_id").select2();
  });
 
  $( "#code_{{$i}}" ).ready(function() {
      // Initialize select2
      $("#code_{{$i}}").select2();
  });
  $( "#brand_{{$i}}" ).ready(function() {
      // Initialize select2
      $("#brand_{{$i}}").select2();
  });
  $( "#commodity_{{$i}}" ).ready(function() {
      // Initialize select2
      $("#commodity_{{$i}}").select2();
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

                  <div class="col-1 justify-content-center align-items-center">
                    <div class="form-group">
                          <img src="/storage/img/code/no-img.jpg" id="img_${i}" 
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
                        <option value="" disabled selected>Choose Code</option>
                         
                      </select>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="brand">Brand <span style="color: red">*</span> </label> 
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

                  <div class="col-2">
                    <div class="form-group">
                      <label for="vr_no">MR No<span style="color: red">*</span> </label> 
                      <!-- Dropdown --> 
                      <select id='vr_no_${i}' required name="vr_no_${i}" class=" form-control getQty">
                        <option value="" disabled selected>Choose MR No</option>
                        
                      </select>
                    </div>
                  </div>

                  <div class="col-1">
                    <div class="form-group">
                      <label for="qty_${i}" class="labelQty">__Qty </label>
                      <input type="number" class="form-control isQty" required id="qty_${i}" name="qty_${i}" step=".01" min=0.01 oninput="validity.valid||(value='');" placeholder="">
                     
                    </div>
                  </div>

                  <div class="col-2">
                    <div class="form-group">
                      <label for="usage">Usage </label> 
                      <p id="usage_${i}" 
                          name="usage_${i}" 
                          style="color: rgb(149, 155, 155)"
                          class="isUsage"
                          >Detailed Description
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
      
            if (commodity_id == '' ) {
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
            };
        });

        var shelfnum_id = $('#shelfnum_id').val();
        if (shelfnum_id !== undefined) {
          $.ajax({
              url: "{{ route('issue_returns.getCode') }}",
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
                              '<option value="" >Choose MR No</option>'
                          );
                      $(`#usage_${i}`).html("Detailed Description");
                      $(`#img_${i}`).attr("src", "/storage/img/code/no-img.jpg");

                      $($(`label[for=qty_${i}]`)).text(`__Qty`);
                      $(`#qty_${i}`).prop({
                                      "max" : ` `,
                                      "value" : 0,
                                   });
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

        //accessing issue lists under uvr
        $('.getVr').on('change', function() {
            var commodity_id = this.value;
            var row_id = this.id.split('_')[1];

            var brand_id = $(`#brand_${row_id}`).val();
            var code_name = $(`#code_${row_id}`).val();
            var shelfnum_id = $("#shelfnum_id").val();

            $.ajax({
                url: "{{ route('issue_returns.getVr') }}",
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
                              `<option value="" >Choose MR No</option>`
                          )
                        $.each(result.vr_nos, function(key, value) {
                          
                            $(`#vr_no_${row_id}`).append(
                              `<option value="${value.id}" >${value.mr_no}</option>`
                            )
                          })

                          //qty under vr no
                          $(`#vr_no_${row_id}`).on('change', function() {
                            var vr_no = this.value;
                            
                            $.each(result.vr_nos, function(key, value) {
                             
                              if (value.id == vr_no) {
                                var left_mr_qty = value.mr_qty - value.mrr_qty;
                                $(`#qty_${row_id}`).attr({
                                            "max" : `${left_mr_qty}`,
                                            "value" : 0,
                                        });
                                $(`label[for=qty_${row_id}]`).text(`${left_mr_qty} Qty`);
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
    $('#shelfnum_id').on('change', function() {
        var shelfnum_id = this.value;
        $.ajax({
            url: "{{ route('issue_returns.getCode') }}",
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
                    $(".labelQty").text(`__Qty`);
                    $(".isQty").prop({
                                        "max" : ` `,
                                        "value" : 0,
                                   });
                    $(".isUsage").html("Detailed Description");
                    $(".isImg").attr("src", "/storage/img/code/no-img.jpg");

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
  // accessing brand/Commodity under choosen code
  $('#code_{{$i}}').on('change', function() {
      var code_name = this.value;
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
      var shelfnum_id = $("#shelfnum_id").val();

      $.ajax({
          url: "{{ route('issue_returns.getVr') }}",
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
                        `<option value="" >Choose MR No</option>`
                    )
                    console.log(result.vr_nos);
                  $.each(result.vr_nos, function(key, value) {
                      $("#vr_no_{{$i}}").append(
                        `<option value="${value.id}" >${value.mr_no}</option>`
                      )
                        
                    })

                    //qty under vr no
                    $(`#vr_no_{{$i}}`).on('change', function() {
                      var vr_no = this.value;
                      
                      $.each(result.vr_nos, function(key, value) {
                          if (value.id == vr_no) {
                           
                            var left_mr_qty = value.mr_qty - value.mrr_qty;

                          $("#qty_{{$i}}").attr({
                                      "max" : `${left_mr_qty}`,
                                      "value" : 0,
                                  });
                          $("label[for='qty_{{$i}}']").text(`${left_mr_qty} Qty`);
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
              if ($('#shelfnum_id').val() != null && $('#shelfnum_id').val() != '') {
                if ($('#scanner').val() != null && $('#scanner').val() != '') {
                  ++i;
                      $.ajax({
                          url: "{{ route('scanners.storeMRR') }}",
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
                                        <option value="${result['product'].brand_id}" >${result['product'].brand_name}</option>
                                        
                                      </select>
                                    </div>
                                  </div>
              
                                  <div class="col-1">
                                    <div class="form-group">
                                      <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                                      <!-- Dropdown --> 
                                      <select id='commodity_${i}' required name="commodity_${i}" class=" form-control getVr">
                                        <option value="${result['product'].commodity_id}" >${result['product'].commodity_name}</option>
                                        
                                      </select>
                                    </div>
                                  </div>
              
                                  <div class="col-2">
                                    <div class="form-group">
                                      <label for="vr_no">MR No<span style="color: red">*</span> </label> 
                                      <!-- Dropdown --> 
                                      <select id='vr_no_${i}' required name="vr_no_${i}" class=" form-control getQty">
                                        <option value="" disabled selected>Choose MR No</option>
                                      </select>
                                    </div>
                                  </div>
              
                                  <div class="col-1">
                                    <div class="form-group">
                                      <label for="qty_${i}">__ Qty </label> 
                                      <input type="number" class="form-control"
                                       required id="qty_${i}" 
                                       name="qty_${i}" step=".01" 
                                       min=0.01 oninput="validity.valid||(value='');" 
                                       value=0
                                       placeholder="">
                                     
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
            
                              $.each(result['issues'], function(key, value) {
                                  $(`#vr_no_${i}`).append(
                                      `<option value="${value.id}">${value.mr_no} )</option>`
                                  );
                                });
                                $(`#vr_no_${i}`).on('change', function() {
                                      var vr_no = this.value;
                                      
                                      $.each(result['issues'], function(mr_key, mr_value) {
                                        
                                        if (mr_value.id == vr_no) {
                                          var left_mr_qty = mr_value.mr_qty - mr_value.mrr_qty;
                                          $(`#qty_${i}`).attr({
                                                      "max" : `${left_mr_qty}`,
                                                      "value" : 0,
                                                  });
                                          $(`label[for=qty_${i}]`).text(`${left_mr_qty} Qty`);
                                        }
                                        
                                        });
                                });
            
                            }
                          }
                      });
            
                }else{
                  $('#scanner').val('');
                }
              }else{
                $('#scanner').val('');
              } isScannerInput = value;
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