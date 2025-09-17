 <!-- ံHeader -->
 <nav class="main-header navbar menuList
    navbar-expand 
    shadow-sm
    d-flex justify-content-around" 
    style="z-index: auto; ">
    <!-- Menu -->
    <ul class="" style="margin-bottom: 0 !important">


        <li class="nav-item d-none d-sm-inline-block">
            <a href=" {{ route("products.index")}}" class="nav-link">
                <i class="fas fa-boxes" style="color: rgb(136, 32, 139)"></i>               
                Receive
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href=" {{ route("transfers.index")}}" class="nav-link">
            <i class="fas fa-dolly-flatbed mr-1" style="color: rgb(136, 32, 139);"></i>Transfer</a>
        </li>
        
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route("issues.index")}}" class="nav-link">
            <i class="fas fa-exclamation-circle mr-1" style="color: rgb(136, 32, 139);"></i>
                MR
            </a>
        </li>
        
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route("issue_returns.index")}}" class="nav-link">
           <i class="fas fa-exchange-alt mr-1" style="color: rgb(136, 32, 139);"></i>
                MRR
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route("supplier_returns.index")}}" class="nav-link">
            <i class="fas fa-share-square mr-1" style="color: rgb(136, 32, 139);"></i>Supplier Return</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route("instocks.index")}}" class="nav-link">
            <i class="fas fa-clipboard-check  mr-1" style="color: rgb(136, 32, 139);"></i>
            Instock</a>
        </li>

        <li class="nav-item dropdown d-none d-sm-inline-block">
            <a class="nav-link d-flex align-items-center " data-toggle="dropdown" href="#">
                <i class="fas fa-warehouse mr-1" style="color: rgb(136, 32, 139);"></i>
                Warehouse
                <i class="fa fa-light fa-caret-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-center mt-3">
                <a href="{{ route("warehouses.index")}}" class="dropdown-item">
                    <i class="fas fa-warehouse mr-1" style="color: rgb(136, 32, 139);"></i>
                    Warehouse
                </a>
                <a href="{{ route("shelves.index")}}" class="dropdown-item">
                    <i class="fas fa-swatchbook mr-1" style="color: rgb(136, 32, 139);"></i>
                    Shelf
                </a>
                <a href="{{ route("shelf_nums.index")}}" class="dropdown-item">
                    <i class="fas fa-swatchbook mr-1" style="color: rgb(136, 32, 139);"></i>
                    Shelf Number
                </a>
                <a href="{{ route("departments.index")}}" class="dropdown-item">
                    <i class="fas fa-store-alt mr-1" style="color: rgb(136, 32, 139);"></i>
                    Department
                </a>
            </div> 
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route("adjustments.index")}}" class="nav-link">
            <i class="fas fa-sliders-h mr-1" style="color: rgb(136, 32, 139);"></i>Adjustment</a>
        </li>

        @auth
            @if(auth()->user()->name === 'admin')
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('canceled.index') }}" class="nav-link">
                        <i class="fas fa-ban mr-1" style="color: rgb(136, 32, 139);"></i>
                        Canceled List
                    </a>
                </li>
            @endif
        @endauth


        <li class="nav-item dropdown d-none d-sm-inline-block">
            <a class="nav-link d-flex align-items-center " data-toggle="dropdown" href="#" >
                <i class="fas fa-tags  mr-1" style="color: rgb(136, 32, 139);"></i>
                Brand
                <i class="fa fa-light fa-caret-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-center mt-3">
                 <a href="{{ route("codes.index")}}"class="dropdown-item">
                    <i class="fas fa-barcode mr-1" style="color: rgb(136, 32, 139);">
                        
                    </i>
                    Code
                </a>
                <a href="{{ route("brands.index")}}"  class="dropdown-item">
                    <i class="fas fa-tags  mr-1" style="color: rgb(136, 32, 139);"></i>
                    Brand
                </a>
                <a href="{{ route("commodities.index")}}" class="dropdown-item">
                    <i class="fas fa-tag  mr-1" style="color: rgb(136, 32, 139);"></i>
                Commodity
                </a>
                <a href="{{ route("units.index")}}"class="dropdown-item">
                    <i class="fas fa-pencil-ruler mr-1" style="color: rgb(136, 32, 139);"></i>
                    Unit
                </a>
               
            </div> 
        </li>

        <li class="nav-item dropdown d-none d-sm-inline-block">
            <a class="nav-link d-flex align-items-center " data-toggle="dropdown" href="#">
                <i class="fas fa-user-cog mr-1" style="color: rgb(136, 32, 139);"></i>
                User
                <i class="fa fa-light fa-caret-down ml-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-center mt-3">
                <a href="{{ route("suppliers.index")}}" class="dropdown-item">
                    <i class="fas fa-user-tie  mr-1" style="color: rgb(136, 32, 139);"></i>
                    Supplier
                </a>
                <a href="{{ route("customers.index")}}" class="dropdown-item">
                    <i class="fas fa-users mr-1" style="color: rgb(136, 32, 139);"></i>
                    Customer
                </a>
                <a href="{{ route("users.index")}}" class="dropdown-item">
                    <i class="fas fa-user-cog mr-1" style="color: rgb(136, 32, 139);"></i>
                    User
                </a>
                <a href="{{ route("users.permissions")}}" class="dropdown-item">
                    <i class="fas fa-user-cog mr-1" style="color: rgb(136, 32, 139);"></i>
                    Permission
                </a>
            </div> 
        </li>

    </ul>

</nav>
<!-- / Header -->

