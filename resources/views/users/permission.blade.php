@extends('layouts.master')
@section('title', 'Permission List')

@section('css')
 
@endsection

@section('buttons')
@endsection

@section('content')
 <!-- Main content -->
        
    <div class="card card-primary">
        <!-- form start -->
        <form action="{{ route('users.permission_store')}}" method="post" >
        @csrf
            <div class="card-body">
                <div class="row mb-3">
                    
                    <div class="col-md-6 col-12">
                        <label class="control-label">User Name</label>
                        <div class="mb-3">
                            <select class="form-control select2" required name="user" id="user">
                                <option disabled selected>Select</option>
                                @foreach ($users as $user)
                                    @if (isset($_REQUEST['user_id']))
                                        @if ($user->id == $_REQUEST['user_id'])
                                            <option selected value="{{ $user->id }}">{{$user->name}}</option>
                                        @else
                                            <option value="{{ $user->id }}">{{$user->name}}</option>
                                        @endif
                                    @else
                                        <option value="{{ $user->id}}">{{ $user->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <div class="form-check mx-3 my-2">
                    <input type="checkbox" class="form-check-input" id="checkAll">
                    <label class="form-check-label" for="checkAll">Check all</label>
                </div>

                <div class="row mx-auto my-3">
                    <div class="col-3 mx-auto my-3">

                        <label class="control-label">Product</label>

                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'product')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" id="product" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Transfer</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'transfer')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" id="transfer" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Issue(MR)</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'mr')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" id="mr" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Issue Return(MRR)</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'mrr')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" id="mrr" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Supplier Return</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'supplier_return')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Warehouse</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'warehouse')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Shelf</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'shelf')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Shelf Number</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'shelf_number')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Shelf Number</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'shelf_number')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Adjustment</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'adjustment')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Brand</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'brand')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Commodity</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'commodity')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Unit</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'unit')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Code</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'code')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Supplier</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'supplier')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Customer</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'customer')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">User</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'user')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Permission</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'permission')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">Instock</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'instock')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-3 mx-auto my-3">
                        <label class="control-label">BarCode Scanner</label>
                        @foreach ($permissions as $permission)
                            @if ($permission->group == 'barcode')
                                <div class=" form-check mx-3 my-2">
                                    <input type="checkbox" class="form-check-input" name="{{$permission->name}}">
                                    <label class="form-check-label" for="permission">{{$permission->display_name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
            <div class="card-footer ">
                @php
                    $check_create = Auth::user()->hasPermissionTo('create_permission');

                @endphp
                @if ($check_create == true)
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                @endif
            </div>
        </form>
        
    </div>
    <!-- /.card -->

@endsection
@section('scripts')

<script>
  $( "#user" ).ready(function() {
      $("#user").select2();
  });
</script>

<script>
    $('#user').on('change', function() {
        var user_id = this.value;
        var current_url = "{{asset('users/permissions')}}";
        var url = current_url + "?user_id=" + user_id;
        window.location.href = url;

    });

    //permission list under user
    var permission_names = '<?php echo $permission_names ?? null ?>'
    if (permission_names) {
        
        $.each(JSON.parse(permission_names), function(key, value) {

            var checked = $(`input[name="${value}"]`).attr("name");
            if (checked == value) {
                $(`input[name="${value}"]`).prop('checked', true);
            }

        });
    }
</script>

<script>
    $('#checkAll').click(function(e) {
        $("input:checkbox").prop('checked',$(this).is(':checked'))
    });
</script>

@endsection