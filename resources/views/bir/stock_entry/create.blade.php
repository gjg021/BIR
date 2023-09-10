@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>Stock Entry</h1>
    </section>
@endsection
@section('content2')
    <section class="content">
        <form id="stock_entry_form">
            <div class="box box-solid">
                <div class="box-footer" style="padding-bottom: 0px;">
                    <button type="submit"  class="btn btn-primary btn-sm pull-right"><i class="fa fa-check"></i> Save</button>
                </div>
                <div class="box-body">
                    <div class="row">
                        {!! \App\Swep\ViewHelpers\__form2::textbox('date',[
                            'label' => 'Date:',
                            'cols' => 3,
                            'type' => 'date',
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('po_no',[
                            'label' => 'PO #:',
                            'cols' => 3,
                        ]) !!}

                        {!! \App\Swep\ViewHelpers\__form2::textbox('supplier',[
                            'label' => 'Supplier:',
                            'cols' => 6,
                        ]) !!}
                    </div>
                    <p class="page-header-sm text-info text-strong" style="border-bottom: 1px solid #cedbe1">
                        Details
                    </p>

                    <table id="details_table" class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 150px">Stock #</th>
                            <th style="width: 20%"> Article</th>
                            <th>Description</th>
                            <th style="width: 100px">Unit</th>
                            <th style="width: 90px">Qty</th>
                            <th style="width: 130px">Unit Cost</th>
                            <th style="width: 130px">Amount</th>
                            <th><button type="button" id="add_item_btn" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add</button></th>
                        </tr>
                        </thead>
                        <tbody>

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
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][qty]',[
                    'class' => 'input-sm qty_x_cost qty',
                    'type' => 'number',
                ]) !!}
            </td>
            <td>
                {!! \App\Swep\ViewHelpers\__form2::textboxOnly('details[slug][unit_cost]',[
                    'class' => 'input-sm text-right autonum_slug qty_x_cost unit_cost',
                ]) !!}
            </td>
            <td class="text-right" style="vertical-align: middle">
                <label>
                     <span class="amount">0.00</span>
                </label>
            </td>
            <td>
                <button class="btn btn-sm btn-danger remove_row_btn"><i class="fa fa-times"></i> </button>
            </td>
        </tr>
        </tbody>
    </table>
@endsection


@section('modals')

@endsection

@php
    $articles = \App\Models\BIR\OfficeSupplies::query()->get();

@endphp
@section('scripts')
    <script type="text/javascript">
        var data = {!! $articles->map(function ($data){
            return [
                'id' => $data->slug,
                'text' => $data->stock_no,
                'article' => $data->article,
                'stock_no' => $data->stock_no,
                'description' => $data->description,
                'uom' => $data->uom,
            ];
        })->toJson() !!};
        $(document).ready(function (){
            $('#add_item_btn').trigger('click');
        })

        $("#select_article").select2({
            data : data,
        });

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

        $("#stock_entry_form").submit(function (e) {
            e.preventDefault()
            let form = $(this);
            loading_btn(form);
            $.ajax({
                url : '{{route("dashboard.stock_entry.store")}}',
                data : form.serialize(),
                type: 'POST',
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
    </script>
@endsection