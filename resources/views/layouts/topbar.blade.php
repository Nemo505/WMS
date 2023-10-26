
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" >
      <!-- Left navbar links -->
      <ul class="navbar-nav ">
        
        <li class="nav-item d-none d-sm-inline-block logoContent">
          <a href="" class="nav-link">HanSeinThant</a>
        </li>
      </ul>
  
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">
          <div class="user-panel mr-3 d-flex" data-toggle="dropdown">
            <div class="image">
              <img src="../../assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="#" class="d-block " style="font-weight: 700; color:black">{{ Auth::user()->name ?? 'User' }}</a>
            </div>
          </div>

          <div class="dropdown-menu dropdown-menu-md dropdown-menu-center mt-2">
            <a href="{{ route("users.profile")}}" class="dropdown-item d-flex justify-content-around align-items-center">
              <i class="fas fa-user-circle " style="color: rgb(136, 135, 135)"></i>
              Profile
            </a>

            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
                class="dropdown-item d-flex justify-content-around align-items-center">
              <i class="fas fa-sign-out-alt" style="color: rgb(136, 135, 135)"></i>
                  LogOut

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                {{ csrf_field() }}
              </form>
            </a>
          </div> 

        </li>

      </ul>
    </nav>
    <!-- /.navbar -->
    
   
