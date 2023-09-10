@extends('layouts.admin-master')

@php
    $articles = \App\Models\BIR\OfficeSupplies::query()->get();
    $articlesAssoc = $articles->mapWithKeys(function ($data){
        return [$data->slug => $data->stock_no];
    })->toArray();
    $articles = $articles->map(function ($data){
        return [
            'id' => $data->slug,
            'text' => $data->stock_no,
            'article' => $data->article,
            'stock_no' => $data->stock_no,
            'description' => $data->description,
            'uom' => $data->uom,
        ];
    });

@endphp


@section('content')

    <section class="content-header">
        <h1>RIS</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <form id="ris_form">
            <div class="box box-solid">
                <div class="box-footer" style="padding-bottom: 0px;">
                    <button type="submit"  class="btn btn-primary btn-sm pull-right"><i class="fa fa-check"></i> Save</button>
                </div>
                <div class="box-body">
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('entity_name',[
                            'label' => 'Entity Name:',
                            'cols' => 3,
                            'readonly' => 'readonly',
                        ],$ris ?? null) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('division',[
                            'label' => 'Division:',
                            'cols' => 3,
                        ],$ris ?? null) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('fund_cluster',[
                            'label' => 'Fund Cluster:',
                            'cols' => 2,
                        ],$ris ?? null) !!}
                        {!! \App\Swep\ViewHelpers\__form2::textbox('rcc',[
                            'label' => 'RCC:',
                            'cols' => 4,
                        ],$ris ?? null) !!}
                    </div>
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('office',[
                            'label' => 'Office:',
                            'cols' => 3,
                            'readonly' => 'readonly',
                        ],$ris ?? null) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('ris_no',[
                            'label' => 'RIS No.:',
                            'cols' => 3,
                        ],$ris ?? null) !!}

                    </div>
                    <p class="page-header-sm text-info text-strong" style="border-bottom: 1px solid #cedbe1">
                        Details
                    </p>

                    <table id="details_table" class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 130px">Stock #</th>
                            <th style="width: 20%"> Article</th>
                            <th>Description</th>
                            <th style="width: 100px">Unit</th>
                            <th style="width: 130px">Qty Requested</th>
                            <th style="width: 130px">Qty Issued</th>
                            <th style="width: 130px">Remarks</th>
                            <th><button type="button" id="add_item_btn" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add</button></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ris->details as $detail)
                            <tr>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::selectOnly('details['.$detail->slug.'][article]',[
                                        'class' => 'input-sm select_stock_no static_stock_no',
                                        'id' => 'select_stock_no_slug',
                                        'options' => $articlesAssoc,
                                        'container_class' => 'select2-sm',
                                    ],$detail->article ?? null) !!}
                                    <input style="display: none" name="details[{{$detail->slug}}][stock_no]" value="{{$detail->stock_no ?? null}}" class="stock_no">
                                </td>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details['.$detail->slug.'][article_name]',[
                                        'class' => 'input-sm article',
                                        'readonly' => 'readonly',
                                    ],$detail->article_name ?? null) !!}
                                    <input style="display: none" name="details[{{$detail->slug}}][article_name]" value="{{$detail->article_name ?? null}}" class="article_name">
                                </td>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details['.$detail->slug.'][description]',[
                                        'class' => 'input-sm description',
                                    ],$detail->description ?? null) !!}
                                </td>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details['.$detail->slug.'][uom]',[
                                        'class' => 'input-sm uom',
                                    ],$detail->uom ?? null) !!}
                                </td>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details['.$detail->slug.'][qty_requested]',[
                                        'class' => 'input-sm qty_requested',
                                        'type' => 'number',
                                    ],$detail->qty_requested ?? null) !!}
                                </td>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details['.$detail->slug.'][qty_issued]',[
                                        'class' => 'input-sm qty_issued',
                                        'type' => 'number',
                                    ],$detail->qty_issued ?? null) !!}
                                </td>
                                <td>
                                    {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details['.$detail->slug.'][remarks]',[
                                        'class' => 'input-sm',
                                    ],$detail->remarks ?? null) !!}
                                </td>
                                <td>
                                    <button tabindex="-1" class="btn btn-sm btn-danger remove_row_btn"><i class="fa fa-times"></i> </button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Total</th>
                            <th class="text-right">
                                <span id="total"></span>
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>

    </section>

    <table style="display: none">
        <tbody id="row_template">
        <tr>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::selectOnly('details[slug][article]',[
                    'class' => 'input-sm select_stock_no',
                    'id' => 'select_stock_no_slug',
                    'options' => [],
                    'container_class' => 'select2-sm',
                ]) !!}
                <input style="display: none" name="details[slug][stock_no]" class="stock_no">
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][article_name]',[
                    'class' => 'input-sm article',
                    'readonly' => 'readonly',
                ]) !!}
                <input style="display: none" name="details[slug][article_name]" class="article_name">
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][description]',[
                    'class' => 'input-sm description',
                ]) !!}
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][uom]',[
                    'class' => 'input-sm uom',
                ]) !!}
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][qty_requested]',[
                    'class' => 'input-sm qty_requested',
                    'type' => 'number',
                ]) !!}
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][qty_issued]',[
                    'class' => 'input-sm qty_issued',
                    'type' => 'number',
                ]) !!}
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][remarks]',[
                    'class' => 'input-sm',
                ]) !!}
            </td>
            <td>
                <button tabindex="-1" class="btn btn-sm btn-danger remove_row_btn"><i class="fa fa-times"></i> </button>
            </td>
        </tr>
        </tbody>
    </table>
@endsection


@section('modals')

@endsection


@section('scripts')
    <script type="text/javascript">
        var data = {!! $articles->toJson() !!};

        $(".static_stock_no").select2();

        $("body").on('select2:select',".select_stock_no",function(){
            let slug = $(this).val();
            var result = data.find(item => item.id === slug);
            let t = $(this);
            t.parents('tr').find('.article').val(result.article);
            t.parents('tr').find('.stock_no').val(result.stock_no);
            t.parents('tr').find('.description').val(result.description);
            t.parents('tr').find('.uom').val(result.uom);
            t.parents('tr').find('.article_name').val(result.article);
        });

        $("#add_item_btn").click(function (){
            let id = makeId(10);
            let template = $("#row_template").html();
            template = template.replaceAll('slug',id);
            let detailsTbl = $("#details_table");

            detailsTbl.find('tbody').append(template);
            $("#select_stock_no_"+id).select2({
                data: data,
            });

            $(".autonum_"+id).each(function(){
                new AutoNumeric(this, autonum_settings);
            });
        })

        $("#ris_form").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.ris.update",$ris->slug)}}',
                data : form.serialize(),
                type: 'PATCH',
                headers: {
                    {!! __html::token_header() !!}
                },
                success: function (res) {
                    succeed(form,true,false);
                },
                error: function (res) {
                    errored(form,res);
                }
            })
        })
        $("body").on('change keyup','.qty_x_cost',function (){
            let t = $(this);
            let tr = t.parents('tr');
            let qty = parseFloat(tr.find('.qty').val());
            let unitCost = sanitizeAutonum(tr.find('.unit_cost').val());
            tr.find('.amount').html($.number(qty*unitCost,2));

            $("#total").html($.number(getTotal(),2));
        })

        function getTotal(){
            let total = 0;
            $("span.amount").each(function (){
                let t = $(this);
                total = total + sanitizeAutonum(t.html());
            });
            return total;
        }

        $("body").on('click','.remove_row_btn',function (){
            $("#total").html($.number(getTotal(),2));
        });

        $("body").on('change keyup','.qty_requested',function (){
            let t = $(this);
            let tr = t.parents('tr');
            tr.find('.qty_issued').val(t.val());
        })
    </script>
@endsection