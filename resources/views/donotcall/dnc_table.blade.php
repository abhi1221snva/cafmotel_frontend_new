
    @foreach($dnc_list as $key => $dnc)
    <tr>
        <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
        <td>{{$dnc->number}}</td>
        <td>{{$dnc->extension}}</td>
        <td>{{$dnc->comment}}</td>
        <td>
            @if(session()->get('level') >= 7)
            <a style="cursor:pointer;color:blue;" class='editDnc' data-number={{$dnc->number}}><i class="fa fa-edit fa-lg"></i></a> |
            <a style="cursor:pointer;color:red;" class='openDncDelete' data-number={{$dnc->number}}><i class="fa fa-trash fa-lg"></i></a>
            @endif
        </td>
    </tr>
    @endforeach
    
