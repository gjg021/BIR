<div class="btn-group">
    <button type="button" class="btn btn-default btn-sm show_ors_btn" data="{{$data->slug}}" data-toggle="modal" data-target="#show_ors_modal" title="" data-placement="left" data-original-title="View more">
        <i class="fa fa-file-text"></i>
    </button>
    <a class="btn btn-default btn-sm print_pr_btn" data="{{$data->slug}}" target="popup" href="{{route('dashboard.ors.edit',$data->slug)}}"title="" data-placement="left" data-original-title="Print">
        <i class="fa fa-edit"></i>
    </a>
    <button type="button" onclick="delete_data('{{$data->slug}}','{{route("dashboard.ors.destroy",$data->slug)}}')" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a  data="{{$data->slug}}" target="popup" href="{{route('dashboard.ors.print',$data->slug)}}"title="" data-placement="left" data-original-title="Print"><i class="fa fa-print"></i> Print</a>
            </li>
        </ul>
    </div>
</div>