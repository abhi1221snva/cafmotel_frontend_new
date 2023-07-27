@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


      <section class="content-header">
                <h1>
                    <b>Power Dial Calls</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Call Data Reports</li>
                    <li class="active"> Power Dial Calls</li>
                </ol>
        </section>
    

    <!-- Main content -->
    <section class="content">
 <div class="row">

          <div class="col-xs-12">

                
                <div class="box">

                  




                    
                    <div class="box-body">

<form class="form-inline" method="post">

      @csrf
                                    <div class="form-group col-md-2">
                                        <label>Mobile</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <input class="form-control" type="number" name="mobile" value="" id="mobile" maxlength="12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Extension</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select class="form-control" name="extension" id="extension">
                                                    <option>Select</option>

                                                    @foreach($extension_list as $ext)



                                                    <option value="{{$ext->extension}}">{{$ext->extension}}</option>

                                                    @endforeach
                                                   
                                                     </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Campaign</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select name="campaign" class="form-control" id="campaign" onchange="getDisposition();">
                                                    <option value="">Select</option>
                                                    @foreach($campaign_list as $campaign)



                                                    <option value="{{$campaign->id}}">{{$campaign->name}}</option>

                                                    @endforeach
                                                    
                                                                                                            
                                                  </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group m-l-10">
                                        <label>Disposition</label>
                                      <div>
                                            <div class="input-daterange input-group" id="date-range2">
                                                <select name="disposition[]" class="form-control" id="disposition"  >
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group m-l-10">
                                        <label>Route</label>
                                        <div>
                                            <div class="input-daterange input-group" id="date-range1">
                                                <select name="route" class="form-control" id="route">
                                                    <option value="">Select</option>
                                                    <option value="IN">IN</option>
                                                    <option value="OUT">OUT</option>
                                                    <option value="TRANSFER">TRANSFER</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-l-10">
                                        <label>Date Range</label>
                                        <div>
                                            <div class="input-daterange input-group datepicker" id="date-range" style="width: 270px;">
                                                <input type="text" class="form-control col-md-6" id="start_date" name="start" value="">
                                                <span class="input-group-addon bg-primary text-white b-0">to</span>
                                                <input type="text" class="form-control col-md-6 datepicker" id="end_date" name="end" value="">
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

                        
                    </div>
                </div>
            </div>





     



            <div class="col-xs-12">


                <div class="box">

                  




                    
                    <div class="box-body">


                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                               
                                <th>Extension</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>Disposition</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                               <th>Duration</th>
                               <th>Route</th>
                               <th>Recording</th>


                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $k=0;
                                foreach ($pdc as $key=>$value){ ?>
                                    <tr>
                                        <td><?php echo ++$k; ?></td>
                                        <td><?php echo $value->exten;?></td>
                                        <td><?php echo $value->name;?></td>
                                        <td><?php echo $value->mobile;?></td>
                                        <td><?php echo $value->disposition;?></td>
                                        <td><?php echo $value->starttime;?></td>
                                        <td><?php echo $value->endtime;?></td>
                                        <td><?php echo $value->duration;?></td>
                                        <td><?php echo $value->route;?></td>
                                        <td><audio controls preload ='none'><source src="<?php echo $value->call_recording;?>" type='audio/wav'></audio></td>
                                        <!-- <td>
                                            <?php if(!empty($value->lead_id)){?>
                                                <a href="lead_detail.php?id=<?php echo $value->lead_id;?>" target="_blank"><span class="glyphicon glyphicon-search"></span></a>
                                        <?php } else { ?>
                                                NA
                                        <?php } ?>
                                        </td> -->
                                    </tr>
                                <?php } ?>
                            </tbody>
                         
                        </table>

                         
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

              
            </div><!-- /.col -->







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


                            <input type="hidden" class="form-control" name="username" value="{{$userdetails->username}}" id ="first-name" required>

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