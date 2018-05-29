@php

  $date_started = DataTypeHelper::date_out($account->date_started);
  $projected_date_end = DataTypeHelper::date_out($account->projected_date_end);

@endphp

@extends('layouts.admin-master')

@section('content')
    
  <section class="content-header">
      <h1>Edit Account</h1>
      <div class="pull-right" style="margin-top: -25px;">
        {!! HtmlHelper::back_button(['dashboard.account.index']) !!}
      </div>
  </section>

  <section class="content">

    <div class="box">
    
      <div class="box-header with-border">
        <h3 class="box-title">Form</h3>
        <div class="pull-right">
            <code>Fields with asterisks(*) are required</code>
        </div> 
      </div>
      
      <form role="form" method="POST" autocomplete="off" action="{{ route('dashboard.account.update', $account->slug) }}">

        <div class="box-body">
          
          <input name="_method" value="PUT" type="hidden">
      
          @csrf

          {!! FormHelper::select_dynamic(
              '3', 'department_id', 'Department *', old('department_id') ? old('department_id') : $account->department_id, $global_departments_all, 'department_id', 'name', $errors->has('department_id'), $errors->first('department_id'), 'select2', ''
          ) !!}

          <input type="hidden" name="department_name" id="department_name" value="{{ old('department_name') ? old('department_name') : $account->department_name }}">

          {!! FormHelper::textbox(
             '3', 'account_code', 'text', 'Account Code *', 'Account Code', old('account_code') ? old('account_code') : $account->account_code , $errors->has('account_code'), $errors->first('account_code'), ''
          ) !!}

          {!! FormHelper::textbox(
             '6', 'description', 'text', 'Description *', 'Description', old('description') ? old('description') : $account->description, $errors->has('description'), $errors->first('description'), ''
          ) !!}

          {!! FormHelper::textbox_numeric(
            '3', 'mooe', 'text', 'MOOE:', 'MOOE', old('mooe') ? old('mooe') : $account->mooe, $errors->has('mooe'), $errors->first('mooe'), ''
          ) !!}

          {!! FormHelper::textbox_numeric(
            '3', 'co', 'text', 'CO:', 'CO', old('co') ? old('co') : $account->co, $errors->has('co'), $errors->first('co'), ''
          ) !!}

          {!! FormHelper::datepicker('3', 'date_started',  'Date Started', old('date_started') ? old('date_started') : $date_started, '', '') !!}

          {!! FormHelper::datepicker('3', 'projected_date_end',  'Projected Date End', old('projected_date_end') ? old('projected_date_end') : $projected_date_end, '', '') !!}

          {!! FormHelper::textbox(
             '6', 'project_in_charge', 'text', 'Project Incharge', 'Project Incharge', old('project_in_charge') ? old('project_in_charge') : $account->project_in_charge, $errors->has('project_in_charge'), $errors->first('project_in_charge'), 'data-transform="uppercase"'
          ) !!}

        </div>

        <div class="box-footer">
          <button type="submit" class="btn btn-default">Save</button>
        </div>

      </form>

    </div>

  </section>

@endsection


@section('scripts')

  <script type="text/javascript">

    {!! JSHelper::ajax_select_to_input('department_id', 'department_name', '/api/textbox_response_departmentName_from_departmentId/', 'name') !!}

  </script> 
    
@endsection