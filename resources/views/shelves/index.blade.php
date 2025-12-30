@extends('layouts.master')
@section('title', 'Shelf List')

@section('css')

@endsection

@section('buttons')

    @php
        $check_create = Auth::user()->hasPermissionTo('create_shelf');
    @endphp
    @if ($check_create == true)
        <a href="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
            <i class="fa fa-solid fa-plus" style="color: #ffffff;"></i>
            Add New
        </a>
    @endif
    <a href="" type="button" class="btn btn-success" data-toggle="modal" data-target="#import-modal" >
        <i class="fas fa-upload" style="color: #ffffff;"></i>
        Import
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
                            $s_shelves = App\Models\Shelf::get();
                        @endphp
                        <form action="" method="GET">

                            <div class="row d-flex justify-content-between">
                                <div class="col-6 d-flex">

                                    <div class="col-5">
                                        <div class="form-group">
                                            <label for="shelf">Shelf Name</label>

                                            <select id='shelf' name="shelf_id" class=" form-control">
                                                <option value="" disabled selected>Choose Shelf</option>
                                                @foreach ($s_shelves as $s_shelf)
                                                    @if (isset($_REQUEST['shelf_id']))
                                                        @if ($s_shelf->id == $_REQUEST['shelf_id'])
                                                            <option value="{{ $s_shelf->id }}" selected>
                                                                {{ $s_shelf->name }}</option>
                                                        @else
                                                            <option value="{{ $s_shelf->id }}">{{ $s_shelf->name }}
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $s_shelf->id }}">{{ $s_shelf->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-5">
                                        <div class="form-group">
                                            <label for="warehouse">Warehouse Name</label>

                                            <select id='warehouse' name="warehouse_id" class=" form-control">
                                                <option value="" disabled selected>Choose Warehouse</option>
                                                @foreach ($warehouses as $warehouse)
                                                    @if (isset($_REQUEST['warehouse_id']))
                                                        @if ($warehouse->id == $_REQUEST['warehouse_id'])
                                                            <option value="{{ $warehouse->id }}" selected>
                                                                {{ $warehouse->name }}</option>
                                                        @else
                                                            <option value="{{ $warehouse->id }}">
                                                                {{ $warehouse->name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $warehouse->id }}">
                                                            {{ $warehouse->name }}</option>
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
                                    <th>Shelf Name</th>
                                    <th>Warehouse Name</th>
                                    <th>Remarks</th>
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
                                @foreach ($shelves as $shelf)
                                    @php
                                        ++$i;
                                        $c_user = \App\Models\User::find($shelf->created_by);
                                        $u_user = \App\Models\User::find($shelf->updated_by);
                                        $warehouse_name = \App\Models\Warehouse::find($shelf->warehouse_id);

                                        $check_edit = Auth::user()->hasPermissionTo('edit_shelf');
                                        $check_delete = Auth::user()->hasPermissionTo('delete_shelf');
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td class="name_{{ $shelf->id }}">{{ $shelf->name }}</td>
                                        <td class="warehouse_{{ $shelf->id }}" id="{{ optional($warehouse_name)->id }}">{{ optional($warehouse_name)->name }}</td>
                                       
                                        <td class="remarks_{{ $shelf->id }}">{{ $shelf->remarks }}</td>
                                        <td>{{ optional($c_user)->name }}</td>
                                        <td>{{ optional($u_user)->name }}</td>
                                        <td>{{ date('Y-m-d', strtotime($shelf->created_at)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($shelf->updated_at)) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-around">
                                                @if ($check_edit == true)
                                                    <a href="" data-toggle="modal" data-target="#edit-modal"
                                                        id="{{ $shelf->id }}" class="edit_class">
                                                        <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                                                    </a>
                                                @endif

                                                @if ($check_delete == true)
                                                    <a href="" data-toggle="modal" data-target="#del-modal"
                                                        id="{{ $shelf->id }}" class="del_class">
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
                                    <th>Shelf Name</th>
                                    <th>Warehouse Name</th>
                                    <th>Remarks</th>
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

    <!--Create modal -->
    <div class="modal fade" id="create-modal" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create New Shelf</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('shelves.store') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Shelf Name <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" required id="name" name="name"
                                placeholder="Enter shelf Name">
                        </div>
                        <div class="form-group ">
                            <label for="c_warehouse">Warehouse Name <span style="color: red">*</span> </label>

                            <select id='c_warehouse' required name="warehouse_id" class="form-control">
                                <option value="" disabled selected>Choose Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">
                                        {{ $warehouse->name }}</option>
                                @endforeach
                            </select>

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
                    <h4 class="modal-title">Edit shelf</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('shelves.update') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="edit_name">Shelf Name <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" required id="edit_name" name="edit_name">
                            <input type="hidden" name="edit_id">
                        </div>
                        <div class="form-group ">
                            <label for="e_warehouse">Warehouse Name <span style="color: red">*</span> </label>

                            <select id='e_warehouse' required name="e_warehouse_id" class="form-control">
                                <option value="" disabled selected>Choose Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">
                                        {{ $warehouse->name }}</option>
                                @endforeach
                            </select>

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
                        <i class="fas fa-exclamation" style="color: #ea1010;"></i>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="GET" action="{{ route('shelves.delete') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="text-center">
                            Do you really want to del this record? This process cannot be undone.
                        </div>
                        <div class="dropdown-divider"></div>

                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_name">Shelf Name :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_name"></p>
                            </div>
                        </div>
                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_warehouse">Warehouse :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_warehouse"></p>
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
    
    <!--import modal -->
    <div class="modal fade" id="import-modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Shelf Lists!</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('shelves.import') }}" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                      <div class="col-8">
                          <div class="form-group ">
                              <label class="form-label" >Excel</label>
                              <input type="file"required class="form-control" name="shelves" required>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-group ">
                              <label class="form-label">Sample File</label>
                                <a href="{{ route("shelves.sample")}}" type="button"class="btn btn-success">
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


@endsection
@section('scripts')

    <script>
        $("#shelf").ready(function() {
            $("#shelf").select2();
        });
        $("#warehouse").ready(function() {
            $("#warehouse").select2();
        });
        
        // $(function(){
        //     $("#e_warehouse").select2({
        //         dropdownParent: $('#create-modal')
        //     });     
        // });
    </script>

    <script>
        $('.edit_class').click(function() {
            var id = this.id;
            var name = $(".name_" + id).html();
            var warehouse = $(".warehouse_" + id).attr('id');
            var remarks = $(".remarks_" + id).html();
            $('input[name=edit_id]').val(id);
            $('input[name=edit_name]').val(name);
            $('select[name=e_warehouse_id]').val(warehouse);
            $('textarea[name=edit_remarks]').html(remarks);
        })

        $('.del_class').click(function() {
            var id = this.id;
            var name = $(".name_" + id).html();
            var warehouse = $(".warehouse_" + id).html();
            var remarks = $(".remarks_" + id).html();
            $('input[name=del_id]').val(id);
            $('#del_name').text(name);
            $('#del_warehouse').text(warehouse);
            $('#del_remarks').text(remarks);
        })
    </script>

@endsection
