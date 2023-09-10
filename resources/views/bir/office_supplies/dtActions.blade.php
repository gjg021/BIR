<div class="btn-group">
    <a type="button" class="btn btn-default btn-sm show_ors_btn" href="{{route('dashboard.office_supplies.show',$data->slug)}}" >
        <i class="fa fa-file-text"></i>
    </a>
    <a class="btn btn-default btn-sm" data="{{$data->slug}}" target="_self" href="" title="" data-placement="left" data-original-title="Print">
        <i class="fa fa-edit"></i>
    </a>
    <button type="button" onclick="delete_data('{{$data->slug}}','{{route("dashboard.office_supplies.destroy",$data->slug)}}')" class="btn btn-sm btn-danger" data-toggle="tooltip" title="" data-placement="top" data-original-title="Delete">
        <i class="fa fa-trash"></i>
    </button>
    <div class="btn-group btn-group-sm" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li>
                <a  data="{{$data->slug}}" target="popup" href="" title="" data-placement="left" data-original-title="Print"><i class="fa fa-print"></i> Print</a>
            </li>
            <li>
                <a  data="{{$data->slug}}" target="popup" href="? withOrsEntries=true&accountEntriesPerPage=12" title="" data-placement="left" data-original-title="Print"><i class="fa fa-print"></i> Print with ORS Entries</a>
            </li>
            <li>
                <a  data="{{$data->slug}}" target="popup" href="? attachment=true" title="" data-placement="left" data-original-title="Print"><i class="fa fa-print"></i> Print attachment</a>
            </li>
        </ul>
    </div>
</div>