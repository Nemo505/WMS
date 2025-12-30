@extends('layouts.master')
@section('title', 'Customer List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route('customers.create')}}" class="btn btn-primary" >
      <i class="fa fa-solid fa-plus" style="color: #ffffff;"></i>
      Add New
    </a>
@endsection

@section('content')
 <!-- Main content -->
 
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
             {{-- form --}}
             @php
                $s_customers =  App\Models\customer::get();
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-between">
                <div class="col-6 d-flex justify-content-start">

                  <div class="col-5">
                    <div class="form-group">
                      <label for="customer">Name</label>

                      <select id='customer' name="customer" class=" form-control">
                        <option value="" disabled selected>Choose Name</option>
                        @foreach ($s_customers as $s_customer)
                          @if (isset($_REQUEST['customer']))
                              @if ($s_customer->id == $_REQUEST['customer'])
                                  <option value="{{ $s_customer->id }}" selected>{{ $s_customer->name }}</option>
                              @else
                                  <option value="{{ $s_customer->id }}">{{ $s_customer->name }}</option>
                              @endif
                          @else
                              <option value="{{ $s_customer->id }}">{{ $s_customer->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                  <div class="col-5">
                    <div class="form-group">
                      <label for="customer">Phone</label>

                      <select id='phone' name="phone" class=" form-control">
                        <option value="" disabled selected>Choose Phone</option>
                        @foreach ($s_customers as $s_customer)
                          @if (isset($_REQUEST['phone']))
                              @if ($s_customer->phone == $_REQUEST['phone'])
                                  <option value="{{ $s_customer->phone }}" selected>{{ $s_customer->phone }}</option>
                              @else
                                  <option value="{{ $s_customer->phone }}">{{ $s_customer->phone }}</option>
                              @endif
                          @else
                              <option value="{{ $s_customer->phone }}">{{ $s_customer->phone }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>

                </div>

                <div class="col-2 d-flex justify-content-end">
                    <div class="col-6">
                      <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" name="search">Search</button>
                      </div>
                    </div>
                    <div class="col-6 ">
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
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Emergency</th>
                    <th>Created_By</th>
                    <th>Updated_By</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                      $i = 0;
                  @endphp
                  @foreach ($customers as $customer)
                  @php
                    ++$i;
                    $c_user = \App\Models\User::find($customer->created_by);
                    $u_user = \App\Models\User::find($customer->updated_by);
                    $check_delete = Auth::user()->hasPermissionTo('delete_customer');
                  @endphp
                    <tr>
                        <td>{{  $i }}</td>
                        <td class="name_{{ $customer->id }}">{{ $customer->name }}</td>
                        <td class="phone_{{ $customer->id }}">{{ $customer->phone }}</td>
                        <td class="email_{{ $customer->id }}">{{ $customer->email }}</td>
                        <td class="address_{{ $customer->id }}">{{ $customer->address }}</td>
                        <td class="address_{{ $customer->id }}">{{ $customer->emergency }}</td>
                        <td>{{  optional($c_user)->name }}</td>
                        <td>{{ optional($u_user)->name }}</td>
                        <td>{{date('Y-m-d', strtotime($customer->created_at)) }}</td>
                        <td>{{ date('Y-m-d', strtotime($customer->updated_at)) }}</td>
                        <td>
                            <div class="d-flex justify-content-around"> 
                                <a href="{{ route('customers.edit', ["id" => $customer->id])}}" class="" >
                                    <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                                </a>
                                @if ($check_delete == true)
                                  <a href="" data-toggle="modal" data-target="#del-modal" id="{{ $customer->id }}" class="del_class">
                                    <i class="fas fa-trash-alt" style="color: rgb(221, 67, 40)"></i>
                                  </a>
                                @endif
                            </div>
                          
                        </td>
                    </tr>
                  @endforeach
                </tbody>
  
                <tfoot>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Emergency</th>
                    <th>Created_By</th>
                    <th>Updated_By</th>
                    <th>Created_at</th>
                    <th>Updated_at</th>
                    <th>Action</th>
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

    <!--del modal -->
    <div class="modal fade" id="del-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header ">
            <h4 class="modal-title w-100 text-center">Are you Sure?
              <i class="fas fa-exclamation"  style="color: #ea1010;"></i>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="GET" action="{{ route('customers.delete') }}">
              <div class="modal-body">
                  @csrf
                  <div class="text-center">
                      Do you really want to del this record? This process cannot be undone.
                  </div>
                  <div class="dropdown-divider"></div>
                  <div class="row mx-4">
                    <div class="col-6">
                        <label for="del_name">Customer Name :</label>
                    </div>
                    <div class="col-6">
                        <p id="del_name"></p>
                    </div>
                </div>
                <div class="row mx-4">
                    <div class="col-6">
                        <label for="del_phone">Phone :</label>
                    </div>
                    <div class="col-6">
                        <p id="del_phone"></p>
                    </div>
                </div>

                <div class="row mx-4">
                    <div class="col-6">
                        <label for="del_email">Email :</label>
                    </div>
                    <div class="col-6">
                        <p id="del_email"></p>
                    </div>
                </div>
                <div class="row mx-4">
                    <div class="col-6">
                        <label for="del_address">Address :</label>
                    </div>
                    <div class="col-6">
                        <p id="del_address"></p>
                    </div>
                </div>
                <div class="row mx-4">
                    <div class="col-6">
                        <label for="del_sos">Emergency :</label>
                    </div>
                    <div class="col-6">
                        <p id="del_sos"></p>
                        <input type="hidden" name="del_id">
                    </div>
                </div>

              </div>
              <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Confirm</button>
              </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.del modal -->
@endsection
@section('scripts')

<script>
  $( "#customer" ).ready(function() {
      $("#customer").select2();
  });
  $( "#phone" ).ready(function() {
      $("#phone").select2();
  });
</script>

<script>
    $('.del_class').click(function() {
      var id = this.id;
      var name = $(".name_" + id).html();
      var phone = $(".phone_" + id).html();
      var email = $(".email_" + id).html();
      var address = $(".address_" + id).html();
      var sos = $(".sos_" + id).html();
      $('input[name=del_id]').val(id);

      $('#del_name').text(name);
      $('#del_phone').text(phone);
      $('#del_email').text(email);
      $('#del_address').text(address);
      $('#del_sos').text(sos);
  })
</script>
@endsection