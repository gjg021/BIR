@extends('layouts.admin-master')

@section('content')
    
      <section class="content-header">
          <h1>User List</h1>
      </section>

      <section class="content">
        
        {{-- Form Start --}}
        <form data-pjax class="form" id="filter_form" method="GET" autocomplete="off" action="{{ route('dashboard.user.index') }}">

        {{-- Advance Filters --}}
        {!! HtmlHelper::filter_open() !!}

          {!! FormHelper::select_static_for_filter(
            '2', 'online', 'Login Status', old('online'), ['Online' => 'true', 'Offline' => 'false'], 'submit_user_filter', ''
          ) !!}

          {!! FormHelper::select_static_for_filter(
            '2', 'active', 'User Status', old('active'), ['Active' => 'true', 'Inactive' => 'false'], 'submit_user_filter', ''
          ) !!}

        {!! HtmlHelper::filter_close() !!}


        <div class="box" id="pjax-container">

          {{-- Table Search --}}        
          <div class="box-header with-border">
            {!! HtmlHelper::table_search(route('dashboard.user.index')) !!}
          </div>

        {{-- Form End --}}  
        </form>

          {{-- Table Grid --}}        
          <div class="box-body no-padding">
            <table class="table table-bordered">
              <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Online</th>
                <th>Active</th>
                <th style="width: 150px">Action</th>
              </tr>
              @foreach($users as $data) 
                <tr {!! Session::get('USER_UPDATE_SUCCESS_SLUG') == $data->slug || Session::get('USER_RESET_PASSWORD_SLUG') == $data->slug ? 'style="background-color: #b3e5fc;"' : '' !!} >
                  <td>{{ $data->username }}</td>
                  <td>{{ $data->fullname }}</td>
                  <td>{!! $data->is_online == 1 ? '<span class="badge bg-green"><i class="fa fa-check "></i></span>' : '<span class="badge bg-red"><i class="fa fa-times "></i></span>' !!}</td>
                  <td>{!! $data->is_active == 1 ? '<span class="badge bg-green"><i class="fa fa-check "></i></span>' : '<span class="badge bg-red"><i class="fa fa-times "></i></span>' !!}</td>
                  <td> 
                    <select id="action" class="form-control input-sm">
                      <option value="">Select</option>
                      <option data-type="1" data-url="{{ route('dashboard.user.show', $data->slug) }}">Details</option>
                      <option data-type="1" data-url="{{ route('dashboard.user.edit', $data->slug) }}">Edit</option>
                      <option data-type="0" data-action="delete" data-url="{{ route('dashboard.user.destroy', $data->slug) }}">Delete</option>
                      
                      @if($data->is_active == 1 && $data->is_online == 1)
                        <option data-type="0" data-action="logout" data-url="{{ route('dashboard.user.logout', $data->slug) }}">Logout</option>
                      @endif 
                      
                      @if($data->is_active == 0)
                        <option data-type="0" data-action="activate" data-url="{{ route('dashboard.user.activate', $data->slug) }}">Activate</option>
                      @else
                        <option data-type="0" data-action="deactivate" data-url="{{ route('dashboard.user.deactivate', $data->slug) }}">Deactivate</option>
                      @endif

                      <option data-type="1" data-action="reset_password" data-url="{{ route('dashboard.user.reset_password', $data->slug) }}">Reset Password</option>

                    </select>
                  </td>
                </tr>
                @endforeach
              </table>
          </div>

          @if($users->isEmpty())
            <div style="padding :5px;">
              <center><h4>No Records found!</h4></center>
            </div>
          @endif

          <div class="box-footer">
            <strong>Displaying {{ $users->firstItem() > 0 ? $users->firstItem() : 0 }} - {{ $users->lastItem() > 0 ? $users->lastItem() : 0 }} out of {{ $users->total()}} Records</strong>
            {!! $users->appends([
                  'q'=>Input::get('q'), 
                  'online' => Input::get('online'), 
                  'active' => Input::get('active'),
                ])->render('vendor.pagination.bootstrap-4')
            !!}
          </div>

        </div>

    </section>

    <form id="from_user_logout" method="POST" style="display: none;">
      @csrf
    </form>

    <form id="from_user_activate" method="POST" style="display: none;">
      @csrf
    </form>

    <form id="from_user_deactivate" method="POST" style="display: none;">
      @csrf
    </form>

@endsection


@section('modals')

  {!! HtmlHelper::modal_delete('user_delete') !!}

@endsection 


@section('scripts')

  <script type="text/javascript">

    {{-- CALL CONFIRM DELETE MODAL --}}
    {!! JSHelper::modal_confirm_delete_caller('user_delete') !!}


    {{-- CALL LOGOUT FORM --}}
    {!! JSHelper::form_submitter_via_action('logout', 'from_user_logout') !!}


    {{-- CALL ACTIVATE FORM --}}
    {!! JSHelper::form_submitter_via_action('activate', 'from_user_activate') !!}


    {{-- CALL DEACTIVATE FORM --}}
    {!! JSHelper::form_submitter_via_action('deactivate', 'from_user_deactivate') !!}


    {{-- FORM VARIABLES RULE --}}
    {!! JSHelper::table_action_rule() !!}


    {{-- UPDATE TOAST --}}
    @if(Session::has('USER_UPDATE_SUCCESS'))
      {!! JSHelper::toast(Session::get('USER_UPDATE_SUCCESS')) !!}
    @endif


    {{-- DELETE TOAST --}}
    @if(Session::has('USER_DELETE_SUCCESS'))
      {!! JSHelper::toast(Session::get('USER_DELETE_SUCCESS')) !!}
    @endif


    {{-- LOGOUT TOAST --}}
    @if(Session::has('USER_LOGOUT_SUCCESS'))
      {!! JSHelper::toast(Session::get('USER_LOGOUT_SUCCESS')) !!}
    @endif


    {{-- ACTIVATE TOAST --}}
    @if(Session::has('USER_ACTIVATE_SUCCESS'))
      {!! JSHelper::toast(Session::get('USER_ACTIVATE_SUCCESS')) !!}
    @endif


    {{-- DEACTIVATE TOAST --}}
    @if(Session::has('USER_DEACTIVATE_SUCCESS'))
        {!! JSHelper::toast(Session::get('USER_DEACTIVATE_SUCCESS')) !!}
    @endif


    {{-- RESET PASSWORD SUCCESS TOAST --}}
    @if(Session::has('USER_RESET_PASSWORD_SUCCESS'))
        {!! JSHelper::toast(Session::get('USER_RESET_PASSWORD_SUCCESS')) !!}
    @endif

  </script>
    
@endsection