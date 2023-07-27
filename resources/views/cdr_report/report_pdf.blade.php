
    <div id="thanks"style="text-align:center;"><h3>Call Reports for Date :{{$start_date}} to {{$end_date}}<h3></div>
               
    <main>
      <div id="details" class="clearfix">
        
        
      </div>
      
      <table style="font-family:Arial,Helvetica,sans-serif;border-collapse:collapse;width:100%">
                            <tr>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">#</th>  

                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Extension</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Campaign</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Route</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Type</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Number</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Disposition</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Duration</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">Start Time</th>
                                <th style="padding: 8px;border: 1px solid #ddd;padding-top: 12px;padding-bottom: 12px;text-align: left;background-color: #444444;color: white;">End Time</th>
                            </tr>   
                            <?php 
             $k=$lower_limit;
             foreach ($report as $key=>$value){ ?>
          <tr>
              <td style="border: 1px solid #ddd;padding: 8px;"><?php echo ++$k; ?></td>
              <td style="border: 1px solid #ddd;padding: 8px;"><h3><?php echo $value->extension;?></h3></td>
            <?php if($value->type =='manual') { ?>
                                              <td style="border: 1px solid #ddd;padding: 8px;">N/A</td>

                                        <?php }
                                        else if($value->type =='dialer'){
                                        if(!empty($value->campaign_id)) {
                                            foreach($campaign_list as $key => $campaign){
                                                if($campaign->id == $value->campaign_id){ ?>
                                                       <td style="border: 1px solid #ddd;padding: 8px;"><?php echo $campaign->title;?></td>
                                                <?php }
                                             }
                                             
                                         }else { ?>
                                              <td style="border: 1px solid #ddd;padding: 8px;">N/A</td>
                                         <?php }
                                         } ?>
              <td style="border: 1px solid #ddd;padding: 8px;"><?php echo $value->route;?></td>
                                          <td style="border: 1px solid #ddd;padding: 8px;"><?php echo $value->type;?></td>
                                        
                                         <td style="border: 1px solid #ddd;padding: 8px;"><?php echo $value->number;?></td>
                                          <td style="border: 1px solid #ddd;padding: 8px;"><?php
                                                if (!empty($value->disposition_id)) {
                                                    // echo "<pre>";print_r($disposition_list);die;
                                                    foreach ($disposition_list as $key => $dispo) {
                                                        if ($dispo->id == $value->disposition_id) {
                                                            ?>
                                                            <?php echo $dispo->title; ?>
                                                            <?php
                                                        }
                 
                                                    }
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?></td>

                                          <td style="border: 1px solid #ddd;padding: 8px;"><?php echo  gmdate("H:i:s",$value->duration);?></td>
                                          <td style="border: 1px solid #ddd;padding: 8px;"><?php echo $value->start_time;?></td>
                                          <td style="border: 1px solid #ddd;padding: 8px;"><?php echo $value->end_time;?></td>

          </tr>
      <?php }?>
                        </table>
    </main>
    <!-- <footer>
      © Copyright <?php echo date('Y'); ?> {{$company_name}} .
    </footer> -->
  </body>
</html>