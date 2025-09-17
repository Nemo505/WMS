@extends('layouts.master')
@section('title', 'Code List')

@section('css')

@endsection

@section('buttons')

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
                                                @foreach ($code_lists as $code)
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
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>BarCode</th>
                                    <th>Brand</th>
                                    <th>Commodity</th>
                                    <th >Usage</th>
                                    <th>Created_By</th>
                                    <th>Updated_By</th>
                                    <th>Canceled_at</th>
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
                                       <td class="text-center">
                                            <a href="{{ route('codes.printBarcode', ['id' => $code->id]) }}" target="_blank">
                                                {!! DNS1D::getBarcodeSVG($code->barcode, 'C39+', 1, 55, 'black', false) !!}
                                                <div style="color: black; font-size: 12px;">
                                                    {{ $code->name }}
                                                </div>
                                            </a>
                                        </td>


                                        <td class="brand_{{ $code->id }}" id="{{ optional($brand_name)->id }}">{{ optional($brand_name)->name }}</td>
                                        <td class="commodity_{{ $code->id }}" id="{{ optional($commodity_name)->id }}">{{ optional($commodity_name)->name }}</td>
                                       
                                        <td class="usage_{{ $code->id }}" >{{ $code->usage }}</td>
                                        <td>{{ optional($c_user)->name }}</td>
                                        <td>{{ optional($u_user)->name }}</td>
                                        <td>{{ date('Y-m-d', strtotime($code->canceled_at)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($code->created_at)) }}</td>
                                        <td>{{ date('Y-m-d', strtotime($code->updated_at)) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-around">
                                                <a href="" data-toggle="modal" data-target="#edit-modal"
                                                    id="{{ $code->id }}" class="edit_class">
                                                    <i class="far fa-bookmark" style="color: rgb(221, 142, 40)"></i>
                                                </a>
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
                                    <th>BarCode</th>
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
                    
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item {{ $codes->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $codes->previousPageUrl() }}">&laquo;</a>
                        </li>
                        @php
                            $numAdjacent = 2; // Number of adjacent page links to display
                            $start = max(1, $codes->currentPage() - $numAdjacent);
                            $end = min($start + $numAdjacent * 2, $codes->lastPage());
                        @endphp
                        @if($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $codes->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif
                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i === $codes->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $codes->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                        @if($end < $codes->lastPage())
                            @if($end < $codes->lastPage() - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $codes->url($codes->lastPage()) }}">{{ $codes->lastPage() }}</a>
                            </li>
                        @endif
                        <li class="page-item {{ $codes->currentPage() === $codes->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $codes->nextPageUrl() }}">&raquo;</a>
                        </li>
                    </ul>
                    </div>
                    
                </div>
                <!-- /.card -->


            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->

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
        
        $(document).ready(function() {
            $('#c_brand').select2({
                dropdownParent: $('#create-modal')
            });
            $('#c_commodity').select2({
                dropdownParent: $('#create-modal')
            });
            
            $('#e_brand').select2({
                dropdownParent: $('#edit-modal')
            });
            $('#e_commodity').select2({
                dropdownParent: $('#edit-modal')
            });
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
            $('select[name=e_commodity_id]').val(commodity).trigger('change');
            $('select[name=e_brand_id]').val(brand).trigger('change');
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
