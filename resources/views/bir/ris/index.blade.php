@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>RIS</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <div class="box box-solid">
            <div class="box-header">
                <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#add_supply_modal"><i class="fa fa-plus"></i> Add</button>
            </div>
            <div class="box-body">
                <div class="panel">
                    <div class="box box-sm box-default box-solid collapsed-box">
                        <div class="box-header with-border">
                            <p class="no-margin"><i class="fa fa-filter"></i> Advanced Filters <small id="filter-notifier" class="label bg-blue blink"></small></p>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool advanced_filters_toggler" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body" style="display: none">
                            <form id="filter_form">
                                <div class="row">
                                    <div class="col-md-2 dt_filter-parent-div">
                                        <label>Fund Source:</label>
                                        <select name="funds"  class="form-control dt_filter filters">
                                            <option value="">Don't filter</option>
                                            {!! \App\Swep\Helpers\Helper::populateOptionsFromArray(\App\Swep\Helpers\Arrays::orsFunds()) !!}
                                        </select>
                                    </div>
                                    {{--                                    <div class="col-md-1 dt_filter-parent-div">--}}
                                    {{--                                        <label>Ref Book:</label>--}}
                                    {{--                                        <select name="ref_book"  class="form-control dt_filter filter_sex filters select22">--}}
                                    {{--                                            <option value="">Don't filter</option>--}}
                                    {{--                                            {!! \App\Swep\Helpers\Helper::populateOptionsFromArray(\App\Swep\Helpers\Arrays::orsBooks()) !!}--}}
                                    {{--                                        </select>--}}
                                    {{--                                    </div>--}}
                                    <div class="col-md-4 dt_filter-parent-div">
                                        <label>Applied Projects:</label>
                                        {!! \App\Swep\ViewHelpers\__form2::selectOnly('applied_projects',[
                                            'class' => 'select2_clear select2_pap_code dt_filter filters',
                                            'container_class' => 'select2-md',
                                            'options' => [],
                                            'select2_preSelected' => '' ,
                                        ],$data->pap_code ?? null) !!}
                                    </div>

                                    <div class="col-md-2 dt_filter-parent-div">
                                        <label>Ref Book:</label>
                                        <select name="ref_book"  class="form-control dt_filter filters">
                                            <option value="">Don't filter</option>
                                            {!! \App\Swep\Helpers\Helper::populateOptionsFromArray(\App\Swep\Helpers\Arrays::orsBooks()) !!}
                                        </select>
                                    </div>

                                    <div class="col-md-4 dt_filter-parent-div">
                                        <label>Payee:</label>
                                        @php
                                            $payees = \App\Models\Budget\ORS::query()
                                                    ->select('payee')
                                                    ->groupBy('payee')
                                                    ->orderBy('payee','asc')
                                                    ->get();
                                            $payees = $payees->pluck('payee')->map(function ($key,$value){
                                                return $key;
                                            });
                                        @endphp
                                        {!! \App\Swep\ViewHelpers\__form2::selectOnly('payee',[
                                            'class' => 'dt_filter filters',
                                            'container_class' => 'select2-md',
                                            'options' => \App\Swep\Helpers\Helper::flattenArray(array_values($payees->toArray())),
                                            'id' => 'select2_payee',
                                        ],'') !!}
                                    </div>


                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div id="supplies_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="supplies_table" style="width: 100%">
                        <thead>
                        <tr class="">
                            <th >RIS No.</th>
                            <th class="th-20">Division</th>
                            <th >Details</th>
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
        </div>

    </section>


@endsection


@section('modals')
    <div class="modal fade" id="add_supply_modal" tabindex="-1" role="dialog" aria-labelledby="add_supply_modal_label">
        <div class="modal-dialog" role="document">
            <form id="add_supply_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add article</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('stock_no',[
                                'label' => 'Stock No.',
                                'cols' => 4,
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::select('classification',[
                                'label' => 'Classification',
                                'cols' => 8,
                                'options' => \App\Swep\Helpers\Arrays::suppliesClassification(),
                            ]) !!}
                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('article',[
                                'label' => 'Article',
                                'cols' => 4,
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('description',[
                                'label' => 'Description',
                                'cols' => 8,
                            ]) !!}
                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('uom',[
                                'label' => 'Unit of Meas.',
                                'cols' => 4,
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('reordering_point',[
                                'label' => 'Reordering Pt.',
                                'cols' => 4,
                                'type' => 'number',
                            ]) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('stock',[
                                'label' => 'Stock No.',
                                'cols' => 4,
                                'type' => 'number',
                            ]) !!}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {!! \App\Swep\ViewHelpers\__html::blank_modal('show_ors_modal','lg') !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        //-----DATATABLES-----//
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable
        active = '{{(\Illuminate\Support\Facades\Request::has('active') && \Illuminate\Support\Facades\Request::get('active') != '') ? \Illuminate\Support\Facades\Request::get('active') : ''}}';
        supplies_tbl = $("#supplies_table").on('xhr.dt', function (e, settings, json, xhr){
            if(xhr.status > 500){
                alert('Error '+xhr.status+': '+xhr.responseJSON.message);
            }
        }).DataTable({
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "ajax" : '{{route('dashboard.ris.index')}}',
            "columns": [
                { "data": "ris_no" },
                { "data": "division" },
                { "data": "details" },
                { "data": "action"},

            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[
                {
                    "targets" : 3,
                    "class" : 'action4'
                },


            ],
            "stateSave": true,
            "stateDuration": 60 * 5,
            stateSaveCallback: function(settings,data) {
                console.log(settings);
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
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
                    $("#supplies_table_container").fadeIn(function () {
                        @if(request()->has('initiator') && request('initiator') == 'create')
                        introJs().start();
                        @endif
                    });
                    if(find != ''){
                        supplies_tbl.search(find).draw();
                        setTimeout(function(){
                            active = '';
                        },3000);
                        window.history.pushState({}, document.title, "/dashboard/ors");
                    }
                    if(active != ''){
                        toast('success','Data successfully updated','Success');
                        window.history.pushState({}, document.title, "/dashboard/ors");
                    }

                });
                @if(\Illuminate\Support\Facades\Request::get('toPage') != null && \Illuminate\Support\Facades\Request::get('mark') != null)
                setTimeout(function () {
                    supplies_tbl.page({{\Illuminate\Support\Facades\Request::get('toPage')}}).draw('page');
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
                // console.log(supplies_tbl.page.info().page);
                $("#supplies_table a[for='linkToEdit']").each(function () {
                    let orig_uri = $(this).attr('href');
                    $(this).attr('href',orig_uri+'?page='+supplies_tbl.page.info().page);
                });

                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="modal"]').tooltip();
                if(active != ''){
                    $("#supplies_table #"+active).addClass('success');
                }
            }
        })

        style_datatable("#supplies_table");

        $(".dt_filter").change(function () {
            filterDT(supplies_tbl);
        })

        //Need to press enter to search
        $('#supplies_table_filter input').unbind();
        $('#supplies_table_filter input').bind('keyup', function (e) {
            if (e.keyCode == 13) {
                supplies_tbl.search(this.value).draw();
            }
        });

        $("#add_supply_form").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.office_supplies.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    active = res.slug;
                    supplies_tbl.draw(false);
                    succeed(form,true,true);
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })




        $(".select2_pap_code").select2({
            ajax: {
                url: "{{route('dashboard.ajax.get','pap')}}",
            },
            placeholder: 'Select item',
        });
        $("#select2_payee").select2();
    </script>
@endsection