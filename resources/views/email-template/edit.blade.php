@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

         <section class="content-header">
                <h1>
                   <b>Edit Email Templete</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    
                    <li class="active">Edit Email Templete</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <a href="{{ url('/email-templates') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Email Templete</a>
           </div>
        </section>
    
       

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <form method="post" name="userform" id="userform" action="">
                            @csrf
                            <div class="box-body">
                                <div class="modal-body">
                                    <div class="form-group m-b-10">
                                        <div class="col-md-3">
                                            <label>Templete Name <i data-toggle="tooltip" data-placement="right" title="Type email template name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" value="{{$email_template['template_name']}}" id="template_name" class="form-control" name="template_name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Lead Placeholders <i data-toggle="tooltip" data-placement="right" title="Select label from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <span id="setBoxValue" style="display:none;"></span>
                                                <select id="multiple_labels" class="form-control" autocomplete="off" style="width: 100%;">
                                                    <option value="">Select to Insert</option>
                                                    @foreach($label_list as $list)
                                                        <option value="[[{{ $list->title }}]]">{{$list->title}}</option>
                                                    @endforeach;
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Sender Placeholders <i data-toggle="tooltip" data-placement="right" title="Select Sender details" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select id="multiple_names" class="form-control" autocomplete="off" style="width: 100%;">
                                                    <option value="">Select to Insert</option>
                                                    @foreach($user_column as $user_list)
                                                        <option value="[[{{ $user_list }}]]">{{$user_list}}</option>
                                                    @endforeach;
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                        <label>Custom Placeholders <i data-toggle="tooltip" data-placement="right" title="Select Sender details" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select id="multiple_custom_names" class="form-control" autocomplete="off" style="width: 100%;">
                                                <option value="">Select to Insert</option>
                                                @foreach($custom_field_labels as $label_list)
                                                <option value="[[{{ $label_list->title }}]]">{{$label_list->title}}</option>
                                                @endforeach;
                                            </select>
                                        </div>
                                    </div>


                                       

                                         <div class="col-md-12">
                                            <label>Subject <i data-toggle="tooltip" data-placement="right" title="Type subject for email template" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" class="form-control" value="{{$email_template['subject']}}" name="subject" id="subject_box" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Templete Preview <i data-toggle="tooltip" data-placement="right" title="Add or modify Templete Preview" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <textarea type="text" class="form-control" name="template_html" id="editor1">{{$email_template['template_html']}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Save</button>
                                </div>
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
<script src="{{ asset("asset/plugins/ckeditor/ckeditor.js") }}"></script>
<script language="javascript">
$(function () {
    CKEDITOR.config.autoParagraph = false;
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_P;
    CKEDITOR.replace('editor1', {
        enterMode: CKEDITOR.ENTER_BR,
        filebrowserUploadUrl: "{{route('start-dialing.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
        allowedContent: true
    });

    $("#multiple_labels").on('change', function () {
        console.log($(this).val());
        var hidden_box = $('#setBoxValue').html();
        if (hidden_box == 'subject_box') {

            var cursorPos = $('#subject_box').prop('selectionStart');
            var v = $('#subject_box').val();
            var textBefore = v.substring(0, cursorPos);
            var textAfter = v.substring(cursorPos, v.length);
            $('#subject_box').val(textBefore + $(this).val() + textAfter);
        } else {
            for (var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].insertHtml($(this).val());
            }
        }
    });

    $("#multiple_names").on('change', function () {
        console.log($(this).val());
        var hidden_box = $('#setBoxValue').html();
        if (hidden_box == 'subject_box') {

            var cursorPos = $('#subject_box').prop('selectionStart');
            var v = $('#subject_box').val();
            var textBefore = v.substring(0, cursorPos);
            var textAfter = v.substring(cursorPos, v.length);
            $('#subject_box').val(textBefore + $(this).val() + textAfter);
        } else {

        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].insertHtml($(this).val());
        }

    }
    });

    $("#multiple_custom_names").on('change', function () {
        console.log($(this).val());
        var hidden_box = $('#setBoxValue').html();
        if (hidden_box == 'subject_box') {

            var cursorPos = $('#subject_box').prop('selectionStart');
            var v = $('#subject_box').val();
            var textBefore = v.substring(0, cursorPos);
            var textAfter = v.substring(cursorPos, v.length);
            $('#subject_box').val(textBefore + $(this).val() + textAfter);
        } else {

        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].insertHtml($(this).val());
        }

    }
    });

    CKEDITOR.instances['editor1'].on('contentDom', function () {
        this.document.on('click', function (event) {
            $('#setBoxValue').html('');
        });
    });
});

$('#subject_box').on('click', function () {
    $('#setBoxValue').html('subject_box');
});


</script>
@endpush

