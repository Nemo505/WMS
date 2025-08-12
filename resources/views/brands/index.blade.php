@extends('layouts.master')
@section('title', 'Brand List')

@section('css')
 
@endsection


@section('buttons')

    @php
        $check_create = Auth::user()->hasPermissionTo('create_brand');
        $check_import = Auth::user()->hasPermissionTo('import_brand');
    @endphp

    @if ($check_create == true)
      <a href="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal" >
        <i class="fa fa-solid fa-plus" style="color: #ffffff;"></i>
        Add New
      </a>
    @endif
   
    @if ($check_import == true)
      <a href="" type="button" class="btn btn-success" data-toggle="modal" data-target="#import-modal" >
        <i class="fas fa-upload" style="color: #ffffff;"></i>
        Import
      </a>
    @endif

@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            {{-- form --}}
            @php
                $s_brands =  App\Models\Brand::get();
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-between">
                <div class="col-4  ">

                    <div class="form-group">
                      <label for="brand">Brand</label>
                        <div >
                        
                          <select id='brand' name="brand_id" class=" form-control">
                            <option value="" disabled selected>Choose Brand</option>
                            @foreach ($s_brands as $s_brand)
                              @if (isset($_REQUEST['brand_id']))
                                  @if ($s_brand->id == $_REQUEST['brand_id'])
                                      <option value="{{ $s_brand->id }}" selected>{{ $s_brand->name }}</option>
                                  @else
                                      <option value="{{ $s_brand->id }}">{{ $s_brand->name }}</option>
                                  @endif
                              @else
                                  <option value="{{ $s_brand->id }}">{{ $s_brand->name }}</option> 
                              @endif
                            @endforeach
                          </select>
                          </div >
                     </div>
               
                </div>

                <div class="col-6 d-flex justify-content-end">
                    <div class="col-2">
                      <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" name="search">Search</button>
                      </div>
                    </div>
                    <div class="col-2">
                      <div class="form-group mt-4">
                        <button  class="btn btn-primary" type="submit" name="export">Export</button>
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
                  <th>Brand Name</th>
                  <th>Created By</th>
                  <th>Updated By</th>
                  <th>Created at</th>
                  <th>Updated at</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($brands as $brand)
                @php
                  ++$i;
                  $c_user = \App\Models\User::find($brand->created_by);
                  $u_user = \App\Models\User::find($brand->updated_by);

                  $check_edit = Auth::user()->hasPermissionTo('edit_brand');
                  $check_delete = Auth::user()->hasPermissionTo('delete_brand');
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td class="name_{{ $brand->id }}">{{ $brand->name }}</td>
                      <td>{{  optional($c_user)->name }}</td>
                      <td>{{ optional($u_user)->name }}</td>
                      <td>{{date('Y-m-d', strtotime($brand->created_at)) }}</td>
                      <td>{{ date('Y-m-d', strtotime($brand->updated_at)) }}</td>
                      <td>
                          <div class="d-flex justify-content-around"> 
                            @if ($check_edit == true)
                              <a href="" data-toggle="modal" data-target="#edit-modal" id="{{ $brand->id }}" class="edit_class">
                                  <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                              </a>
                            @endif

                            @if ($check_delete == true)
                                
                              <a href="" data-toggle="modal" data-target="#del-modal" id="{{ $brand->id }}" class="del_class">
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
                <th>Brand Name</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Created at</th>
                <th>Updated at</th>
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

  <!--Create modal -->
  <div class="modal fade" id="create-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create New Brand</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('brands.store') }}">
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <label for="name">Brand Name <span style="color: red">*</span> </label>
                    <input type="text" required class="form-control" id="name" name="name" placeholder="Enter Brand Name">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.Create modal -->

  <!--import modal -->
  <div class="modal fade" id="import-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Brand Lists!</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('brands.import') }}" enctype="multipart/form-data">
            <div class="modal-body">
                @csrf
                <div class="row">
                  <div class="col-8">
                      <div class="form-group ">
                          <label class="form-label" >Excel <span style="color: red">*</span> </label>
                          <input type="file"required class="form-control" name="brands" required>
                      </div>
                  </div>
                  <div class="col-4">
                      <div class="form-group ">
                          <label class="form-label">Sample File</label>
                            <a href="{{ route("brands.sample")}}" type="button"class="btn btn-success">
                                <i class="fas fa-file-download mr-1"></i>Download
                            </a>
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.Import modal -->

 <!--Edit modal -->
  <div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Brand</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('brands.update') }}">
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <label for="edit_name">Brand Name <span style="color: red">*</span> </label>
                    <input type="text" required class="form-control" id="edit_name" name="edit_name" >
                    <input type="hidden" name="edit_id">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.del modal -->

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
        <form method="GET" action="{{ route('brands.delete') }}">
            <div class="modal-body">
                @csrf
                <div class="text-center">
                    Do you really want to del this record? This process cannot be undone.
                </div>
                <div class="dropdown-divider"></div>

                <div class="form-group mx-5 d-flex justify-content-around mt-3">
                    <label for="name">Brand Name :</label>
                    <p id="del_name"></p>
                    <input type="hidden" name="del_id">
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
  $( "#brand" ).ready(function() {
      $("#brand").select2();
  });
</script>

<script>
  $('.edit_class').click(function() {
      var id = this.id;
      var name = $(".name_" + id).html();
      $('input[name=edit_id]').val(id);
      $('input[name=edit_name]').val(name);
  })

  $('.del_class').click(function() {
      var id = this.id;
      var name = $(".name_" + id).html();
      $('input[name=del_id]').val(id);
      $('#del_name').text(name);
  })
</script>

@endsection