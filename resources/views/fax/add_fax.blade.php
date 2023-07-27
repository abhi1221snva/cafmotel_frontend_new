@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
                <h1>
                   <b>Send New Fax</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Fax</li>
                    
                    <li class="active">Send New Fax</li>
                </ol>
        </section>
        

    <!-- Main content -->
    <section class="content">
        <div id="faxpageloader">
            <img src="{{URL::asset('/asset/img/loading.gif')}}" alt="profile Pic" >

        </div>

        <div class="row">
            <div class="col-md-3">
                @include('fax.menu',['page_title' => 'My Amazing Site'])       
            </div>

            <!-- /.col -->

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Send New Fax</h3>
                    </div>
                    <!-- /.box-header -->
                    <form method="post" id="myform" action="{{url('save-fax')}}" enctype="multipart/form-data">

                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="width:322px;">
                                        <label>Select the phone number you wish to send fax from: <i data-toggle="tooltip" data-placement="right" title="Select the phone number you wish to send fax from" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select name="from_id" class="form-control" id="from_id" required="">
                                            <option value="">Select Any</option>
                                             @if (is_array($group))
                                                @foreach($group as $did)
                                                <option value="{{$did->did}}">{{$did->did}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group" >
                                        <label>Recipient Fax Number : <i data-toggle="tooltip" data-placement="right" title="Type recipient Fax Number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <input placeholder="Recipient Fax Number" type="text" class="form-control" name="to_id" id="to_id" required="" data-inputmask="'mask': '(999) 999-9999'" data-mask="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"></div>
                            <div class="form-group">
                                <input type="file" name="pdf_file" id="pdf_file">
                                <p class="help-block">Max. 10MB (Only pdf files are allowed)</p>
                            </div>
                        </div>

                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">                            
                                <button type="submit" name="submit" value="add"  class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                            </div>
                            <button type="reset" class="btn btn-default"><a href="{{ url('/fax-list') }}"><i class="fa fa-times"></i> Discard</a></button>
                        </div>
                    </form>
                    <!-- /.box-footer -->
                </div>
                <!-- /. box -->
            </div>

            <!-- /.col -->
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#to_id').inputmask("(999) 999-9999");
        $("#myform").on("submit", function () {
            $("#faxpageloader").fadeIn();
        });//submit
    });//document ready
</script>
<!-- InputMask -->
<script src="{{url('/asset/js/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{url('/asset/js/input-mask/jquery.inputmask.extensions.js')}}"></script>

<style>
    #faxpageloader
    {
        background: rgba( 255, 255, 255, 0.8 );
        display: none;
        height: 100%;
        position: fixed;
        width: 100%;
        z-index: 9999;
    }

    #faxpageloader img
    {
        left: 19%;
        margin-left: -32px;
        margin-top: -5%;
        position: absolute;
    }
</style>
@endsection