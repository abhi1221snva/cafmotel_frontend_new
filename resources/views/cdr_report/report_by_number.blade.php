@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <section class="content-header">
                <h1>
                    <b> Call Reports </b>
                    
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> Call Data Reports</li>
                    <li class="active"> Call Reports</li>
                </ol>
        </section>
   
    <section class="content-header">
         <div class="text-right mt-5 mb-3"> 
           <a href="{{url('/lead')}}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Search Lead</a>
        </div>
             
            
        
    </section>

    <!-- Main content -->
    <section class="content">
 <div class="row">

    

           <?php  if(!empty($report)){ 


           
          if($lower_limit == '0'){
            $lower_limit =0;
          }


           if($lower_limit > 0){
            $lower_limit = $lower_limit -10;
          }

           $currentPage  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            // Items per page
            $perPage      = 10;

// Get current items calculated with per page and current page
//$currentItems = array_slice($report, $perPage * ($currentPage - 1), $perPage);

// Create paginator
//$paginator = new Illuminate\Pagination\Paginator($report, 10, $currentPage);

$paginator = new Illuminate\Pagination\LengthAwarePaginator($report, $record_count, $perPage, $currentPage,['path'=>url('report/6473621646')]);

 //echo "<pre>";print_r($report);
     //       echo "<pre>";print_r($record_count);die;


            ?>



            <div class="col-xs-12">


                <div class="box">

                  




                    
                    <div class="box-body">
                        <b>Total Rows :<?= $record_count ?></b>
                         <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                               
                                <th>Extension</th>
                                <th>Campaign</th>

                                <th>Route</th>
                                <th>Type</th>
                                <th>Number</th>
                               <th>Duration</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                              
                               <th>Recording</th>


                            </tr>
                            </thead>
                            <tbody>
                               
                                <?php 


                               
                                $k=$lower_limit;
                                foreach ($paginator->items() as $key=>$value){ ?>
                                    <tr>
                                        <td><?php echo ++$k; ?></td>
                                        <td><?php echo $value->extension;?></td>
                                        <td><?php echo $value->campaign_id;?></td>

                                        <td><?php echo $value->route;?></td>
                                        <td><?php echo $value->type;?></td>
                                        
                                        <td><?php echo $value->number;?></td>
                                        <td><?php echo $value->duration;?></td>
                                        <td><?php echo $value->start_time;?></td>
                                        <td><?php echo $value->end_time;?></td>
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
                            
                         <?=$paginator->render()?>
                        </table>
                        

                      
                         
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

              
            </div><!-- /.col -->

        <?php }?>







 
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection