@extends('layouts.master')
@section('title', 'New User')

@section('css')

@endsection

@section('content')
<div class="container-fluid">
    
      <!-- general form elements -->
      <div class="card card-primary">
        <!-- form start -->
        <form method="POST" action="{{route('users.store')}}">
            @csrf
          <div class="card-body">
              <!-- mainform -->
              
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">Name<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="name" name="name" placeholder="Enter User Name">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">User Name<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="user_name" name="user_name" placeholder="Enter User Name">
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="phone">Phone<span style="color: red">*</span> </label> 
                    <input type="number" name="phone" required class="form-control" id="phone" placeholder="Enter Phone Number">
                  </div>
                </div>              
                <div class="col-6">
                  <div class="form-group">
                    <label for="email">Email </label> 
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email Address">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="password">Password<span style="color: red">*</span> </label> 
                    <input type="password" name="password" required class="form-control" 
                          required id="password" 
                          pattern=".{6,8}" 
                          title="6 to 8 characters"
                          placeholder="Enter Password">
                  </div>
                </div>              
                <div class="col-6">
                  <div class="form-group">
                    <label for="email">Confirm Password <span style="color: red">*</span> <span style="margin-top: 7px;" id="pw_match"></span>   </label> 
                    <input type="password" name="password_confirmation" class="form-control" required id="password_confirmation" placeholder="Enter Confirm Password">
                  </div>
                 
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="emergency">Emergency </label> 
                    <input type="text" name="emergency" class="form-control" id="emergency" placeholder="Enter Emergency Contact">
                  </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="address">Address<span style="color: red">*</span> </label> 
                        <textarea name="address" required class="form-control"  rows="4"></textarea>
                    </div>
                </div>
            </div>
              <!-- end mainform -->

            </div>
            <!-- /.card-body -->

          <div class="card-footer ">
            <div class="d-flex justify-content-around">

              <a type="" class="btn btn-secondary">Cancel</a>
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
  $('#password, #password_confirmation').on('keyup', function() {
      if ($('#password').val() == $('#password_confirmation').val()) {
          $('#pw_match').text('Password match !').css('color', 'green');
      } else
          $('#pw_match').text('Password does not match !').css('color', 'red');
  });
</script>

@endsection