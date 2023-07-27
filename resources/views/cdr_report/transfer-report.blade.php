@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

     <section class="content-header">
                <h1>
                    <b>Call Transfer</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Call Data Reports</li>
                    <li class="active">Call Transfer</li>
                </ol>
        </section>
   

    <!-- Main content -->
    <section class="content">

        <div class="row">

            
        <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <form class="form-inline" method="post">
                                    @csrf
                                    <div class="form-group col-md-2">
                                        <label>Mobile <i data-toggle="tooltip" data-placement="right" title="Type phone number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <input class="form-control" type="text" name="mobile" value="" id="mobile" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Extension <i data-toggle="tooltip" data-placement="right" title="Select extension list" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select class="form-control" name="extension" id="extension">
    <option value="">Select</option>
	@isset($extension_list)
     @foreach($extension_list as $key => $extension)
                                                    <option value="{{$extension->id}}">{{$extension->extension}}</option>
                                                    @endforeach
    @endisset
</select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Campaign <i data-toggle="tooltip" data-placement="right" title="Select campaign list" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                               <select name="campaign" class="form-control" id="campaign">
    <option value="">Select</option>
       @foreach($campaign_list as $key => $campaign)
       @if(!empty($campaign->title))
                                                    <option value="{{$campaign->id}}">{{$campaign->title}}</option>
                                                    @endif
                                                    @endforeach
</select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Status <i data-toggle="tooltip" data-placement="right" title="Select status" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select name="exten_status" class="form-control" id="campaign">
                                                    <option value="">Select</option>
                                                    <option value="CANCEL">CANCEL</option>
                                                    <option value="BUSY">BUSY</option>
                                                    <option value="ANSWER">ANSWER</option>
                                                    <option value="CHAN UNAVAIL">CHAN UNAVAIL</option>
                                                    <option value="NO ANSWER">NO ANSWER</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Date Range <i data-toggle="tooltip" data-placement="right" title="Select date range for call report" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range" style="width: 270px;">
                                                <input type="text" autocomplete="off" class="form-control col-md-6 datepicker" id="start_date" name="start" value="">
                                                <span class="input-group-addon bg-primary text-white b-0">to</span>
                                                <input type="text" autocomplete="off" class="form-control col-md-6 datepicker" id="end_date" name="end" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label></label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <button type="submit" name="submit" class="btn btn-success waves-effect waves-light m-l-10" value="Search">Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> <!-- panel-body -->
                        </div> <!-- panel -->
                    </div>

                      <?php if(!empty($transferData)){ ?>
                    
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-body">

                       
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                               
                                <th>Extension</th>
                                <th>Transferred</th>
                                <th>Campaign</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Start Time</th>
                               <th>Recording</th>
                               <th>Second Recording</th>
                               <th>Action</th>


                            </tr>
                            </thead>
                            <tbody>
                            @isset($transferData)
                                <?php 
                                $k=0;

                               
                                foreach ($transferData as $key=>$value){ ?>
                                    <tr>
                                        <td><?php echo ++$k; ?></td>
                                        <td><?php echo $value->extension;?></td>
                                        <td><?php echo $value->forward_extension;?></td>
                                        <td><?php echo $value->campaign;?></td>
                                        <td><?php echo $value->number;?></td>
                                        <td><?php echo $value->status;?></td>
                                        <td><?php echo $value->datetime;?></td>

                                      
                                       
                                    </tr>
                              
                            </tbody>
                         
                        </table>

                          <?php } ?>
                          @endisset

                         
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

              
            </div><!-- /.col -->

        <?php }?>







 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="add-edit"></h4>
        </div>
                <div class="modal-body">
                    <form method="post" action="">
                            @csrf
                    <div class="box-body">

                         <input type="hidden" class="form-control" name="disposition" value="" id ="id" required>


                            <input type="hidden" class="form-control" name="username" value="" id ="first-name" required>

                         <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputEmail3" class="col-form-label">Name</label>
                            <input type="text" class="form-control" required  name="name" id="name" placeholder="Enter Name">
                        </div>

                        
                    </div>


                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label for="inputPassword3"  id="" class="col-form-label">Status</label>
                    
                      <select class="form-control" required id="status" name="status" >

                          
                          <option value="1">Active</option>
                          <option value="0">Inactive</option>

                      </select>
                        </div>

                       
                    </div>



                    <button type="submit" name ="submit"  class="btn btn btn-primary waves-effect waves-light">Save</button>
                  

                    </div><!-- /.box-body -->
                </form>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
      
    </div>
  </div>
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection