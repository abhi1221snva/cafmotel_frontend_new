<div>
    <form wire:submit.prevent="submitSearch">
        <input type="text" wire:model="search" placeholder="Number or Extension">
        <button type="submit"><i class="fa fa-search"></i></button>
    </form>

    <!-- Rest of your HTML code for the table goes here -->
    <table id="example" class="table table-bordered table-hover"> 
                       

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <!-- <th>Account No</th> -->
                                    <th>Number</th>
                                    <th>Extension</th>

                                    <th>Comment</th>
                                    @if(session()->get('level') >= 7)
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>

                            @if(!empty($dnc_list))
                            <tbody>
                           
                                @foreach($dnc_list as $key => $dnc)
                                <tr>
                                <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                                    <td>{{$dnc->number}}</td>
                                    <td>{{$dnc->extension}}</td>


                                    <td>{{$dnc->comment}}</td>

                                    <td>

                                        @if(session()->get('level') >= 7)

                                        <a style="cursor:pointer;color:blue;" class='editDnc' data-number={{$dnc->number}}  ><i class="fa fa-edit fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:red;" class='openDncDelete' data-number={{$dnc->number}}><i class="fa fa-trash fa-lg"></i></a> @endif
                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                            
                            @endif

                        </table>
</div>
