@extends('layouts.app')
@section('title', 'Add Extension')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    




<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="modal-body">
                    <div class="col-xs-12">
                    
                        <div class="box">
						<div class="box-header with-border">
						  <h4 class="box-title">Add Extension</h4>
                          <div class="text-right ">

                            <a href="{{ url('/extension') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye fa-lg"></i> Show Extension</a>
                            </div>
						</div>
                            <!-- /.box-header -->
                            <form class="form" method="post" name="userform" id="userform" action="{{url('extension/saveExtension')}}">
                            @csrf
                                <div class="box-body">								
                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">First Name <i data-toggle="tooltip" data-placement="right" title="Type your first name" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span></label>
                                        <input type="text" class="form-control"name="first_name" placeholder="First Name"value="{{ old('first_name') }}" id="first_name" required >
                                        </div>
                                    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">Last Name <i data-toggle="tooltip" data-placement="right" title="Type your last name" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span></label></label>
                                        <input type="text" class="form-control"placeholder="Last Name" required name="last_name" value="{{ old('last_name') }}" id="last_name">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Extension <i data-toggle="tooltip" data-placement="right" title="This will be your primary extension" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span><span id="errorExtension"></span> @error('extension') <i class="fa fa-times-circle-o error"> {{ $errors->first('extension') }} </i> @enderror</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error('extension') is-invalid @enderror" name="extension" value="{{ old('extension') }}" placeholder="Extension" id="extension" data-inputmask="'mask': ['9999', '9999']" data-mask="">
                                                    <div class="input-group-btn">
                                                        <button type="button" onclick="document.getElementById('extension').value = getExtension(1000,9999)" class="btn btn-danger">Auto Generate</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">                             
                                                <label class="form-label">Email <i data-toggle="tooltip" data-placement="right" title="Type your email address which will be used to login" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span><span id="errorEmail"></span> @error('email') <i class="fa fa-times-circle-o error"> {{ $errors->first('email') }} </i> @enderror </label>
                                                <div class="input-group">
                                                    <input autocomplete='off' type="email" required class="form-control" name="email" value="{{ old('email') }}" id="email"placeholder="Email">
                                                </div>

                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Password <i data-toggle="tooltip" data-placement="right" title="Set the password to be used for the user to login" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span><span id="errorExtension"></span> @error('extension') <i class="fa fa-times-circle-o error"> {{ $errors->first('extension') }} </i> @enderror</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="password" value="{{ old('password') }}" autocomplete='new-password' id="password" required>

                                                    <div class="input-group-btn">
                                                    <button type="button" onclick="document.getElementById('password').value =  getPassword()" class="btn btn-danger">Auto Generate</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">                             
                                                <label class="form-label">Server Alloted <i data-toggle="tooltip" data-placement="right" title="Select your server from drop down you wish to use" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                <div class="input-group">
                                                    <select class="form-select" name="asterisk_server_id" id="asterisk_server_id">
                                                        @if(!empty($client_data))
                                                            @foreach($client_data as $skey => $slists)
                                                            @php
                                                            if(!empty($slists->detail))
                                                            $details = "-".$slists->detail;
                                                            else
                                                            $details="";

                                                            @endphp

                                                                <option value={{$slists->ip_address}} >{{$slists->title_name}}{{$details}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                    <label class="form-label">Follow Me <i data-toggle="tooltip" data-placement="right" title="We would ring your mobile in case you didn't answer your extension" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                        <select class="form-select"  name="follow_me" id="follow-me">
                                                            <option value="2">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                            </div>                                  
                                        </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                                <label class="form-label">Call Forward <i data-toggle="tooltip" data-placement="right" title="We would forward all your calls to number provided" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                    <select class="form-select" name="call_forward" id="call-forward">
                                                        <option value=2>No</option>
                                                        <option value=1>Yes</option>
                                                    </select>									
                                        </div>
                                    </div>
                                    </div>
                                
                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">VoiceMail <i data-toggle="tooltip" data-placement="right" title="Voice mail will be enabled if you select Yes" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-select" name="voicemail" id="voicemail">
                                                        <option value=2>No</option>
                                                        <option value=1>Yes</option>
                                                    </select>
                                        </div>
                                    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">VoiceMail Pin <i data-toggle="tooltip" data-placement="right" title="Click button for generate Voicemail Pin" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                        <div class="input-group">
                                        <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="vm_pin" value="{{ old('vm_pin') }}" id="vm-pin"  data-inputmask="'mask': ['9999', '9999']" data-mask="">									

                                                    <div class="input-group-btn">
                                                    <button type="button" onclick="document.getElementById('vm-pin').value =  getVoicemail(1000,9999)" class="btn btn-danger">Auto Generate</button>
                                                    </div>
                                                </div>
                                        </div>
                                    
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">Send Voicemail to email <i data-toggle="tooltip" data-placement="right" title="Voicemail will be forwarded to your email" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-select" name="voicemail_send_to_email" id="send-voicemail">
                                                        <option value="2">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                        </div>
                                    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">Twinning <i data-toggle="tooltip" data-placement="right" title="Extension and mobile will ring simultaneoulsy." class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-select" name="twinning" id="twinning">
                                                        <option value="2">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>                                    
                                                </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                                <div class="form-group sms_grid">
                                                    <label class="form-label sms_grid" for="inputEmail3">Mobile <i data-toggle="tooltip" data-placement="right" title="Select country code and enter phone number" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span></label>
                                                    <div class="input-group">
                                                        <select  class="form-select" name="countryCode" id="countryCode">
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
                                                        <span class="input-group-addon">-</span>
                                                        <input style="width: 300px;" name="mobile" id="mobile" type="text" class="form-control" data-inputmask="'mask': '(999) 999-9999'" data-mask="" />
                                                    </div>
                                                </div>
                                        </div>
                            
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">Extension Type <i data-toggle="tooltip" data-placement="right" title="Extension type can be an extension or a ring group" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-select" name="extension_type" id ="extension_type" >
                                                            <option value="1">Extension</option>
                                                            <option value="2">Ring Group</option>

                                                        </select>                                   
                                                </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">CLI Setting <i data-toggle="tooltip" data-placement="right" title="Default will set the main line number as caller ID or you can select the custom caller ID from the list" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-select" name="cli_setting" onchange="return show_box(this.value)" id="cli_setting" onchange="return show_box(this.value)">
                                                            <option value="0">Area Code</option>
                                                            <option value="1">Custom</option>
                                                            <option value="2">AreaCode And Randomizer</option>
                                                        </select>
                                        </div>
                                    
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="form-label">IP Filtering <i data-toggle="tooltip" data-placement="right" title="Select Yes/No For IP Filtering" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <select class="form-select" name="ip_filtering" id="ip_filtering" >
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                    </div>
                                    </div>
                                </div>
                                    <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                                <label class="form-label">Enable 2FA <i data-toggle="tooltip" data-placement="right" title="Select Yes/No For Enable 2FA" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                    <select class="form-select" name="enable_2fa" id="enable_2fa" >
                                                                        <option value="0">No</option>
                                                                        <option value="1">Yes</option>
                                                                    </select>
                                                    </div>                                  
                                                </div>
                                                <div class="col-md-6"style="display: none;">
                                                <span class="cli_box_view"  >
                                                    <div class="form-group">
                                                    <label class="form-label">Custom CLI <i data-toggle="tooltip" data-placement="right" title="Select Custom Caller ID" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                    <select  class="form-select" name="cli" value="" id="cli">
                                                                            <option value="">Select DID</option>
                                                                            @if(count($did_list) > 0)
                                                                            @foreach(array_reverse($did_list) as $key => $lists)
                                                                            <option data-cnam="{{$lists->cnam}}" value="<?php echo $lists->cli ?>"><?php echo $lists->cli ?> <?php if(!empty($lists->cnam)) echo '-'.$lists->cnam ?>
                                                                            @if($lists->dest_type>0)
                                                                                -{{ !empty($destTypeList[$lists->dest_type]) ? $destTypeList[$lists->dest_type] : ''  }}
                                                                            @else
                                                                                @if($lists->cnam != null)
                                                                                    -IVR
                                                                                @else

                                                                                @endif
                                                                            @endif
                                                                            @if($lists->dest_type > 0)
                                                                                @if($lists->dest_type == 1)
                                                                                    @foreach($extension_list as $extension)
                                                                                        @if($lists->extension == $extension->id)
                                                                                            -{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}}
                                                                                        @endif
                                                                                    @endforeach
                                                                                @elseif($lists->dest_type == 2)
                                                                                    @foreach($extension_list as $extension)
                                                                                        @if($lists->voicemail_id == $extension->id)
                                                                                            -{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}}
                                                                                        @endif
                                                                                    @endforeach
                                                                                @elseif($lists->dest_type == 8)
                                                                                    @foreach($ring_group_list as $ring)
                                                                                        @if($lists->ingroup == $ring->id)
                                                                                            -{{$ring->description}} - {{$ring->title}}
                                                                                        @endif
                                                                                    @endforeach
                                                                                @elseif($lists->dest_type == 5)
                                                                                    @foreach($conferencing as $conf)
                                                                                        @if($lists->conf_id == $conf->id)
                                                                                            -{{$conf->title}} - {{$conf->conference_id}}
                                                                                        @endif
                                                                                    @endforeach
                                                                                @elseif($lists->dest_type == 4)
                                                                                    {{$lists->forward_number}}

                                                                                @elseif($lists->dest_type == 10)
                                                                                    Run CNAM

                                                                                    @elseif($lists->dest_type == 11)
                                                                                    Voice AI

                                                                                @else
                                                                                    -{{$destTypeList[$lists->dest_type]}}
                                                                                @endif
                                                                            @else
                                                                                @foreach($ivr_list as $ivr)
                                                                                    @if($lists->ivr_id == $ivr->ivr_id)
                                                                                        -{{$ivr->ivr_desc}}
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                            </option>
                                                                            @endforeach
                                                                            @endif
                                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="display: none;">
                                                    <span id="cnam_show" class="cnam_show" >
                                                        <label>CNAM <i data-toggle="tooltip" data-placement="right" title="Caller ID Name associated with Phone Number will be displayed" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                        <div class="input-daterange input-group col-md-12">
                                                            <input type="text" class="form-control" name="cnam" value="" id="cnam">
                                                        </div>
                                                    </span>
                                                </div>
                                                @if(!empty($group))
                                            <div class="col-md-6">
                                                <label class="form-label">Group <i data-toggle="tooltip" data-placement="right" title="Select the group the extension is associated with " class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                                <div class="form-group">
                                                    <select class="form-select select2" required="" multiple="multiple" name="group_id[]" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                                        @foreach($group as $group_ext)
                                                            <option value="{{$group_ext->id}}">{{$group_ext->title}}</option>
                                                        @endforeach;
                                                    </select>
                                                </div>
                                            </div>
                                            @else

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                <label class="form-label">Group <i data-toggle="tooltip" data-placement="right" title="Select the group the extension is associated with " class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span><span style="color:green" id="successGroup"></span></label>
                                                    <select class="form-select select2" id="groups" required="" multiple="multiple" name="group_id[]" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                                    </select>
                                                @if(empty($group))
                                                    <div id="hiddenDiv" class="input-group-btn">
                                                    <a id="openAEGForm" style="float:right;" type="submit" class="btn btn-danger"><i class="fa fa-plus"></i> Add Group</a>
                                                    </div>
                                                @endif
                                                </div>
                                            </div>

                                            @endif
                                    </div>
                                                    <div class="row">
                                
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                    <label class="form-label">Select Packages <i data-toggle="tooltip" data-placement="right" title="Select the package of the extension you want to associate." class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                                                        <select class="form-select" name="package_id" required id ="package_id" >
                                                                            @if($availablePackages)
                                                                            <option value="">Please Select Package</option>

                                                                            @foreach($availablePackages as $key=> $availablePackage)
                                                                                @php $availableSlot = $availablePackage->quantity - count((array)$availablePackage->assigned);
                                                                                @endphp
                                                                                @if($availableSlot != 0)
                                                                                    <option value="<?php echo $key; ?>">{{$availablePackage->package_name}} ({{$availableSlot}} Remaining)</option>
                                                                                @endif
                                                                            @endforeach

                                                                            @endif

                                                                        </select>
                                                            </div>
                                                        </div>
                                                        @if(!empty($voip_configurations))
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                
                                                                        <label class="form-label">Outbound Line<i data-toggle="tooltip" data-placement="right" title="Select Voip Configuration." class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                                                            <select class="form-select" required name="voip_configurations" autocomplete="off" data-placeholder="Select Disposition" >
                                                                                <option value="">Select VOIP</option>
                                                                                @foreach($voip_configurations as $key => $voip)
                                                                                <option value="{{$voip->id}}">{{$voip->name}}</option>
                                                                                @endforeach;
                                                                            </select>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">App status<i data-toggle="tooltip" data-placement="right" title="Select Yes/No For App Status" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                    <select class="form-select" name="app_status" id="app_status" >
                                                                        <option value="0">No</option>
                                                                        <option value="1">Yes</option>
                                                                    </select>                                   
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                <div class="row">								 
                                                    <span id="cnam_show" class="" >
                                                    <div class="form-group"> 
                                                        <label  class="form-label">Forward Incoming SMS To <i data-toggle="tooltip" data-placement="right" title="Forward Incoming SMS to SMS / Email" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                                                        
                                                            <div class="form-check" id="checkboxes"  data-toggle="checkboxes">
                                                                <input class="form-check-input" type="checkbox"  name="myCheckboxes" id="Email"name="receive_sms_on_email">
                                                                <label class="form-check-label" for="Email"style="margin-right:10px;">Email</label>                                                            
                                                                <input class="form-check-input" type="checkbox"  name="myCheckboxes" id="SMS"name="receive_sms_on_mobile">
                                                                <label class="form-check-label" for="SMS">SMS</label>
                                                            </div>
                                                        </div>                        
                                                    </span>
                                                    <input type="hidden" class="form-control" name="sms_setting_id" value="0" id ="sms_setting_id" >
                                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                <a href="/extension" type="button" class="btn btn btn-danger waves-effect waves-light" data-dismiss="modal"><i class="fa fa-reply fa-lg"></i> Cancel</a>

                                <a onclick="window.location.reload();" type="button" class="btn btn btn-warning waves-effect waves-light" data-dismiss="modal"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                                <button type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Submit</button>
                                </div>  
                            </form>
					    </div>
                   </div><!-- /.col -->
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->

         <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content" style="">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="add-edit"></h4>
                            </div>

                            <form method="post" action="">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" class="form-control" name="id" value="" id="edit-group-id" required>
                                    <div class="form-group">
                                    <label for="inputEmail3" class="col-form-label">Name</label>
                                    <input type="text" class="form-control" required name="title" id="title" placeholder="Enter Name" value="" />
                                     </div>
                                    <span id="errorGroup" style="color:red;"></span>
                                    <div class="form-group">
                                    <label for="inputPassword3" id="" class="col-form-label">Extension <i data-toggle="tooltip" data-placement="right" title="select multiple extension from drop down" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                    
                                    <select class="select2" multiple="multiple"  name="extensions[]" id="extensions" autocomplete="off" data-placeholder="Select Extension" style="width: 100%;">
                                        
                                    </select>
                                    </div>
                                    <input type="hidden" class="form-control" name="status" value="1" id="status" >

                                </div>
                                <div class="modal-footer">
                                    <a href="/extension-group" type="button" class="btn btn btn-danger waves-effect waves-light" data-dismiss="modal"><i class="fa fa-reply fa-lg"></i> Cancel</a>

                                     <a onclick="window.location.reload();" type="button" class="btn btn btn-warning waves-effect waves-light" data-dismiss="modal"><i class="fa fa-refresh fa-lg"></i> Reset</a>
                                    <button type="button" id="submitGroup" name="submit" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </div>
    <!-- /.content-wrapper -->

    <script>
        $(document).on("click", "#openAEGForm", function () {
    $("#add-edit").html('Add Extension Group');
    $("#edit-group-id").val('');
    $("#title").val('');
    loadExtensionOptions([], null);
    $("#myModal").modal();
});

function loadExtensionOptions(selectedExtensions, selectedGroupID) {
    $.ajax({
        url: 'mapExtensionGroup/',
        type: 'get',
        success: function (response) {
            var options = '';

            if (response.success && response.data && response.data.length > 0) {
                var uniqueExtensions = [];
                response.data.forEach(function (extension) {
                    if (extension.is_deleted === 0 && !uniqueExtensions.includes(extension.extension)) {
                        uniqueExtensions.push(extension.extension);
                        options += '<option value="' + extension.extension + '">' + extension.first_name + ' ' + extension.last_name + '-' + extension.extension + '</option>';
                    }
                });
            }

            $("#extensions").html(options);

            if (selectedGroupID) {
                loadSelectedExtensions(selectedGroupID, selectedExtensions);
            } else {
                $('#extensions').val(selectedExtensions).trigger('change');
            }
        }
    });
}

function loadSelectedExtensions(group_id, selectedExtensions) {
    $.ajax({
        url: 'mapExtensionGroup/',
        type: 'get',
        success: function (response) {
            var extensions = [];

            if (response.success && response.data && response.data.length > 0) {
                response.data.forEach(function (extension) {
                    if (extension.group_id === group_id && extension.is_deleted === 0) {
                        extensions.push(extension.extension);
                    }
                });
            }

            if (selectedExtensions) {
                selectedExtensions.forEach(function (extension) {
                    if (!extensions.includes(extension)) {
                        extensions.push(extension);
                    }
                });
            }

            $('#extensions').val(extensions).trigger('change');
        }
    });
}
        $(document).on("keyup", "#extension", function ()
        {
            $("#errorExtension").html("");
            $("#errorExtension").show();
            var extension = $("#extension").val();
            $.ajax({
                url: 'checkExtension/' + extension,
                type: 'get',
                success: function (response)
                {
                    if (response == 'true')
                    {
                        $("#errorExtension").html("<b style='color:green;'>Extension is available !</b>");
                        setInterval(function(){ $("#errorExtension").hide(); }, 3000);
                    }

                    else
                        if (response == 'false')
                    {
                        $("#errorExtension").html("<b style='color:red;'>Extension Already Exist !</b>");
                        //$("#extension").val('');
                    }
                }
            });
        });

     

        function show_box(value){
            if (value == 0 || value == 2) {
                $('.cli_box_view').hide();
                $('.cnam_show').hide();
            }
            else
            {
                $('.cli_box_view').show();
                $('.cnam_show').show();

            }
        }

        $(document).on("keyup", "#email", function ()
        {
            $("#errorEmail").html("");
            $("#errorEmail").show();

            var email = $("#email").val();
            if(email == "")
            {
                $("#errorEmail").show();
                $("#errorEmail").html("<b style='color:red;'>Please enter email</b>");
                $("#email").focus();
                return false;
            }

            //var mailformat = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
            var mailformat = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(email.match(mailformat))
            {

            }
            else
            {
                $("#errorEmail").html("<b style='color:red;'>You have entered an invalid email address!</b>");
                $("#email").focus();
                return false;
            }

            if(email != "")
            {
                $("#errorEmail").hide();
            }

            $.ajax({
                url: 'checkEmail/'+email,
                type: 'get',
                success: function (response)
                {
                    if (response == 'true')
                    {
                        $("#errorEmail").show();

                        $("#errorEmail").html("<b style='color:green;'>Email is available !</b>");

                    }

                    else
                        if (response == 'false')
                    {
                        $("#errorEmail").show();
                        $("#errorEmail").html("<b style='color:red;'>Email Already Exist !</b>");
                        return false;
                    }
                }
            });
        });
    </script>

<!-- jQuery should be loaded first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Then, the inputmask library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

<!-- Your additional inputmask files (if needed) -->
<script src="{{asset('assets/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{asset('assets/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{asset('assets/input-mask/jquery.inputmask.extensions.js') }}"></script>

    <!-- Add this in the <head> section of your HTML -->

    <script>
      $(document).ready(function () {
    $('#mobile').inputmask('(999) 999-9999');
    $('#extension').inputmask(['9999','9999']);
    $('#vm-pin').inputmask(['9999','9999']);

  });
    </script>
    <script>
        function getExtension(min, max)
        {
            return Math.floor(Math.random() * (max - min)) + min;
        }


        function getVoicemail(min, max)
        {
            return Math.floor(Math.random() * (max - min)) + min;
        }

        function getPassword()
        {
            var length = 10,
            charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
            retVal = "";
            for (var i = 0, n = charset.length; i < length; ++i)
            {
                retVal += charset.charAt(Math.floor(Math.random() * n));
            }
            return retVal;
        }

        $(document).on("click", "#submitGroup", function () {
            console.log('hi');
            var title = $('#title').val();
            var extensions = $('#extensions').val();
            if(title == ""){
                $("#errorGroup").html("Please enter group title");
            }
            $.ajax({
                url: 'addGroup/'+title+'/'+extensions,
                type: 'get',

                success: function (json)
                {
                    for(var i = 0; i < json.length; i++)
                    {
                        var obj = json[i];
                        optionText = obj.title;
                        optionValue = obj.id;
                        $('#groups').append(new Option(optionText, optionValue));
                    }
                    $("#successGroup").html("Group added successful! Please select");
                    $("#hiddenDiv").hide();
                    $('#myModal').modal('hide');
                }
            });
        });
        
        $('#cli').change(function()
        {
            $("#cnam_show").show();
            var criteria_value =  $(':selected',this).data('cnam');
            $('#cnam').val(criteria_value);
        });

    </script>
	
	


	
	
</body>
@endsection
<!-- Mirrored from joblly-admin-template-dashboard.multipurposethemes.com/bs5/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 20 Jul 2023 04:57:15 GMT -->
</html>


