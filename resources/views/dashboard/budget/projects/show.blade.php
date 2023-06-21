@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>{{$pap->pap_code}} <i class="fa fa-caret-right"></i> <span style="font-size: 18px">{{$pap->pap_title}}</span></h1>
        <hr class="no-margin">
        <small>{{$pap->pap_desc}}</small>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <dl>
                            <dt>Responsibility Center:</dt>
                            <dd>{{$pap->responsibilityCenter->desc ?? 'N/A'}}</dd>
                        </dl>
                    </div>
                    <div class="col-md-1">
                        <dl>
                            <dt>Budget Type:</dt>
                            <dd>{{$pap->budget_type}}</dd>
                        </dl>
                    </div>
                    <div class="col-md-1">
                        <dl>
                            <dt>PS:</dt>
                            <dd>{{\App\Swep\Helpers\Helper::toNumber($pap->ps,2,'0.00')}}</dd>
                        </dl>
                    </div>
                    <div class="col-md-2">
                        <dl>
                            <dt>CO:</dt>
                            <dd><table style="width: 100%;">
                                    <tr>
                                        <td>Amount: </td>
                                        <td class="text-right text-strong" style="font-family: Consolas">{{\App\Swep\Helpers\Helper::toNumber($pap->co,2,'0.00')}}</td>
                                    </tr>
                                    <tr>
                                        <td>Utilized: </td>
                                        <td class="text-right text-strong" style="font-family: Consolas">{{\App\Swep\Helpers\Helper::toNumber($pap->orsAppliedProjects->sum('co'),2,'0.00')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="b-top">Balance: </td>
                                        <td class="text-right text-strong b-top" style="font-family: Consolas">{{\App\Swep\Helpers\Helper::toNumber($pap->co - $pap->orsAppliedProjects->sum('co'),2,'0.00')}}</td>
                                    </tr>
                                </table></dd>
                        </dl>
                    </div>
                    <div class="col-md-2">
                        <dl>
                            <dt>MOOE:</dt>
                            <dd>
                                <table style="width: 100%;">
                                    <tr>
                                        <td>Amount: </td>
                                        <td class="text-right text-strong" style="font-family: Consolas">{{\App\Swep\Helpers\Helper::toNumber($pap->mooe,2,'0.00')}}</td>
                                    </tr>
                                    <tr>
                                        <td>Utilized: </td>
                                        <td class="text-right text-strong" style="font-family: Consolas">{{\App\Swep\Helpers\Helper::toNumber($pap->orsAppliedProjects->sum('mooe'),2,'0.00')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="b-top">Balance: </td>
                                        <td class="text-right text-strong b-top" style="font-family: Consolas">{{\App\Swep\Helpers\Helper::toNumber($pap->mooe - $pap->orsAppliedProjects->sum('mooe'),2,'0.00')}}</td>
                                    </tr>
                                </table>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>


        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">ORS</a></li>
{{--                <li><a href="#tab_2" data-toggle="tab">Tab 2</a></li>--}}
{{--                <li><a href="#tab_3" data-toggle="tab">Tab 3</a></li>--}}
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div id="ors_table_container" style="display: none">
                        <table class="table table-bordered table-striped table-hover" id="ors_table" style="width: 100%">
                            <thead>
                            <tr class="">
                                <th >ORS No.</th>
                                <th class="th-20">Date</th>
                                <th class="th-20">Payee</th>
                                <th >Particulars</th>
                                <th >Applied Projects</th>
                                <th >Amount</th>
                                <th >Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="tbl_loader">
                        <center>
                            <img style="width: 100px" src="{{asset('images/loader.gif')}}">
                        </center>
                    </div>
                </div>

                <div class="tab-pane" id="tab_2">
                    The European languages are members of the same family. Their separate existence is a myth.
                    For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                    in their grammar, their pronunciation and their most common words. Everyone realizes why a
                    new common language would be desirable: one could refuse to pay expensive translators. To
                    achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                    words. If several languages coalesce, the grammar of the resulting language is more simple
                    and regular than that of the individual languages.
                </div>

                <div class="tab-pane" id="tab_3">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                    sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                    like Aldus PageMaker including versions of Lorem Ipsum.
                </div>

            </div>

        </div>

    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        //-----DATATABLES-----//
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable
        active = '';
        ors_tbl = $("#ors_table").on('xhr.dt', function (e, settings, json, xhr){
            if(xhr.status > 500){
                alert('Error '+xhr.status+': '+xhr.responseJSON.message);
            }
        }).DataTable({
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "ajax" : '{{route('dashboard.projects.show',$pap->slug)}}',
            "columns": [
                { "data": "ors_no" },
                { "data": "ors_date" },
                { "data": "payee" },
                { "data": "particulars" },
                { "data": "details" },
                { "data": "amount"},
                { "data": "action"},

            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[
                {
                    "targets" : 6,
                    "orderable" : false,
                    "class" : 'action2'
                },
                {
                    'targets' : 0,
                    "class" : 'w-10p',
                },
                {
                    'targets' : [1],
                    'class' : 'w-8p',
                },
                {
                    'targets' : 5,
                    'class' : 'w-8p text-right',
                },
                {
                    'targets' : 4,
                    'class' : 'w-16p',
                },

            ],
            "order" : [[1, 'desc'],[2,'desc']],
            "responsive": true,
            "initComplete": function( settings, json ) {
                // console.log(settings);
                setTimeout(function () {
                    $("#filter_form select[name='is_active']").val('ACTIVE');
                    $("#filter_form select[name='is_active']").trigger('change');
                },100);

                setTimeout(function () {
                    // $('a[href="#advanced_filters"]').trigger('click');
                    // $('.advanced_filters_toggler').trigger('click');
                },1000);

                $('#tbl_loader').fadeOut(function(){
                    $("#ors_table_container").fadeIn(function () {
                        @if(request()->has('initiator') && request('initiator') == 'create')
                        introJs().start();
                        @endif
                    });
                    if(find != ''){
                        ors_tbl.search(find).draw();
                        setTimeout(function(){
                            active = '';
                        },3000);
                        // window.history.pushState({}, document.title, "/dashboard/employee");
                    }

                });
                @if(\Illuminate\Support\Facades\Request::get('toPage') != null && \Illuminate\Support\Facades\Request::get('mark') != null)
                setTimeout(function () {
                    ors_tbl.page({{\Illuminate\Support\Facades\Request::get('toPage')}}).draw('page');
                    active = '{{\Illuminate\Support\Facades\Request::get("mark")}}';
                    notify('Employee successfully updated.');
                    // window.history.pushState({}, document.title, "/dashboard/employee");
                },700);
                @endif
            },
            "language":
                {
                    "processing": "<center><img style='width: 70px' src='{{asset("images/loader.gif")}}'></center>",
                },
            "drawCallback": function(settings){
                // console.log(ors_tbl.page.info().page);
                $("#ors_table a[for='linkToEdit']").each(function () {
                    let orig_uri = $(this).attr('href');
                    $(this).attr('href',orig_uri+'?page='+ors_tbl.page.info().page);
                });
                $("#totalCo").html(settings.json.totalCo);
                $("#totalMooe").html(settings.json.totalMooe);
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="modal"]').tooltip();
                if(active != ''){
                    $("#ors_table #"+active).addClass('success');
                }
            }
        })

        style_datatable("#ors_table");
    </script>
@endsection