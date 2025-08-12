@extends('layouts.master')
@section('title', 'Edit Issue')

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
        <form method="POST" id="myForm" action="{{route('issues.update')}}">
          @csrf
          <div class="card-body">

              <!-- mainform -->
              
              <div class="row">
                 <div class="col-3">
                  <label for="customer_id">Customer<span style="color: red">*</span> </label> 
                  <!-- Dropdown --> 
                  <div>
                    <select id='customer_id' required name="customer_id" class="form-control">
                      <option value="" disabled selected>Choose Customer</option>
                        @foreach ($customers as $customer)
                          @if ($customer->id == $edit_customer->id)
                              <option selected value="{{$customer->id}}">{{$customer->name}}</option>
                          @else
                              <option value="{{$customer->id}}">{{$customer->name}}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-3">
                  <label for="department_id">Department<span style="color: red">*</span> </label> 
                  <!-- Dropdown --> 
                  <div>
                    <select id='department_id' required name="department_id" class="form-control">
                      <option value="" disabled selected>Choose Department</option>
                        @foreach ($departments as $department)
                          @if ($department->id == $edit_department->id)
                              <option selected value="{{$department->id}}">{{$department->name}}</option>
                          @else
                              <option value="{{$department->id}}">{{$department->name}}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-3">
                  <div class="form-group">
                    <label for="mr_no">MR No<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="mr_no" name="mr_no" value="{{$issue->mr_no}}">
                  </div>
                </div>
                <div class="col-3">
                  <div class="form-group">
                    <label for="date">Date<span style="color: red">*</span> </label> 
                    <input type="date" name="date" required class="form-control" id="date" placeholder="" value="{{$issue->issue_date}}">
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


              {{-- hidden one issue for check --}}
              <input type="hidden" value="{{$issue->id}}" name="old_issue" >
      
              <!-- Add New Card -->
              <div class="moreCols">
               
                @foreach ($choosen_issues as $choosen_issue)
                    @php
                      ++$i;
                        $choosen_transfer = App\Models\Transfer::find(optional($choosen_issue)->transfer_id);
                        
                         $edit_shelfnum = App\Models\ShelfNumber::where('shelf_numbers.id', $choosen_issue->shelf_number_id)
                                            ->join('shelves', 'shelves.id', '=', 'shelf_numbers.shelf_id')
                                            ->join('warehouses', 'warehouses.id', '=', 'shelf_numbers.warehouse_id')
                                            ->first([
                                                'shelf_numbers.id', 'shelf_numbers.name', 'shelves.name as shelf_name', 'warehouses.name as warehouse_name'
                                            ]);
                    @endphp
                    {{-- hidden all products --}}
                    <input type="hidden" value="{{$choosen_issue->issue_id}}" name="issue_{{$i}}" >
                    <div class="row d-flex justify-content-around deleteRow">
                        @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
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
                  
                      @if ($choosen_issue->image != null )
                        <div class="form-group">
                            <img src="{{URL::asset($choosen_issue->image)}}" id="img_{{$i}}" 
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
                    
                     <div class="col-2">
                          <div class="form-group">
                              <label for="shelfnum_id">Shelf Number<span style="color: red">*</span> </label> 
                              <div>
                                  @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
                                  
                                    <select id='shelfnum_{{$i}}' required disabled name="shelfnum_{{$i}}" class="form-control">
                                      <option value="{{ $edit_shelfnum->id }}">
                                           {{ $edit_shelfnum->name }} - {{ $edit_shelfnum->shelf_name }} - {{ $edit_shelfnum->warehouse_name }} </option>
                                    </select>
                                  @else
                                  <select id='shelfnum_{{$i}}' required name="shelfnum_{{$i}}" class="form-control">
                                      <option value="{{ $edit_shelfnum->id }}">
                                           {{ $edit_shelfnum->name }} - {{ $edit_shelfnum->shelf_name }} - {{ $edit_shelfnum->warehouse_name }} </option>
                                    </select>
                                @endif
                               
                            </div>
                          </div>
                        </div>

                    <div class="col-1">
                        <div class="form-group">
                        <label for="code">Code<span style="color: red">*</span> </label> 
                        <!-- Dropdown --> 
                        @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
                            <select id='code_{{$i}}' required disabled name="code_{{$i}}" class="form-control getCode">
                              
                                <option value="{{$choosen_issue->code_name}}">{{$choosen_issue->code_name}}</option>
                            </select>
                          @else
                          <select id='code_{{$i}}' required name="code_{{$i}}" class="form-control getCode">
                                <option value="{{$choosen_issue->code_name}}">{{$choosen_issue->code_name}}</option>
                          </select>
                        @endif

                        </div>
                    </div>
        
                    <div class="col-1">
                        <div class="form-group">
                        <label for="brand">Brand<span style="color: red">*</span> </label> 
                     
                        <!-- Dropdown --> 
                        @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
                          <select id='brand_{{$i}}' disabled required name="brand_{{$i}}" class=" form-control getBrand ">
                            <option selected value="{{$choosen_issue->brand_id}}">{{$choosen_issue->brand_name}}</option>
                          </select>
                        @else
                          <select id='brand_{{$i}}' required name="brand_{{$i}}" class=" form-control getBrand ">
                            <option selected value="{{$choosen_issue->brand_id}}">{{$choosen_issue->brand_name}}</option>
                          </select>
                        @endif
                        </div>
                    </div>
        
                    <div class="col-1">
                        <div class="form-group">
                        <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                        <!-- Dropdown --> 
                        @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
                          <select id='commodity_{{$i}}' disabled required name="commodity_{{$i}}" class=" form-control getVr">
                            <option selected value="{{$choosen_issue->commodity_id}}">{{$choosen_issue->commodity_name}}</option>
                          </select>
                        @else
                          <select id='commodity_{{$i}}' required name="commodity_{{$i}}" class=" form-control getVr">
                            <option selected value="{{$choosen_issue->commodity_id}}">{{$choosen_issue->commodity_name}}</option>
                          </select>
                        @endif
                        </div>
                    </div>
        
                    <div class="col-1">
                        <div class="form-group">
                        <label for="voucher">Voucher_No<span style="color: red">*</span> </label> 
                        <!-- Dropdown --> 
                        @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
                            <select id='vr_no_{{$i}}' disabled required name="vr_no_{{$i}}" class=" form-control getQty">
                              <option selected value="{{$choosen_issue->product_id}}">
                                {{$choosen_issue->voucher_no}} {{$choosen_transfer != null ? $choosen_transfer->transfer_no : ''}}</option>
                            </select>
                          @else
                            <select id='vr_no_{{$i}}' required name="vr_no_{{$i}}" class=" form-control getQty">
                              <option selected value="{{$choosen_issue->product_id}}">
                                {{$choosen_issue->voucher_no}} 
                                {{$choosen_transfer != null ? $choosen_transfer->transfer_no : ''}}
                            </option>
                            </select>
                          @endif
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group">
                        <label for='job_{{$i}}'>Job No</label> 
                        @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
                            <input type="text" class="form-control" disabled id='job_{{$i}}' name='job_{{$i}}' placeholder="" value="{{$choosen_issue->job_no}}">
                        @else
                            <input type="text" class="form-control" id='job_{{$i}}' name='job_{{$i}}' placeholder="" value="{{$choosen_issue->job_no}}">
                        @endif
                        </div>
                    </div>

                    <div class="col-1">
                        <div class="form-group">
                          <label for="qty_{{$i}}" class="labelQty"> {{$choosen_issue->balance_qty }} Qty </label> 
                          
                          @if ($choosen_issue->mrr_qty != null && $choosen_issue->mrr_qty != 0)
                            <input type="number" class="form-control isQty" 
                                    required id="qty_{{$i}}" 
                                    name="qty_{{$i}}"
                                    step=".01" min={{$choosen_issue->mrr_qty}} max={{$choosen_issue->balance_qty + $choosen_issue->mr_qty  }}
                                    oninput="validity.valid||(value='');"
                                    value="{{$choosen_issue->mr_qty}}"
                            > 
                          @else
                              <input type="number" class="form-control isQty" 
                                      required id="qty_{{$i}}" 
                                      name="qty_{{$i}}"
                                      step=".01" min=0.01 max={{$choosen_issue->balance_qty + $choosen_issue->mr_qty }}
                                      oninput="validity.valid||(value='');"
                                      value="{{$choosen_issue->mr_qty}}"
                              > 
                          @endif
                        </div>
                    </div>
        
                    <div class="col-1">
                        <div class="form-group">
                        <label for="usage">Usage</label> 
                        <p id="usage_{{$i}}" 
                            name="usage_{{$i}}" 
                            style="color: rgb(149, 155, 155)"
                            class="isUsage"
                            >{{$choosen_issue->usage }}
                        </p>
                        </div>
                    </div>

                    <div class="col-1">
                        <div class="form-group">
                        <label for="remark">Remark </label> 
                        <input type="text" class="form-control" id="remark_{{$i}}" name="remark_{{$i}}" value="{{$choosen_issue->remarks}}">
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

            <a type="" href="{{route('issues.index')}}" class="btn btn-secondary">Cancel</a>
            <button type="button" class="btn btn-primary changeBtn" onclick="changeButtonType()">Submit</button>
          </div>
        </div>

      </form>
</div>
  <!-- /.container-fluid -->

@endsection
@section('scripts')

<script>

//   $( "#shelfnum_{{$i}}" ).ready(function() {
//       $("#shelfnum_{{$i}}").select2();
//   });
  $( "#department_id" ).ready(function() {
      $("#department_id").select2();
  });
  $( "#customer_id" ).ready(function() {
      $("#customer_id").select2();
  });
 
//   $( "#code_{{$i}}" ).ready(function() {
//       $("#code_{{$i}}").select2();
//   });
//   $( "#brand_{{$i}}" ).ready(function() {
//       $("#brand_{{$i}}").select2();
//   });
//   $( "#commodity_{{$i}}" ).ready(function() {
//       $("#commodity_{{$i}}").select2();
//   });
//   $( "#vr_no_{{$i}}" ).ready(function() {
//       $("#vr_no_{{$i}}").select2();
//   });

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
    $('#shelfnum_{{$i}}').on('change', function() {
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
                  $('#code_{{$i}}').empty();
                  $('#code_{{$i}}').append(
                          '<option value="" >Choose Code</option>'
                      );
                  $('#brand_{{$i}}').empty();
                  $('#brand_{{$i}}').append(
                          '<option value="" >Choose Brand</option>'
                      );
                  $('#commodity_{{$i}}').empty();
                  $('#commodity_{{$i}}').append(
                          '<option value="" >Choose Commodity</option>'
                      );
                  $('#vr_no_{{$i}}').empty();
                  $('#vr_no_{{$i}}').append(
                    '<option value="" >Choose Voucher</option>'
                    );
                  $("#usage_{{$i}}").html("Detailed Description");
                  $("#img_{{$i}}").attr("src", "/storage/img/code/no-img.jpg");

                  $(`.labelQty`).text(`__Qty`);
                  $(`#qty_{{$i}}`).prop({
                                      "max" : ` `,
                                      "value" : 0,
                                   });

                  $.each(result.codes, function(key, value) {
                          $("#code_{{$i}}").append(
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
    var shelfnum_id = $(`#shelfnum_{row_id}`).val();

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
                            `<option value="${value.id}" >${value.voucher_no} (${value.transfer_no})</option>`
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
    var return_exist = '<?php echo $choosen_issues ?>'

    $.each(JSON.parse(return_exist), function(key, value) {

        $(".isQty").hover( function() {
            var row_id = this.id.split('_')[1];
            var min = $(`#qty_${row_id}`).attr('min');
            var max = $(`#qty_${row_id}`).attr('max');
            $(`#qty_${row_id}`).popover({
              content: `Please Ensure that the value is between (${min}) and (${max}).` 
            })
         });
         
       if ( value.mrr_qty != null ) {
        if (value.mrr_qty != 0) {
            
            $("#mr_no").attr("disabled",true);
            $("#department_id").attr("disabled",true);
            $("#customer_id").attr("disabled",true);
            }
        }
    });

</script>

<script>

  let isScannerInput = '';
    
  $('#scanner').keyup(function() {
      var value = $('#scanner').val();
      if(value.length == 10) {
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
                                    
                                    <div class="col-2">
                                        <div class="form-group">
                                          <label for="shelfnum">ShelfNumber <span style="color: red">*</span></label>
                                            <div>
                                              <select id='shelfnum_${i}' required name="shelfnum_${i}" class="form-control">
                                                <option value="${result['product'].shelf_number_id}" > ${result['product'].shelfnum_name} </option>
                                              </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="code">Code<span style="color: red">*</span> </label> 
                                          <div>
                                            <select id='code_${i}' required name="code_${i}" class="form-control">
                                              <option value="${result['product'].name}" >${result['product'].name}</option>
                                            </select>
                                          </div>
                                      </div>
                                    </div>
                
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="brand">Brand <span style="color: red">*</span> </label> 
                                        <div> 
                                            <select id='brand_${i}' required name="brand_${i}" class=" form-control getBrand">
                                              <option value="${result['product'].brand_name}" >${result['product'].brand_name}</option>
                                            </select>
                                        </div>
                                      </div>
                                    </div>
                                    
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="commodity">Commodity<span style="color: red">*</span> </label> 
                                        <div>
                                            <select id='commodity_${i}' required name="commodity_${i}" class=" form-control getVr">
                                              <option value="${result['product'].commodity_name}" >${result['product'].commodity_name}</option>
                                            </select>
                                        </div>
                                      </div>
                                    </div>
                
                                    <div class="col-1">
                                      <div class="form-group">
                                        <label for="vr_no">Voucher_No<span style="color: red">*</span> </label> 
                                        <div>
                                            <select id='vr_no_${i}' required name="vr_no_${i}" class=" form-control getQty">
                
                                              ${result['transfer'] !== null ?
                                              
                                                `<option value="${result['product'].id}" >${result['product'].voucher_no} || ${result['transfer'].transfer_no}</option>`
                                              :
                                                `<option value="${result['product'].id}" >${result['product'].voucher_no}</option>`
                                              
                                             }
                                              
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
                                        <label for="qty_${i}">${result['product'].balance_qty} Qty </label> 
                                        <input type="number" class="form-control"
                                         required id="qty_${i}" 
                                         name="qty_${i}" step=".01" 
                                         min=0.01 oninput="validity.valid||(value='');" 
                                         max=${result['product'].balance_qty}
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
                                            >${result['product'].usage}
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
    $('#scanner').css('display','block');
    $('#scanner').focus();
    $('.submit_barcode').css('display','block');
  })
</script>

<script>
    function changeButtonType() {
        var button = document.querySelector('.changeBtn');
        // start
         var codetxt=$(`#code_${i}`).val();
         var brandtxt=$(`#brand_${i}`).val();
         var commoditytxt=$(`#commodity_${i}`).val();
         var vr_notxt=$(`#vr_no_${i}`).val();
         
         var qtytxt=$(`#qty_${i}`).val();
         
        if (!codetxt && codetxt== ''){
            alert('please enter code');
        }else if(!brandtxt && brandtxt== ''){
          alert('please enter bandname');  
        }else if(!commoditytxt && commoditytxt== ''){
          alert('please enter commodity');
        }else if(!vr_notxt && vr_notxt== ''){
          alert('please enter Vr No');  
        }else if(qtytxt == 0 ){
          alert('please enter qty');  
        }else{
             // end
           button.type = 'submit';
        // Optionally, trigger the form submission after changing the button type
          var form = document.getElementById('myForm');
            form.submit();
        }
       
    }

</script>

@endsection