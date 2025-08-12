@extends('layouts.master')
@section('title', 'Department List')

@section('css')
 
@endsection

@section('buttons')
  @php
    $check_create = Auth::user()->hasPermissionTo('create_department');
  @endphp
  @if ($check_create == true)
    <a href="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal" >
    <i class="fa fa-solid fa-plus" style="color: #ffffff;"></i>
    Add New
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
                $s_departments =  App\Models\Department::get();
            @endphp
            <form action="" method="GET">

              <div class="row d-flex justify-content-between">
                <div class="col-6 d-flex">

                  <div class="col-5">
                    <div class="form-group">
                      <label for="department">Department Name</label>

                      <select id='department' name="department_id" class=" form-control">
                        <option value="" disabled selected>Choose department</option>
                        @foreach ($s_departments as $s_department)
                          @if (isset($_REQUEST['department_id']))
                              @if ($s_department->id == $_REQUEST['department_id'])
                                  <option value="{{ $s_department->id }}" selected>{{ $s_department->name }}</option>
                              @else
                                  <option value="{{ $s_department->id }}">{{ $s_department->name }}</option>
                              @endif
                          @else
                              <option value="{{ $s_department->id }}">{{ $s_department->name }}</option> 
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
          <div class="card-body" style="overflow-x: scroll;">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Department Name</th>
                  <th>Remarks</th>
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
                @foreach ($departments as $department)
                @php
                  ++$i;
                  $c_user = \App\Models\User::find($department->created_by);
                  $u_user = \App\Models\User::find($department->updated_by);
                  
                  $check_edit = Auth::user()->hasPermissionTo('edit_department');
                  $check_delete = Auth::user()->hasPermissionTo('delete_department');
                @endphp
                  <tr>
                      <td>{{  $i }}</td>
                      <td class="name_{{ $department->id }}">{{ $department->name }}</td>
                      
                      <td class="remarks_{{ $department->id }}">{{ $department->remarks }}</td>
                      <td>{{  optional($c_user)->name }}</td>
                      <td>{{ optional($u_user)->name }}</td>
                      <td>{{date('Y-m-d', strtotime($department->created_at)) }}</td>
                      <td>{{ date('Y-m-d', strtotime($department->updated_at)) }}</td>
                      <td>
                          <div class="d-flex justify-content-around"> 
                            @if ($check_edit == true)
                              <a href="" data-toggle="modal" data-target="#edit-modal" id="{{ $department->id }}" class="edit_class">
                                  <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                              </a>
                            @endif

                            @if ($check_delete == true)
                              <a href="" data-toggle="modal" data-target="#del-modal" id="{{ $department->id }}" class="del_class">
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
                  <th>Department Name</th>
                  <th>Remarks</th>
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
          <h4 class="modal-title">Create New Department</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('departments.store') }}">
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <label for="name">Department Name <span style="color: red">*</span> </label>
                    <input type="text" class="form-control" required id="name" name="name" placeholder="Enter department Name">
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea type="text" class="form-control" rows="5" id="remarks" name="remarks" placeholder="Enter Remark"></Textarea>
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

 <!--Edit modal -->
 <div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Department</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" action="{{ route('departments.update') }}">
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <label for="edit_name">Department Name <span style="color: red">*</span> </label>
                    <input type="text" class="form-control" required id="edit_name" name="edit_name" >
                    <input type="hidden" name="edit_id">
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea type="text" class="form-control" rows="5" id="edit_remarks" name="edit_remarks"></Textarea>
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
        <form method="GET" action="{{ route('departments.delete') }}">
            <div class="modal-body">
                @csrf
                <div class="text-center">
                    Do you really want to del this record? This process cannot be undone.
                </div>
                <div class="dropdown-divider"></div>

                <div class="row mx-4">
                    <div class="col-6">
                      <label for="del_name">Department Name :</label>
                    </div>
                    <div class="col-6">
                       <p id="del_name"></p>
                    </div>
                </div>
                <div class="row mx-4">
                    <div class="col-6">
                      <label for="del_remarks">Remark :</label>
                    </div>
                    <div class="col-6">
                       <p id="del_remarks"></p>
                    </div>
                </div>
                <input type="hidden" name="del_id">
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
  $( "#department" ).ready(function() {
      $("#department").select2();
  });
</script>

<script>
  $('.edit_class').click(function() {
      var id = this.id;
      var name = $(".name_" + id).html();
      var remarks = $(".remarks_" + id).html();
      $('input[name=edit_id]').val(id);
      $('input[name=edit_name]').val(name);
      $('textarea[name=edit_remarks]').html(remarks);
  })

  $('.del_class').click(function() {
      var id = this.id;
      var name = $(".name_" + id).html();
      var remarks = $(".remarks_" + id).html();
      $('input[name=del_id]').val(id);
      $('#del_name').text(name);
      $('#del_remarks').text(remarks);
  })
</script>

@endsection