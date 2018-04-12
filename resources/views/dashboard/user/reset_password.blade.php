@extends('layouts.admin-master')

@section('content')

<section class="content-header">
  <h1>User Reset Password</h1>
  <div class="pull-right" style="margin-top: -25px;">
    <a href="{{ url()->previous() }}" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back</a>
  </div>
</section>

<section class="content">
            
    <div class="box">
        
      <div class="box-header with-border">
        <h3 class="box-title">Form</h3>
      </div>
      
      <form class="form-horizontal">

        <div class="box-body">

          @if(Session::has('USER_RESET_PASSWORD_CONFIRMATION_FAIL'))
            {!! HtmlHelper::alert('danger', '<i class="icon fa fa-ban"></i> Alert!', Session::get('USER_RESET_PASSWORD_CONFIRMATION_FAIL')) !!}
          @endif

          @if(Session::has('USER_RESET_PASSWORD_OWN_ACCOUNT_FAIL'))
            {!! HtmlHelper::alert('danger', '<i class="icon fa fa-ban"></i> Alert!', Session::get('USER_RESET_PASSWORD_OWN_ACCOUNT_FAIL')) !!}
          @endif

          <div class="col-md-11">
                  
              @csrf    

              {!! FormHelper::password_inline(
                  'password', 'New Password', 'New Password', $errors->has('password'), $errors->first('password'), ''
              ) !!}

              {!! FormHelper::password_inline(
                  'password_confirmation', 'Confirm Password', 'Confirm Password', '', '', ''
              ) !!}

          </div>

        </div>

      </form>

      <div class="box-footer">
        <button class="btn btn-success" id="reset_button">Reset</button>
      </div>

    </div>

</section>

@endsection




    
@section('modals')

  {{-- USER RESET CONFIRMATION MODAL --}}  
  <div class="modal fade" id="user_reset_password" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title"><i class="fa fa-key"></i> &nbsp;User Confirmation</h4>
        </div>
        <div class="modal-body">
          <form id="reset_password_form" class="form-horizontal" method="POST" autocomplete="off" action="{{ route('dashboard.user.reset_password_post', $user->slug) }}">
            @csrf
            <p style="font-size: 17px;">Confirm first your identity before resetting someone's password!</p><br>
            <input id="password_in_modal" type="hidden" name="password" value="">
            <input id="password_confirmation_in_modal" type="hidden" name="password_confirmation" value="">

            {!! FormHelper::textbox_inline(
                'username', 'text', 'Username', 'Username', old('username'), $errors->has('username'), $errors->first('username'), ''
            ) !!}

            {!! FormHelper::password_inline(
                'user_password', 'Password', 'Password', $errors->has('user_password'), $errors->first('user_password'), ''
            ) !!}

        </div>

        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Sign-in</button>
        </div>

        </form>

      </div>
    </div>
  </div>

@endsection


@section('scripts')

  <script type="text/javascript">

    {!! JSHelper::show_password('password', 'show_password') !!}
    {!! JSHelper::show_password('password_confirmation', 'show_password_confirmation') !!}
    {!! JSHelper::show_password('user_password', 'show_user_password') !!}

    {{-- CALL RESET PASSWORD CONFIRMATION MODAL --}}
    $(document).on("click", "#reset_button", function () {
      var password = $("#password").val();
      var password_confirmation = $("#password_confirmation").val();
      $("#user_reset_password").modal("show");
      $("#reset_password_form #password_in_modal").attr("value", password);
      $("#reset_password_form #password_confirmation_in_modal").attr("value", password_confirmation);
    });

  </script>
    
@endsection