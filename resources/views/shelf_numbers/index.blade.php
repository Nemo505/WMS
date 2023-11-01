@extends('layouts.master')
@section('title', 'Shelf Number List')

@section('css')

@endsection

@section('buttons')
    @php
        $check_create = Auth::user()->hasPermissionTo('create_shelf_number');
    @endphp
    @if ($check_create == true)
        <a href="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
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
                            $s_shelf_nums = App\Models\ShelfNumber::get();
                        @endphp
                        <form action="" method="GET">

                            <div class="row d-flex justify-content-between">
                                <div class="col-6 d-flex">

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="shelf_num">Shelf Number</label>

                                            <select id='shelf_num' name="shelf_num_id" class=" form-control">
                                                <option value="" disabled selected>Choose Number</option>
                                                @foreach ($s_shelf_nums as $s_shelf_num)
                                                    @if (isset($_REQUEST['shelf_num_id']))
                                                        @if ($s_shelf_num->id == $_REQUEST['shelf_num_id'])
                                                            <option value="{{ $s_shelf_num->id }}" selected>
                                                                {{ $s_shelf_num->name }}</option>
                                                        @else
                                                            <option value="{{ $s_shelf_num->id }}">{{ $s_shelf_num->name }}
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $s_shelf_num->id }}">{{ $s_shelf_num->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="shelf">Shelf Name</label>

                                            <select id='shelf' name="shelf_id" class=" form-control">
                                                <option value="" disabled selected>Choose Shelf</option>
                                                @foreach ($shelves as $shelf)
                                                    @if (isset($_REQUEST['shelf_id']))
                                                        @if ($shelf->id == $_REQUEST['shelf_id'])
                                                            <option value="{{ $shelf->id }}" selected>
                                                                {{ $shelf->name }}</option>
                                                        @else
                                                            <option value="{{ $shelf->id }}">
                                                                {{ $shelf->name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $shelf->id }}">
                                                            {{ $shelf->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-4">
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
                                    <th>Shelf Number</th>
                                    <th>Shelf Name</th>
                                    <th>Warehouse Name</th>
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
                                @foreach ($shelf_nums as $shelf_num)
                                    @php
                                        ++$i;
                                        $c_user = \App\Models\User::find($shelf_num->created_by);
                                        $u_user = \App\Models\User::find($shelf_num->updated_by);
                                        $warehouse_name = \App\Models\Warehouse::find($shelf_num->warehouse_id);
                                        $shelf_name = \App\Models\Shelf::find($shelf_num->shelf_id);

                                        $check_edit = Auth::user()->hasPermissionTo('edit_shelf_number');
                                        $check_delete = Auth::user()->hasPermissionTo('delete_shelf_number');
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td class="name_{{ $shelf_num->id }}">{{ $shelf_num->name }}</td>
                                        <td class="shelf_{{ $shelf_num->id }}" id="{{ optional($shelf_name)->id }}">{{ optional($shelf_name)->name }}</td>
                                        <td class="warehouse_{{ $shelf_num->id }}" id="{{ optional($warehouse_name)->id }}">{{ optional($warehouse_name)->name }}</td>
                                       
                                        <td class="remarks_{{ $shelf_num->id }}">{{ $shelf_num->remarks }}</td>
                                        <td>{{ optional($c_user)->name }}</td>
                                        <td>{{ optional($u_user)->name }}</td>
                                        <td>{{ date('Y-m-d', strtotime($shelf_num->created_at)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($shelf_num->updated_at)) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-around">
                                                @if ($check_edit == true)
                                                    <a href="" data-toggle="modal" data-target="#edit-modal"
                                                        id="{{ $shelf_num->id }}" class="edit_class">
                                                        <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                                                    </a>
                                                @endif

                                                @if ($check_delete == true)
                                                    <a href="" data-toggle="modal" data-target="#del-modal"
                                                        id="{{ $shelf_num->id }}" class="del_class">
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
                                    <th>Shelf Number</th>
                                    <th>Shelf Name</th>
                                    <th>Warehouse Name</th>
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
    <div class="modal fade" id="create-modal" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create New Shelf Number</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('shelf_nums.store') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Shelf Number <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" required id="name" name="name"
                                placeholder="Enter Shelf Number">
                        </div>

                        <div class="form-group ">
                            <label for="c_warehouse">Warehouse Name <span style="color: red">*</span> </label>

                            <select id='c_warehouse' required name="warehouse_id" class="form-control getShelf">
                                <option value="" disabled selected>Choose Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">
                                        {{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="c_shelf">Shelf Name <span style="color: red">*</span> </label>

                            <select id='c_shelf' required name="shelf_id" class="form-control">
                                <option value="" disabled selected>Choose Warehouse First</option>
                                {{-- AjaxData --}}

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
                    <h4 class="modal-title">Edit Shelf Number <span style="color: red">*</span> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('shelf_nums.update') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="edit_name">Shelf Number Name <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" required id="edit_name" name="edit_name">
                            <input type="hidden" name="edit_id">
                        </div>
                        <div class="form-group ">
                            <label for="e_warehouse">Warehouse Name <span style="color: red">*</span> </label>

                            <select id='e_warehouse' required name="e_warehouse_id" class="form-control getShelf">
                                <option value="" disabled selected>Choose Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">
                                        {{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="e_shelf">Shelf Name</label>

                            <select id='e_shelf' required name="e_shelf_id" class="form-control">
                                <option value="" disabled selected>Choose Warehouse First</option>
                               {{-- AjaxData --}}
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
                <form method="GET" action="{{ route('shelf_nums.delete') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="text-center">
                            Do you really want to del this record? This process cannot be undone.
                        </div>
                        <div class="dropdown-divider"></div>

                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_name">Shelf Number Name :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_name"></p>
                            </div>
                        </div>

                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_shelf_name">Shelf Name :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_shelf_name"></p>
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


@endsection
@section('scripts')

    <script>
        $("#shelf_num").ready(function() {
            $("#shelf_num").select2();
        });
        $("#warehouse").ready(function() {
            $("#warehouse").select2();
        });
        $("#shelf").ready(function() {
            $("#shelf").select2();
        });
      
    </script>

    <script>
        $('.edit_class').click(function() {
            var id = this.id;
            var name = $(".name_" + id).html();
            var warehouse = $(".warehouse_" + id).attr('id');
            var shelf = $(".shelf_" + id).attr('id');
            var remarks = $(".remarks_" + id).html();
            $('input[name=edit_id]').val(id);
            $('input[name=edit_name]').val(name);
            $('select[name=e_warehouse_id]').val(warehouse);
            $('select[name=e_shelf_id]').val(shelf);
            $('textarea[name=edit_remarks]').html(remarks);

            $.ajax({
                url: "{{ route('shelf_nums.getShelf') }}",
                type: "GET",
                data: {
                    "warehouse_id": warehouse,
                },
                cache: false,
                success: function(result) {
                    if (result) {
                        $("#c_shelf, #e_shelf").empty();
                        $.each(result, function(key, value) {
                            $("#c_shelf, #e_shelf").append(
                                (value.id == shelf ? 
                                '<option selected value="' + value.id + '">' + value.name +'</option>'
                                : 
                                '<option value="' + value.id + '">' + value.name +'</option>'
                                ) 
                            );
                        });
                    } else {
                        $("#shelf_id").empty();
                    }
                }
            });
        })

        $('.del_class').click(function() {
            var id = this.id;
            var name = $(".name_" + id).html();
            var shelf = $(".shelf_" + id).html();
            var warehouse = $(".warehouse_" + id).html();
            var remarks = $(".remarks_" + id).html();
            $('input[name=del_id]').val(id);
            $('#del_name').text(name);
            $('#del_shelf_name').text(shelf);
            $('#del_warehouse').text(warehouse);
            $('#del_remarks').text(remarks);
        })
    </script>

    <script>
        // accessing self numbers under choosen warehouse
        $('.getShelf').on('change', function() {
            var warehouse_id = this.value;
            $.ajax({
                url: "{{ route('shelf_nums.getShelf') }}",
                type: "GET",
                data: {
                    "warehouse_id": warehouse_id,
                },
                cache: false,
                success: function(result) {
                    if (result) {
                        $("#c_shelf, #e_shelf").empty();
                        $.each(result, function(key, value) {
                            $("#c_shelf, #e_shelf").append(
                                '<option value="' + value.id + '">' + value.name +'</option>'
                            );
                        });
                    } else {
                        $("#shelf_id").empty();
                    }
                }
            });
        });
    </script>

@endsection
