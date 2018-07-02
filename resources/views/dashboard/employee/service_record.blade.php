@php
  $table_sessions = [ 
                      Session::get('EMPLOYEE_SR_UPDATE_SLUG'),
                    ];
@endphp

@extends('layouts.admin-master')

@section('content')
    
  <section class="content-header">
      <h1>Edit Employee Service Record</h1>
      <div class="pull-right" style="margin-top: -25px;">
      {!! HtmlHelper::back_button(['dashboard.employee.index']) !!}
    </div>
  </section>

  <section class="content" id="pjax-container" >


    {{-- Form --}}
    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form</h3>
          <div class="pull-right">
              <code>Fields with asterisks(*) are required</code>
          </div> 
        </div>

        <form data-pjax role="form" method="POST" autocomplete="off" action="{{ route('dashboard.employee.service_record_store', $employee->slug) }}">

          @csrf

          <div class="box-body">

            {!! FormHelper::textbox(
               '2', 'sequence_no', 'text', 'Sequence No. *', 'Sequence No.', old('sequence_no'), $errors->has('sequence_no'), $errors->first('sequence_no'), ''
            ) !!}

            {!! FormHelper::textbox(
               '5', 'date_from', 'text', 'Date From *', 'Date From', old('date_from'), $errors->has('date_from'), $errors->first('date_from'), ''
            ) !!}

            {!! FormHelper::textbox(
               '5', 'date_to', 'text', 'Date To *', 'Date To', old('date_to'), $errors->has('date_to'), $errors->first('date_to'), ''
            ) !!}

            <div class="col-md-12"></div>

            {!! FormHelper::textbox(
               '6', 'position', 'text', 'Position *', 'Position', old('position'), $errors->has('position'), $errors->first('position'), 'data-transform="uppercase"'
            ) !!}

            {!! FormHelper::textbox(
               '6', 'appointment_status', 'text', 'Appointment Status *', 'Appointment Status', old('appointment_status'), $errors->has('appointment_status'), $errors->first('appointment_status'), 'data-transform="uppercase"'
            ) !!}

            <div class="col-md-12"></div>

            {!! FormHelper::textbox_numeric(
              '8', 'salary', 'text', 'Salary *', 'Salary', old('salary'), $errors->has('salary'), $errors->first('salary'), ''
            ) !!}

            {!! FormHelper::textbox(
               '4', 'mode_of_payment', 'text', 'Mode of Payment', 'Mode of Payment', old('mode_of_payment'), $errors->has('mode_of_payment'), $errors->first('mode_of_payment'), ''
            ) !!}

            <div class="col-md-12"></div>

            {!! FormHelper::textbox(
               '4', 'station', 'text', 'Station', 'Station', old('station'), $errors->has('station'), $errors->first('station'), ''
            ) !!}

            {!! FormHelper::textbox(
               '4', 'gov_serve', 'text', 'Government Serve', 'Government Serve', old('gov_serve'), $errors->has('gov_serve'), $errors->first('gov_serve'), ''
            ) !!}

            {!! FormHelper::textbox(
               '4', 'psc_serve', 'text', 'PSC Serve', 'PSC Serve', old('psc_serve'), $errors->has('psc_serve'), $errors->first('psc_serve'), ''
            ) !!}

            <div class="col-md-12"></div>

            {!! FormHelper::textbox(
               '4', 'lwp', 'text', 'LWP', 'LWP', old('lwp'), $errors->has('lwp'), $errors->first('lwp'), ''
            ) !!}

            {!! FormHelper::textbox(
               '4', 'spdate', 'text', 'SP Date', 'SP Date', old('spdate'), $errors->has('spdate'), $errors->first('spdate'), ''
            ) !!}

            {!! FormHelper::textbox(
               '4', 'status', 'text', 'Status', 'Status', old('status'), $errors->has('status'), $errors->first('status'), ''
            ) !!}

            <div class="col-md-12"></div>

            {!! FormHelper::textbox(
               '12', 'remarks', 'text', 'Remarks', 'Remarks', old('remarks'), $errors->has('remarks'), $errors->first('remarks'), ''
            ) !!}


          </div>

          <div class="box-footer">
            <button type="submit" class="btn btn-default">Save</button>
          </div>

        </form>
      </div>
    </div>






    {{-- Table --}}
    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Service Records</h3> 
        </div>

        <div class="box-body">

          <table class="table table-bordered">
            <tr>
              <th>Seq #</th>
              <th>Date From</th>
              <th>Date To</th>
              <th>Position</th>
              <th>Appointment Status</th>
              <th>Salary</th>
              <th>Action</th>
            </tr>
            @foreach($employee->employeeServiceRecord as $data) 
              <tr {!! HtmlHelper::table_highlighter( $data->slug, $table_sessions) !!}>
                <td>{{ $data->sequence_no }}</td>
                <td>{{ $data->date_from }}</td>
                <td>{{ $data->date_to }}</td>
                <td>{{ $data->position }}</td>
                <td>{{ $data->appointment_status }}</td>
                <td>{{ number_format($data->salary, 2) .' / '. $data->mode_of_payment }}</td>
                <td>
                  <div class="btn-group">
                    <a href="#" id="sr_update_btn" es="{{ $data->slug }}" data-url="{{ route('dashboard.employee.service_record_update', [$employee->slug, $data->slug]) }}" class="btn btn-sm btn-default">
                      <i class="fa fa-pencil-square-o"></i>
                    </a>
                    <a href="#" id="sr_delete_btn" data-url="{{ route('dashboard.employee.service_record_destroy', $data->slug) }}" class="btn btn-sm btn-default">
                      <i class="fa  fa-trash-o"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </table>

          @if(count($employee->employeeServiceRecord) == 0)
            <div style="padding :5px;">
              <center><h4>No Records found!</h4></center>
            </div>
          @endif

        </div>

      </div>
    </div>

    

  </section>

@endsection





@section('modals')
  

  {{-- Delete --}}
  {!! HtmlHelper::modal_delete('sr_delete') !!}


  {{-- Update --}}
  <div class="modal fade bs-example-modal-lg" id="sr_update" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body" id="sr_update_body">
          <form data-pjax id="sr_update_form" method="POST" autocomplete="off">

            <div class="row">
                
              @csrf
              <input name="_method" value="PUT" type="hidden">

              {!! FormHelper::textbox(
               '2', 'sequence_no', 'text', 'Sequence No. *', 'Sequence No.', old('sequence_no'), $errors->has('sequence_no'), $errors->first('sequence_no'), ''
              ) !!}

              {!! FormHelper::textbox(
                 '5', 'date_from', 'text', 'Date From *', 'Date From', old('date_from'), $errors->has('date_from'), $errors->first('date_from'), ''
              ) !!}

              {!! FormHelper::textbox(
                 '5', 'date_to', 'text', 'Date To *', 'Date To', old('date_to'), $errors->has('date_to'), $errors->first('date_to'), ''
              ) !!}

              <div class="col-md-12"></div>

              {!! FormHelper::textbox(
                 '6', 'position', 'text', 'Position *', 'Position', old('position'), $errors->has('position'), $errors->first('position'), 'data-transform="uppercase"'
              ) !!}

              {!! FormHelper::textbox(
                 '6', 'appointment_status', 'text', 'Appointment Status *', 'Appointment Status', old('appointment_status'), $errors->has('appointment_status'), $errors->first('appointment_status'), 'data-transform="uppercase"'
              ) !!}

              <div class="col-md-12"></div>

              {!! FormHelper::textbox_numeric(
                '8', 'salary', 'text', 'Salary *', 'Salary', old('salary'), $errors->has('salary'), $errors->first('salary'), ''
              ) !!}

              {!! FormHelper::textbox(
                 '4', 'mode_of_payment', 'text', 'Mode of Payment', 'Mode of Payment', old('mode_of_payment'), $errors->has('mode_of_payment'), $errors->first('mode_of_payment'), ''
              ) !!}

              <div class="col-md-12"></div>

              {!! FormHelper::textbox(
                 '4', 'station', 'text', 'Station', 'Station', old('station'), $errors->has('station'), $errors->first('station'), ''
              ) !!}

              {!! FormHelper::textbox(
                 '4', 'gov_serve', 'text', 'Government Serve', 'Government Serve', old('gov_serve'), $errors->has('gov_serve'), $errors->first('gov_serve'), ''
              ) !!}

              {!! FormHelper::textbox(
                 '4', 'psc_serve', 'text', 'PSC Serve', 'PSC Serve', old('psc_serve'), $errors->has('psc_serve'), $errors->first('psc_serve'), ''
              ) !!}

              <div class="col-md-12"></div>

              {!! FormHelper::textbox(
                 '4', 'lwp', 'text', 'LWP', 'LWP', old('lwp'), $errors->has('lwp'), $errors->first('lwp'), ''
              ) !!}

              {!! FormHelper::textbox(
                 '4', 'spdate', 'text', 'SP Date', 'SP Date', old('spdate'), $errors->has('spdate'), $errors->first('spdate'), ''
              ) !!}

              {!! FormHelper::textbox(
                 '4', 'status', 'text', 'Status', 'Status', old('status'), $errors->has('status'), $errors->first('status'), ''
              ) !!}

              <div class="col-md-12"></div>

              {!! FormHelper::textbox(
                 '12', 'remarks', 'text', 'Remarks', 'Remarks', old('remarks'), $errors->has('remarks'), $errors->first('remarks'), ''
              ) !!}

            </div>

        </div>
        <div class="modal-footer">
          <button class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </form>
      </div>
    </div>
  </div>


@endsection 





@section('scripts')

  <script type="text/javascript">

    // Delete Button Action
    $(document).on("click", "#sr_delete_btn", function () {
      $("#sr_delete").modal("show");
      $("#delete_body #form").attr("action", $(this).data("url"));
      $("#delete_body #form").attr("data-pjax", '');
      $(this).val("");
    });


    // Delete Form Action
    $(document).on("submit", "#delete_body #form", function () {
        $('#sr_delete').delay(100).fadeOut(100);
       setTimeout(function(){
          $('#sr_delete').modal("hide");
       }, 200);
    });


    // Update Button Action
    $(document).on("click", "#sr_update_btn", function () {

      var slug = $(this).attr("es");

      $("#sr_update").modal("show");
      $("#sr_update_body #sr_update_form").attr("action", $(this).data("url"));

      // Price Format
      $(".priceformat").priceFormat({
          prefix: "",
          thousandsSeparator: ",",
          clearOnEmpty: true,
          allowNegative: true
      });

      $.ajax({
        headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
          url: "/api/employee/serviceRecord/"+slug+"/edit",
          type: "GET",
          dataType: "json",
          success:function(data) {       
            
            $.each(data, function(key, value) {
              $("#sr_update_form #sequence_no").val(value.sequence_no);
              $("#sr_update_form #date_from").val(value.date_from);
              $("#sr_update_form #date_to").val(value.date_to);
              $("#sr_update_form #position").val(value.position);
              $("#sr_update_form #appointment_status").val(value.appointment_status);
              $("#sr_update_form #salary").val(value.salary);
              $("#sr_update_form #mode_of_payment").val(value.mode_of_payment);
              $("#sr_update_form #station").val(value.station);
              $("#sr_update_form #gov_serve").val(value.gov_serve);
              $("#sr_update_form #psc_serve").val(value.psc_serve);
              $("#sr_update_form #lwp").val(value.lwp);
              $("#sr_update_form #spdate").val(value.spdate);
              $("#sr_update_form #status").val(value.status);
              $("#sr_update_form #remarks").val(value.remarks);
            });

          }
      });

    });

    // Delete Form Action
    $(document).on("submit", "#sr_update_body #sr_update_form", function () {
        $('#sr_update').delay(100).fadeOut(100);
       setTimeout(function(){
          $('#sr_update').modal("hide");
       }, 200);
    });

  </script> 
    
@endsection