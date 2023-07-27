@extends('layouts.app')
@section('title', 'Edit Extension')
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
                                <h4 class="box-title">Edit Extension</h4>
                                    <div class="text-right ">
                                    <a href="{{ url('/extension') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye fa-lg"></i> Show Extension</a>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <form method="post" name="userform" id="userform" action="{{url('save-edit-extension')}}">
                                    @csrf
                                    <input type="hidden" class="form-control" name="extension_id" value="{{$extension_list->id}}" id ="extension_id" required>
                                        <div class="modal-body">
                                            <div class="box-body">								
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                        <label class="form-label">First Name <i data-toggle="tooltip" data-placement="right" title="Type your first name" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span></label>
                                                        <input type="text" class="form-control"name="first_name" placeholder="First Name" value="{{$extension_list->first_name}}" id="first_name" required >
                                                        </div>                                    
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                        <label class="form-label">Last Name <i data-toggle="tooltip" data-placement="right" title="Type your last name" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span></label></label>
                                                        <input type="text" class="form-control"placeholder="Last Name" required name="last_name" value="{{$extension_list->last_name}}" id="last_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Extension <i data-toggle="tooltip" data-placement="right" title="This will be your primary extension" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span><span id="errorExtension"></span> @error('extension') <i class="fa fa-times-circle-o error"> {{ $errors->first('extension') }} </i> @enderror</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="user_ext" value="{{substr($extension_list->extension,-4,4)}}" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="old_email" value="{{$extension_list->email}}" />
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                        <label class="form-label">Email <i data-toggle="tooltip" data-placement="right" title="Type your email address which will be used to login" class="fa fa-info-circle" aria-hidden="true"></i>  
                                                        <span style="color:red;">*</span><span id="errorEmailExtension"></span></label>
                                                            <div class="input-group">
                                                            <input type="text" class="form-control" disabled name="email" value="{{$extension_list->email}}"  id="email">   
                                                            @if(Session::get('level') >= 7)
                                                                <div class="input-group-btn closed" id="editOption">
                                                                    <button type="button" onclick="editEmail();" class="btn btn-danger">Edit</button>
                                                                </div>
                                                                <div class="input-group-btn open" id="update" style="display: none;">
                                                                    <button type="button"  id="updateEmail" class="btn btn-info">Update</button>
                                                                </div>
                                                                <div class="input-group-btn open" style="display: none;padding: 0px 3px;">
                                                                    <a type="button" id="showEdit"    class="btn btn-warning">Cancel</a>
                                                                </div>
                                                                @endif
                                                                                                        
                                                            </div>
                                                        </div>
                                                    </div>
                    
                                                </div>
                                                <div class="row">                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">                             
                                                            <label class="form-label">Server Alloted <i data-toggle="tooltip" data-placement="right" title="Select your server from drop down you wish to use" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                            <div class="input-group">
                                                                        <select class="form-select" name="asterisk_server_id" id="asterisk_server_id" disabled="">
                                                                            @if(!empty($server_list))
                                                                                        @foreach($server_list as $skey => $slists)
                                                                                            @php
                                                                                            if(!empty($slists->detail))
                                                                                            $details = "-".$slists->detail;
                                                                                            else
                                                                                            $details="";

                                                                                            @endphp
                                                                                            <option value="{{$slists->id}}"
                                                                                            @if(!empty($server_list))
                                                                                                @if ($extension_list->asterisk_server_id == $slists->id)
                                                                                                            {{'selected="selected"'}}
                                                                                                @endif
                                                                                            @endif
                                                                                            >{{$slists->title_name}}{{$details}}</option>
                                                                                        @endforeach
                                                                            @endif
                                                                        </select>
                                                            </div>
                                                        </div>                                            
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="form-label">Follow Me <i data-toggle="tooltip" data-placement="right" title="We would ring your mobile in case you didn't answer your extension" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                    <select class="form-select"  name="follow_me" id="follow-me">
                                                                        <option @if($extension_list->follow_me == 1) selected @endif value="1">Yes</option>
                                                                        <option @if($extension_list->follow_me == 2) selected @endif value="2">No</option>
                                                                    </select>
                                                        </div>                                  
                                                    </div>
                                                </div>
                                                <div class="row">                                       
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                                <label class="form-label">Call Forward <i data-toggle="tooltip" data-placement="right" title="We would forward all your calls to number provided" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                    <select class="form-select" name="call_forward" id="call-forward">
                                                                        <option @if($extension_list->call_forward == 1) selected @endif value="1">Yes</option>
                                                                        <option @if($extension_list->call_forward == 2) selected @endif value="2">No</option>
                                                                    </select>									
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">VoiceMail <i data-toggle="tooltip" data-placement="right" title="Voice mail will be enabled if you select Yes" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                            <select class="form-select" name="voicemail" id="voicemail">
                                                                <option @if($extension_list->voicemail == 1) selected @endif value="1">Yes</option>
                                                                <option @if($extension_list->voicemail == 2) selected @endif value="2">No</option>
                                                            </select>
                                                        </div>                                        
                                                    </div>
                                                </div>
                                            
                                                <div class="row">
                                            
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">VoiceMail Pin <i data-toggle="tooltip" data-placement="right" title="Click button for generate Voicemail Pin" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                                                <div class="input-group">
                                                                <input type="text" onkeypress="return isNumberKey($(this));" class="form-control" name="vm_pin" value="{{$extension_list->vm_pin}}" id="vm-pin" data-inputmask="'mask': ['9999', '9999']" data-mask="">									
                                                                    <div class="input-group-btn">
                                                                    <button type="button" onclick="document.getElementById('vm-pin').value =  getVoicemail(1000,9999)" class="btn btn-danger">Auto Generate</button>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                        <label class="form-label">Send Voicemail to email <i data-toggle="tooltip" data-placement="right" title="Voicemail will be forwarded to your email" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                <select class="form-select" name="voicemail_send_to_email" id="send-voicemail">
                                                                    <option @if($extension_list->voicemail_send_to_email == 1) selected @endif value="1">Yes</option>
                                                                    <option @if($extension_list->voicemail_send_to_email == 2) selected @endif value="2">No</option>
                                                                </select>
                                                        </div>
                                                    
                                                    </div>
                                                </div>
                                                <div class="row">
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label class="form-label">Twinning <i data-toggle="tooltip" data-placement="right" title="Extension and mobile will ring simultaneoulsy." class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                            <select class="form-select" name="twinning" id="twinning">
                                                                <option @if($extension_list->twinning == 1) selected @endif value="1">Yes</option>
                                                                <option @if($extension_list->twinning == 2) selected @endif value="2">No</option>
                                                            </select>                                    
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                            <div class="form-group sms_grid">
                                                                <label class="form-label sms_grid" for="inputEmail3">Mobile <i data-toggle="tooltip" data-placement="right" title="Select country code and enter phone number" class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;">*</span></label>
                                                                <div class="input-group">
                                                                    <select  class="form-select" name="countryCode" id="countryCode">
                                                                        <option @if($extension_list->country_code == '44') selected @endif data-countryCode="GB" value="44" >UK (+44)</option>
                                                                        <option @if($extension_list->country_code == '1') selected @endif data-countryCode="CA" value="1">Canada (+1)</option>
                                                                        <option @if($extension_list->country_code == '1') selected @endif data-countryCode="US" value="1">USA (+1)</option>
                                                                        <optgroup label="Other countries">
                                                                            <option @if($extension_list->country_code == '213') selected @endif data-countryCode="DZ" value="213">Algeria (+213)</option>
                                                                            <option @if($extension_list->country_code == '376') selected @endif data-countryCode="AD" value="376">Andorra (+376)</option>
                                                                            <option @if($extension_list->country_code == '244') selected @endif data-countryCode="AO" value="244">Angola (+244)</option>
                                                                            <option @if($extension_list->country_code == '1264') selected @endif data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                                                            <option @if($extension_list->country_code == '1268') selected @endif data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                                                            <option @if($extension_list->country_code == '54') selected @endif data-countryCode="AR" value="54">Argentina (+54)</option>
                                                                            <option @if($extension_list->country_code == '374') selected @endif data-countryCode="AM" value="374">Armenia (+374)</option>
                                                                            <option @if($extension_list->country_code == '297') selected @endif data-countryCode="AW" value="297">Aruba (+297)</option>
                                                                            <option @if($extension_list->country_code == '61') selected @endif data-countryCode="AU" value="61">Australia (+61)</option>
                                                                            <option @if($extension_list->country_code == '43') selected @endif data-countryCode="AT" value="43">Austria (+43)</option>
                                                                            <option @if($extension_list->country_code == '994') selected @endif data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                                                            <option @if($extension_list->country_code == '1242') selected @endif data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                                                            <option @if($extension_list->country_code == '973') selected @endif data-countryCode="BH" value="973">Bahrain (+973)</option>
                                                                            <option @if($extension_list->country_code == '880') selected @endif data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                                                            <option @if($extension_list->country_code == '1246') selected @endif data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                                                            <option @if($extension_list->country_code == '375') selected @endif data-countryCode="BY" value="375">Belarus (+375)</option>
                                                                            <option @if($extension_list->country_code == '32') selected @endif data-countryCode="BE" value="32">Belgium (+32)</option>
                                                                            <option @if($extension_list->country_code == '501') selected @endif data-countryCode="BZ" value="501">Belize (+501)</option>
                                                                            <option @if($extension_list->country_code == '229') selected @endif data-countryCode="BJ" value="229">Benin (+229)</option>
                                                                            <option @if($extension_list->country_code == '1441') selected @endif data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                                                            <option @if($extension_list->country_code == '975') selected @endif data-countryCode="BT" value="975">Bhutan (+975)</option>
                                                                            <option @if($extension_list->country_code == '591') selected @endif data-countryCode="BO" value="591">Bolivia (+591)</option>
                                                                            <option @if($extension_list->country_code == '387') selected @endif data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                                                            <option @if($extension_list->country_code == '267') selected @endif data-countryCode="BW" value="267">Botswana (+267)</option>
                                                                            <option @if($extension_list->country_code == '55') selected @endif data-countryCode="BR" value="55">Brazil (+55)</option>
                                                                            <option @if($extension_list->country_code == '673') selected @endif data-countryCode="BN" value="673">Brunei (+673)</option>
                                                                            <option @if($extension_list->country_code == '359') selected @endif data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                                                            <option @if($extension_list->country_code == '226') selected @endif data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                                                            <option @if($extension_list->country_code == '257') selected @endif data-countryCode="BI" value="257">Burundi (+257)</option>
                                                                            <option @if($extension_list->country_code == '855') selected @endif data-countryCode="KH" value="855">Cambodia (+855)</option>
                                                                            <option @if($extension_list->country_code == '237') selected @endif data-countryCode="CM" value="237">Cameroon (+237)</option>

                                                                            <option @if($extension_list->country_code == '238') selected @endif data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                                                            <option @if($extension_list->country_code == '1345') selected @endif data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                                                            <option @if($extension_list->country_code == '236') selected @endif data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                                                            <option @if($extension_list->country_code == '53') selected @endif data-countryCode="CL" value="56">Chile (+56)</option>
                                                                            <option @if($extension_list->country_code == '86') selected @endif data-countryCode="CN" value="86">China (+86)</option>
                                                                            <option @if($extension_list->country_code == '57') selected @endif data-countryCode="CO" value="57">Colombia (+57)</option>
                                                                            <option @if($extension_list->country_code == '269') selected @endif data-countryCode="KM" value="269">Comoros (+269)</option>
                                                                            <option @if($extension_list->country_code == '242') selected @endif data-countryCode="CG" value="242">Congo (+242)</option>
                                                                            <option @if($extension_list->country_code == '682') selected @endif data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                                                            <option @if($extension_list->country_code == '506') selected @endif data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                                                            <option @if($extension_list->country_code == '385') selected @endif data-countryCode="HR" value="385">Croatia (+385)</option>
                                                                            <option @if($extension_list->country_code == '53') selected @endif data-countryCode="CU" value="53">Cuba (+53)</option>
                                                                            <option @if($extension_list->country_code == '90392') selected @endif data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                                                            <option @if($extension_list->country_code == '357') selected @endif data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                                                            <option @if($extension_list->country_code == '42') selected @endif data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                                                            <option @if($extension_list->country_code == '45') selected @endif data-countryCode="DK" value="45">Denmark (+45)</option>
                                                                            <option @if($extension_list->country_code == '253') selected @endif data-countryCode="DJ" value="253">Djibouti (+253)</option>

                                                                            <option @if($extension_list->country_code == '1809') selected @endif data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                                                            <option @if($extension_list->country_code == '593') selected @endif data-countryCode="EC" value="593">Ecuador (+593)</option>
                                                                            <option @if($extension_list->country_code == '20') selected @endif data-countryCode="EG" value="20">Egypt (+20)</option>
                                                                            <option @if($extension_list->country_code == '503') selected @endif data-countryCode="SV" value="503">El Salvador (+503)</option>
                                                                            <option @if($extension_list->country_code == '240') selected @endif data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                                                            <option @if($extension_list->country_code == '291') selected @endif data-countryCode="ER" value="291">Eritrea (+291)</option>
                                                                            <option @if($extension_list->country_code == '372') selected @endif data-countryCode="EE" value="372">Estonia (+372)</option>
                                                                            <option @if($extension_list->country_code == '251') selected @endif data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                                                            <option @if($extension_list->country_code == '500') selected @endif data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                                                            <option @if($extension_list->country_code == '298') selected @endif data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                                                            <option @if($extension_list->country_code == '679') selected @endif data-countryCode="FJ" value="679">Fiji (+679)</option>
                                                                            <option @if($extension_list->country_code == '358') selected @endif data-countryCode="FI" value="358">Finland (+358)</option>
                                                                            <option @if($extension_list->country_code == '33') selected @endif data-countryCode="FR" value="33">France (+33)</option>
                                                                            <option @if($extension_list->country_code == '594') selected @endif data-countryCode="GF" value="594">French Guiana (+594)</option>
                                                                            <option @if($extension_list->country_code == '689') selected @endif data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                                                            <option @if($extension_list->country_code == '241') selected @endif data-countryCode="GA" value="241">Gabon (+241)</option>
                                                                            <option @if($extension_list->country_code == '220') selected @endif data-countryCode="GM" value="220">Gambia (+220)</option>
                                                                            <option @if($extension_list->country_code == '7880') selected @endif data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                                                            <option @if($extension_list->country_code == '49') selected @endif data-countryCode="DE" value="49">Germany (+49)</option>
                                                                            <option @if($extension_list->country_code == '233') selected @endif data-countryCode="GH" value="233">Ghana (+233)</option>
                                                                            <option @if($extension_list->country_code == '350') selected @endif data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                                                            <option @if($extension_list->country_code == '30') selected @endif data-countryCode="GR" value="30">Greece (+30)</option>
                                                                            <option @if($extension_list->country_code == '299') selected @endif data-countryCode="GL" value="299">Greenland (+299)</option>
                                                                            <option @if($extension_list->country_code == '1473') selected @endif data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                                                            <option @if($extension_list->country_code == '590') selected @endif  data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                                                            <option @if($extension_list->country_code == '671') selected @endif data-countryCode="GU" value="671">Guam (+671)</option>
                                                                            <option @if($extension_list->country_code == '502') selected @endif data-countryCode="GT" value="502">Guatemala (+502)</option>
                                                                            <option @if($extension_list->country_code == '224') selected @endif data-countryCode="GN" value="224">Guinea (+224)</option>
                                                                            <option @if($extension_list->country_code == '245') selected @endif data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                                                            <option @if($extension_list->country_code == '592') selected @endif data-countryCode="GY" value="592">Guyana (+592)</option>
                                                                            <option @if($extension_list->country_code == '509') selected @endif data-countryCode="HT" value="509">Haiti (+509)</option>
                                                                            <option @if($extension_list->country_code == '504') selected @endif data-countryCode="HN" value="504">Honduras (+504)</option>
                                                                            <option @if($extension_list->country_code == '852') selected @endif data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                                                            <option @if($extension_list->country_code == '36') selected @endif data-countryCode="HU" value="36">Hungary (+36)</option>
                                                                            <option @if($extension_list->country_code == '354') selected @endif data-countryCode="IS" value="354">Iceland (+354)</option>
                                                                            <option @if($extension_list->country_code == '91') selected @endif data-countryCode="IN" value="91">India (+91)</option>
                                                                            <option @if($extension_list->country_code == '62') selected @endif data-countryCode="ID" value="62">Indonesia (+62)</option>
                                                                            <option @if($extension_list->country_code == '98') selected @endif data-countryCode="IR" value="98">Iran (+98)</option>
                                                                            <option @if($extension_list->country_code == '964') selected @endif data-countryCode="IQ" value="964">Iraq (+964)</option>
                                                                            <option @if($extension_list->country_code == '353') selected @endif data-countryCode="IE" value="353">Ireland (+353)</option>
                                                                            <option @if($extension_list->country_code == '972') selected @endif data-countryCode="IL" value="972">Israel (+972)</option>
                                                                            <option @if($extension_list->country_code == '39') selected @endif data-countryCode="IT" value="39">Italy (+39)</option>
                                                                            <option @if($extension_list->country_code == '1876') selected @endif data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                                                            <option @if($extension_list->country_code == '81') selected @endif @if($extension_list->country_code == '81') selected @endif data-countryCode="JP" value="81">Japan (+81)</option>
                                                                            <option @if($extension_list->country_code == '962') selected @endif data-countryCode="JO" value="962">Jordan (+962)</option>
                                                                            <option @if($extension_list->country_code == '7') selected @endif data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                                                            <option @if($extension_list->country_code == '254') selected @endif data-countryCode="KE" value="254">Kenya (+254)</option>
                                                                            <option @if($extension_list->country_code == '686') selected @endif data-countryCode="KI" value="686">Kiribati (+686)</option>
                                                                            <option @if($extension_list->country_code == '850') selected @endif data-countryCode="KP" value="850">Korea North (+850)</option>
                                                                            <option @if($extension_list->country_code == '82') selected @endif data-countryCode="KR" value="82">Korea South (+82)</option>
                                                                            <option @if($extension_list->country_code == '965') selected @endif data-countryCode="KW" value="965">Kuwait (+965)</option>
                                                                            <option @if($extension_list->country_code == '996') selected @endif data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                                                            <option @if($extension_list->country_code == '856') selected @endif data-countryCode="LA" value="856">Laos (+856)</option>
                                                                            <option @if($extension_list->country_code == '371') selected @endif data-countryCode="LV" value="371">Latvia (+371)</option>
                                                                            <option @if($extension_list->country_code == '961') selected @endif data-countryCode="LB" value="961">Lebanon (+961)</option>
                                                                            <option @if($extension_list->country_code == '266') selected @endif data-countryCode="LS" value="266">Lesotho (+266)</option>
                                                                            <option @if($extension_list->country_code == '231') selected @endif data-countryCode="LR" value="231">Liberia (+231)</option>
                                                                            <option @if($extension_list->country_code == '218') selected @endif data-countryCode="LY" value="218">Libya (+218)</option>
                                                                            <option @if($extension_list->country_code == '417') selected @endif data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                                                            <option @if($extension_list->country_code == '370') selected @endif data-countryCode="LT" value="370">Lithuania (+370)</option>
                                                                            <option @if($extension_list->country_code == '352') selected @endif data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                                                            <option @if($extension_list->country_code == '853') selected @endif data-countryCode="MO" value="853">Macao (+853)</option>
                                                                            <option @if($extension_list->country_code == '389') selected @endif data-countryCode="MK" value="389">Macedonia (+389)</option>
                                                                            <option @if($extension_list->country_code == '261') selected @endif data-countryCode="MG" value="261">Madagascar (+261)</option>
                                                                            <option @if($extension_list->country_code == '265') selected @endif data-countryCode="MW" value="265">Malawi (+265)</option>
                                                                            <option @if($extension_list->country_code == '60') selected @endif data-countryCode="MY" value="60">Malaysia (+60)</option>
                                                                            <option @if($extension_list->country_code == '960') selected @endif data-countryCode="MV" value="960">Maldives (+960)</option>
                                                                            <option @if($extension_list->country_code == '223') selected @endif data-countryCode="ML" value="223">Mali (+223)</option>
                                                                            <option @if($extension_list->country_code == '356') selected @endif data-countryCode="MT" value="356">Malta (+356)</option>
                                                                            <option @if($extension_list->country_code == '692') selected @endif data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                                                            <option @if($extension_list->country_code == '596') selected @endif data-countryCode="MQ" value="596">Martinique (+596)</option>
                                                                            <option @if($extension_list->country_code == '222') selected @endif data-countryCode="MR" value="222">Mauritania (+222)</option>
                                                                            <option @if($extension_list->country_code == '269') selected @endif data-countryCode="YT" value="269">Mayotte (+269)</option>
                                                                            <option @if($extension_list->country_code == '52') selected @endif data-countryCode="MX" value="52">Mexico (+52)</option>
                                                                            <option @if($extension_list->country_code == '691') selected @endif data-countryCode="FM" value="691">Micronesia (+691)</option>
                                                                            <option @if($extension_list->country_code == '373') selected @endif data-countryCode="MD" value="373">Moldova (+373)</option>
                                                                            <option @if($extension_list->country_code == '377') selected @endif data-countryCode="MC" value="377">Monaco (+377)</option>
                                                                            <option @if($extension_list->country_code == '976') selected @endif data-countryCode="MN" value="976">Mongolia (+976)</option>
                                                                            <option @if($extension_list->country_code == '1664') selected @endif data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                                                            <option @if($extension_list->country_code == '212') selected @endif data-countryCode="MA" value="212">Morocco (+212)</option>
                                                                            <option @if($extension_list->country_code == '258') selected @endif data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                                                            <option @if($extension_list->country_code == '95') selected @endif data-countryCode="MN" value="95">Myanmar (+95)</option>
                                                                            <option @if($extension_list->country_code == '264') selected @endif data-countryCode="NA" value="264">Namibia (+264)</option>
                                                                            <option @if($extension_list->country_code == '674') selected @endif data-countryCode="NR" value="674">Nauru (+674)</option>
                                                                            <option @if($extension_list->country_code == '977') selected @endif data-countryCode="NP" value="977">Nepal (+977)</option>
                                                                            <option @if($extension_list->country_code == '31') selected @endif data-countryCode="NL" value="31">Netherlands (+31)</option>
                                                                            <option @if($extension_list->country_code == '687') selected @endif data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                                                            <option @if($extension_list->country_code == '64') selected @endif data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                                                            <option @if($extension_list->country_code == '505') selected @endif data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                                                            <option @if($extension_list->country_code == '227') selected @endif data-countryCode="NE" value="227">Niger (+227)</option>
                                                                            <option @if($extension_list->country_code == '234') selected @endif data-countryCode="NG" value="234">Nigeria (+234)</option>
                                                                            <option @if($extension_list->country_code == '683') selected @endif data-countryCode="NU" value="683">Niue (+683)</option>
                                                                            <option @if($extension_list->country_code == '672') selected @endif data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                                                            <option @if($extension_list->country_code == '670') selected @endif data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                                                            <option @if($extension_list->country_code == '47') selected @endif data-countryCode="NO" value="47">Norway (+47)</option>
                                                                            <option @if($extension_list->country_code == '968') selected @endif data-countryCode="OM" value="968">Oman (+968)</option>
                                                                            <option @if($extension_list->country_code == '680') selected @endif data-countryCode="PW" value="680">Palau (+680)</option>
                                                                            <option @if($extension_list->country_code == '507') selected @endif data-countryCode="PA" value="507">Panama (+507)</option>
                                                                            <option @if($extension_list->country_code == '675') selected @endif data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                                                            <option @if($extension_list->country_code == '595') selected @endif data-countryCode="PY" value="595">Paraguay (+595)</option>
                                                                            <option @if($extension_list->country_code == '51') selected @endif data-countryCode="PE" value="51">Peru (+51)</option>
                                                                            <option @if($extension_list->country_code == '63') selected @endif data-countryCode="PH" value="63">Philippines (+63)</option>
                                                                            <option @if($extension_list->country_code == '48') selected @endif data-countryCode="PL" value="48">Poland (+48)</option>
                                                                            <option @if($extension_list->country_code == '351') selected @endif data-countryCode="PT" value="351">Portugal (+351)</option>
                                                                            <option @if($extension_list->country_code == '1787') selected @endif data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                                                            <option @if($extension_list->country_code == '974') selected @endif data-countryCode="QA" value="974">Qatar (+974)</option>
                                                                            <option @if($extension_list->country_code == '262') selected @endif data-countryCode="RE" value="262">Reunion (+262)</option>
                                                                            <option @if($extension_list->country_code == '40') selected @endif data-countryCode="RO" value="40">Romania (+40)</option>
                                                                            <option @if($extension_list->country_code == '7') selected @endif data-countryCode="RU" value="7">Russia (+7)</option>
                                                                            <option @if($extension_list->country_code == '250') selected @endif data-countryCode="RW" value="250">Rwanda (+250)</option>
                                                                            <option @if($extension_list->country_code == '378') selected @endif data-countryCode="SM" value="378">San Marino (+378)</option>
                                                                            <option @if($extension_list->country_code == '239') selected @endif data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                                                            <option @if($extension_list->country_code == '966') selected @endif data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                                                            <option @if($extension_list->country_code == '221') selected @endif data-countryCode="SN" value="221">Senegal (+221)</option>
                                                                            <option @if($extension_list->country_code == '381') selected @endif data-countryCode="CS" value="381">Serbia (+381)</option>
                                                                            <option @if($extension_list->country_code == '248') selected @endif data-countryCode="SC" value="248">Seychelles (+248)</option>
                                                                            <option @if($extension_list->country_code == '232') selected @endif data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                                                            <option @if($extension_list->country_code == '65') selected @endif data-countryCode="SG" value="65">Singapore (+65)</option>
                                                                            <option @if($extension_list->country_code == '421') selected @endif data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                                                            <option @if($extension_list->country_code == '386') selected @endif data-countryCode="SI" value="386">Slovenia (+386)</option>
                                                                            <option @if($extension_list->country_code == '677') selected @endif data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                                                            <option @if($extension_list->country_code == '252') selected @endif data-countryCode="SO" value="252">Somalia (+252)</option>
                                                                            <option @if($extension_list->country_code == '27') selected @endif data-countryCode="ZA" value="27">South Africa (+27)</option>
                                                                            <option @if($extension_list->country_code == '34') selected @endif data-countryCode="ES" value="34">Spain (+34)</option>
                                                                            <option @if($extension_list->country_code == '94') selected @endif data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                                                            <option @if($extension_list->country_code == '262') selected @endif data-countryCode="SH" value="290">St. Helena (+290)</option>
                                                                            <option @if($extension_list->country_code == '1869') selected @endif data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                                                            <option @if($extension_list->country_code == '1758') selected @endif data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                                                            <option @if($extension_list->country_code == '249') selected @endif data-countryCode="SD" value="249">Sudan (+249)</option>
                                                                            <option @if($extension_list->country_code == '597') selected @endif data-countryCode="SR" value="597">Suriname (+597)</option>
                                                                            <option @if($extension_list->country_code == '268') selected @endif data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                                                            <option @if($extension_list->country_code == '46') selected @endif data-countryCode="SE" value="46">Sweden (+46)</option>
                                                                            <option @if($extension_list->country_code == '41') selected @endif data-countryCode="CH" value="41">Switzerland (+41)</option>
                                                                            <option @if($extension_list->country_code == '963') selected @endif data-countryCode="SI" value="963">Syria (+963)</option>
                                                                            <option @if($extension_list->country_code == '886') selected @endif data-countryCode="TW" value="886">Taiwan (+886)</option>
                                                                            <option @if($extension_list->country_code == '7') selected @endif data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                                                            <option @if($extension_list->country_code == '66') selected @endif data-countryCode="TH" value="66">Thailand (+66)</option>
                                                                            <option @if($extension_list->country_code == '228') selected @endif data-countryCode="TG" value="228">Togo (+228)</option>
                                                                            <option @if($extension_list->country_code == '676') selected @endif data-countryCode="TO" value="676">Tonga (+676)</option>
                                                                            <option @if($extension_list->country_code == '1868') selected @endif data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                                                            <option @if($extension_list->country_code == '216') selected @endif data-countryCode="TN" value="216">Tunisia (+216)</option>
                                                                            <option @if($extension_list->country_code == '90') selected @endif data-countryCode="TR" value="90">Turkey (+90)</option>
                                                                            <option @if($extension_list->country_code == '7') selected @endif data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                                                            <option @if($extension_list->country_code == '993') selected @endif data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                                                            <option @if($extension_list->country_code == '1649') selected @endif data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                                                            <option @if($extension_list->country_code == '688') selected @endif data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                                                            <option @if($extension_list->country_code == '256') selected @endif data-countryCode="UG" value="256">Uganda (+256)</option>
                                                                            <!-- <option data-countryCode="GB" value="44">UK (+44)</option> -->
                                                                            <option @if($extension_list->country_code == '380') selected @endif data-countryCode="UA" value="380">Ukraine (+380)</option>
                                                                            <option @if($extension_list->country_code == '971') selected @endif data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                                                            <option @if($extension_list->country_code == '598') selected @endif data-countryCode="UY" value="598">Uruguay (+598)</option>
                                                                            <!-- <option data-countryCode="US" value="1">USA (+1)</option> -->
                                                                            <option @if($extension_list->country_code == '7') selected @endif data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                                                            <option @if($extension_list->country_code == '678') selected @endif data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                                                            <option @if($extension_list->country_code == '379') selected @endif data-countryCode="VA" value="379">Vatican City (+379)</option>
                                                                            <option @if($extension_list->country_code == '58') selected @endif data-countryCode="VE" value="58">Venezuela (+58)</option>
                                                                            <option @if($extension_list->country_code == '84') selected @endif data-countryCode="VN" value="84">Vietnam (+84)</option>
                                                                            <option @if($extension_list->country_code == '84') selected @endif data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                                                            <option @if($extension_list->country_code == '84') selected @endif data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                                                            <option @if($extension_list->country_code == '681') selected @endif data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                                                            <option @if($extension_list->country_code == '969') selected @endif data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                                                            <option @if($extension_list->country_code == '967') selected @endif data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                                                            <option @if($extension_list->country_code == '260') selected @endif data-countryCode="ZM" value="260">Zambia (+260)</option>
                                                                            <option @if($extension_list->country_code == '263') selected @endif data-countryCode="ZW" value="263">Zimbabwe (+263)</option>

                                                                        </optgroup>
                                                                    </select>
                                                                    <span class="input-group-addon">-</span>
                                                                    <input style="width: 300px;" name="mobile" id="mobile" type="text"value="{{$extension_list->mobile}}" class="form-control" data-inputmask="'mask': '(999) 999-9999'" data-mask="" />
                                                                </div>
                                                            </div>
                                                    </div>
                                        
                                                </div>                                
                                                <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">CLI Setting <i data-toggle="tooltip" data-placement="right" title="Default will set the main line number as caller ID or you can select the custom caller ID from the list" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                <select class="form-select" name="cli_setting" id ="cli_setting" onchange="return show_box(this.value)">
                                                                    <option @if($extension_list->cli_setting == 0) selected @endif value="0">Area Code</option>
                                                                    <option @if($extension_list->cli_setting == 1) selected @endif value="1">Custom</option>
                                                                    <option @if($extension_list->cli_setting == 2) selected @endif value="2">AreaCode And Randomizer</option>                             
                                                                </select>
                                                            </div>                                    
                                                        </div>
                                                        <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">IP Filtering <i data-toggle="tooltip" data-placement="right" title="Select Yes/No For IP Filtering" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                        <select class="form-select" name="ip_filtering" id="ip_filtering" >
                                                                            <option @if($extension_list->ip_filtering == 0) selected @endif value="0">No</option>
                                                                            <option @if($extension_list->ip_filtering == 1) selected @endif value="1">Yes
                                                                        </select>
                                                                    </div>
                                                        </div>
                                                </div>
                                                <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                            <label class="form-label">Enable 2FA <i data-toggle="tooltip" data-placement="right" title="Select Yes/No For Enable 2FA" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                                <select class="form-select" name="enable_2fa" id="enable_2fa" >
                                                                                <option @if($extension_list->enable_2fa == 0) selected @endif value="0">No</option>
                                                                                <option @if($extension_list->enable_2fa == 1) selected @endif value="1">Yes</option>>
                                                                                </select>
                                                                </div>                                  
                                                            </div>
                                                            <div class="col-md-6 cli_box_view" @if($extension_list->cli_setting == 0 || $extension_list->cli_setting == 2) style='display:none' @endif>
                                                                        <span class="" >
                                                                        <div class="form-group">
                                                                                <label class="form-label">Custom CLI <i data-toggle="tooltip" data-placement="right" title="Select Custom Caller ID" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                                <select  class="form-select" name="cli" value="" id="cli">
                                                                                        <option value="">Select DID</option>
                                                                                        @if(count($did_list) > 0)
                                                                                                @foreach(array_reverse($did_list) as $key => $lists)
                                                                                                    <option data-cnam="{{$lists->cnam}}" @if($extension_list->cli == $lists->cli) selected @endif value="<?php echo $lists->cli ?>"><?php echo $lists->cli ?> <?php if(!empty($lists->cnam)) echo '-'.$lists->cnam ?>
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
                                                                                                @foreach($user_extension_list as $extension)
                                                                                                    @if($lists->extension == $extension->id)
                                                                                                        -{{$extension->first_name}} {{$extension->last_name}} - {{$extension->extension}} 
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @elseif($lists->dest_type == 2)
                                                                                                @foreach($user_extension_list as $extension)
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
                                                            <div class="col-md-6">
                                                                <span class="" >
                                                                <label>Extension Type <i data-toggle="tooltip" data-placement="right" title="Extension type can be an extension or a ring group" class="fa fa-info-circle" aria-hidden="true"></i> </label>
                                                                <select class="form-select" name="extension_type" id ="extension_type" >
                                                                    <option @if($extension_list->extension_type == 1) selected @endif value="1">Extension</option>
                                                                    <option @if($extension_list->extension_type == 2) selected @endif value="2">Ring Group</option>
                                                                </select>
                                                                </span>
                                                            </div>
                                                            
                                                            <input type="hidden" class="form-control" name="sms_setting_id" value="0" id ="sms_setting_id" >
                                    
                                                            <?php /* ?><div class="col-md-6">
                                                                <span class="" >
                                                                <label>SMS API <i data-toggle="tooltip" data-placement="right" title="Select the sms api." class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                                            
                                                                <select class="form-select" name="sms_setting_id" required id ="sms_setting_id" >
                                                                    <option @if($extension_list->sms_setting_id == 0) selected @endif  value="0">DIDFORSALE (Default)</option>

                                                                    @if(Session::get('level') > 5)
                                                                    @if(!empty($sms))
                                                                    @foreach($sms as $smslist)

                                                                        <option  @if($extension_list->sms_setting_id == $smslist->id) selected @endif value="<?php echo $smslist->id; ?>">{{$smslist->sender_name}}</option>
                                                                    
                                                                    @endforeach
                                                                    @endif
                                                                    @endif

                                                                </select>
                                                                
                                                                </span>
                                                            </div> <?php */ ?>
                                                            @if($assigned_package)
                                                            <div class="col-md-6">
                                                                <span class="" >
                                                                <label>Package Assigned <i data-toggle="tooltip" data-placement="right" title="Select the package of the extension you want to associate." class="fa fa-info-circle" aria-hidden="true"></i> </label>
                                                            
                                                                <div class="input-daterange input-group col-md-12">
                                                                        <input type="text" class="form-control" disabled  name="package-assigned" value="{{$assigned_package}}" id ="package-assigned">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6"><div>
                                                                
                                                                </span>
                                                            </div>
                                                            @endif
                                                        
                                                        <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Group <i data-toggle="tooltip" data-placement="right" title="Select the group the extension is associated with " class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                                            
                                                                <select class="form-select select2" required="" multiple="multiple" name="group_id[]" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">
                                                                @foreach($group as $groups)
                                                                <option @if(in_array($groups->id, $mapping))  selected  @endif value="{{$groups->id}}">{{$groups->title}}</option>
                                                                @endforeach;
                                                                </select>
                                                            </div>
                                                        </div>
                                                    

                                                        @if(!empty($voip_configurations))
                                                                        <div class="form-group col-md-6">
                                                                            <label class="form-label">Outbound Line <i data-toggle="tooltip" data-placement="right" title="Select Voip Configuration " class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                            <div class=" input-group ">
                                                                            <select class="form-select" required name="voip_configurations" autocomplete="off" data-placeholder="Select Disposition" >
                                                                                <option value="">Select VOIP</option>
                                                                                @foreach($voip_configurations as $key => $voip)
                                                                                <option @if($extension_list->voip_configuration_id == $voip->id) selected @endif  value="{{$voip->id}}">{{$voip->name}}</option>
                                                                                @endforeach;
                                                                            </select>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">App status<i data-toggle="tooltip" data-placement="right" title="Select Yes/No For App Status" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                                <select class="form-select" name="app_status" id="app_status" >
                                                                                <option @if($extension_list->app_status == 0) selected @endif value="0">No</option>
                                                                                <option @if($extension_list->app_status == 1) selected @endif value="1">Yes</option>
                                                                                </select>                                   
                                                                        </div>
                                                                    </div>
                                                    
                                                </div>
                                                                <div class="row">
                                                                <span id="cnam_show" class="" >
                                                                    <div class="form-group"> 
                                                                    <label  class="form-label">Forward Incoming SMS To <i data-toggle="tooltip" data-placement="right" title="Forward Incoming SMS to SMS / Email" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                                                                                                    
                                                                        <div class="form-check" id="checkboxes"  data-toggle="checkboxes">
                                                                            <input class="form-check-input" type="checkbox"  name="myCheckboxes" id="Email"name="receive_sms_on_email"@if($extension_list->receive_sms_on_email == 1) checked  @endif>
                                                                            <label class="form-check-label" for="Email"style="margin-right:10px;">Email</label>                                                            
                                                                            <input class="form-check-input" type="checkbox"  name="myCheckboxes" id="SMS"name="receive_sms_on_mobile"@if($extension_list->receive_sms_on_mobile == 1) checked  @endif>
                                                                            <label class="form-check-label" for="SMS">SMS</label>
                                                                        </div>
                                                                    </div>                        
                                                                    </span>
                                                                </div>
                                                        
                                            <!-- /.box-body -->
                                            <div class="box-footer">
                                                <a href="/extension" type="button" class="btn btn btn-danger waves-effect waves-light" data-dismiss="modal"><i class="fa fa-reply fa-lg"></i> Cancel</a>

                                                <a onclick="window.location.reload();" type="button" class="btn btn btn-warning waves-effect waves-light" data-dismiss="modal"><i class="fa fa-refresh fa-lg"></i> Reset</a>

                                                <button type="submit" name ="submit" value="add" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Update</button>
                                            </div> 
                                        </div> 
                                </form>
                            </div>
                        </div><!-- /.col -->
                    </div>
                </div>
        </section><!-- /.content -->

      
    </div>
    <!-- /.content-wrapper -->

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
    $('#vm-pin').inputmask(['9999','9999']);

  });
    </script>
<script>
//$('input[name="vm_pin"]').mask('0000');

$('#cli').change(function()
{
    $("#cnam_show").show();
    var criteria_value =  $(':selected',this).data('cnam');
    $('#cnam').val(criteria_value);
});

function show_box(value){
   if(value==0 || value == 2){
        $('.cli_box_view').hide();
        $("#cnam_show").hide();
   }else{
        $('.cli_box_view').show();
        $("#cnam_show").show();

   }
}

function editEmail(emailss)
{
    email = $("#email").val();
    $('#email').removeAttr("disabled");
    $(".closed").hide();
    $(".open").show();
    $("#showEdit").show();


}

$(document).on("click", "#updateEmail", function ()
{
    var email = $("#email").val();
            if(email == "")
            {
                $("#errorEmailExtension").show();
                $("#errorEmailExtension").html("<b style='color:red;'>Please enter email</b>");
                $("#email").focus();
                return false;
            }


            const mailformat = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(email.match(mailformat))
            {

            }
            else
            {
                $("#errorEmailExtension").html("<b style='color:red;'>You have entered an invalid email address!</b>");
                $("#email").focus();
                return false;
            }

            if(email != "")
            {
                $("#errorEmailExtension").hide();
            }
            extension_id = $("#extension_id").val();
            old_email = $("#old_email").val();
            if(email == old_email)
            {
                $("#errorEmailExtension").html("<b style='color:red;'>Please change current email for update.</b>");
                return false;
            }

            $.ajax({
                url: '/updateEmail',
                type: 'post',
                data:{               
                     _token: $('meta[name="csrf-token"]').attr('content'),
                     email:email,
                     user_id:extension_id
                    },
                success: function (response)
                {
                    if (response == 'true')
                    {
                          $("#errorEmailExtension").show();
                        $("#errorEmailExtension").html("<b style='color:green;'>Email changed Successfully !</b>");
                        window.location.reload(1);
                    }

                    else
                        if (response == 'false')
                    {
                          $("#errorEmailExtension").show();
                        setTimeout(function(){ $("#errorEmailExtension").hide(); }, 3000);
                        $("#errorEmailExtension").html("<b style='color:red;'>Email Already Exists</b>");
                        //$("#extension").val('');
                    }
                }
            });

});

function getVoicemail(min, max)
{
    return Math.floor(Math.random() * (max - min)) + min;
}



$("#showEdit").click(function()
{
    $("#update").hide();
    $("#showEdit").hide();
    $("#editOption").show();



})
</script>

	
	
</body>
@endsection
<!-- Mirrored from joblly-admin-template-dashboard.multipurposethemes.com/bs5/main/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 20 Jul 2023 04:57:15 GMT -->
</html>


