@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">

         <section class="content-header">
                <h1>
                   <b>Marketing Campaign</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>

                    <li class="active">Marketing Campaign List</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a id="openMarketingForm" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Campaign</a>
           </div>
        </section>
        <!-- Content Header (Page header) -->
        <section class="content-header">
           
           <div class="box box-default" style="display: none" id="add_marketing_campaign">

        <!-- /.box-header -->
        <div class="box-body" >
          <div class="row">
            <form method="post">
                    @csrf

               <input type="hidden" class="form-control" required name="marketing_id" id="marketing_id">

            <div class="col-md-4">
              <div class="form-group">
                <label>Title <i data-toggle="tooltip" data-placement="right" title="Type marketing campaign title" class="fa fa-info-circle" aria-hidden="true"></i></label>
               <input type="text" class="form-control" required name="title" id="title">
              </div>

            </div>
            <!-- /.col -->
            <div class="col-md-7">
              <div class="form-group">
                <label>Description <i data-toggle="tooltip" data-placement="right" title="type marketing campaign description" class="fa fa-info-circle" aria-hidden="true"></i></label>
               <input type="text" class="form-control" required name="description" id="description">

              </div>

            </div>

               <div class="col-md-1">
              <div class="form-group">
                <label></label>
               <button type="submit"  class="form-control btn btn-primary btn-sm"  id="saveMarketingCampaign">Save</button>

              </div>

            </div>
        </form>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->


      </div>


        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="example" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Campaign Name</th>
                                    <th>Description</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(!empty($marketing_campaigns))
                                @foreach($marketing_campaigns as $key => $campaigns)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$campaigns->title}}</td>



                                    <td>{{$campaigns->description}}</td>
                                    <td><a style="cursor:pointer;color:blue;;" title="Edit Marketing Campaign" data-marketingid={{$campaigns->id}}  class='editMC'><i class="fa fa-edit fa-lg"></i></a>
                                        |
                                        <a style="cursor:pointer;color:blue;;" href="/marketing-campaign/{{$campaigns->id}}/schedules" title="View Marketing Campaign Schedule List" data-marketingid={{$campaigns->id}}><i class="fa fa-list fa-lg" aria-hidden="true"></i></a>
                                        |
                                        <a style="cursor:pointer;color:blue;;" title="Add Text Schedule" data-marketingid={{$campaigns->id}}  class='addSmsSchedule'><i class="fa fa-commenting-o fa-lg"></i></a> |
                                        <a style="cursor:pointer;color:blue;;"  title="Add Email Schedule"  data-marketingid={{$campaigns->id}} class='addEmailSchedule'><i class="fa fa-envelope fa-lg"></i></a>

                                       </td>

                                </tr>

                                @endforeach
                                @endif
                            </tbody>

                        </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

        <div class="modal fade" id="addEmailScheduleModel" role="dialog">

                <!-- Modal content-->

                <div class="modal-dialog">
                    <div class="modal-content" >
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Email Schedule</h4>
                        </div>
                        <div class="modal-body">


                            <input type="hidden"  class="form-control" name="send" id="send" value="1"/>

                                            <input type="hidden"  class="form-control" name="campaign_id" id="campaign_id" value=""/>


                                             <div class="form-group">
                                            <label>Select List <i data-toggle="tooltip" data-placement="right" title="Select list name" class="fa fa-info-circle" aria-hidden="true"></i>  </label>

                                                <select class="form-control" name="list_id" id="list_id">
                                                    <option value="">Select List</option>
                                                    @if(!empty($list))
                                                        @foreach($list as $key => $clists)
                                                            <option value="{{$clists->list_id}}" >{{$clists->list}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span style="color:red;" id="errorlist_id"></span>

                                                </div>


                                                <div class="form-group" id="hidden_listheader" style="display: none;">
                                            <label>Select column having email <i data-toggle="tooltip" data-placement="right" title="Select column having email for send email message" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                                <select class="form-control" name="listheader" id="listheader">


                                                    </select>
                                                <span style="color:red;" id="errorlistheader"></span>

                                                </div>







                                             <div class="form-group">
                                                <label>Email Template <i data-toggle="tooltip" data-placement="right" title="Select email template" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="email_template_id" id="email_template_id">
                                                    <option value="">Select Template</option>
                                                    @if(!empty($email_templates))
                                                        @foreach($email_templates as $key => $clists)
                                                            <option value="{{$clists->id}}" >{{$clists->template_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span style="color:red;" id="erroremail_template_id"></span>

                                            </div>


                                            </div>

                                            <div class="form-group">
                                                <label>SMTP for sending email [Host - From Email - From Name] <i data-toggle="tooltip" data-placement="right" title="Select SMTP setting" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="email_setting_id" id="email_setting_id">
                                                    <option value="">Email Setting</option>
                                                    @if(!empty($smtp_setting))
                                                        @foreach($smtp_setting as $skey => $clists)
                                                            <option value="{{$clists->id}}" >{{$clists->mail_host}}:{{$clists->mail_port}} - {{$clists->from_email}} - {{$clists->from_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span style="color:red;" id="erroremail_setting_id"></span>

                                            </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Run Time <i data-toggle="tooltip" data-placement="right" title="Choose date and time to send text message" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">

                    <div class="col-md-6">
                     <input type="date" class="form-control" min="{{date('Y-m-d')}}" required="" value=""  id="run_date" name="run_date" >
                 </div>

                 <div class="col-md-3">
                     <input type="time" class="form-control" required="" value=""  id="run_time" name="run_time" >
                     <input type="hidden" value=""  id="email_run_time_utc" name="email_run_time_utc">
                 </div>
                </div>
                                                <span style="color:red;" id="errorrun_time"></span>


                                            </div>

                                             <div id="email-schedule"></div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" id="addEmailSchedule-cancel" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-info btn-ok" id="addEmailSchedule">Add Email Schedule</button>
                        </div>
                    </div>
                </div>

            </div>


            <!-- add sms schedule -->

            <div class="modal fade" id="addSmsScheduleModel" role="dialog">

                <!-- Modal content-->

                <div class="modal-dialog">
                    <div class="modal-content" >
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Add Text Schedule</h4>
                        </div>
                        <div class="modal-body">

                                             <input type="hidden"  class="form-control" name="sms_send" id="sms_send" value="2"/>
                                            <input type="hidden"  class="form-control" name="sms_campaign_id" id="sms_campaign_id" value=""/>
                        <div class="form-group">
                            <label>Country Code <i data-toggle="tooltip" data-placement="right" title="Select country code" class="fa fa-info-circle" aria-hidden="true"></i></label>
                            <select class="form-control" name="sms_country_code" id="sms_country_code">
                                <option data-countryCode="GB" value="44" >UK (+44)</option>
                                <option data-countryCode="US" value="1" Selected>USA (+1)</option>
                                <optgroup label="Other countries">
                                <option data-countryCode="DZ" value="213">Algeria (+213)</option>
                                <option data-countryCode="AD" value="376">Andorra (+376)</option>
                                <option data-countryCode="AO" value="244">Angola (+244)</option>
                                <option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                <option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                <option data-countryCode="AR" value="54">Argentina (+54)</option>
                                <option data-countryCode="AM" value="374">Armenia (+374)</option>
                                <option data-countryCode="AW" value="297">Aruba (+297)</option>
                                <option data-countryCode="AU" value="61">Australia (+61)</option>
                                <option data-countryCode="AT" value="43">Austria (+43)</option>
                                <option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                <option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                <option data-countryCode="BH" value="973">Bahrain (+973)</option>
                                <option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                <option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                <option data-countryCode="BY" value="375">Belarus (+375)</option>
                                <option data-countryCode="BE" value="32">Belgium (+32)</option>
                                <option data-countryCode="BZ" value="501">Belize (+501)</option>
                                <option data-countryCode="BJ" value="229">Benin (+229)</option>
                                <option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                <option data-countryCode="BT" value="975">Bhutan (+975)</option>
                                <option data-countryCode="BO" value="591">Bolivia (+591)</option>
                                <option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                <option data-countryCode="BW" value="267">Botswana (+267)</option>
                                <option data-countryCode="BR" value="55">Brazil (+55)</option>
                                <option data-countryCode="BN" value="673">Brunei (+673)</option>
                                <option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                <option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                <option data-countryCode="BI" value="257">Burundi (+257)</option>
                                <option data-countryCode="KH" value="855">Cambodia (+855)</option>
                                <option data-countryCode="CM" value="237">Cameroon (+237)</option>
                                <option data-countryCode="CA" value="1">Canada (+1)</option>
                                <option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                <option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                <option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                <option data-countryCode="CL" value="56">Chile (+56)</option>
                                <option data-countryCode="CN" value="86">China (+86)</option>
                                <option data-countryCode="CO" value="57">Colombia (+57)</option>
                                <option data-countryCode="KM" value="269">Comoros (+269)</option>
                                <option data-countryCode="CG" value="242">Congo (+242)</option>
                                <option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                <option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                <option data-countryCode="HR" value="385">Croatia (+385)</option>
                                <option data-countryCode="CU" value="53">Cuba (+53)</option>
                                <option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                <option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                <option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                <option data-countryCode="DK" value="45">Denmark (+45)</option>
                                <option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                <option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                                <option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                <option data-countryCode="EC" value="593">Ecuador (+593)</option>
                                <option data-countryCode="EG" value="20">Egypt (+20)</option>
                                <option data-countryCode="SV" value="503">El Salvador (+503)</option>
                                <option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                <option data-countryCode="ER" value="291">Eritrea (+291)</option>
                                <option data-countryCode="EE" value="372">Estonia (+372)</option>
                                <option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                <option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                <option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                <option data-countryCode="FJ" value="679">Fiji (+679)</option>
                                <option data-countryCode="FI" value="358">Finland (+358)</option>
                                <option data-countryCode="FR" value="33">France (+33)</option>
                                <option data-countryCode="GF" value="594">French Guiana (+594)</option>
                                <option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                <option data-countryCode="GA" value="241">Gabon (+241)</option>
                                <option data-countryCode="GM" value="220">Gambia (+220)</option>
                                <option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                <option data-countryCode="DE" value="49">Germany (+49)</option>
                                <option data-countryCode="GH" value="233">Ghana (+233)</option>
                                <option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                <option data-countryCode="GR" value="30">Greece (+30)</option>
                                <option data-countryCode="GL" value="299">Greenland (+299)</option>
                                <option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                <option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                <option data-countryCode="GU" value="671">Guam (+671)</option>
                                <option data-countryCode="GT" value="502">Guatemala (+502)</option>
                                <option data-countryCode="GN" value="224">Guinea (+224)</option>
                                <option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                <option data-countryCode="GY" value="592">Guyana (+592)</option>
                                <option data-countryCode="HT" value="509">Haiti (+509)</option>
                                <option data-countryCode="HN" value="504">Honduras (+504)</option>
                                <option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                <option data-countryCode="HU" value="36">Hungary (+36)</option>
                                <option data-countryCode="IS" value="354">Iceland (+354)</option>
                                <option data-countryCode="IN" value="91">India (+91)</option>
                                <option data-countryCode="ID" value="62">Indonesia (+62)</option>
                                <option data-countryCode="IR" value="98">Iran (+98)</option>
                                <option data-countryCode="IQ" value="964">Iraq (+964)</option>
                                <option data-countryCode="IE" value="353">Ireland (+353)</option>
                                <option data-countryCode="IL" value="972">Israel (+972)</option>
                                <option data-countryCode="IT" value="39">Italy (+39)</option>
                                <option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                <option data-countryCode="JP" value="81">Japan (+81)</option>
                                <option data-countryCode="JO" value="962">Jordan (+962)</option>
                                <option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                <option data-countryCode="KE" value="254">Kenya (+254)</option>
                                <option data-countryCode="KI" value="686">Kiribati (+686)</option>
                                <option data-countryCode="KP" value="850">Korea North (+850)</option>
                                <option data-countryCode="KR" value="82">Korea South (+82)</option>
                                <option data-countryCode="KW" value="965">Kuwait (+965)</option>
                                <option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                <option data-countryCode="LA" value="856">Laos (+856)</option>
                                <option data-countryCode="LV" value="371">Latvia (+371)</option>
                                <option data-countryCode="LB" value="961">Lebanon (+961)</option>
                                <option data-countryCode="LS" value="266">Lesotho (+266)</option>
                                <option data-countryCode="LR" value="231">Liberia (+231)</option>
                                <option data-countryCode="LY" value="218">Libya (+218)</option>
                                <option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                <option data-countryCode="LT" value="370">Lithuania (+370)</option>
                                <option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                <option data-countryCode="MO" value="853">Macao (+853)</option>
                                <option data-countryCode="MK" value="389">Macedonia (+389)</option>
                                <option data-countryCode="MG" value="261">Madagascar (+261)</option>
                                <option data-countryCode="MW" value="265">Malawi (+265)</option>
                                <option data-countryCode="MY" value="60">Malaysia (+60)</option>
                                <option data-countryCode="MV" value="960">Maldives (+960)</option>
                                <option data-countryCode="ML" value="223">Mali (+223)</option>
                                <option data-countryCode="MT" value="356">Malta (+356)</option>
                                <option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                <option data-countryCode="MQ" value="596">Martinique (+596)</option>
                                <option data-countryCode="MR" value="222">Mauritania (+222)</option>
                                <option data-countryCode="YT" value="269">Mayotte (+269)</option>
                                <option data-countryCode="MX" value="52">Mexico (+52)</option>
                                <option data-countryCode="FM" value="691">Micronesia (+691)</option>
                                <option data-countryCode="MD" value="373">Moldova (+373)</option>
                                <option data-countryCode="MC" value="377">Monaco (+377)</option>
                                <option data-countryCode="MN" value="976">Mongolia (+976)</option>
                                <option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                <option data-countryCode="MA" value="212">Morocco (+212)</option>
                                <option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                <option data-countryCode="MN" value="95">Myanmar (+95)</option>
                                <option data-countryCode="NA" value="264">Namibia (+264)</option>
                                <option data-countryCode="NR" value="674">Nauru (+674)</option>
                                <option data-countryCode="NP" value="977">Nepal (+977)</option>
                                <option data-countryCode="NL" value="31">Netherlands (+31)</option>
                                <option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                <option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                <option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                <option data-countryCode="NE" value="227">Niger (+227)</option>
                                <option data-countryCode="NG" value="234">Nigeria (+234)</option>
                                <option data-countryCode="NU" value="683">Niue (+683)</option>
                                <option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                <option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                <option data-countryCode="NO" value="47">Norway (+47)</option>
                                <option data-countryCode="OM" value="968">Oman (+968)</option>
                                <option data-countryCode="PW" value="680">Palau (+680)</option>
                                <option data-countryCode="PA" value="507">Panama (+507)</option>
                                <option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                <option data-countryCode="PY" value="595">Paraguay (+595)</option>
                                <option data-countryCode="PE" value="51">Peru (+51)</option>
                                <option data-countryCode="PH" value="63">Philippines (+63)</option>
                                <option data-countryCode="PL" value="48">Poland (+48)</option>
                                <option data-countryCode="PT" value="351">Portugal (+351)</option>
                                <option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                <option data-countryCode="QA" value="974">Qatar (+974)</option>
                                <option data-countryCode="RE" value="262">Reunion (+262)</option>
                                <option data-countryCode="RO" value="40">Romania (+40)</option>
                                <option data-countryCode="RU" value="7">Russia (+7)</option>
                                <option data-countryCode="RW" value="250">Rwanda (+250)</option>
                                <option data-countryCode="SM" value="378">San Marino (+378)</option>
                                <option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                <option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                <option data-countryCode="SN" value="221">Senegal (+221)</option>
                                <option data-countryCode="CS" value="381">Serbia (+381)</option>
                                <option data-countryCode="SC" value="248">Seychelles (+248)</option>
                                <option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                <option data-countryCode="SG" value="65">Singapore (+65)</option>
                                <option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                <option data-countryCode="SI" value="386">Slovenia (+386)</option>
                                <option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                <option data-countryCode="SO" value="252">Somalia (+252)</option>
                                <option data-countryCode="ZA" value="27">South Africa (+27)</option>
                                <option data-countryCode="ES" value="34">Spain (+34)</option>
                                <option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                <option data-countryCode="SH" value="290">St. Helena (+290)</option>
                                <option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                <option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                <option data-countryCode="SD" value="249">Sudan (+249)</option>
                                <option data-countryCode="SR" value="597">Suriname (+597)</option>
                                <option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                <option data-countryCode="SE" value="46">Sweden (+46)</option>
                                <option data-countryCode="CH" value="41">Switzerland (+41)</option>
                                <option data-countryCode="SI" value="963">Syria (+963)</option>
                                <option data-countryCode="TW" value="886">Taiwan (+886)</option>
                                <option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                <option data-countryCode="TH" value="66">Thailand (+66)</option>
                                <option data-countryCode="TG" value="228">Togo (+228)</option>
                                <option data-countryCode="TO" value="676">Tonga (+676)</option>
                                <option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                <option data-countryCode="TN" value="216">Tunisia (+216)</option>
                                <option data-countryCode="TR" value="90">Turkey (+90)</option>
                                <option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                <option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                <option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                <option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                <option data-countryCode="UG" value="256">Uganda (+256)</option>
                                <!-- <option data-countryCode="GB" value="44">UK (+44)</option> -->
                                <option data-countryCode="UA" value="380">Ukraine (+380)</option>
                                <option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                <option data-countryCode="UY" value="598">Uruguay (+598)</option>
                                <!-- <option data-countryCode="US" value="1">USA (+1)</option> -->
                                <option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                <option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                <option data-countryCode="VA" value="379">Vatican City (+379)</option>
                                <option data-countryCode="VE" value="58">Venezuela (+58)</option>
                                <option data-countryCode="VN" value="84">Vietnam (+84)</option>
                                <option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                <option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                <option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                <option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                <option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                <option data-countryCode="ZM" value="260">Zambia (+260)</option>
                                <option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                            </optgroup>
                        </select>
                        <span style="color:red;" id="error_sms_country_code"></span>
                    </div>
                                             <div class="form-group">
                                            <label>Select List <i data-toggle="tooltip" data-placement="right" title="Select marketing list" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                                <select class="form-control" name="sms_list_id" id="sms_list_id">
                                                    <option value="">Select List</option>
                                                    @if(!empty($list))
                                                        @foreach($list as $key => $clists)
                                                            <option value="{{$clists->list_id}}" >{{$clists->list}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span style="color:red;" id="errorsms_list_id"></span>
                                                </div>


                                                 <div class="form-group" id="hidden_sms_listheader" style="display: none;">
                                            <label>Select column having phone <i data-toggle="tooltip" data-placement="right" title="Select column having phone for send text message" class="fa fa-info-circle" aria-hidden="true"></i></label>

                                                <select class="form-control" name="listheader" id="sms_listheader">


                                                    </select>
                                                <span style="color:red;" id="errorsmslistheader"></span>

                                                </div>





                                             <div class="form-group">
                                                <label>Text Template <i data-toggle="tooltip" data-placement="right" title="Select text template" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="sms_template_id" id="sms_template_id">
                                                    <option value="">Select Template</option>
                                                    @if(!empty($sms_templates))
                                                        @foreach($sms_templates as $key => $clists)
                                                            <option value="{{$clists->templete_id}}" >{{$clists->templete_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                <span style="color:red;" id="errorsms_template_id"></span>

                                            </div>


                                            </div>

                                            <div class="form-group">
                                                <label>DID <i data-toggle="tooltip" data-placement="right" title="Select phone number to send template" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control" name="did_setting" id="did_setting">
                                                    <option value="">DID Setting</option>
                                                    @if(!empty($did))
                                                        @foreach($did as $skey => $dids)
                                                            <option value="{{$dids->id}}" >{{$dids->cli}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                <span style="color:red;" id="errordid_setting"></span>

                                            </div>
                                            </div>


                                <div class="form-group">
                                    <label>Run Time <i data-toggle="tooltip" data-placement="right" title="Choose date and time to send text message" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    <div class="input-daterange input-group col-md-12">
                                        <div class="col-md-6" style="margin-left: -14px;">
                                            <input type="date" class="form-control" min="{{date('Y-m-d')}}" required="" value=""  id="sms_run_date" name="sms_run_date" >
                                        </div>
                                        <div class="col-md-3">
                                            <input type="time" class="form-control" required=""  id="sms_run_time" name="sms_run_time" >
                                        </div>
                                    </div>
                                    <span style="color:red;" id="errorsms_run_time"></span>
                                </div>



                                            <div id="email-schedule-sms"></div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-info btn-ok" id="addSmsSchedule">Add Text Schedule</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- close sms schedul -->

            <!-- add email schedule -->

            <div class="modal fade" id="deleteMarketingModel" role="dialog">

                <!-- Modal content-->

                <div class="modal-dialog">

                     <div class="modal-content" style="background-color: #d33724 !important;color:white;">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                        </div>
                        <div class="modal-body">
                            <p>You are about to delete <b><i class="title"></i></b> Campaign.</p>
                            <p>Do you want to proceed?</p>
                            <input type="" class="form-control" name="marketing_id" value="" id="marketing_id">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger btn-ok deleteMarketing">Delete</button>
                        </div>
                    </div>

                </div>

            </div>
            <!-- close email schedule -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script language="javascript">
        $(document).ready(function() {
            var oTable = $('#example').dataTable( {
                "aoColumnDefs": [
                { "bSortable": false, "aTargets": [ 2,3 ] }
                ]
            });
        } );
    </script>

    <script>
        $("#openMarketingForm").click(function(){
            $("#add_marketing_campaign").show();
            $("#openMarketingForm").hide();

        });

    </script>

    <script>
        $(".editMC").click(function(){
            $("#add_marketing_campaign").show();
            $("#openMarketingForm").hide();
            $("#saveMarketingCampaign").html('Update');

            var marketingid = $(this).data('marketingid');
            //alert(marketingid);

            $.ajax({
                url: 'marketing-campaign/' + marketingid,
                type: 'get',
                success: function(response) {
                    //alert(response.id);
                    $("#title").val(response.title);
                    $("#description").val(response.description);
                    $("#marketing_id").val(response.id);


                    //window.location.reload(1);
                }
            });

        });
    </script>

    <!-- add sms schedule -->
    <script>
    $(".addSmsSchedule").click(function()
        {

            var marketingid = $(this).data('marketingid');
            $("#sms_campaign_id").val(marketingid);
            $("#addSmsScheduleModel").modal();
            const now = moment().add(10, 'minutes')
            time = now.format("HH:mm");
            document.getElementById("sms_run_time").value = time ;
        });

    $(document).on("click", "#addSmsSchedule", function (e) {
            e.preventDefault();


            sms_list_id = $("#sms_list_id").val();
            if(sms_list_id == ''){
                $("#errorsms_list_id").html('Please select List');
                return false;
            }


            listheader = $("#sms_listheader").val();

            if(listheader == ''){
                $("#errorsmslistheader").html('Please select listheader');
                return false;
            }


            sms_template_id = $("#sms_template_id").val();
            if(sms_template_id == ''){
                $("#errorsms_template_id").html('Please select text templates');
                return false;
            }

            sms_country_code = $("#sms_country_code").val();
            if(sms_country_code == ''){
                $("#error_sms_country_code").html('Please enter country code');
                return false;
            }

            did_setting = $("#did_setting").val();
            if(did_setting == ''){
                $("#errordid_setting").html('Please select text did');
                return false;
            }

            var run_date = $("#sms_run_date").val();
            if(run_date == ''){
                $("#errorsms_run_time").html('Please select date');
                return false;

            }
            var run_time = $("#sms_run_time").val();
            if(run_time == ''){
                $("#errorsms_run_time").html('Please select time ');
                return false;
            }

            var localTime = new Date(run_date+' '+run_time+':00');
            var now = moment().add(10, 'minutes').format("YYYY-MM-DD HH:mm:ss");

            var localTime = new Date(localTime);
            var now = new Date(now);

            time = moment(localTime).utc().format('YYYY-MM-DD HH:mm:ss');

            if (localTime < now) {
                $("#errorsms_run_time").html('Please select runtime from atleast 10 min ahead from current time ');
                return false;
            }

             sms_send = $("#sms_send").val();
             sms_campaign_id = $("#sms_campaign_id").val();

             created_by = <?= Session::get('id'); ?>
            //alert(created_by);





            postData = {
                "_token"            : $("#user-role-csrf").val(),
                "campaign_id"       : $("#sms_campaign_id").val(),
                "list_id"           : $("#sms_list_id").val(),
                "list_column_name"           : $("#sms_listheader").val(),
                "send"              : $("#sms_send").val(),
                "sms_template_id" : $("#sms_template_id").val(),
                "sms_setting_id"  : $("#did_setting").val(),
                "sms_country_code"  : $("#sms_country_code").val(),
                "run_time"          : time,
                "created_by"        : created_by

            };
            console.log(postData);

            $.ajax({
                type: "POST",
                url: "{{ route('addMarketingScheduleSMS') }}",
                data: postData,
                success: function(responce){
                    console.log(responce);
                    $("#email-schedule-sms").html('<div class="alert alert-success" id="alert-success">Campaign Schedule Added</div>');
                   // $("#addEmailSchedule-cancel").click();
                  setTimeout(function(){ window.location.reload(1); }, 3000);
                },
                error: function(error){
                    console.log(error.responseJSON);
                    $("#email-schedule-sms").html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                    $("#email-schedule-sms").show();
                }
            });
        });
    </script>
    <!-- close sms schedule -->

    <!-- Email schedule -->
    <script>
    $(".addEmailSchedule").click(function()
        {
            var marketingid = $(this).data('marketingid');
            $("#campaign_id").val(marketingid);
            $("#addEmailScheduleModel").modal();
            const now = moment().add(10, 'minutes')
            time = now.format("HH:mm");
            document.getElementById("run_time").value = time ;
        });

    $(document).on("click", "#addEmailSchedule", function (e) {
            e.preventDefault();


            list_id = $("#list_id").val();

            if(list_id == ''){
                $("#errorlist_id").html('Please select List');
                return false;
            }


             listheader = $("#listheader").val();

            if(listheader == ''){
                $("#errorlistheader").html('Please select listheader');
                return false;
            }

            email_template_id = $("#email_template_id").val();
            if(email_template_id == ''){
                $("#erroremail_template_id").html('Please select email templates');
                return false;
            }

            email_setting_id = $("#email_setting_id").val();
            if(email_setting_id == ''){
                $("#erroremail_setting_id").html('Please select email setting id');
                return false;
            }

            var run_date = $("#run_date").val();
            if(run_date == ''){
                $("#errorrun_time").html('Please select date');
                return false;

            }
            var run_time = $("#run_time").val();
            if(run_time == ''){
                $("#errorrun_time").html('Please select time ');
                return false;

            }
            var localTime = new Date(run_date+' '+run_time+':00');
            var now = moment().add(10, 'minutes').format("YYYY-MM-DD HH:mm:ss");

            var localTime = new Date(localTime);
            var now = new Date(now);

            time = moment(localTime).utc().format('YYYY-MM-DD HH:mm:ss');

            if (localTime < now) {
                $("#errorrun_time").html('Please select runtime from atleast 10 min ahead from current time ');
                return false;
            }

             send = $("#send").val();
             campaign_id = $("#campaign_id").val();

             created_by = <?= Session::get('id'); ?>
            //alert(created_by);





            postData = {
                "_token"            : $("#user-role-csrf").val(),
                "campaign_id"       : $("#campaign_id").val(),
                "list_id"           : $("#list_id").val(),
                "list_column_name"  : $("#listheader").val(),
                "send"              : $("#send").val(),
                "email_template_id" : $("#email_template_id").val(),
                "email_setting_id"  : $("#email_setting_id").val(),
                "run_time"          : time,
                "created_by"        : created_by

            };
            console.log(postData);

            $.ajax({
                type: "POST",
                url: "{{ route('addMarketingSchedule') }}",
                data: postData,
                success: function(responce){
                    console.log(responce);
                    $("#email-schedule").html('<div class="alert alert-success" id="alert-success">Campaign Schedule Added</div>');
                   // $("#addEmailSchedule-cancel").click();
                   setTimeout(function(){ window.location.reload(1); }, 3000);
                },
                error: function(error){
                    console.log(error.responseJSON);
                    $("#email-schedule").html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                    $("#email-schedule").show();
                }
            });
        });
    </script>
    <!-- close Email schedule -->

    <script>
        $(".openMarketingDelete").click(function()
        {
            var marketingid = $(this).data('marketingid');
            $("#deleteMarketingModel").modal();
            $("#marketing_id").val(marketingid);
        });

        $(document).on("click", ".deleteMarketing", function()
        {
        // if (confirm("Are you sure you want to delete this record?")) {
            var campaign = $("#campaign_id").val();
            var el = this;
            $.ajax({
                url: 'deleteMarketingCampaign/' + campaign,
                type: 'get',
                success: function(response) {
                    window.location.reload(1);
                }
            });
        });

        $(document).on("change", "#sms_list_id", function()
        {
            var list_id = $("#sms_list_id").val();
            //alert(list_id);
            var el = this;
            $.ajax({
                url: 'findListHeader/' + list_id,
                type: 'get',
                dataType: "json",
                success: function(response) {
                    var text = "<option value=''>Select Column</option>";
                    $.each(response.data, function(key,value) {
                        text += "<option value="+value.column_name+">"+value.header+"</option>";
                    });
                    $("#hidden_sms_listheader").show();
                    document.getElementById("sms_listheader").innerHTML = text;
                }
            });
        });

        $(document).on("change", "#list_id", function()
        {
            var list_id = $("#list_id").val();
            //alert(list_id);
            var el = this;
            $.ajax({
                url: 'findListHeader/' + list_id,
                type: 'get',
                dataType: "json",
                success: function(response) {
                    var text = "<option value=''>Select Column</option>";
                    $.each(response.data, function(key,value) {
                        text += "<option value="+value.column_name+">"+value.header+"</option>";
                    });
                    document.getElementById("listheader").innerHTML = text;
                    $("#hidden_listheader").show();
                }
            });
        });
    </script>
    @endpush
