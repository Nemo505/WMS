@extends('layouts.master')
@section('title', 'Profile')

@section('buttons')
    <a href="javascript:window.history.back();" class="ml-5" style="color:rgb(136, 32, 139)">
        <i class="fas fa-reply"></i>
    <small>Back</small> 
    </a>  
@endsection

@section('content')
 <!-- Main content -->
 <div class="container-fluid">  
    <div class="row mx-4">
        <div class="col-4 mx-auto  card card-body">
            <div class="row">
                <div class="col-md-10 offset-md-1 col-sm-9 col-10">
                    <div class="mb-3">
                        <img src="../../assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2 img-fluid mx-auto d-block" alt="User Image">
                        
                    </div>
                    <div class="text-center">
                        <h4 class="mt-1 mb-3">{{$user->user_name}}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-7 mx-auto card card-body">
            <label class=" mb-3" style="font-size: 20px">User Information:</label>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted "> 
                        <i class="fas fa-user-circle mr-1"></i>
                        User Name : {{$user->user_name}}
                    </p>
                    <p class="text-muted "> 
                        <i class="fas fa-user  mr-1 "></i> 
                        Name : {{$user->name}}
                    </p>

                    <p class="text-muted "> 
                        <i class="fas fa-phone-alt mr-1 "></i>
                        Phone : {{$user->phone}}
                    </p>
                       
                </div>
                <div class="col-md-6">
                    <div>
                       
                        <p class="text-muted">
                            <i class="fas fa-envelope mr-1 "></i>
                            Email : {{$user->email}}
                        </p>
                        
                        <p class="text-muted">
                            <i class="fas fa-address-card mr-1"></i>
                            Address : {{$user->address}}
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-phone-volume  mr-1"></i> 
                            Emergency : {{$user->emergency}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class=" card card-body my-3 mx-5">
        <div class="row mx-3 d-flex justify-content-between align-items-center mb-4">
            <label class=" mb-3" style="font-size: 20px">Profile</label>
            <button type="button" id="editProfile"class="btn btn-outline-secondary">
                <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                Edit
            </button>
        </div>
        <form action="{{ route("users.update")}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{Auth::user()->id}}">
            <div class="table-responsive">
                <table class="table mb-0 table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 400px;">User Name</th>
                            <td>
                                <input type="text" disabled class="form-control" name="user_name" required value="{{$user->user_name}}">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row" style="width: 400px;">Name</th>
                            <td>
                                <input type="text" disabled class="form-control" name="name" required value="{{$user->name}}">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Phone</th>
                            <td>
                                <input type="number" disabled class="form-control" name="phone" required value="{{$user->phone}}">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Email</th>
                            <td>
                                <input type="email" disabled class="form-control" name="email" required value="{{$user->email}}">
                            </td>
                        </tr>
                       
                        <tr>
                            <th scope="row">Address</th>
                            <td>
                                <input type="text" disabled class="form-control" name="address" required value="{{$user->address}}">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Emgergency</th>
                            <td>
                                <input type="text" disabled class="form-control" name="emergency"  value="{{$user->emergency}}">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Password update:  <span style="color: rgb(19, 124, 89)">(Choose or Keep blank.)</span></th>
                            <td>
                                <input type="password" disabled name="password" class="form-control" 
                                    id="password" 
                                    pattern=".{6,8}" 
                                    title="6 to 8 characters">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Confirm Password</span> <span style="margin-top: 7px;" id="pw_match"></span></th>
                            <td>
                                <input type="password" disabled name="password_confirmation" 
                                        class="form-control"  
                                        id="password_confirmation">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer" style="display: none">
                <div class="d-flex justify-content-around">
                    <div href="" class="btn btn-secondary cancel">Cancel</div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
    
        </form>
    </div>
</div>
@endsection

@section('scripts')

<script>
  $( "#user" ).ready(function() {
      $("#user").select2();
  });
</script>
<script>
    var disabled = false;
    $('#editProfile').click(function() {
        if (disabled) {
            $(".form-control").prop('disabled', false);   // if disabled, enable
            $(".card-footer").css('display', 'block');  
        }
        disabled = !disabled;
    })

    $('.cancel').on('click', function(){
        if (disabled) {
            $(".form-control").prop('disabled', true);   // if disabled, enable
            $(".card-footer").css('display', 'none');   
        }
        disabled = !disabled;
    })

</script>
<script>
    $('#password, #password_confirmation').on('keyup', function() {
        if ($('#password').val() == $('#password_confirmation').val()) {
            $('#pw_match').text('Password match !').css('color', 'green');
        } else
            $('#pw_match').text('Password does not match !').css('color', 'red');
    });
  </script>
@endsection