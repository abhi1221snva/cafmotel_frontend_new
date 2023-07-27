@extends('layouts.app')
@section('title', 'Ivr Menu')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
                <h1>
                   <b>Ivr Menu</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">Ivr Menu</li>
                </ol>
        </section>
       
    <!-- Main content -->
    <section class="content">
        <!-- add/edit ivr div start -->
        @if($ivr_id > -1)
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="add_edit_box_header">Add / Edit IVR Menu of {{isset($ivr_menu_details[0]->ivr_desc) ? $ivr_menu_details[0]->ivr_desc : ""}}</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <form method='POST' action='{{url('/')}}/edit-ivr-menu'>
                                    <table class="table table-bordered table-striped nowrap" {{count($ivr_menu_details) > 0 ? "style=display:none;": ""}}>
                                        <thead>
                                            <th>Select IVR <i data-toggle="tooltip" data-placement="right" title="Select Ivr" class="fa fa-info-circle" aria-hidden="true"></i></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select id="ivr_select" class="form-control">
                                                        <!--show disabled only on add new ivr page-->
                                                        @if(count($ivr_menu_details) > 0)
                                                            @foreach($arrIvr as $key => $val)
                                                                <option {{$ivr_id > 0 && $ivr_id == $key ? "selected=selected" : ''}} value='{{$key}}'>{{$val['desc']}}</option>
                                                            @endforeach
                                                        @else
                                                            @foreach($arrIvr as $key => $val)
                                                                <option {{$val['ivr_has_menu'] == 1 ? "disabled=disabled  style=background-color:lightgray;": ""}} {{$ivr_id > 0 && $ivr_id == $key ? "selected='selected'" : ''}} value='{{$key}}'>{{$val['desc']}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <input type='hidden' name='ivr' id='ivr_input' value='{{$ivr_id > 0 ? $ivr_id : 0}}'/>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    @csrf
                                    <table class="table table-bordered table-striped nowrap">
                                        <thead>
                                            <th>DTMF <i data-toggle="tooltip" data-placement="right" title="Select dtmf" class="fa fa-info-circle" aria-hidden="true"></i></th>
                                            <th>Destination Type <i data-toggle="tooltip" data-placement="right" title="Select destination type" class="fa fa-info-circle" aria-hidden="true"></i></th>
                                            <th>Destination <i data-toggle="tooltip" data-placement="right" title="Select destination" class="fa fa-info-circle" aria-hidden="true"></i></th>
                                            <th>Action <i data-toggle="tooltip" data-placement="right" title="Click to action" class="fa fa-info-circle" aria-hidden="true"></i></th>
                                        </thead>
                                        <tbody id="ivr_level_body">
                                            @if(count($ivr_menu_details) > 0)
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach($ivr_menu_details as $ivr_menu_detail)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="ivr_menu_id[]" value="{{isset($ivr_menu_detail->ivr_m_id) && $ivr_menu_detail->ivr_m_id > 0 ? $ivr_menu_detail->ivr_m_id : '0'}}" />
                                                            <select class="form-control" name='dtmf[]'>
                                                                @foreach($arrDtmf as $key => $val)
                                                                    <option {{isset($ivr_menu_detail->dtmf) && $ivr_menu_detail->dtmf == $key ? "selected='selected'" : ''}} value='{{$key}}'>{{$val}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control dest_type" name='dest_type[]'>
                                                                @foreach($arrDestType as $key => $val)
                                                                    @php
                                                                        $seletedDestination = '0';
                                                                        if(isset($ivr_menu_detail->dest_type)) {
                                                                            $seletedDestination = $ivr_menu_detail->dest_type;
                                                                        }
                                                                    @endphp
                                                                    <option {{isset($ivr_menu_detail->dest_type) && $ivr_menu_detail->dest_type == $key ? "selected='selected'" : ''}} value='{{$key}}'>{{$val}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td id="destinationTd" class="destTd">
                                                            @if($seletedDestination == 0)
                                                                <select class="form-control"  name='dest[]'>
                                                                    @foreach($arrIvr as $key => $val)
                                                                        <option {{isset($ivr_menu_detail->dest) && $ivr_menu_detail->dest == $key ? "selected='selected'" : ''}} value='{{$key}}'>{{$val['desc']}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @elseif($seletedDestination == 1 || $seletedDestination == 2)
                                                                <select class="form-control"  name='dest[]'>
                                                                    @foreach($extensionOptions as $ext)
                                                                        <option {{isset($ivr_menu_detail->dest) && $ivr_menu_detail->dest == $ext->extension ? "selected='selected'" : ''}} value='{{$ext->extension}}'>{{$ext->name}} - {{$ext->extension}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @elseif($seletedDestination == 4)
                                                                <select class="form-control"  name='country_code[]'>
                                                                    @foreach($countryCodeOptions as $code)
                                                                        <option {{isset($ivr_menu_detail->dest) && $ivr_menu_detail->dest == $code->phonecode ? "selected='selected'" : ''}} value='{{$code->phonecode}}'>{{$code->phonecode}} - {{$code->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            &nbsp;<input type="text" class="form-control" name="dest[]" value="{{isset($ivr_menu_detail->dest) ? $ivr_menu_detail->dest : ''}}" />
                                                            @elseif($seletedDestination == 5)
                                                                <select class="form-control"  name='dest[]'>
                                                                    @foreach($conferenceOptions as $conf)
                                                                        <option {{isset($ivr_menu_detail->dest) && $ivr_menu_detail->dest  == $conf->id ? "selected='selected'" : ''}} value='{{$conf->id}}'>{{$conf->title}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @elseif($seletedDestination == 8)
                                                                <select class="form-control"  name='dest[]'>
                                                                    @foreach($ringGroupOptions as $ring)
                                                                        <option {{isset($ivr_menu_detail->dest) && $ivr_menu_detail->dest == $ring->title ? "selected='selected'" : ''}} value='{{$ring->title}}'>{{$ring->title}} - {{$ring->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        </td>
                                                        @if($i == count($ivr_menu_details) - 1)
                                                            <td>
                                                                <button type="button" class="btn btn-success"onclick="addIvrRow();" title="Add More IVR Menu"><i class="fa fa-plus"></i></button>
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="ivr_menu_id[]" value="0" />
                                                        <select class="form-control" name='dtmf[]'>
                                                            @foreach($arrDtmf as $key => $val)
                                                                <option value='{{$key}}'>{{$val}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control dest_type" name='dest_type[]'>
                                                            @foreach($arrDestType as $key => $val)
                                                                <option value='{{$key}}'>{{$val}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td id="destinationTd" class="destTd">
                                                        <select class="form-control"  name='dest[]'>
                                                            @foreach($arrIvr as $key => $val)
                                                                <option value='{{$key}}'>{{$val['desc']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-success"onclick="addIvrRow();" title="Add More IVR Menu"><i class="fa fa-plus"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-check-square-o fa-lg"></i> 
                                                        Submit
                                                    </button>
                                                    &nbsp;
                                                    <a type="button" class="btn btn-warning" style="margin-right: 5px;" onclick="window.location.reload();">
                                                        <i class="fa fa-refresh fa-lg"></i> 
                                                        Reset
                                                    </a>
                                                    &nbsp;
                                                    <a type="button" class="btn btn-danger" style="margin-right: 5px;" href="{{url('/ivr-menu')}}">
                                                        <i class="fa fa-reply fa-lg"></i> 
                                                        Cancel
                                                    </a>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row"style="text-align:right; padding-bottom:5px;">
                <div class="col-xs-12">
                    <a type='button' class='btn btn-primary' href="{{url('/ivr-menu/0')}}">Add IVR</a>
                </div>
            </div>
        @endif
        <!-- Add / edit ivr  div ends -->
        
        <!-- ivr parent div  -->
        @if(count($ivr_menu_details) == 0 && $ivr_id == -1)
            @foreach($arrIvrMenu as $key => $val)
                @if(isset($val[0]['dtmf']) && $val[0]['dtmf'] != '' 
                                            && isset($val[0]['is_deleted']) && $val[0]['is_deleted'] != 1)
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-primary box-solid collapsed-box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{isset($val[0]['ivr_desc']) ? $val[0]['ivr_desc'] : ''}}</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <tbody>
                                            <tr>
                                                <th>DTMF</th>
                                                <th>Destination Type</th>
                                                <th>Destination</th>
                                                <th>Delete</th>
                                            </tr>
                                                @foreach($val as $arr)
                                                    <tr id='ivr_menu_row_{{$arr['ivr_m_id']}}'>
                                                        <td>{{$arr['dtmf']}}</td>
                                                        <td>{{isset($arrDestType[$arr['dest_type']]) ? $arrDestType[$arr['dest_type']] : '' }}</td>
                                                        <td>
                                                            @if(isset($arr['dest_type']) )
                                                                @if($arr['dest_type'] == 0 || $arr['dest_type'] == 4)
                                                                    {{$arr['dest']}}
                                                                @elseif($arr['dest_type'] == 1 || $arr['dest_type'] == 2)
                                                                    @foreach($extensionOptions as $ext)
                                                                        {{$ext->extension == $arr['dest'] ? $ext->name.' - '.$ext->extension : ""}}
                                                                    @endforeach
                                                                @elseif($arr['dest_type'] == 5)
                                                                    @foreach($conferenceOptions as $conf)
                                                                        {{$conf->id == $arr['dest'] ? $conf->title : ""}}
                                                                    @endforeach
                                                                @elseif($arr['dest_type'] == 8)
                                                                    @foreach($ringGroupOptions as $ring)
                                                                        {{$ring->title == $arr['dest']  ? $ring->description.' - '.$ring->title : ""}}
                                                                    @endforeach
                                                                @else
                                                                    {{$arr['dest']}}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button onclick="deleteIvrMenu('{{$arr['ivr_m_id']}}');" class="btn btn-danger">
                                                            <i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td>
                                                        <a type="button" href="{{url('/')}}/ivr-menu/{{$arr['ivr_id']}}" class="btn btn-primary" style="margin-right: 5px;">
                                                            <i class="fa fa-edit fa-lg"></i> 
                                                            Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            <!-- ivr parent div ends -->
            @endforeach
        @endif
    </section>
</div>

@endsection
@push('styles')

@endpush
@push('scripts')

<script language="javascript">
    
    var dtmf_options = '';
    var ivr_options = '';
    var dest_type_options = '';
    
    @foreach($arrDtmf as $key => $val)
        dtmf_options += "<option value='{{$key}}'>{{$val}}</option>";
    @endforeach
    
    @foreach($arrIvr as $key => $val)
        ivr_options += "<option value='{{$key}}'>{{$val['desc']}}</option>";
    @endforeach                                                
    
    @foreach($arrDestType as $key => $val)
        dest_type_options += "<option value='{{$key}}'>{{$val}}</option>";
    @endforeach
    
    /*******conf options******/
    var conf_options = '';
    @foreach($conferenceOptions as $conf)
        conf_options += "<option value='{{$conf->id}}'>{{$conf->title}}</option>";
    @endforeach
    
    var ring_group_options = '';
    @foreach($ringGroupOptions as $ring)
        ring_group_options += "<option value='{{$ring->title}}'>{{$ring->title}} - {{$ring->description}}</option>";
    @endforeach
    
    var ext_options = '';
    @foreach($extensionOptions as $ext)
        ext_options += "<option value='{{$ext->extension}}'>{{$ext->name}} - {{$ext->extension}}</option>";
    @endforeach
    
    var country_code_options = '';
    @foreach($countryCodeOptions as $code)
        country_code_options += "<option value='{{$code->phonecode}}'>{{$code->phonecode}} - {{$code->name}}</option>";
    @endforeach
    /*******conf options******/
    
    $(document).ready(function() {
        
        $(document).on( 'change', '.dest_type', function(){
            var html = setDestInput($(this));
            $(this).parent().parent().find('.destTd').html(html);
        });
        
        //Set header box title on DDL change
        $("#ivr_select").on('change', function(){
            $("#add_edit_box_header").text("Add/Edit IVR menu : "+$('#ivr_select :selected').text());
            $("#ivr_input").val($("#ivr_select").val());
        });
        
        //Set header box title on page load
        $("#add_edit_box_header").text("Add/Edit IVR menu : "+$('#ivr_select :selected').text());
        $("#ivr_input").val($("#ivr_select").val());
    });
    
    /**
    * Function to add new row on add more
    */
    function addIvrRow() {
        var html ='<tr>\n\
<td><input type="hidden" name="ivr_menu_id[]" value="0" /><select class="form-control" name="dtmf[]">'+dtmf_options+'</select></td>\n\
<td><select class="form-control dest_type" name="dest_type[]">'+dest_type_options+'</select></td>\n\
<td class="destTd"><select class="form-control" name="dest[]">'+ivr_options+'</select></td>\n\
<td><button onclick="$(this).parent().parent().remove();" class="btn btn-danger"><i class="fa fa-trash"></i></button></td></tr>';
        $('#ivr_level_body').append(html);
    }
    
    /**
     * Function to delete iver menu from list
     */
    function deleteIvrMenu(delete_id) {
        if(confirm("Are you sure want to delete this Ivr Menu?")) {
            $.ajax({
                url: 'deleteIvrMenu/' + delete_id,
                type: 'get',
                success: function(response) {
                    $("#ivr_menu_row_"+delete_id).hide();
                    toastr.success(response);
                    window.location.reload();
                },
                error: function(response) {
                    toastr.error(response);
                }
            });
        }
    }
    
    /**
    * Return destination input html
    */
    function setDestInput(inp) {
        var dest_type_id = inp.val();
        switch(dest_type_id) {
            case '0':
                return '<select class="form-control" name="dest[]">'+ivr_options+'</select>';
            break;
            case '1':
                return '<select class="form-control" name="dest[]">'+ext_options+'</select>';
            break;
            case '2':
                return '<select class="form-control" name="dest[]">'+ext_options+'</select>';
            break;
            case '3':
            break;
            case '4':
                return '<select class="form-control" name="country_code[]">'+country_code_options+'</select>&nbsp;<input type="text" class="form-control" name="dest[]" />';
            break;
            case '5':
                return '<select class="form-control" name="dest[]">'+conf_options+'</select>';
            break;
            case '6':
            break;
            case '7':
            break;
            case '8':
                return '<select class="form-control" name="dest[]">'+ring_group_options+'</select>';
            break;
            case '9':
            break;
        }
    }
</script>
@endpush
