@extends('layouts.master')
@section('title', 'Edit Supplier')

@section('css')

@endsection

@section('content')
<div class="container-fluid">
    
      <!-- general form elements -->
      <div class="card card-primary">
        <!-- form start -->
        <form method="POST" action="{{route('suppliers.update')}}">
            @csrf
          <div class="card-body">
              <!-- mainform -->
              <input type="hidden" name="id" value="{{$supplier->id}}" >
              
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="name">Name<span style="color: red">*</span> </label> 
                    <input type="text" class="form-control" required id="name" value="{{$supplier->name}}" name="name" >
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="phone">Phone<span style="color: red">*</span> </label> 
                    <input type="number" name="phone" required class="form-control" value="{{$supplier->phone}}" id="phone" >
                  </div>
                </div>              
            </div>

            <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="email">Email </label> 
                    <input type="email" name="email" class="form-control" id="email" value="{{$supplier->email}}" placeholder="Enter Email Address">
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="emergency">Emergency<span style="color: red">*</span> </label> 
                    <input type="text" name="emergency" required class="form-control" value="{{$supplier->emergency}}" id="emergency" placeholder="Enter Emergency Contact">
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="address">Address<span style="color: red">*</span> </label> 
                        <textarea name="address" required class="form-control"  rows="4">{{$supplier->address}}</textarea>
                    </div>
                </div>
            </div>
              <!-- end mainform -->

            </div>
            <!-- /.card-body -->

          <div class="card-footer ">
            <div class="d-flex justify-content-around">

              <a type="" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Update</button>
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
 
</script>

@endsection