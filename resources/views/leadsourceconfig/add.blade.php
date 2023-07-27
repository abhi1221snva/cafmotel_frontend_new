@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<style>
        .wrap
        {
            white-space: pre-wrap;      /* CSS3 */   
            white-space: -moz-pre-wrap; /* Firefox */    
            white-space: -pre-wrap;     /* Opera <7 */   
            white-space: -o-pre-wrap;   /* Opera 7 */    
            word-wrap: break-word;      /* IE */
        }
    </style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content-header">
                <h1>
                   <b>Add Lead Source</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active">Add Lead Source</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="{{ url('/lead-source-configs') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Api List</a>
           </div>
        </section>
    
    <!-- Main content -->
    <section class="content">
        <div class="row">
            @include("layouts.messaging")
            <div class="col-xs-12">
                <div class="box">
                    <form method="post" name="userform" id="userform" action="">
                        @csrf
                        <div class="box-body">
                            <div class="modal-body">
                                <div class="form-group m-b-10">

                                     <input type="hidden" value="{{$Api_key}}"  class="form-control" name="api_key" id="api_key">

                                     <input type="hidden" value="{{ Session::get('parentId')}}"  class="form-control" name="client_id" id="client_id" >

                                     

                                    <div class="col-md-3">
                                        <label>Title <i data-toggle="tooltip" data-placement="right" title="Type lead source title" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                           <input type="" class="form-control" value="{{old('title')}}"  name="title" id="title">
                                          
                                        </div>
                                    </div> 

                                    <div class="col-md-3">
                                        <label>Description <i data-toggle="tooltip" data-placement="right" title="Type lead source description" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                           <input type="" class="form-control" value="{{old('description')}}" name="description" id="description">
                                           
                                        </div>
                                    </div> 
                                   
                                    <div class="col-md-3">
                                        <label>Select List <i data-toggle="tooltip" data-placement="right" title="Select list from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <span id="setBoxValue" style="display:none;"></span>
                                            <select id="list_id" required  onchange="makeAPI(this.value,'{{$Api_key}}');" class="form-control" name="list_id" autocomplete="off" style="width: 100%;">
                                                <option value="">Please Select List</option>
                                                @foreach($list_details as $list)
                                                <option value="{{ $list->list_id }}">{{$list->list}}</option>
                                                @endforeach;
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="input-daterange input-group col-md-12">
                                            <button style="margin-top:24px;" type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Save</button>
                                        </div>
                                    </div>

                                </div>


                            </div>
                            <div style="clear: both"></div>
                            <h4  class="wrap" style="margin: 30px 30px 30px 30px;display: none;">API Url :</h4>
                                    <p  style="margin: 30px;margin-top: -22px;" class="wrap"  id="showUrl"></p>
                           
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@push('scripts')
<script language="javascript">

    function makeAPI(value,api_key)
    {
        api_key = api_key;
        list_id = value;
        domain_url = "{{ env('PORTAL_NAME') }}";
        //domain_url = 'https://phone.performancemedia.cloud/';

        $.ajax({
            url: 'getLeadHeader/' +list_id,
            type: 'get',
            success: function (response)
            {
                var text = "";
                for (i = 0; i < response.length; i++)
                {
                    text += response[i].header + "=$"+response[i].header+"&";
                }

                url = domain_url+'insertLeadSource?token='+api_key+'&';
                $(".wrap").show();
                $("#showUrl").html(url+''+text.toLowerCase().split(' ').join('_').slice(0, -1));
            }
        });
    }

</script>
@endpush

