@extends('layouts.master')
@section('title', 'Code List')

@section('css')
 <style>
  .animate-bottom {  
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
        from {-webkit-transform:scale(0)} 
        to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
        from {transform:scale(0)} 
        to {transform:scale(1)}
    }
 </style>
@endsection

@section('buttons')
    @php
        $check_create = Auth::user()->hasPermissionTo('create_code');
        $check_import = Auth::user()->hasPermissionTo('import_code');
    @endphp
    
    @if ($check_create == true)
        <a href="" type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
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
                        <form action="" method="GET">

                            <div class="row d-flex justify-content-between">
                                <div class="col-10 d-flex">
                                  
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="brand">Brand</label>

                                            <select id='brand' name="brand_id" class=" form-control">
                                                <option value="" disabled selected>Choose Brand</option>
                                                @foreach ($brands as $brand)
                                                    @if (isset($_REQUEST['brand_id']))
                                                        @if ($brand->id == $_REQUEST['brand_id'])
                                                            <option value="{{ $brand->id }}" selected>
                                                                {{ $brand->name }}</option>
                                                        @else
                                                            <option value="{{ $brand->id }}">
                                                                {{ $brand->name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $brand->id }}">
                                                            {{ $brand->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="commodity">Commodity</label>

                                            <select id='commodity' name="commodity_id" class=" form-control">
                                                <option value="" disabled selected>Choose commodity</option>
                                                @foreach ($commodities as $commodity)
                                                    @if (isset($_REQUEST['commodity_id']))
                                                        @if ($commodity->id == $_REQUEST['commodity_id'])
                                                            <option value="{{ $commodity->id }}" selected>
                                                                {{ $commodity->name }}</option>
                                                        @else
                                                            <option value="{{ $commodity->id }}">
                                                                {{ $commodity->name }}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $commodity->id }}">
                                                            {{ $commodity->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="code">Code</label>

                                            <select id='code' name="code_id" class=" form-control">
                                                <option value="" disabled selected>Choose Code</option>
                                                @foreach ($codes as $code)
                                                    @if (isset($_REQUEST['code_id']))
                                                        @if ($code->id == $_REQUEST['code_id'])
                                                            <option value="{{ $code->id }}" selected>
                                                                {{ $code->name }}</option>
                                                        @else
                                                            <option value="{{ $code->id }}">{{ $code->name }}
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $code->id }}">{{ $code->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="">
                                          <label for="from_date">From Date</label>
                                          @if(isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date']))
                                            @php
                                                $new_from_date = date('Y-m-d', strtotime($_REQUEST['from_date']));
                                            @endphp
                                            <input type="date" value={{$new_from_date}} name="from_date" class="form-control"  id="from_date">
                                          @else
                                            <input type="date" name="from_date" class="form-control"  id="from_date">
                                          @endif
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="to_date">To Date</label>
                                            @if(isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date']))
                                            @php
                                                $new_to_date = date('Y-m-d', strtotime($_REQUEST['to_date']));
                                            @endphp
                                            <input type="date" value={{$new_to_date}} name="to_date" class="form-control"  id="to_date">
                                            @else
                                            <input type="date" name="to_date" class="form-control"  id="to_date">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2 d-flex justify-content-start">
                                    <div class="col-6">
                                        <div class="mt-4">
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
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Commodity</th>
                                    <th>Usage</th>
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
                                @foreach ($codes as $code)
                                    @php
                                        ++$i;
                                        $c_user = \App\Models\User::find($code->created_by);
                                        $u_user = \App\Models\User::find($code->updated_by);
                                        $commodity_name = \App\Models\Commodity::find($code->commodity_id);
                                        $brand_name = \App\Models\brand::find($code->brand_id);

                                        $check_edit = Auth::user()->hasPermissionTo('edit_code');
                                        $check_delete = Auth::user()->hasPermissionTo('delete_code');
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td class="text-center position-relative pop" id="{{ $code->id }}">
                                            @if ($code->image == null)
                                                <img src="/storage/img/code/no-img.jpg" class="img_{{ $code->id }}" alt="code" height="auto"
                                                width="35">
                                            @else
                                                <img src="{{ URL::asset($code->image) }}" class="img_{{ $code->id }} "  alt="code" height="auto"
                                                    width="35">
                                            @endif
                                        </td>
                                        <td class="name_{{ $code->id }}">{{ $code->name}}</td>
                                        <td class="brand_{{ $code->id }}" id="{{ optional($brand_name)->id }}">{{ optional($brand_name)->name }}</td>
                                        <td class="commodity_{{ $code->id }}" id="{{ optional($commodity_name)->id }}">{{ optional($commodity_name)->name }}</td>
                                       
                                        <td class="usage_{{ $code->id }}">{{ $code->usage }}</td>
                                        <td>{{ optional($c_user)->name }}</td>
                                        <td>{{ optional($u_user)->name }}</td>
                                        <td>{{ date('Y-m-d', strtotime($code->created_at)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($code->updated_at)) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-around">
                                                @if ($check_edit == true)
                                                    
                                                    <a href="" data-toggle="modal" data-target="#edit-modal"
                                                        id="{{ $code->id }}" class="edit_class">
                                                        <i class="far fa-edit" style="color: rgb(221, 142, 40)"></i>
                                                    </a>
                                                @endif

                                                @if ($check_delete == true)
                                                    <a href="" data-toggle="modal" data-target="#del-modal"
                                                        id="{{ $code->id }}" class="del_class">
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
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Commodity</th>
                                    <th>Usage</th>
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
                    <h4 class="modal-title">Create New Code</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('codes.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group ">
                            <label for="c_brand">Brand Name <span style="color: red">*</span> </label>

                            <select id='c_brand' required name="brand_id" class="form-control">
                                <option value="" disabled selected>Choose Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group ">
                            <label for="c_commodity">Commodity Name <span style="color: red">*</span> </label>

                            <select id='c_commodity' required name="commodity_id" class="form-control getbrand">
                                <option value="" disabled selected>Choose Commodity</option>
                                @foreach ($commodities as $commodity)
                                    <option value="{{ $commodity->id }}">
                                        {{ $commodity->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Code <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" required id="name" name="name"
                                placeholder="Enter Code">
                        </div>
                        <div class="form-group">
                            <label for="image">File <span style="color: red">*</span> </label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="form-group">
                            <label for="usage">Usage</label>
                            <textarea type="text" class="form-control" rows="3" id="usage" name="usage"></Textarea>
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
                    <h4 class="modal-title">Edit Code </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('codes.update') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <div class="form-group ">
                            <label for="e_brand">Brand Name <span style="color: red">*</span> </label>

                            <select id='e_brand' required name="e_brand_id" class="form-control">
                                <option value="" disabled selected>Choose Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group ">
                            <label for="e_commodity">Commodity Name <span style="color: red">*</span> </label>

                            <select id='e_commodity' required name="e_commodity_id" class="form-control getbrand">
                                <option value="" disabled selected>Choose commodity</option>
                                @foreach ($commodities as $commodity)
                                    <option value="{{ $commodity->id }}">
                                        {{ $commodity->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_name">Code <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" required id="edit_name" name="edit_name">
                            <input type="hidden" name="edit_id">
                        </div>
                        <div class="form-group">
                            <label for="edit_image">File <span style="color: red">*</span> </label>
                            <div class="d-flex">
                                <img src="" alt="code" name="e_image" height="auto" width="35">
                                <input type="file" class="form-control ml-1" id="edit_image" name="edit_image">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="usage">Usage</label>
                            <textarea type="text" class="form-control" rows="3" id="edit_usage" name="edit_usage"></Textarea>
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
    <!-- /.Edit modal -->

    <!--import modal -->
    <div class="modal fade" id="import-modal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Code Lists!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('codes.import') }}" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group ">
                                <label class="form-label" >Excel <span style="color: red">*</span> </label>
                                <input type="file"required class="form-control" name="codes" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group ">
                                <label class="form-label">Sample File</label>
                                <a href="{{ route("codes.sample")}}" type="button"class="btn btn-success">
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
                <form method="GET" action="{{ route('codes.delete') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="text-center">
                            Do you really want to del this record? This process cannot be undone.
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="text-center py-2">
                            <img src="" alt="code" name="del_image" height="auto" width="60">
                        </div>

                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_name">Code :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_name"></p>
                            </div>
                        </div>

                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_brand_name">Brand Name :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_brand_name"></p>
                            </div>
                        </div>
                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_commodity">Commodity :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_commodity"></p>
                            </div>
                        </div>
                        <div class="row mx-4">
                            <div class="col-6">
                                <label for="del_usage">Usage :</label>
                            </div>
                            <div class="col-6">
                                <p id="del_usage"></p>
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

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog animate-bottom">
              <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
              <img src="" class="imagepreview" style="width: 100%;">
        </div>
      </div>


@endsection
@section('scripts')

    <script>
        $("#code").ready(function() {
            $("#code").select2();
        });
        $("#commodity").ready(function() {
            $("#commodity").select2();
        });
        $("#brand").ready(function() {
            $("#brand").select2();
        });
      
    </script>

    <script>
        $('.edit_class').click(function() {
            var id = this.id;
            var name = $(".name_" + id).html();
            var commodity = $(".commodity_" + id).attr('id');
            var brand = $(".brand_" + id).attr('id');
            var usage = $(".usage_" + id).html();
            var img = $(".img_" + id).attr('src');

            $('input[name=edit_id]').val(id);
            $('input[name=edit_name]').val(name);
            $('select[name=e_commodity_id]').val(commodity);
            $('select[name=e_brand_id]').val(brand);
            $('textarea[name=edit_usage]').html(usage);
            $('img[name=e_image]').attr('src', img);
        })

        $('.del_class').click(function() {
            var id = this.id;
            var name = $(".name_" + id).html();
            var brand = $(".brand_" + id).html();
            var commodity = $(".commodity_" + id).html();
            var usage = $(".usage_" + id).html();
            var img = $(".img_" + id).attr('src');

            $('input[name=del_id]').val(id);
            $('#del_name').text(name);
            $('#del_brand_name').text(brand);
            $('#del_commodity').text(commodity);
            $('#del_usage').text(usage);
            $('img[name=del_image]').attr('src', img);
        })
    </script>
    
    <script>
        $(function() {
                $('.pop').on('click', function() {
                    $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                    $('#imagemodal').modal('show');   
                });     
        });
      </script>


@endsection
