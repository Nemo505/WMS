@extends('layouts.master')
@section('title', 'User List')

@section('css')
 
@endsection

@section('buttons')
    <a href="{{ route('users.create')}}" class="btn btn-primary" >
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
                $s_users =  App\Models\User::where('id', '!=', Auth::user()->id)->get();
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-between">
                <div class="col-6 d-flex justify-content-start">

                  <div class="col-5">
                    <div class="form-group">
                      <label for="user">Name</label>

                      <select id='user' name="user" class=" form-control">
                        <option value="" disabled selected>Choose Name</option>
                        @foreach ($s_users as $s_user)
                          @if (isset($_REQUEST['user']))
                              @if ($s_user->id == $_REQUEST['user'])
                                  <option value="{{ $s_user->id }}" selected>{{ $s_user->name }}</option>
                              @else
                                  <option value="{{ $s_user->id }}">{{ $s_user->name }}</option>
                              @endif
                          @else
                              <option value="{{ $s_user->id }}">{{ $s_user->name }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>
                  <div class="col-5">
                    <div class="form-group">
                      <label for="user">Phone</label>

                      <select id='phone' name="phone" class=" form-control">
                        <option value="" disabled selected>Choose Phone</option>
                        @foreach ($s_users as $s_user)
                          @if (isset($_REQUEST['phone']))
                              @if ($s_user->phone == $_REQUEST['phone'])
                                  <option value="{{ $s_user->phone }}" selected>{{ $s_user->phone }}</option>
                              @else
                                  <option value="{{ $s_user->phone }}">{{ $s_user->phone }}</option>
                              @endif
                          @else
                              <option value="{{ $s_user->phone }}">{{ $s_user->phone }}</option> 
                          @endif
                        @endforeach
                      </select>

                    </div>
                  </div>

                </div>

                <div class="col-6 d-flex justify-content-end">
                    <div class="col-2">
                      <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" name="search">Search</button>
                      </div>
                    </div>
                </div>

              </div>
            </form>
            
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>User Name</th>
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
                  @foreach ($users as $user)
                  @php
                    ++$i;
                    $c_user = \App\Models\User::find($user->created_by);
                    $u_user = \App\Models\User::find($user->updated_by);
                    $check_delete = Auth::user()->hasPermissionTo('delete_user');
                  @endphp
                    <tr>
                        <td>{{  $i }}</td>
                        <td class="name_{{ $user->id }}">{{ $user->name }}</td>
                        <td class="username_{{ $user->id }}">{{ $user->user_name }}</td>
                        <td class="phone_{{ $user->id }}">{{ $user->phone }}</td>
                        <td class="email_{{ $user->id }}">{{ $user->email }}</td>
                        <td class="address_{{ $user->id  }}">{{ $user->address }}</td>
                        <td class="sos_{{ $user->id  }}">{{ $user->emergency }}</td>
                        <td>{{  optional($c_user)->name }}</td>
                        <td>{{ optional($u_user)->name }}</td>
                        <td>{{date('Y-m-d', strtotime($user->created_at)) }}</td>
                        <td>{{ date('Y-m-d', strtotime($user->updated_at)) }}</td>
                        <td>
                            <div class="d-flex justify-content-around"> 
                                <a href="{{ route('users.edit', ["id" => $user->id])}}" class="" >
                                    <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                                </a>
                                @if ($check_delete == true)
                                  <a href="" data-toggle="modal" data-target="#del-modal" id="{{ $user->id }}" class="del_class">
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
                  <th>User Name</th>
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
          <form method="GET" action="{{ route('users.delete') }}">
              <div class="modal-body">
                  @csrf
                  <div class="text-center">
                      Do you really want to del this record? This process cannot be undone.
                  </div>
                  <div class="dropdown-divider"></div>
                  <div class="row mx-4">
                    <div class="col-6">
                        <label for="del_name">User Name :</label>
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

<script>
  $( "#user" ).ready(function() {
      $("#user").select2();
  });
  $( "#phone" ).ready(function() {
      $("#phone").select2();
  });
</script>

@endsection