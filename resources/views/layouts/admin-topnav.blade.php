
<header class="main-header">
  <a href="#" class="logo">
    <span class="logo-mini">A</span>
    <span class="logo-lg"><b>AFD</b></span>
  </a>
  @php
    $sa = \App\Models\SuSettings::query()->where('setting','=','SERVER_ADDR')->first();
    if(empty($sa)){
      $server_address = '';
    }else{
      $server_address = $sa->string_value;
    }

    $userName = Helper::getUserName();
  @endphp

  <nav class="navbar navbar-static-top" @if($_SERVER['SERVER_ADDR'] != $server_address)style="background-color: #054629" @endif>
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>


    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        @if($_SERVER['SERVER_ADDR'] != $server_address)

        @endif
          <li class="dropdown tasks-menu">
            <a href="http://{{$_SERVER['SERVER_NAME']}}/" >
              <i class="fa fa-home"></i> Lobby Page
            </a>
          </li>
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            @if(!empty(Auth::user()->employee))
            <img src="{{asset('images/EmployeePics/1by1Low/'.Auth::user()->employee->employee_no.'.jpg')}}" class="user-image" alt="User Image">
            @else
            <img src="{{asset('images/avatar.jpeg')}}" class="user-image" alt="User Image">
            @endif
            @if(Auth::check())
              {!! strtoupper($userName['firstname']) !!}
            @endif
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              @if(!empty(Auth::user()->employee))
                <img src="{{asset('images/EmployeePics/1by1Low/'.Auth::user()->employee->employee_no.'.jpg')}}" class="img-circle" alt="User Image">
              @else
                <img src="{{asset('images/avatar.jpeg')}}" class="img-circle" alt="User Image">
              @endif


              <p>
                @if(Auth::check())
                  {!! strtoupper($userName['firstname']) !!}  {!! strtoupper($userName['lastname']) !!}
                  <small>{!! strtoupper($userName['position']) !!}</small>
                @endif
                
              </p>
            </li>

            <li class="user-footer">
              <div class="pull-left">
                <a href="{{ route('dashboard.profile.details') }}" class="btn btn-default btn-flat">Profile</a>
              </div>
              <div class="pull-right">
                <a  href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();" class="btn btn-default btn-flat">Sign out</a>
              </div>
              <form id="frm-logout" action="{{ route('auth.logout') }}?portal={{\Illuminate\Support\Facades\Auth::user()->portal}}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>