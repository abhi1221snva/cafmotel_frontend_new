@extends('layouts.app')
@section('title', 'Edit Phone Number')
@section('content')
<div class="content-wrapper">

    <section class="content-header">
                <h1>
                   <b>Phone Number</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Inbound Configuration</li>
                    <li class="active">Phone Number</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
                <a href="{{ url('/did') }}"  type="submit"
            class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Phone Number</a>
           </div>
       </section>
    

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Phone Number</h3>
                    </div>

                    <form enctype="multipart/form-data" class="form-horizontal" method="post"
                    action="{{url('did/saveEditDid')}}">
                    @csrf
                    <div class="box-body" style="padding: 10px 160px;">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="hidden" name="operator_check" value="0" />
                                <input type="hidden" name="operator" value="0" />
                                <input type="hidden" class="form-control" name="did_id" value="{{$didData->id}}"
                                id="did_id" required="">
                                <div class="form-group m-b-10">
                                        <div class="col-md-2">
                                            <label>Phone Number <i data-toggle="tooltip" data-placement="right" title="Type phone number" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" maxlength="12" onkeypress="return isNumberKey($(this));" class="form-control" name="cli" value="{{$didData->cli}}"
                                        id="cli" required="" onkeyup="check(); return false;"  placeholder="Enter Phone Number" {{$didData->cli != '' ? "readonly='true'" : ""}}>
                                                <span id="message"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Caller Name <i data-toggle="tooltip" data-placement="right" title="Type caller name" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" maxlength="13" class="form-control" name="cnam" value="{{$didData->cnam}}" id="cnam" required=""
                                            placeholder="Enter Caller Name">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Set this number as your mainline ? <i data-toggle="tooltip" data-placement="right" title="Phone number will be used as default Caller ID if you set this feature as Yes" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  id="default_did" name="default_did" >
                                            <option @if(1 == $didData->default_did) selected @endif value="1">Yes</option>
                                            <option @if(0 == $didData->default_did) selected @endif value="0">No</option>
                                        </select>
                                            </div>
                                        </div>

                                         <div class="col-md-3">
                                            <label>Redirect to Last Spoke Agent <i data-toggle="tooltip" data-placement="right" title="Spoke last time" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  id="redirect_last_agent" name="redirect_last_agent" >
                                            <option @if(1 == $didData->redirect_last_agent) selected @endif value="1">Yes</option>
                                            <option @if(0 == $didData->redirect_last_agent) selected @endif value="0">No</option>
                                        </select>
                                            </div>
                                        </div>
                                </div>

                                
                            </div>
                        </div>



                        <div class="row" id="showRow"> 
                            <div class="col-sm-12">
                                <div class="form-group m-b-10">
                                        <div class="col-md-4">
                                            <label>Destination Type <i data-toggle="tooltip" data-placement="right" title="You can make your callers to hear welcome message or ring your extension or forward to your mobile" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="dest_type" id="dest_type" onchange="return showData(this.value)">
                                            @if (!empty($dest_type))
                                            @foreach($dest_type as $item)
                                                <option value={{$item->dest_id}} @if ($item->dest_id ==
                                                    $didData->dest_type)
                                                    {{'selected="selected"'}}
                                                    @endif
                                                    > {{$item->dest_type}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                            </div>
                                        </div>


                                        <div class="col-md-8 hideMe inner_div" id="0_div" @if (0==$didData->dest_type)
                                        style='display: block' @endif >
                                            <label>Destination <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to " class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="ivr_id" id="ivr_id">
                                                @if (isset($ivr_list))
                                                @foreach($ivr_list as $ivr_lst)
                                                <option @if ($didData->ivr_id ==
                                                    $ivr_lst->ivr_id)
                                                    {{'selected="selected"'}}
                                                    @endif value={{$ivr_lst->ivr_id}}>{{$ivr_lst->ivr_desc}} - {{$ivr_lst->ivr_id}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                        </div>

                                        <div id="1_div" class="col-md-8 hideMe inner_div" @if (1==$didData->dest_type)
                                        style='display: block' @endif >
                                            <label>Destination <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="extension" id="extension">
                                                @if (is_array($extension_list))
                                                @foreach($extension_list as $ext_lst)
                                                <option @if($didData->extension == $ext_lst->id ) selected @endif value={{$ext_lst->id}}>{{$ext_lst->first_name}} {{$ext_lst->last_name}} - {{$ext_lst->extension}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                        </div>


                                        <div id="2_div" class="col-md-8 hideMe inner_div" @if (2==$didData->dest_type)
                                        style='display: block' @endif >
                                            <label>Destination <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="voicemail_id" id="voicemail_id">
                                                @if (is_array($extension_list))
                                                @foreach($extension_list as $ext_lst)
                                                <option @if($didData->voicemail_id == $ext_lst->id ) selected @endif value={{$ext_lst->id}}>{{$ext_lst->first_name}} {{$ext_lst->last_name}} - {{$ext_lst->extension}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                        </div>

                                         <div id="4_div" class="col-md-3 hideMe inner_div 4_div" @if (4==$didData->dest_type)
                                        style='display: block' @endif >
                                        <label for="exampleInputEmail1">Country Phone Code <i data-toggle="tooltip" data-placement="right" title="Select The Country Phone Code" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                             <select class="form-control"  name="country_code" id="country_code">
                                                @if (is_array($phone_country))
                                                @foreach($phone_country as $code)
                                                <option @if($didData->country_code == $code->phone_code ) selected @endif value={{$code->phone_code}}>{{$code->country_name}} (+{{$code->phone_code}})
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>      
                                    </div>

                                    @php
                                    $newstring = substr($didData->forward_number, -10); 
                                    @endphp

                                        <div id="4_div" class="col-md-5 hideMe inner_div 4_div" @if (4==$didData->dest_type)
                                        style='display: block' @endif >
                                        <label for="exampleInputEmail1">Phone Number <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" class="form-control" value="{{$newstring}}"  name="forward_number" id="forward_number" placeholder="Enter the phone number in Format: +12345678901">
                                        </div>      
                                    </div>

                                     

                                    <div id="5_div" class="col-md-8 hideMe inner_div" @if (5==$didData->
                                        dest_type) style='display: block' @endif >
                                        <label for="exampleInputEmail1">Destination <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="conf_id" id="conf_id">
                                                @if (is_array($conferencing))
                                                @foreach($conferencing as $conf)
                                                <option @if($didData->conf_id == $conf->id ) selected @endif value={{$conf->id}}>{{$conf->title}} - {{$conf->conference_id}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div> 

                                    <div id="9_div" class="col-md-8 hideMe inner_div" @if (9==$didData->
                                        dest_type) style='display: block' @endif >
                                        <label  for="exampleInputEmail1">Destination <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="queue_id" id="queue_id"></select>
                                        </div> 
                                    </div>

                                    <div id="8_div" class="col-md-8 hideMe inner_div" @if (8==$didData->
                                        dest_type) style='display: block' @endif >
                                        <label  for="exampleInputEmail1">Destination <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="ingroup" id="ingroup">
                                                @if (isset($ring_group_list))
                                                @foreach($ring_group_list as $rgroup_lst)
                                                <option @if($didData->ingroup == $rgroup_lst->id ) selected @endif value={{$rgroup_lst->id}}>{{$rgroup_lst->description}} - {{$rgroup_lst->title}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div> 
                                    </div>

                                    @php 
                                    $did_fax_array = array(); 
                                    if($fax_did_list)
                                    {
                                        foreach($fax_did_list as $ky=>$vl)
                                        {
                                            $did_fax_array[] = $vl->userId;
                                        }
                                    }
                                    // print_r($did_fax_array);exit;
                                    @endphp

                                    <div id="6_div" class="col-md-8 hideMe inner_div" @if (6==$didData->dest_type)
                                        style='display: block' @endif >
                                        <label for="exampleInputEmail1">Destination <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control select2" multiple  name="fax_did[]" id="fax_did" style="width:100%">
                                                @if (is_array($extension_list))
                                                @foreach($extension_list as $ext_lst_1)
                                                <option <?php if($did_fax_array){  if (in_array($ext_lst_1->id, $did_fax_array)){  echo "selected='selected'"; }else{ echo ''; } } ?> value={{$ext_lst_1->id}} >{{$ext_lst_1->first_name}} {{$ext_lst_1->last_name}} - {{$ext_lst_1->email}} - {{$ext_lst_1->extension}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                        
                                    </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group m-b-10">
                                        <div class="col-md-2">
                                            <label>Enable SMS <i data-toggle="tooltip" data-placement="right" title="This feature will enable Text if you set this feature as Yes" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  id="chk_sms" name="chk_sms" >
                                            <option @if ('' !=$didData->sms_phone || ''!=$didData->sms_email)
                                                selected @endif value="">No</option>
                                            <option  @if ($didData->sms == 1)
                                                selected @endif  value="1">Yes</option>
                                        </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4 sms_grid">
                                            <label class="">Assign To User <i data-toggle="tooltip" data-placement="right" title="You can assign the Text feature to an user" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="sms_email" id="sms_email" style="width:100%">
                                            @if (is_array($extension_list))
                                            @foreach($extension_list as $ext_lst_1)
                                            <option @if ($ext_lst_1->id == $didData->sms_email) {{'selected="selected"'}}
                                            @endif value={{$ext_lst_1->id}} >{{$ext_lst_1->first_name}} {{$ext_lst_1->last_name}} - {{$ext_lst_1->extension}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                            </div>
                                        </div>

                                        @php
                                            
                                        $phone_no = substr($didData->sms_phone, -10);
                                        $country_code = substr($didData->sms_phone, 0, -10);
                                        if(empty($didData->sms_phone))
                                        {
                                            $country_code = 1;
                                            $phone_no = $didData->sms_phone;
                                        }

                                        @endphp

                                      <?php /*?>  <div class="col-md-6">
    
                                            <label class="sms_grid">Forward SMS To Mobile <i data-toggle="tooltip" data-placement="right" title="Type phone number with country code" class="fa fa-info-circle" aria-hidden="true"></i><span id="sms_message"></span></label>
                                            <div class="input-daterange input-group col-md-12 sms_grid">
                                                <select  class="form-control" name="countryCode" id="countryCode">
                                            <option @if($country_code == '44') selected @endif data-countryCode="GB" value="44" >UK (+44)</option>
                                            <option @if($country_code == '1') selected @endif data-countryCode="CA" value="1">Canada (+1)</option>
                                            <option @if($country_code == '1') selected @endif data-countryCode="US" value="1">USA (+1)</option>
                                            <optgroup label="Other countries">
                                                <option @if($country_code == '213') selected @endif data-countryCode="DZ" value="213">Algeria (+213)</option>
                                                <option @if($country_code == '376') selected @endif data-countryCode="AD" value="376">Andorra (+376)</option>
                                                <option @if($country_code == '244') selected @endif data-countryCode="AO" value="244">Angola (+244)</option>
                                                <option @if($country_code == '1264') selected @endif data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                                                <option @if($country_code == '1268') selected @endif data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                                                <option @if($country_code == '54') selected @endif data-countryCode="AR" value="54">Argentina (+54)</option>
                                                <option @if($country_code == '374') selected @endif data-countryCode="AM" value="374">Armenia (+374)</option>
                                                <option @if($country_code == '297') selected @endif data-countryCode="AW" value="297">Aruba (+297)</option>
                                                <option @if($country_code == '61') selected @endif data-countryCode="AU" value="61">Australia (+61)</option>
                                                <option @if($country_code == '43') selected @endif data-countryCode="AT" value="43">Austria (+43)</option>
                                                <option @if($country_code == '994') selected @endif data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                                                <option @if($country_code == '1242') selected @endif data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                                                <option @if($country_code == '973') selected @endif data-countryCode="BH" value="973">Bahrain (+973)</option>
                                                <option @if($country_code == '880') selected @endif data-countryCode="BD" value="880">Bangladesh (+880)</option>
                                                <option @if($country_code == '1246') selected @endif data-countryCode="BB" value="1246">Barbados (+1246)</option>
                                                <option @if($country_code == '375') selected @endif data-countryCode="BY" value="375">Belarus (+375)</option>
                                                <option @if($country_code == '32') selected @endif data-countryCode="BE" value="32">Belgium (+32)</option>
                                                <option @if($country_code == '501') selected @endif data-countryCode="BZ" value="501">Belize (+501)</option>
                                                <option @if($country_code == '229') selected @endif data-countryCode="BJ" value="229">Benin (+229)</option>
                                                <option @if($country_code == '1441') selected @endif data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                                                <option @if($country_code == '975') selected @endif data-countryCode="BT" value="975">Bhutan (+975)</option>
                                                <option @if($country_code == '591') selected @endif data-countryCode="BO" value="591">Bolivia (+591)</option>
                                                <option @if($country_code == '387') selected @endif data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                                                <option @if($country_code == '267') selected @endif data-countryCode="BW" value="267">Botswana (+267)</option>
                                                <option @if($country_code == '55') selected @endif data-countryCode="BR" value="55">Brazil (+55)</option>
                                                <option @if($country_code == '673') selected @endif data-countryCode="BN" value="673">Brunei (+673)</option>
                                                <option @if($country_code == '359') selected @endif data-countryCode="BG" value="359">Bulgaria (+359)</option>
                                                <option @if($country_code == '226') selected @endif data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                                                <option @if($country_code == '257') selected @endif data-countryCode="BI" value="257">Burundi (+257)</option>
                                                <option @if($country_code == '855') selected @endif data-countryCode="KH" value="855">Cambodia (+855)</option>
                                                <option @if($country_code == '237') selected @endif data-countryCode="CM" value="237">Cameroon (+237)</option>
                                                
                                                <option @if($country_code == '238') selected @endif data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                                                <option @if($country_code == '1345') selected @endif data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                                                <option @if($country_code == '236') selected @endif data-countryCode="CF" value="236">Central African Republic (+236)</option>
                                                <option @if($country_code == '53') selected @endif data-countryCode="CL" value="56">Chile (+56)</option>
                                                <option @if($country_code == '86') selected @endif data-countryCode="CN" value="86">China (+86)</option>
                                                <option @if($country_code == '57') selected @endif data-countryCode="CO" value="57">Colombia (+57)</option>
                                                <option @if($country_code == '269') selected @endif data-countryCode="KM" value="269">Comoros (+269)</option>
                                                <option @if($country_code == '242') selected @endif data-countryCode="CG" value="242">Congo (+242)</option>
                                                <option @if($country_code == '682') selected @endif data-countryCode="CK" value="682">Cook Islands (+682)</option>
                                                <option @if($country_code == '506') selected @endif data-countryCode="CR" value="506">Costa Rica (+506)</option>
                                                <option @if($country_code == '385') selected @endif data-countryCode="HR" value="385">Croatia (+385)</option>
                                                <option @if($country_code == '53') selected @endif data-countryCode="CU" value="53">Cuba (+53)</option>
                                                <option @if($country_code == '90392') selected @endif data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                                                <option @if($country_code == '357') selected @endif data-countryCode="CY" value="357">Cyprus South (+357)</option>
                                                <option @if($country_code == '42') selected @endif data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                                                <option @if($country_code == '45') selected @endif data-countryCode="DK" value="45">Denmark (+45)</option>
                                                <option @if($country_code == '253') selected @endif data-countryCode="DJ" value="253">Djibouti (+253)</option>
                                                
                                                <option @if($country_code == '1809') selected @endif data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                                                <option @if($country_code == '593') selected @endif data-countryCode="EC" value="593">Ecuador (+593)</option>
                                                <option @if($country_code == '20') selected @endif data-countryCode="EG" value="20">Egypt (+20)</option>
                                                <option @if($country_code == '503') selected @endif data-countryCode="SV" value="503">El Salvador (+503)</option>
                                                <option @if($country_code == '240') selected @endif data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                                                <option @if($country_code == '291') selected @endif data-countryCode="ER" value="291">Eritrea (+291)</option>
                                                <option @if($country_code == '372') selected @endif data-countryCode="EE" value="372">Estonia (+372)</option>
                                                <option @if($country_code == '251') selected @endif data-countryCode="ET" value="251">Ethiopia (+251)</option>
                                                <option @if($country_code == '500') selected @endif data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                                                <option @if($country_code == '298') selected @endif data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                                                <option @if($country_code == '679') selected @endif data-countryCode="FJ" value="679">Fiji (+679)</option>
                                                <option @if($country_code == '358') selected @endif data-countryCode="FI" value="358">Finland (+358)</option>
                                                <option @if($country_code == '33') selected @endif data-countryCode="FR" value="33">France (+33)</option>
                                                <option @if($country_code == '594') selected @endif data-countryCode="GF" value="594">French Guiana (+594)</option>
                                                <option @if($country_code == '689') selected @endif data-countryCode="PF" value="689">French Polynesia (+689)</option>
                                                <option @if($country_code == '241') selected @endif data-countryCode="GA" value="241">Gabon (+241)</option>
                                                <option @if($country_code == '220') selected @endif data-countryCode="GM" value="220">Gambia (+220)</option>
                                                <option @if($country_code == '7880') selected @endif data-countryCode="GE" value="7880">Georgia (+7880)</option>
                                                <option @if($country_code == '49') selected @endif data-countryCode="DE" value="49">Germany (+49)</option>
                                                <option @if($country_code == '233') selected @endif data-countryCode="GH" value="233">Ghana (+233)</option>
                                                <option @if($country_code == '350') selected @endif data-countryCode="GI" value="350">Gibraltar (+350)</option>
                                                <option @if($country_code == '30') selected @endif data-countryCode="GR" value="30">Greece (+30)</option>
                                                <option @if($country_code == '299') selected @endif data-countryCode="GL" value="299">Greenland (+299)</option>
                                                <option @if($country_code == '1473') selected @endif data-countryCode="GD" value="1473">Grenada (+1473)</option>
                                                <option @if($country_code == '590') selected @endif  data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                                                <option @if($country_code == '671') selected @endif data-countryCode="GU" value="671">Guam (+671)</option>
                                                <option @if($country_code == '502') selected @endif data-countryCode="GT" value="502">Guatemala (+502)</option>
                                                <option @if($country_code == '224') selected @endif data-countryCode="GN" value="224">Guinea (+224)</option>
                                                <option @if($country_code == '245') selected @endif data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                                                <option @if($country_code == '592') selected @endif data-countryCode="GY" value="592">Guyana (+592)</option>
                                                <option @if($country_code == '509') selected @endif data-countryCode="HT" value="509">Haiti (+509)</option>
                                                <option @if($country_code == '504') selected @endif data-countryCode="HN" value="504">Honduras (+504)</option>
                                                <option @if($country_code == '852') selected @endif data-countryCode="HK" value="852">Hong Kong (+852)</option>
                                                <option @if($country_code == '36') selected @endif data-countryCode="HU" value="36">Hungary (+36)</option>
                                                <option @if($country_code == '354') selected @endif data-countryCode="IS" value="354">Iceland (+354)</option>
                                                <option @if($country_code == '91') selected @endif data-countryCode="IN" value="91">India (+91)</option>
                                                <option @if($country_code == '62') selected @endif data-countryCode="ID" value="62">Indonesia (+62)</option>
                                                <option @if($country_code == '98') selected @endif data-countryCode="IR" value="98">Iran (+98)</option>
                                                <option @if($country_code == '964') selected @endif data-countryCode="IQ" value="964">Iraq (+964)</option>
                                                <option @if($country_code == '353') selected @endif data-countryCode="IE" value="353">Ireland (+353)</option>
                                                <option @if($country_code == '972') selected @endif data-countryCode="IL" value="972">Israel (+972)</option>
                                                <option @if($country_code == '39') selected @endif data-countryCode="IT" value="39">Italy (+39)</option>
                                                <option @if($country_code == '1876') selected @endif data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                                                <option @if($country_code == '81') selected @endif @if($country_code == '81') selected @endif data-countryCode="JP" value="81">Japan (+81)</option>
                                                <option @if($country_code == '962') selected @endif data-countryCode="JO" value="962">Jordan (+962)</option>
                                                <option @if($country_code == '7') selected @endif data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                                                <option @if($country_code == '254') selected @endif data-countryCode="KE" value="254">Kenya (+254)</option>
                                                <option @if($country_code == '686') selected @endif data-countryCode="KI" value="686">Kiribati (+686)</option>
                                                <option @if($country_code == '850') selected @endif data-countryCode="KP" value="850">Korea North (+850)</option>
                                                <option @if($country_code == '82') selected @endif data-countryCode="KR" value="82">Korea South (+82)</option>
                                                <option @if($country_code == '965') selected @endif data-countryCode="KW" value="965">Kuwait (+965)</option>
                                                <option @if($country_code == '996') selected @endif data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                                                <option @if($country_code == '856') selected @endif data-countryCode="LA" value="856">Laos (+856)</option>
                                                <option @if($country_code == '371') selected @endif data-countryCode="LV" value="371">Latvia (+371)</option>
                                                <option @if($country_code == '961') selected @endif data-countryCode="LB" value="961">Lebanon (+961)</option>
                                                <option @if($country_code == '266') selected @endif data-countryCode="LS" value="266">Lesotho (+266)</option>
                                                <option @if($country_code == '231') selected @endif data-countryCode="LR" value="231">Liberia (+231)</option>
                                                <option @if($country_code == '218') selected @endif data-countryCode="LY" value="218">Libya (+218)</option>
                                                <option @if($country_code == '417') selected @endif data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                                                <option @if($country_code == '370') selected @endif data-countryCode="LT" value="370">Lithuania (+370)</option>
                                                <option @if($country_code == '352') selected @endif data-countryCode="LU" value="352">Luxembourg (+352)</option>
                                                <option @if($country_code == '853') selected @endif data-countryCode="MO" value="853">Macao (+853)</option>
                                                <option @if($country_code == '389') selected @endif data-countryCode="MK" value="389">Macedonia (+389)</option>
                                                <option @if($country_code == '261') selected @endif data-countryCode="MG" value="261">Madagascar (+261)</option>
                                                <option @if($country_code == '265') selected @endif data-countryCode="MW" value="265">Malawi (+265)</option>
                                                <option @if($country_code == '60') selected @endif data-countryCode="MY" value="60">Malaysia (+60)</option>
                                                <option @if($country_code == '960') selected @endif data-countryCode="MV" value="960">Maldives (+960)</option>
                                                <option @if($country_code == '223') selected @endif data-countryCode="ML" value="223">Mali (+223)</option>
                                                <option @if($country_code == '356') selected @endif data-countryCode="MT" value="356">Malta (+356)</option>
                                                <option @if($country_code == '692') selected @endif data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                                                <option @if($country_code == '596') selected @endif data-countryCode="MQ" value="596">Martinique (+596)</option>
                                                <option @if($country_code == '222') selected @endif data-countryCode="MR" value="222">Mauritania (+222)</option>
                                                <option @if($country_code == '269') selected @endif data-countryCode="YT" value="269">Mayotte (+269)</option>
                                                <option @if($country_code == '52') selected @endif data-countryCode="MX" value="52">Mexico (+52)</option>
                                                <option @if($country_code == '691') selected @endif data-countryCode="FM" value="691">Micronesia (+691)</option>
                                                <option @if($country_code == '373') selected @endif data-countryCode="MD" value="373">Moldova (+373)</option>
                                                <option @if($country_code == '377') selected @endif data-countryCode="MC" value="377">Monaco (+377)</option>
                                                <option @if($country_code == '976') selected @endif data-countryCode="MN" value="976">Mongolia (+976)</option>
                                                <option @if($country_code == '1664') selected @endif data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                                                <option @if($country_code == '212') selected @endif data-countryCode="MA" value="212">Morocco (+212)</option>
                                                <option @if($country_code == '258') selected @endif data-countryCode="MZ" value="258">Mozambique (+258)</option>
                                                <option @if($country_code == '95') selected @endif data-countryCode="MN" value="95">Myanmar (+95)</option>
                                                <option @if($country_code == '264') selected @endif data-countryCode="NA" value="264">Namibia (+264)</option>
                                                <option @if($country_code == '674') selected @endif data-countryCode="NR" value="674">Nauru (+674)</option>
                                                <option @if($country_code == '977') selected @endif data-countryCode="NP" value="977">Nepal (+977)</option>
                                                <option @if($country_code == '31') selected @endif data-countryCode="NL" value="31">Netherlands (+31)</option>
                                                <option @if($country_code == '687') selected @endif data-countryCode="NC" value="687">New Caledonia (+687)</option>
                                                <option @if($country_code == '64') selected @endif data-countryCode="NZ" value="64">New Zealand (+64)</option>
                                                <option @if($country_code == '505') selected @endif data-countryCode="NI" value="505">Nicaragua (+505)</option>
                                                <option @if($country_code == '227') selected @endif data-countryCode="NE" value="227">Niger (+227)</option>
                                                <option @if($country_code == '234') selected @endif data-countryCode="NG" value="234">Nigeria (+234)</option>
                                                <option @if($country_code == '683') selected @endif data-countryCode="NU" value="683">Niue (+683)</option>
                                                <option @if($country_code == '672') selected @endif data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                                                <option @if($country_code == '670') selected @endif data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                                                <option @if($country_code == '47') selected @endif data-countryCode="NO" value="47">Norway (+47)</option>
                                                <option @if($country_code == '968') selected @endif data-countryCode="OM" value="968">Oman (+968)</option>
                                                <option @if($country_code == '680') selected @endif data-countryCode="PW" value="680">Palau (+680)</option>
                                                <option @if($country_code == '507') selected @endif data-countryCode="PA" value="507">Panama (+507)</option>
                                                <option @if($country_code == '675') selected @endif data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                                                <option @if($country_code == '595') selected @endif data-countryCode="PY" value="595">Paraguay (+595)</option>
                                                <option @if($country_code == '51') selected @endif data-countryCode="PE" value="51">Peru (+51)</option>
                                                <option @if($country_code == '63') selected @endif data-countryCode="PH" value="63">Philippines (+63)</option>
                                                <option @if($country_code == '48') selected @endif data-countryCode="PL" value="48">Poland (+48)</option>
                                                <option @if($country_code == '351') selected @endif data-countryCode="PT" value="351">Portugal (+351)</option>
                                                <option @if($country_code == '1787') selected @endif data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                                                <option @if($country_code == '974') selected @endif data-countryCode="QA" value="974">Qatar (+974)</option>
                                                <option @if($country_code == '262') selected @endif data-countryCode="RE" value="262">Reunion (+262)</option>
                                                <option @if($country_code == '40') selected @endif data-countryCode="RO" value="40">Romania (+40)</option>
                                                <option @if($country_code == '7') selected @endif data-countryCode="RU" value="7">Russia (+7)</option>
                                                <option @if($country_code == '250') selected @endif data-countryCode="RW" value="250">Rwanda (+250)</option>
                                                <option @if($country_code == '378') selected @endif data-countryCode="SM" value="378">San Marino (+378)</option>
                                                <option @if($country_code == '239') selected @endif data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                                                <option @if($country_code == '966') selected @endif data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                                                <option @if($country_code == '221') selected @endif data-countryCode="SN" value="221">Senegal (+221)</option>
                                                <option @if($country_code == '381') selected @endif data-countryCode="CS" value="381">Serbia (+381)</option>
                                                <option @if($country_code == '248') selected @endif data-countryCode="SC" value="248">Seychelles (+248)</option>
                                                <option @if($country_code == '232') selected @endif data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                                                <option @if($country_code == '65') selected @endif data-countryCode="SG" value="65">Singapore (+65)</option>
                                                <option @if($country_code == '421') selected @endif data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                                                <option @if($country_code == '386') selected @endif data-countryCode="SI" value="386">Slovenia (+386)</option>
                                                <option @if($country_code == '677') selected @endif data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                                                <option @if($country_code == '252') selected @endif data-countryCode="SO" value="252">Somalia (+252)</option>
                                                <option @if($country_code == '27') selected @endif data-countryCode="ZA" value="27">South Africa (+27)</option>
                                                <option @if($country_code == '34') selected @endif data-countryCode="ES" value="34">Spain (+34)</option>
                                                <option @if($country_code == '94') selected @endif data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                                                <option @if($country_code == '262') selected @endif data-countryCode="SH" value="290">St. Helena (+290)</option>
                                                <option @if($country_code == '1869') selected @endif data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                                                <option @if($country_code == '1758') selected @endif data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                                                <option @if($country_code == '249') selected @endif data-countryCode="SD" value="249">Sudan (+249)</option>
                                                <option @if($country_code == '597') selected @endif data-countryCode="SR" value="597">Suriname (+597)</option>
                                                <option @if($country_code == '268') selected @endif data-countryCode="SZ" value="268">Swaziland (+268)</option>
                                                <option @if($country_code == '46') selected @endif data-countryCode="SE" value="46">Sweden (+46)</option>
                                                <option @if($country_code == '41') selected @endif data-countryCode="CH" value="41">Switzerland (+41)</option>
                                                <option @if($country_code == '963') selected @endif data-countryCode="SI" value="963">Syria (+963)</option>
                                                <option @if($country_code == '886') selected @endif data-countryCode="TW" value="886">Taiwan (+886)</option>
                                                <option @if($country_code == '7') selected @endif data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                                                <option @if($country_code == '66') selected @endif data-countryCode="TH" value="66">Thailand (+66)</option>
                                                <option @if($country_code == '228') selected @endif data-countryCode="TG" value="228">Togo (+228)</option>
                                                <option @if($country_code == '676') selected @endif data-countryCode="TO" value="676">Tonga (+676)</option>
                                                <option @if($country_code == '1868') selected @endif data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                                                <option @if($country_code == '216') selected @endif data-countryCode="TN" value="216">Tunisia (+216)</option>
                                                <option @if($country_code == '90') selected @endif data-countryCode="TR" value="90">Turkey (+90)</option>
                                                <option @if($country_code == '7') selected @endif data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                                                <option @if($country_code == '993') selected @endif data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                                                <option @if($country_code == '1649') selected @endif data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                                                <option @if($country_code == '688') selected @endif data-countryCode="TV" value="688">Tuvalu (+688)</option>
                                                <option @if($country_code == '256') selected @endif data-countryCode="UG" value="256">Uganda (+256)</option>
                                                <!-- <option data-countryCode="GB" value="44">UK (+44)</option> -->
                                                <option @if($country_code == '380') selected @endif data-countryCode="UA" value="380">Ukraine (+380)</option>
                                                <option @if($country_code == '971') selected @endif data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                                                <option @if($country_code == '598') selected @endif data-countryCode="UY" value="598">Uruguay (+598)</option>
                                                <!-- <option data-countryCode="US" value="1">USA (+1)</option> -->
                                                <option @if($country_code == '7') selected @endif data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                                                <option @if($country_code == '678') selected @endif data-countryCode="VU" value="678">Vanuatu (+678)</option>
                                                <option @if($country_code == '379') selected @endif data-countryCode="VA" value="379">Vatican City (+379)</option>
                                                <option @if($country_code == '58') selected @endif data-countryCode="VE" value="58">Venezuela (+58)</option>
                                                <option @if($country_code == '84') selected @endif data-countryCode="VN" value="84">Vietnam (+84)</option>
                                                <option @if($country_code == '84') selected @endif data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                                                <option @if($country_code == '84') selected @endif data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                                                <option @if($country_code == '681') selected @endif data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                                                <option @if($country_code == '969') selected @endif data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                                                <option @if($country_code == '967') selected @endif data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                                                <option @if($country_code == '260') selected @endif data-countryCode="ZM" value="260">Zambia (+260)</option>
                                                <option @if($country_code == '263') selected @endif data-countryCode="ZW" value="263">Zimbabwe (+263)</option>

                                            </optgroup>
                                        </select>
                                        <span class="input-group-addon">-</span>
    <input type="text" maxlength="10" value="{{$phone_no}}" onkeyup="checkPhoneNo(); return false;" style="width:280px;" onkeypress="return isNumberKey($(this));" class="form-control"  name="sms_phone" id="sms_phone" placeholder="Your Phone Number">
                                            </div>

                                        </div>

                                        <?php */ ?>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!--OOH-->
                        <hr>
                        <div class="row"> 
                            <div class="col-sm-12">
                                <div class="form-group m-b-10">

                                      <div class="col-md-4">
                                        <label>Set Exclusive For User <i data-toggle="tooltip" data-placement="right" title="Set Exclusive for user" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="set_exclusive_for_user" id="set_exclusive_for_user">
                                                <option value="1" {{$didData->set_exclusive_for_user == 1 ? 'selected' : ''}}>Yes</option>
                                                <option value="0" {{$didData->set_exclusive_for_user == 0 ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Apply Call Times <i data-toggle="tooltip" data-placement="right" title="Select call times" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="call_time_department_id" id="call_time_department">
                                                <option value="0" >No</option>
                                                @if (isset($department_list->data) && !empty($department_list->data))
                                                    @foreach($department_list->data as $item)
                                                        <option value="{{$item->id}}" {{isset($didData->call_time_department_id) && $didData->call_time_department_id == $item->id ? 'selected' : ''}}> {{$item->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Apply Holiday Calendar <i data-toggle="tooltip" data-placement="right" title="This Option will route the call to below configuration whenever the system receives the call on holidays" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="call_time_holiday" id="holiday">
                                                <option value="0" {{isset($didData->call_time_holiday) && $didData->call_time_holiday == 0 ? 'selected' : ''}}>No</option>
                                                <option value="1" {{isset($didData->call_time_holiday) && $didData->call_time_holiday == 1 ? 'selected' : ''}}>Yes</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="OutOfHours" style="{{$didData->call_time_holiday > 0 || $didData->call_time_department_id > 0  ? '' : 'display: none;'}}"> 
                            <div class="col-sm-12">
                                <div class="form-group m-b-10">
                                    <div class="col-md-4">
                                            <label>Destination Type (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination type for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="dest_type_ooh" id="dest_type_ooh" onchange="return showDataOoh(this.value)">
                                            @if (!empty($dest_type))
                                            @foreach($dest_type as $item)
                                                <option value="{{$item->dest_id}}" {{$didData->dest_type_ooh == $item->dest_id ? 'selected' : ''}} > {{$item->dest_type}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                            </div>
                                        </div>


                                        <div class="col-md-8 hideMe ooh_div" id="0_div_ooh" @if (0==$didData->dest_type_ooh)
                                        style='display: block' @endif >
                                            <label>Destination (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="ivr_id_ooh" id="ivr_id">
                                                @if (isset($ivr_list))
                                                @foreach($ivr_list as $ivr_lst)
                                                <option value={{$ivr_lst->ivr_id}} {{$didData->ivr_id_ooh == $ivr_lst->ivr_id ? 'selected' : ''}} >{{$ivr_lst->ivr_desc}} - {{$ivr_lst->ivr_id}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                        </div>

                                        <div id="1_div_ooh" class="col-md-8 hideMe ooh_div" @if (1==$didData->dest_type_ooh)
                                        style='display: block' @endif >
                                            <label>Destination (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="extension_ooh" id="extension">
                                                @if (is_array($extension_list))
                                                @foreach($extension_list as $ext_lst)
                                                <option {{$didData->extension_ooh == $ext_lst->id ? 'selected' : ''}}  value={{$ext_lst->id}}>{{$ext_lst->first_name}} {{$ext_lst->last_name}} - {{$ext_lst->extension}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                        </div>


                                        <div id="2_div_ooh" class="col-md-8 hideMe ooh_div" @if (2==$didData->dest_type_ooh)
                                        style='display: block' @endif >
                                            <label>Destination (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                            <div class="input-daterange input-group col-md-12">
                                                <select class="form-control"  name="voicemail_id_ooh" id="voicemail_id">
                                                @if (is_array($extension_list))
                                                @foreach($extension_list as $ext_lst)
                                                <option {{$didData->voicemail_id_ooh == $ext_lst->id ? 'selected' : ''}} value={{$ext_lst->id}}>{{$ext_lst->first_name}} {{$ext_lst->last_name}} - {{$ext_lst->extension}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            </div>
                                        </div>

                                           <div id="4_div_ooh" class="col-md-3 hideMe inner_div 4_div_ooh" @if (4==$didData->dest_type)
                                        style='display: block' @endif >
                                        <label for="exampleInputEmail1">Country Phone Code <i data-toggle="tooltip" data-placement="right" title="Select The Country Phone Code" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                             <select class="form-control"  name="country_code_ooh" id="country_code_ooh">
                                                @if (is_array($phone_country))
                                                @foreach($phone_country as $code)
                                                <option @if($didData->country_code_ooh == $code->phone_code ) selected @endif value={{$code->phone_code}}>{{$code->country_name}} (+{{$code->phone_code}})
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>      
                                    </div>

                                    @php
                                    $newstring = substr($didData->forward_number, -10); 
                                    @endphp

                                        <div id="4_div_ooh" class="col-md-5 hideMe ooh_div 4_div_ooh" @if (4==$didData->dest_type_ooh)
                                        style='display: block' @endif >
                                        <label>Phone Number <i data-toggle="tooltip" data-placement="right" title="Select the destination you wish to forward the calls to" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <input type="text" class="form-control" value="{{$didData->forward_number_ooh}}"  name="forward_number_ooh" id="forward_number_ooh" placeholder="Enter the phone number in Format: +12345678901">
                                        </div>      
                                    </div>

                                    <div id="5_div_ooh" class="col-md-8 hideMe ooh_div" @if (5==$didData->dest_type_ooh) style='display: block' @endif >
                                        <label for="exampleInputEmail1">Destination (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="conf_id_ooh" id="conf_id">
                                                @if (is_array($conferencing))
                                                @foreach($conferencing as $conf)
                                                <option {{$didData->conf_id == $conf->id ? 'selected' : ''}}  value={{$conf->id}}>{{$conf->title}} - {{$conf->conference_id}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div> 

                                    <div id="9_div_ooh" class="col-md-8 hideMe ooh_div" @if (9==$didData->dest_type_ooh) style='display: block' @endif >
                                        <label  for="exampleInputEmail1">Destination (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="queue_id_ooh" id="queue_id"></select>
                                        </div> 
                                    </div>

                                    <div id="8_div_ooh" class="col-md-8 hideMe ooh_div" @if (8==$didData->dest_type_ooh) style='display: block' @endif >
                                        <label  for="exampleInputEmail1">Destination (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control"  name="ingroup_ooh" id="ingroup">
                                                @if (isset($ring_group_list))
                                                @foreach($ring_group_list as $rgroup_lst)
                                                <option {{$didData->ingroup_ooh == $rgroup_lst->id ? 'selected' : ''}} value={{$rgroup_lst->id}}>{{$rgroup_lst->description}} - {{$rgroup_lst->title}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div> 
                                    </div>

                                    @php 
                                    $did_fax_array = array(); 
                                    if($fax_did_list)
                                    {
                                        foreach($fax_did_list as $ky=>$vl)
                                        {
                                            $did_fax_array[] = $vl->userId;
                                        }
                                    }
                                    // print_r($did_fax_array);exit;
                                    @endphp

                                    <div id="6_div_ooh" class="col-md-8 hideMe ooh_div" @if (6==$didData->dest_type_ooh)
                                        style='display: block' @endif >
                                        <label >Destination (Out Of Hours) <i data-toggle="tooltip" data-placement="right" title="Select destination for holiday" class="fa fa-info-circle" aria-hidden="true"></i></label>
                                        <div class="input-daterange input-group col-md-12">
                                            <select class="form-control select2" multiple  name="fax_did_ooh[]" id="fax_did_ooh" style="width:100%">
                                                @if (is_array($extension_list))
                                                @foreach($extension_list as $ext_lst_1)
                                                <option <?php if($did_fax_array){  if (in_array($ext_lst_1->id, $did_fax_array)){  echo "selected='selected'"; }else{ echo ''; } } ?> value={{$ext_lst_1->id}} >{{$ext_lst_1->first_name}} {{$ext_lst_1->last_name}} - {{$ext_lst_1->email}} - {{$ext_lst_1->extension}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--OOH-->


                                <!-- <input type="" class="form-control" name="old_call_screening_ivr_id" value="{{isset($didData->call_screening_ivr_id) ? $didData->call_screening_ivr_id : "0"}}" id ="old_call_screening_ivr_id" required> -->
                               <input type="hidden" class="form-control" name="old_ann_id" value="{{isset($didData->call_screening_ivr_id) ? $didData->call_screening_ivr_id : "0"}}" id="ann_id">

                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label for="" class="col-form-label">Call Screening Audio <i data-toggle="tooltip" data-placement="right" title="Select yes/no for call screening audio" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">*</span></label>
                                        <?php /*?><input value="{{isset($didData->ivr_desc) ? $didData->ivr_desc : ""}}" type="text" class="form-control" required  name="ivr_desc" id="ivr_desc" placeholder="IVR DESC">
                                        <?php */ ?>
                                        <select class="form-control" name="call_screening_status" id="call_screening_status">
                                        <option @if($didData->call_screening_status == 0) selected @endif value="0">No</option>
                                        <option @if($didData->call_screening_status == 1) selected @endif value="1">Yes</option>
                                    </select>
                                    </div>
                                </div>
                                <hr>
                                <div @if($didData->call_screening_status == 0)  style="display: none;"  @endif id="show_call_screen">
                                <div class="form-group row ivr-input-types">
                                    <div class="col-sm-6 radio ivr_type_file">

                                        <label for="ivr_audio_option_upload_file" class="col-form-label">
                                            <input type="radio" checked="checked" id="ivr_audio_option_upload_file" name="ivr_audio_option" value="upload" onclick="selectIvrUploadFileOption('upload_file_div');" />
                                            Upload File
                                        </label>
                                    </div>
                                    <div class="col-sm-6 radio ivr_type_txt_to_speech">
                                        <label for="ivr_audio_option_audio" class="col-form-label">
                                            <input type="radio" id="ivr_audio_option_audio" name="ivr_audio_option" value="text_to_speech" onclick="selectIvrUploadFileOption('text_to_speech_div');" />
                                            Convert Text to Audio
                                        </label>
                                    </div>
{{--                                    <div class="col-sm-4 radio ivr_type_audio_record">--}}
{{--                                        <label for="ivr_audio_option_audio_record" class="col-form-label">--}}
{{--                                            <input type="radio" @if(isset($didData->prompt_option)) @if($didData->prompt_option == 2) checked="checked" @endif @endif id="ivr_audio_option_audio_record" name="ivr_audio_option" value="audio_record" onclick="selectIvrUploadFileOption('audio_record');" />--}}
{{--                                            Record Audio--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
                                </div>
                                <hr>
                                <div class="form-group row" id="upload_file_div">
                                    <div class="col-sm-6">
                                        <label for="" class="col-form-label">Upload File <i data-toggle="tooltip" data-placement="right" title="Upload only mp3 or wav file type" class="fa fa-info-circle" aria-hidden="true"></i><span style="color:red;">* &nbsp; (Only mp3 or wav file type is allowed)</span></label>
                                        <input type="file" accept="audio/*"  class="form-control"   name="ann_id"  placeholder="Please Upload file"  />
                                    </div>
                                </div>
                                <div class="form-group row" id="text_to_speech_div" style="display: none;">
                                    <div class="col-sm-6">
                                        <label for="" class="col-form-label">Language</label>
                                        <select id="language_ddl" name="language" class="form-control" onchange="selectVoiceNameOnLanugageChange();">
                                            <option value="">--Select Language--</option>
                                            @foreach($arrLang as $key => $val)
                                            <option {{isset($didData->language) && $didData->language == base64_decode($key) ? "selected='selected'" : ""}} value="{{$key}}">{{base64_decode($key)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="" class="col-form-label">Voice Name</label>
                                        <select id="voice_name_ddl" name="voice_name" class="form-control">
                                            <option value="">--Select Voice Name--</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-10" style="padding-top: 10px;">
                                        <label for="" class="col-form-label">Text </label>
                                        <textarea id="speech_text" class="form-control" name="speech_text"
                                            placeholder="Type what you like your customers to hear and click on Listen button to listen">{{isset($didData->speech_text) ? $didData->speech_text : ""}}</textarea>
                                        <audio style="display:none;" id="test_audio" controls preload ='none'>
                                            <source src="" type='audio/mp3'>
                                        </audio>
                                    </div>
                                    <div class="col-sm-2" style="padding-top: 30px;">
                                        <a class="btn btn-primary" href="javascript:void(0);" onclick="getAudioOnText();">Listen</a>
                                    </div>
                                </div>
                                <div class="form-group row" id="record_audio" style="display: none;">
                                    <div class="col-sm-6">
                                        <button type="button" id="record" class="btn"><i class="fa fa-microphone"></i></button>
                                        <button type="button" id="stopRecord" class="btn" disabled><i class="fa fa-stop"></i></button>
                                        <span class="recording-status">Voice recording...</span>
                                        <audio id=recordedAudio></audio>
                                    </div>
                                </div>
                            </div>
                                <?php /*?><button type="submit" name ="submit"  class="btn btn btn-primary waves-effect waves-light">Save</button> <?php */ ?>
                            

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" style="float:right;">
                                    <tfoot>
                                        <tr>
                                            <td>
                                                <button id="submit"  class="btn btn-primary" type="submit">
                                                    <i class="fa fa-check-square-o fa-lg"></i> 
                                                    Update
                                                </button>
                                                &nbsp;
                                                <a type="button" class="btn btn-warning"  onclick="window.location.reload();">
                                                    <i class="fa fa-refresh fa-lg"></i> 
                                                    Reset
                                                </a>
                                                &nbsp;
                                                <a type="button" class="btn btn-danger" style="margin-right: 14px;" href="{{url('/did')}}">
                                                    <i class="fa fa-close fa-lg"></i> 
                                                    Cancel
                                                </a>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </div>
                            </div>
                        </div>


                    </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="models" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content" >
                        <div class="modal-header" style="background-color: #00a7d0 !important;">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Confirm Changes</h4>
                        </div>

                        <div class="modal-body" style="background-color: #00c0ef !important;color: #fff;border-color: #122b40">
                            <p>< <span id="view"></span> > is currently the default CLI for your account.</p>
                            <p>Do you want to set < <span id="viewCurrentCLI"></span> > as your default CLI ?</p>
                        </div>

                        <div class="modal-footer" style="background-color: #00a7d0 !important;">
                            <button type="button" class="btn btn-danger changeDefault" data-dismiss="modal">No</button>
                            <button type="button" class="btn btn-success btn-ok " data-dismiss="modal">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.row-margin-15
{
    margin-top: 1.5em;
}

.hideMe
{
    display: none;
}

.hide_show
{
    display:none;
}

.operator_txt
{
    display:none;
}



.fax_email_grid
{
    display:none;
}

</style>

<script src="{{asset('asset/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{asset('asset/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{asset('asset/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script>
        $(document).ready(function () {
        $('#forward_number').inputmask("(999) 999-9999");
        $('#forward_number_ooh').inputmask("(999) 999-9999");

    })
    </script>

<script>
    $("#call_screening_status").change(function()
    {
        if($("#call_screening_status").val() == 0)
        {
            $("#show_call_screen").hide();
        }
        else
        if($("#call_screening_status").val() == 1)
        {
            $("#show_call_screen").show();
        }

    });
</script>

<script>

    $(document).ready(function () {
        setTimeout(function() {
            selectVoiceNameOnLanugageChange();
        }, 1000);

        $(".edit_ivr_form").submit(function () {
            if ($("#ivr_audio_option_audio").prop("checked") && !$("#test_audio").attr("src")) {
                toastr.info("Please listen to the audio before submitting");
                return false;
            }
        });

        var prompt_option = @if(isset($didData->prompt_option)) @php echo $didData->prompt_option @endphp @else"0"@endif;
        var voice_name = "@if(isset($didData->voice_name))@php echo $didData->voice_name @endphp@else 0 @endif";

        if(prompt_option == 2){
            $("#ivr_audio_option_audio_record").prop("checked", true);
            $("#upload_file_div").hide();
            $("#text_to_speech_div").hide();
            $("#record_audio").show();
        } else if(prompt_option == 1){
            $("#ivr_audio_option_audio").prop("checked", true);
            $("#upload_file_div").hide();
            $("#record_audio").hide();
            $("#text_to_speech_div").show();

            setTimeout(function() {
                selectVoiceNameOnLanugageChange(voice_name);
            }, 2000);
        } else{
            $("#ivr_audio_option_upload_file").prop("checked", true);
            $("#record_audio").hide();
            $("#upload_file_div").show();
            $("#text_to_speech_div").hide();
        }
    });

    function selectIvrUploadFileOption(option) {
        if (option == 'text_to_speech_div') {
            $("#text_to_speech_div").show();
            $("#upload_file_div").hide();
            $("#record_audio").hide();
        } else if(option == 'audio_record') {
            getAudioRecordPermission();
            $("#record_audio").show();
            $("#upload_file_div").hide();
            $("#text_to_speech_div").hide();
        } else {
            $("#upload_file_div").show();
            $("#text_to_speech_div").hide();
            $("#record_audio").hide();
        }
    }

    function selectVoiceNameOnLanugageChange(defaultSelected = null) {
        $.ajax({
            url: root_url+'/get-voice-name-on-lanugage',
            type: 'post',
            data: {'language': $('#language_ddl').val()},
            success: function (response) {
                var html = '';
                $.each(response, function (index, value) {
                    var optionValue = value.language_code + " ## " + value.voice_name + " ## " + value.ssml_gender;
                    html += '<option value="'+ optionValue +'" ';
                    html += (defaultSelected == optionValue) ? 'selected >' : '>';
                    html += optionValue + '</option>';
                });
                $("#voice_name_ddl").html('');
                $("#voice_name_ddl").html(html);
            }
        });

    }

    function getAudioOnText() {
        if ($('#speech_text').val() == '') {
            toastr.error("Please Type Text");
            return;
        }
        if ($('#language_ddl').val() == '') {
            toastr.error("Please Select Language");
            return;
        }
        if ($('#voice_name_ddl').val() == '' || $('#voice_name_ddl').val() == null) {
            toastr.error("Please Select Voice Name");
            return;
        }

        $("#test_audio").attr('src', "");
        $.ajax({
            url: root_url+'/get-audio-on-text',
            type: 'post',
            data: {'language': $('#language_ddl').val(),
                'voice_name_ddl': $('#voice_name_ddl').val(),
                'speech_text': $('#speech_text').val(),
                'prompt_for' : 'announcements'},

            success: function (response) {
                if (typeof (response.file) != 'undefined') {
                    var file = "{{env('FILE_UPLOAD_URL')}}" + "{{env('ANNOUNCEMENTS_FILE_UPLOAD_FOLDER_NAME')}}" + "/" + response.file;
                     var d = new Date();
                    $("#test_audio").attr('src', file+"?"+d.getTime());
                    var x = document.getElementById("test_audio");
                    x.play();
                } else {

                }
            }
        });
    }

    function getAudioRecordPermission(){
        navigator.mediaDevices.getUserMedia({audio:true})
            .then(stream => {handlerFunction(stream)});
    }

    function handlerFunction(stream) {
        rec = new MediaRecorder(stream);
        rec.ondataavailable = e => {
            audioChunks.push(e.data);
            if (rec.state == "inactive"){
                let blob = new Blob(audioChunks,{type:'audio/wav'});
                recordedAudio.src = URL.createObjectURL(blob);
                recordedAudio.controls=true;
                recordedAudio.autoplay=true;
                sendData(blob)
            }
        }
    }

    function sendData(data) {
        var fd = new FormData();
        fd.append('data', data);

        $.ajax({
            url: '/save-recorded-audio-announcements',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function (response){
                // console.log(response);
            },
            error: function (response) {
                console.log(response);
            }
        });
    }

    record.onclick = e => {
        record.disabled = true;
        $(".recording-status").show();
        stopRecord.disabled=false;
        audioChunks = [];
        rec.start();
    }
    stopRecord.onclick = e => {
        record.disabled = false;
        stop.disabled=true;
        $(".recording-status").hide();
        rec.stop();
    }
</script>

<script>
    function showData(id)
    {
        $('.inner_div').hide();
        $('#' + id + '_div').css("display", "block");
        $('.' + id + '_div').css("display", "block");

    }
    
    function showDataOoh(id)
    {
        $('.ooh_div').hide();
        $('#' + id + '_div_ooh').css("display", "block");
        $('.' + id + '_div_ooh').css("display", "block");
        
    }

    function checkPhoneNo()
    {
        var mobile = document.getElementById('sms_phone');
        var message = document.getElementById('sms_message');
        var goodColor = "#dd4b39";
        var badColor = "";

        if(sms_phone.value.length < 9)
        {
            sms_phone.style.backgroundColor = badColor;
            sms_message.style.color = goodColor;
            sms_message.innerHTML = "required 10 digits!";
            $("#message").show();
            $("#submit").attr("disabled", true);
            return false;
        }

        if(sms_phone.value.length >9)
        {
            sms_phone.style.backgroundColor = badColor;
            sms_message.style.color = badColor;
            $("#message").hide();
            $("#submit").removeAttr("disabled");
        }
    }

    function check()
    {
        var mobile = document.getElementById('cli');
        var message = document.getElementById('message');
        var goodColor = "#dd4b39";
        var badColor = "";

        if(cli.value.length < 9)
        {
            cli.style.backgroundColor = badColor;
            message.style.color = goodColor;
            message.innerHTML = "required 10 digits!";
            $("#message").show();
            $("#submit").attr("disabled", true);
            return false;
        }

        if(cli.value.length >9)
        {
            cli.style.backgroundColor = badColor;
            message.style.color = badColor;
            $("#message").hide();
            $("#submit").removeAttr("disabled");
        }
    }

    $(document).ready(function()
    {
        $('#default_did').change(function()
        {
            default_did = $('#default_did').val();
            $.ajax({
                url: '/findDefaultDid/' + default_did,
                type: 'get',
                success: function (response)
                {
                    if(response[0].cli)
                    {
                        cli = $("#cli").val();
                        $("#view").html(response[0].cli);
                        $("#viewCurrentCLI").html(cli);


                        $("#models").modal();
                    }
                }
            });
        });

        $(document).on("click", ".changeDefault" , function()
        {
            $("#default_did").val('0');
        });

        $('#operator_check').change(function()
        {
            operator = $('#operator_check').val();
            if(operator == 0)
            {
                $('.operator_txt').hide();
            }

            else if(operator == 1)
            {
                $('.operator_txt').show();
            }
        });

        $('#veh1').change(function()
        {
            veh1 = $('#veh1').val();
            if(veh1 == 'v')
            {
                $('.fax_email_grid').hide();
            }

            else if(veh1 == 'f')
            {
                $('.fax_email_grid').show();
            }
        });


        chk_sms = $('#chk_sms').val();
        if(chk_sms == '1')
        {
            $('.sms_grid').show();
        }
        else
            $('.sms_grid').hide();

       

        $('#chk_sms').change(function()
        {
            chk_sms = $('#chk_sms').val();
            if(chk_sms == 0)
            {
                $('.sms_grid').hide();
            }
            else
            if(chk_sms == 1)
            {
                $('.sms_grid').show();
            }
        });

        redirect_last_agent = $('#redirect_last_agent').val();
        if(redirect_last_agent == '1')
        {
            $('#showRow').hide();
        }
        else
            $('#showRow').show();

        $('#redirect_last_agent').change(function()
        {
            redirect_last_agent = $('#redirect_last_agent').val();
            if(redirect_last_agent == 1)
            {
                $('#showRow').hide();
            }
            else
            if(redirect_last_agent == 0)
            {
                $('#showRow').show();

            }
        });

        $('#fax_did').select2()
        $('#fax_did_ooh').select2()
        
        //Toggle ooh inputs
        $("#call_time_department").on('change', function(){
            toggleOutOfHousDiv();
        });
        //Toggle ooh inputs
        $("#holiday").on('change', function(){
            toggleOutOfHousDiv();
        });
    });
    
    /**
    * Toggle ooh inputs
    * @returns {undefined}
    */
    function toggleOutOfHousDiv() {
        if($("#call_time_department").val() == '0' && $("#holiday").val() == '0') {
            $("#OutOfHours").hide();
        } else {
            $("#OutOfHours").show();
        }
    }

    </script>

    @endsection