@extends('layouts.admin-master')

@section('content')

    <section class="content-header">
        <h1>{{$officeSupply->article}}</h1>
    </section>
@endsection
@section('content2')
    <section class="content">

            <div class="box box-solid">
                <div class="box-header" style="padding-bottom: 0px;">
                       </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Details</th>
                                <th style="width: 100px">Stock Entry</th>
                                <th style="width: 100px">RIS</th>
                                <th style="width: 100px">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-info">
                                <td colspan="4">
                                    BEGINNING BALANCE
                                </td>
                                <td class="text-right">
                                    {{ $balance = $officeSupply->stock}}
                                </td>
                            </tr>
                            @forelse($balanceSheet as $data)
                                <tr>
                                    <td class="">{{Helper::dateFormat($data->date,'m/d/Y')}}</td>
                                    <td>{{$data->reference}}</td>
                                    @if($data->type == 'stock_entry')
                                        <td class="text-right">{{$data->qty}}</td>
                                        <td class="text-right"></td>
                                    @elseif($data->type == 'ris')
                                        <td class="text-right"></td>
                                        <td class="text-right">{{$data->qty}}</td>
                                    @endif
                                    <td class="text-right">
                                        @php
                                            if($data->type == 'stock_entry'){
                                                $balance = $balance + $data->qty;
                                            }elseif($data->type == 'ris'){
                                                $balance = $balance - $data->qty;
                                            }
                                        @endphp
                                        {{$balance}}
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-success">
                                <th colspan="4">
                                    STOCK BALANCE
                                </th>
                                <th class="text-right">
                                    {{$balance}}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>


    </section>


@endsection


@section('modals')

@endsection


@section('scripts')
    <script type="text/javascript">

    </script>
@endsection