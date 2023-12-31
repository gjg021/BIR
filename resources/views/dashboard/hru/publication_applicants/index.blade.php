@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>{{$publicationDetail->position}} - Item No. {{$publicationDetail->item_no}}</h1>
    </section>
@endsection
@section('content2')

    <section class="content">
        <div class="box box-solid">
            <div class="box-header with-border">
                <p class="box-title" class="no-margin">List of Applicants</p>
            </div>

            <div class="box-body">
                <div id="applicants_table_container" style="display: none">
                    <table class="table table-bordered table-striped table-hover" id="applicants_table" style="width: 100% !important">
                        <thead>
                        <tr class="">
                            <th>Applicant</th>
                            <th>Education</th>
                            <th>Eligibility</th>
                            <th>Experience</th>
                            <th>Training</th>
                            <th class="action">Action</th>
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
    {!! \App\Swep\ViewHelpers\__html::blank_modal('assess-applicant-modal',85) !!}
@endsection

@section('scripts')
    <script type="text/javascript">
        //-----DATATABLES-----//
        modal_loader = $("#modal_loader").parent('div').html();
        //Initialize DataTable
        var active = '';
        applicants_tbl = $("#applicants_table").DataTable({
            "ajax" : '{{\Illuminate\Support\Facades\Request::url()}}',
            "columns": [
                { "data": "lastname" },
                { "data": "education" },
                { "data": "eligibility" },
                { "data": "experience" },
                { "data": "training" },
                { "data": "actions" },
            ],
            "buttons": [
                {!! __js::dt_buttons() !!}
            ],
            "columnDefs":[
                {
                    "targets" : 5,
                    "orderable" : false,
                    "class" : 'action3'
                },
            ],
            "responsive": false,
            'dom' : 'lBfrtip',
            "processing": true,
            "serverSide": true,
            "initComplete": function( settings, json ) {
                style_datatable("#"+settings.sTableId);
                $('#tbl_loader').fadeOut(function(){
                    $("#"+settings.sTableId+"_container").fadeIn();
                    if(find != ''){
                        applicants_tbl.search(find).draw();
                    }
                });
                //Need to press enter to search
                $('#'+settings.sTableId+'_filter input').unbind();
                $('#'+settings.sTableId+'_filter input').bind('keyup', function (e) {
                    if (e.keyCode == 13) {
                        applicants_tbl.search(this.value).draw();
                    }
                });
            },

            "language":
                {
                    "processing": "<center><img style='width: 70px' src='{{asset("images/loader.gif")}}'></center>",
                },
            "drawCallback": function(settings){
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="modal"]').tooltip();
                if(active != ''){
                    $("#"+settings.sTableId+" #"+active).addClass('success');
                }
            }
        });


        $("body").on("click",".assess-applicant-btn",function () {
            let btn = $(this);
            load_modal2(btn);
            let uri = '{{route("dashboard.publication_applicants.assess","slug")}}';
            uri = uri.replace('slug',btn.attr('data'));
            $.ajax({
                url : uri,
                type: 'GET',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    populate_modal2(btn,res);
                },
                error: function (res) {
                    populate_modal2_error(res);
                }
            })
        })

        $("body").on("click",'.disqualify-btn',function (){
            let t = $(this);
            let uri = '{{route('dashboard.publication_applicants.assess','slug')}}';
            uri = uri.replace('slug',t.attr('data'));
            let text = t.html();
            let colors = {
                'Qualify' :'#00acd6',
                'Disqualify' : '#d73925',
            }
            let status = {
                'Qualify' : null,
                'Disqualify' : 'DISQUALIFIED',
            }
            Swal.fire({
                title: text+' applicant?',
                // html: 'ss',
                inputAttributes: {
                    autocapitalize: 'off',
                },
                inputValue: 'bm_uid',
                showCancelButton: true,
                confirmButtonText: text,
                showLoaderOnConfirm: true,
                confirmButtonColor : colors[text],
                preConfirm: (texta) => {
                    return $.ajax({
                        url : uri,
                        data : {'status':status[text]},
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            active = res.slug;
                            applicants_tbl.draw(false);
                            notify('Changes were saved successfully.','success');
                        },
                        error: function (res) {
                            if(res.status == 422){
                                var message = res.responseJSON.errors.biometric_user_id;
                            }else{
                                var message = res.responseJSON.message;
                            }
                            Swal.showValidationMessage(
                                'Request failed: ' + message
                            );
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    }).catch(error => {

                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        })

        $("body").on("click", ".finalize-assessment-btn",function (){
            let t = $(this);
            let uri = '{{route('dashboard.publication_applicants.assess','slug')}}';
            uri = uri.replace('slug',t.attr('data'));
            let text = t.html();
            let action = t.attr('action');
            if(action == 'reassess'){
                text = 'Re-assess applicant';
            }
            console.log(action);
            Swal.fire({
                    title: text+'?',
                // html: 'ss',
                    inputAttributes: {
                    autocapitalize: 'off',
                },
                inputValue: 'bm_uid',
                showCancelButton: true,
                confirmButtonText: text,
                showLoaderOnConfirm: true,
                preConfirm: (texta) => {
                    return $.ajax({
                        url : uri,
                        data : {'action':action},
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (res) {
                            active = res.slug;
                            applicants_tbl.draw(false);
                            notify('Changes were saved successfully.','success');
                        },
                        error: function (res) {
                            if(res.status == 422){
                                var message = res.responseJSON.errors.biometric_user_id;
                            }else{
                                var message = res.responseJSON.message;
                            }
                            Swal.showValidationMessage(
                                'Request failed: ' + message
                            );
                        }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    }).catch(error => {

                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        })


    </script>
@endsection