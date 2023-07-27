

@extends('layouts.app')

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                   <b>Send Mails</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Configuration</li>
                    
                    <li class="active">Send Mails</li>
                </ol>
        </section>
       

    <!-- Main content -->
    <section class="content">
        <div class="row">   
                 <div class="modal-body">
                    <form method="post" action="">
                         @csrf
                                   <div class="box-body">

                         <div class="form-group row">

                              <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">SMTP</label>
                            <select name="result_arr[host]"  required="" class="form-control" id="smtp_host" >
                            <option value="">Select SMTP</option>
                            @foreach($smtp_list as $record)
                            <option value="{{$record->mail_host}}">{{$record->mail_host}}</option>
                            @endforeach
                            
                            
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Subject</label>
                            <input type="text" class="form-control" required  name="result_arr[subject]" id="title" placeholder="Enter Name">
                        </div>

                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">From</label>
                            <input type="text" class="form-control" required  name="result_arr[from]" id="title" placeholder="Enter Name">
                        </div>

                         <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">To</label>
                            <input type="text" class="form-control" required  name="result_arr[to_email]" id="title" placeholder="Enter Name">
                        </div>

                       
                         <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Template Name</label>
                            <select name="result_arr[template_name]"  required="" onchange="editorValue(this.value);" class="form-control" id="result_arr['template_name']" >
                            <option value="">Select Template</option>
                              @foreach($template_list as $record)
                            <option value="{{$record->id}}">{{$record->template_name}}</option>
                            
                                 @endforeach
                            
                            
                            </select>
                        </div>


                          <div class="col-md-4">
                            <label>Labels</label>
                            <div class="input-daterange input-group col-md-12">
                            <select id="multiple_labels" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Labels" style="width: 100%;">
                                 @foreach($label_list as $list)
                                    <option value="<?php echo '{{$'.$list->title.'}}' ?>">{{$list->title}}</option>
                                @endforeach;
                            </select>
                            </div>
                        </div> 
                         <div class="col-md-4">
                            <label>Sender Details</label>
                            <div class="input-daterange input-group col-md-12">
                            <select id="multiple_names" class="select2"  multiple="multiple"  autocomplete="off" data-placeholder="Select Names" style="width: 100%;">
                                @foreach($user_column as $user_list)
                                    <option value="<?php echo '{{$'.$user_list.'}}' ?>">{{$user_list}}</option>
                                @endforeach;
                            </select>
                            </div>
                        </div>



                        <div class="col-md-12">
                             <label>Templete Preview</label>
                            <div class="input-daterange input-group col-md-12">
                               <textarea type="text" class="form-control" name="result_arr[template_html]" id ="editor1"></textarea>
                                           
                    
                            </div>
                        </div>




                      

                        
                    </div>


                



                    <button type="submit" name ="submit"  class="btn btn btn-primary waves-effect waves-light">Save</button>
                  

                    </div><!-- /.box-body -->
                </form>
        </div>
       
            </div>

@push('scripts')

<script src="{{ asset('asset/js/ckeditor/ckeditor.js') }}"></script>





  <script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
  CKEDITOR.replace('editor1')
    //bootstrap WYSIHTML5 - text editor
   /* $('.textarea').wysihtml5()*/
  })
</script>
<script>



    function editorValue(val){
      

          $.ajax({
            url: 'findTemplateValue/' + val,
            type: 'get',
            success: function(response) {

               // $("#editor1").html();

                CKEDITOR.instances['editor1'].setData(response[0].template_html);
              //  window.location.reload(1);
                // alert(response);
            }
        });
    }


    function displayVals() {
       // $( "#editor1" ).val('');
        var singleValues = CKEDITOR.instances['editor1'].getData();
        var multipleValues = $( "#multiple_labels" ).val() || [];

                CKEDITOR.instances['editor1'].setData(singleValues+' '+ multipleValues.join( " " ) );

        //$( "#editor1" ).val(singleValues+' '+ multipleValues.join( " " ) );

        $( "#multiple_labels" ).val('');
    }
    $( "#multiple_labels" ).on('change',function(){ 
        displayVals();
    });


     function displayValsNames() {
        var singleValues = CKEDITOR.instances['editor1'].getData();
        //alert('abhi')
        //alert(singleValues);
        var multipleValues = $( "#multiple_names" ).val() || [];
        var input = singleValues+','+ multipleValues.join( " " );
        var splitted = input.split(',');
        var collector = {};

        for (i = 0; i < splitted.length; i++) {
            key = splitted[i].replace(/^\s*/, "").replace(/\s*$/, "");
            collector[key] = true;
        }

        var out = [];
        for (var key in collector) {
            out.push(key);
        }
        var output = out.join('');
        //alert(output);
       // $( "#editor1" ).val(output);
                CKEDITOR.instances['editor1'].setData(output);

        $( "#multiple_names" ).val('');

    }

    $( "#multiple_names" ).on('change',function(){ 
        displayValsNames();
    });





</script>

@endpush
@endsection


