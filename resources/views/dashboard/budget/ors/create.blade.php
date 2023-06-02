@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Obligation and Request Status</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-success">
            <form id="ors_form">
                <div class="box-header with-border">
                    <h3 class="box-title">ORS</h3>
                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-check"></i> Save</button>
                </div>
                <div class="box-body">

                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::select('funds',[
                                'label' => 'Funds:',
                                'cols' => 1,
                                'options' => \App\Swep\Helpers\Arrays::orsFunds(),
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('ors_date',[
                                'label' => 'Date:',
                                'cols' => 2,
                                'type' => 'date',
                            ],Carbon::now()->format('Y-m-d')) !!}
                            {!! \App\Swep\ViewHelpers\__form2::textbox('ors_no',[
                                'label' => 'ORS No:',
                                'cols' => 2,
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('payee',[
                                'label' => 'Payee:',
                                'cols' => 4,
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('office',[
                                'label' => 'Office:',
                                'cols' => 3,
                            ]) !!}


                        </div>
                        <div class="row">
                            {!! \App\Swep\ViewHelpers\__form2::textbox('address',[
                                'label' => 'Address:',
                                'cols' => 3,
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::select('ref_book',[
                                'label' => 'Book:',
                                'cols' => 1,
                                'options' => \App\Swep\Helpers\Arrays::orsBooks(),
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('ref_doc',[
                                'label' => 'Document Ref No:',
                                'cols' => 2,
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('particulars',[
                                'label' => 'Remarks:',
                                'cols' => 4,
                            ]) !!}

                            {!! \App\Swep\ViewHelpers\__form2::textbox('amount',[
                                'label' => 'Amount:',
                                'cols' => 2,
                                'class' => 'text-right autonum'
                            ]) !!}
                        </div>

{{--                        <p class="page-header-sm text-info" style="margin-bottom: 0px;background-color: #00a65a;border-bottom: 1px solid #cedbe1;border-radius: 5px;text-align: center; color: white">--}}
{{--                            CERTIFICATION--}}
{{--                        </p>--}}
                        <div class="row">
                            <div class="col-md-6">
                                <p class="page-header-sm text-info" style="border-bottom: 1px solid #cedbe1">
                                    Certified By
                                </p>
                                <div class="row">
                                    {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by',[
                                        'label' => 'Certified by:',
                                        'cols' => 6,
                                    ]) !!}
                                    {!! \App\Swep\ViewHelpers\__form2::textbox('certified_by_position',[
                                        'label' => 'Position:',
                                        'cols' => 6,
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <p class="page-header-sm text-info" style="border-bottom: 1px solid #cedbe1">
                                    Budget Certification
                                </p>
                                <div class="row">
                                    {!! \App\Swep\ViewHelpers\__form2::textbox('certified_budget_by',[
                                        'label' => 'Budget Cert.:',
                                        'cols' => 6,
                                    ]) !!}
                                    {!! \App\Swep\ViewHelpers\__form2::textbox('certified_budget_by_position',[
                                        'label' => 'Position:',
                                        'cols' => 6,
                                    ]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">Account Entries</a></li>
                                <li><a href="#tab_2" data-toggle="tab">Applied Projects</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <fieldset id="account_entries_fieldset">
                                        <button type="button" onclick="totalAccountEntries()" hidden>SUM</button>
                                        <button data-target="#account_entries_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=ors_account_entry" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                                        <table id="account_entries_table" class="table table-bordered table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th style="width: 100px">Type</th>
                                                <th style="width: 25%">Resp Center</th>
                                                <th style="width: 200px;">Account Code</th>
                                                <th>Account Title</th>
                                                <th style="width: 200px">Debit</th>
                                                <th style="width: 200px">Credit</th>
                                                <th style="width: 80px"></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-right">TOTAL DV</th>
                                                <th id="totalDvDebit" class="text-right"></th>
                                                <th id="totalDvCredit" class="text-right"></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-right">TOTAL ORS</th>
                                                <th id="totalOrsDebit" class="text-right"></th>
                                                <th id="totalOrsCredit" class="text-right"></th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </fieldset>
                                </div>

                                <div class="tab-pane" id="tab_2">

                                    <fieldset id="applied_projects_fieldset">
                                        <button type="button" onclick="totalAppliedProjects()" hidden>SUM</button>
                                        <button data-target="#applied_projects_table" uri="{{route('dashboard.ajax.get','add_row')}}?view=ors_applied_project" style="margin-bottom: 5px" type="button" class="btn btn-xs btn-success pull-right add_button"><i class="fa fa-plus"></i> Add item</button>
                                        <table id="applied_projects_table" class="table table-bordered table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th style="width: 30%">Resp Center</th>
                                                <th>PAP</th>
                                                <th style="width: 200px">MOOE</th>
                                                <th style="width: 200px">CO</th>
                                                <th style="width: 50px"></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-right">Total</th>
                                                <th id="totalMooe" class="text-right"></th>
                                                <th id="totalCo" class="text-right"></th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </fieldset>
                                </div>

                            </div>

                        </div>

                </div>
            </form>
        </div>

    </section>


@endsection


@section('modals')

@endsection

@section('scripts')
    <script type="text/javascript">
        function totalAccountEntries() {
            let form = $("#ors_form");
            let data = form.serialize();
            let arrayByType = new Array();
            $('#account_entries_table tbody tr').each(function () {
                let id = $(this).attr('id');
                let debit = $("#"+id+' input[for=debit]').val();
                let credit = $("#"+id+' input[for=credit]').val();

                if(typeof (arrayByType[$("#"+id+' select[for=type]').val()]) != 'undefined'){
                    arrayByType[$("#"+id+' select[for=type]').val()]['debit'] = arrayByType[$("#"+id+' select[for=type]').val()]['debit'] + sanitizeAutoNum(debit);
                    arrayByType[$("#"+id+' select[for=type]').val()]['credit'] = arrayByType[$("#"+id+' select[for=type]').val()]['credit'] + sanitizeAutoNum(credit);
                }else{
                    arrayByType[$("#"+id+' select[for=type]').val()]= new Array();
                    arrayByType[$("#"+id+' select[for=type]').val()]['debit'] = sanitizeAutoNum(debit);
                    arrayByType[$("#"+id+' select[for=type]').val()]['credit'] = sanitizeAutoNum(credit);

                }
            })

            if(typeof  arrayByType['DV'] != 'undefined'){
                $("#totalDvDebit").html($.number(arrayByType['DV']['debit'],2));
                $("#totalDvCredit").html($.number(arrayByType['DV']['credit'],2));
            }else{
                $("#totalDvDebit").html('N/A');
                $("#totalDvCredit").html('N/A');
            }
            if(typeof (arrayByType['ORS']) != "undefined"){
                $("#totalOrsDebit").html($.number(arrayByType['ORS']['debit'],2));
                $("#totalOrsCredit").html($.number(arrayByType['ORS']['credit'],2));
            }else{
                $("#totalOrsDebit").html('N/A');
                $("#totalOrsCredit").html('N/A');
            }
        }
        
        function totalAppliedProjects() {
            let array = new Array()
            array['mooe'] = 0;
            array['co'] = 0;
            $("#applied_projects_table tbody tr").each(function () {
                if($(this).find('input[for=mooe]').val() !== ''){
                    array['mooe'] = array['mooe'] + sanitizeAutoNum($(this).find('input[for=mooe]').val());
                }
                if($(this).find('input[for=co]').val() !== ''){
                    array['co'] = array['co'] + sanitizeAutoNum($(this).find('input[for=co]').val());
                }
            })
            $("#totalMooe").html($.number(array['mooe'],2));
            $("#totalCo").html($.number(array['co'],2));
        }

        function sanitizeAutoNum($number){
            return parseFloat($number.replaceAll('₱','').replaceAll(',',''));
        }

        $("#applied_projects_fieldset").change(function () {
            totalAppliedProjects();
        });

        $("#account_entries_fieldset").change(function () {
            totalAccountEntries();
        });

        $("body").on("click",".remove_row_btn",function () {
            totalAppliedProjects();
            totalAccountEntries();
        })

        $(document).ready(function () {
            $(".add_button").each(function () {
                $(this).trigger('click');
            })
        })

        $("body").on("change",".resp_center_clear",function () {
            let id = $(this).parents('tr').attr('id');
            $('#select2_id_'+id).val(null).trigger('change');

        })

        $("#ors_form").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.ors.store")}}',
                data : form.serialize(),
                type: 'POST',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,true);
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })

        $("body").on("click",".clone_btn",function () {
            let btn = $(this);
            let id = btn.parents('tr').attr('id');
            $.ajax({
                url : '{{route("dashboard.ajax.get","orsAccountEntry")}}',
                data : {
                    type: $('#'+id+' select[for=type]').val(),
                    resp_center : $('#'+id+' select[for=resp_center]').val(),
                    account_code : $('#'+id+' input[for=account_code]').val(),
                    account_title : $('#'+id+' select[for=account_title]').val(),
                    debit : $('#'+id+' input[for=debit]').val(),
                    credit : $('#'+id+' input[for=credit]').val(),
                    select2_text : $('#'+id+' input[for=text-value]').val(),
                },
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    $("#account_entries_table tbody").append(res);
                    totalAccountEntries();
                },
                error: function (res) {
                    console.log(res);
                }
            });
        });



    </script>
@endsection