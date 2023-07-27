@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <section class="content-header">
                <h1>
                   <b>Configure List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active"> Configure List</li>
                </ol>
        </section>
        <section class="content-header">

                   <div class="text-right mt-5 mb-3">

                  <a href="{{ url('/list') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Lists</a>

           </div>
        </section>

    <!-- Main content -->
    <section class="content">

<form class="form-inline" method="post" action="">

    @csrf
          <div class="row">


                     <div class="col-xs-12">


                <div class="box">







                    <div class="box-body">

                        <input type="hidden" name="list_id" value="{{$lists->list_id}}" />
                        <input type="hidden" name="campaign_id" value="{{$lists->campaign_id}}" />



                        <div class="form-group m-l-10">
                                        <label>Title</label>
                                        <div>
                                            <div class="input-group" id="date-range2">
                                                 <input class="form-control" type="text" name="title" value="{{$lists->list}}" id="name" required>
                                            </div>
                                        </div>
                                    </div>



                                      <div class="form-group m-l-10">
                  <label>Campaign</label>
                  <!-- <select class="select2" multiple="multiple" name="new_campaign_id[]" autocomplete="off" data-placeholder="Select Disposition" style="width: 100%;">


                    @foreach($campaign_list as $key => $campaign)
                                    <option @if($lists->campaign_id == $campaign->id) selected @endif value="{{$campaign->id}}">{{$campaign->title}}</option>
                                    @endforeach;
                  </select> -->

                  <select class="form-group select2"  name="new_campaign_id" autocomplete="off" data-placeholder="Select Disposition" style="width: 100%;">


                    @foreach($campaign_list as $key => $campaign)
                                    <option @if($lists->campaign_id == $campaign->id) selected @endif value="{{$campaign->id}}">{{$campaign->title}}</option>
                                    @endforeach;
                  </select>
                </div>







                                     <div class="form-group m-l-10">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <button type="submit" name="submit"
                                                            class="btn btn-success waves-effect waves-light m-l-10"
                                                            value="edit">Save
                                                    </button>
                                            </div>
                                        </div>
                                    </div>

                            </div> <!-- panel-body -->
                        </div> <!-- panel -->
                    </div> <!-- col -->

                </div>
  <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>

                                    <th>#</th>
                                    <th>File Header</th>
                                    <th>Search Filter</th>
                                    <th>Dialing Column</th>
                                    <th>Visible</th>
                                    <th>Editable</th>
                                    <th>Label</th>
                                </th>
                                </thead>
                                 <tbody>

                                    @php
                                    $i=0;
                                    @endphp

                                        @foreach ($lists->list_header as $key => $value)

                                            <tr>
                                                <td>{{$i}}</td>
                                                <td><input type="hidden" name="id[]" value="<?php echo $value->id; ?>"/><?php echo $value->header; ?>
                                                    <input type="hidden" name="column_name[{{$i}}]" value="<?php echo $value->column_name; ?>"/>
                                                </td>
                                                <td><input type="checkbox" @if($value->is_search == '1') checked  @endif  value="1" name="is_search[{{$i}}]"/></td>
                                                <td><input required type="radio"  @if($value->is_dialing == '1')  checked  @endif name="is_dialing" required value="{{$value->id}}"/></td>
                                                <td><input type="checkbox"  @if($value->is_visible == '1')  checked  @endif name="is_visible[{{$i}}]" value="1" /></td>
                                                <td><input type="checkbox"  @if($value->is_editable == '1')  checked  @endif name="is_editable[{{$i}}]" value="1" /></td>
                                                <td>
                                                    <select name="label_id[]"
                                                            class="form-control" >
                                                        <option value="">Select Label</option>

                                                        <?php foreach ($label as $listKey => $listValue) { ?>
                                                            <option @if($value->label_id == $listValue->id) selected @endif  value="<?php echo $listValue->id; ?>"><?php echo $listValue->title; ?></option>
                                                        <?php } ?>

                                                    </select>
                                                </td>






                                            </tr>

                                            @php
                                    $i++;
                                    @endphp

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- <script>
    $("input[name='is_dialing']").change(function(){

        value = $(".is_dialing").val();
        alert(value);

});
</script> -->
@endsection
