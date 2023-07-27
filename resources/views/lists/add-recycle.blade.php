@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->


<div class="content-wrapper">
    <!-- Content Header (Page header) -->


     <section class="content-header">
                <h1>
                   <b>Add Recycle Rule</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Lead Management</li>
                    <li class="active"> Add Recycle Rule</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                 <a href="{{ url('/recycle-rule') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Recycle Rule</a>
            
           </div>
        </section>
    

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-xs-12">
                <div class="box">
                   
                    <div class="box-body">
                         <form method="post" action="">
                            @csrf
                         	
                <div class="modal-body">
                    <div class="form-group m-b-10">
                        <div class="col-md-6">

                        	
                            <label>Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-group col-md-12">
                            <select name="campaign_id" class="form-control" id="campaign" required="">
                                

                                        <?php foreach ($campaign_list as $key=>$value){
                                            if(!empty($value->title)){?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->title;?></option>

                                        <?php } }?>
                            </select>
                        </div>
                        </div>
                        <div class="col-md-6">
                            <label>List <i data-toggle="tooltip" data-placement="right" title="Select list name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-group col-md-12">
                            <select name="list_id" class="form-control" id="list-rule" required="">
                                <option value="">Select Lists</option>
                                <?php foreach ($list_details as $key=>$value){?>
                                                        <option value="<?php echo $value->list_id;?>"><?php echo $value->list;?></option>
                                                    <?php }?>

                            </select>
                        </div>
                        </div>
                    </div>
                    <div class="form-group m-b-10">
                        <div class="col-md-6">
                            <label>Disposition <i data-toggle="tooltip" data-placement="right" title="Select multiple disposition name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-group col-md-12">
                            <select class="select2" multiple="multiple" name="disposition_id[]" autocomplete="off" data-placeholder="Select Disposition" style="width: 100%;">
                           
                                <option value="">select disposition</option>
                                                    <?php foreach ($disposition_list as $key=>$value){?>
                                                        <option value="<?php echo $value->id;?>"><?php echo $value->title;?></option>
                                                    <?php }?>
                            </select>
                        </div>
                        </div>

                         <div class="col-md-6">
                            <label>Time <i data-toggle="tooltip" data-placement="right" title="Choose call time" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-group col-md-12">
                            <input type="time" class="form-control" value="09:30" name="time" id="timepicker">
                        </div>
                        </div>

                         <div class="col-md-6">
                            <label>Day <i data-toggle="tooltip" data-placement="right" title="Select multiple days" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-group col-md-12" id="date-range1">
                                                <select class="select2" multiple="multiple" name="days[]" autocomplete="off" data-placeholder="Select Days" style="width: 100%;">
                                                
                                                    <option value="sunday">Sunday</option>
                                                    <option value="monday">Monday</option>
                                                    <option value="tuesday">Tuesday</option>
                                                    <option value="wednesday">Wednesday</option>
                                                    <option value="thursday">Thursday</option>
                                                    <option value="friday">Friday</option>
                                                    <option value="saturday">Saturday</option>
                                                </select>
                                            </div>
                                        </div>
                        </div>


                         <div class="col-md-6">
                            <label>Call Time <i data-toggle="tooltip" data-placement="right" title="Select call time from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                        <div class="input-group col-md-12">
                        <select name="call_time" class="form-control" id="calltime-rule" required="">
                            <option value="1">1</option>
                            <option value="2"> less than or equal to 2</option>
                            <option value="3"> less than or equal to 3</option>
                            <option value="4">less than or equal to 4</option>
                            <option value="5">less than or equal to 5</option>
                            <option value="6">less than or equal to 6</option>
                            <option value="7">less than or equal to 7</option>
                            <option value="8">less than or equal to 8</option>
                            <option value="9">less than or equal to 9</option>
                            <option value="10">less than or equal to 10</option>
                            <option value="11">less than or equal to 11</option>
                            <option value="12">less than or equal to 12</option>
                            <option value="13">less than or equal to 13</option>
                            <option value="14">less than or equal to 14</option>
                            <option value="15">less than or equal to 15</option>
                        </select>
                        </div>
                        </div>

                        
                    </div>
                  
                    <div style="clear: both"></div>
                </div>
                <div class="modal-footer">
                   
                    <button type="submit" name ="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Save</button>
                   
                </div>
            </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

               
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection