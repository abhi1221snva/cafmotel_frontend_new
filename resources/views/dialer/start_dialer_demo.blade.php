<!DOCTYPE html>
<html>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <input type="hidden" name="csrf-token" id="csrf-token" value="{{ csrf_token() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/font-awsome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/webphone.css') }}">
    <script src="{{ asset('asset/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
</head>


<body>


    <div class="row m-l-r-0 dialer-section-wrapper">
        <div id="dash-dailer" class="col-md-10 dash-dailer p-0">
           <iframe  src="/dashboard" class="iframe">
                Your browser doesn't support iframes
            </iframe>
        </div>

        <div id="dialer-key-wrapper" class="col-md-2 dialer-key-wrapper p-0" style="background-color: blackoverflow:scroll; height:750px;">
            <div id="mySidenav" class="sidenav">
                <div class="footer_demo_top">
                <div style="">
                    <div class="">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()" title="minimize"><i class="fa fa-minus-square" aria-hidden="true"></i></a>
                        <form id="logout-form" action="" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

                <a title="Log Out" href="/logout" style=" background-color: transparent;color: white;text-decoration: none;border: none;padding: 10px 10px 0px 0px;;position:absolute;top:0%;font-size: 17px;right: 0px;" class="btn btn-default btn-flat"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out
                </a> 
            </div>


                <div class="webphone-container">
                    <div id="divCallCtrl" class="containerwebphone">
                        <label style="width: 100%;text-align:center;" id="txtRegStatus"></label>
                        <label style="width: 100%;text-align:center;" id="txtCallStatus"></label>
                        <input type="text" class="form-control sipml5-phone-no-input" id="txtPhoneNumber" value="" placeholder="Enter Phone Number">
                        <div class="dialpad-area">
                            <table style="margin: 10px auto;">
                                <tr>
                                    <td><input type="button" class="dialpad-number" value="1" onclick="sipSendDTMF('1');" />
                                        <input type="button" class="dialpad-number" value="2" onclick="sipSendDTMF('2');" />
                                        <input type="button" class="dialpad-number" value="3" onclick="sipSendDTMF('3');" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="button" class="dialpad-number" value="4" onclick="sipSendDTMF('4');" />
                                        <input type="button" class="dialpad-number" value="5" onclick="sipSendDTMF('5');" />
                                        <input type="button" class="dialpad-number" value="6" onclick="sipSendDTMF('6');" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="button" class="dialpad-number" value="7" onclick="sipSendDTMF('7');" />
                                        <input type="button" class="dialpad-number" value="8" onclick="sipSendDTMF('8');" />
                                        <input type="button" class="dialpad-number" value="9" onclick="sipSendDTMF('9');" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="button" class="dialpad-number" value="*" onclick="sipSendDTMF('*');" />
                                        <input type="button" class="dialpad-number" value="0" onclick="sipSendDTMF('0');" />
                                        <input type="button" class="dialpad-number" value="#" onclick="sipSendDTMF('#');" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <table style="margin: 10px auto;">
                            <tr>
                                <td>
                                    <button id="offsetbutton" style="display: inline-block;visibility: hidden;width: 65px;margin: 3px;"></button>
                                    <div id="divBtnCallGroup" class="btn-group">
                                        <button id="btnCall" onclick="closeNav()" class=""><i class="fa fa-phone"></i></button>
                                    </div>
                                    <button type="button" id="btnHangUp" class="btn btn-primary" onclick='sipHangUp();'><i class="fa fa-phone"></i></button>
                                    <button id="dialpad-backspace"><i class="fa fa-chevron-left" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                        <hr />
                        <div>
                            <input type="button" class="btn btn-success" id="btnRegister" value="Enable Webphone" disabled onclick='sipRegister();' />
                            <input type="button" class="btn btn-danger" id="btnUnRegister" value="Disable Webphone" disabled onclick='sipUnRegister();' />
                        </div>


                        <div id='divCallOptions' class='call-options' style='display:none;opacity: 0; margin-top: 10px'>
                            <input type="button" class="btn btn-primary" style="" id="btnFullScreen" value="FullScreen" disabled onclick='toggleFullScreen();' />
                            <input type="button" class="btn btn-primary" style="" id="btnMute" value="Mute" onclick='sipToggleMute();' />
                            <input type="button" class="btn btn-primary" style="" id="btnHoldResume" value="Hold" onclick='sipToggleHoldResume();' />
                            <input type="button" class="btn btn-primary" style="" id="btnTransfer" value="Transfer" onclick='sipTransfer();' />
                            <input type="button" class="btn btn-primary" style="" id="btnKeyPad" value="KeyPad" onclick='openKeyPad();' />
                        </div>
                        <table style='display:none;width: 100%;'>
                            <tr>
                                <td id="tdVideo" class='tab-video'>
                                    <div id="divVideo" class='div-video'>
                                        <div id="divVideoRemote" style='position:relative; height:100%; width:100%; z-index: auto; opacity: 1'>
                                            <video class="video" width="100%" height="100%" id="video_remote" autoplay="autoplay" style="opacity: 0;
                                            background-color: #000000; -webkit-transition-property: opacity; -webkit-transition-duration: 2s;"></video>
                                        </div>

                                        <div id="divVideoLocalWrapper" style="margin-left: 0px; border:0px solid #009; z-index: 1000">
                                            <iframe class="previewvideo" style="border:0px solid #009; z-index: 1000"></iframe>
                                            <div id="divVideoLocal" class="previewvideo" style=' border:0px solid #009; z-index: 1000'>
                                                <video class="video" width="100%" height="100%" id="video_local" autoplay="autoplay" muted="true" style="opacity: 0;
                                                background-color: #000000; -webkit-transition-property: opacity;
                                                -webkit-transition-duration: 2s;"></video>
                                            </div>
                                        </div>
                                        <div id="divScreencastLocalWrapper" style="margin-left: 90px; border:0px solid #009; z-index: 1000">
                                            <iframe class="previewvideo" style="border:0px solid #009; z-index: 1000"></iframe>
                                            <div id="divScreencastLocal" class="previewvideo" style=' border:0px solid #009; z-index: 1000'></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <ul id="ulCallOptions" class="dropdown-menu" style="visibility:hidden">
                            <li><a href="#" onclick='sipCall("call-audio");'>Audio</a></li>
                            <li><a href="#" onclick='sipCall("call-audiovide`o");'>Video</a></li>
                            <li id='liScreenShare'><a href="#" onclick='sipShareScreen();'>Screen Share</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onclick='uiDisableCallOptions();'><b>Disable these options</b></a></li>
                        </ul>

                         @if($client['webphone'] == 0)

                        <div class="overlaywebphone">
                            <a href="#" class="icon" title="User Profile">
                            <i style="font-size: 50px;margin-right: 13px;" class="fa fa-lock"></i></a>
                        </div>
                        @endif
                    </div>



                    
                   

                    <div id="divCallCtrlSMS" >

                        <section id="contact" @if($client['sms'] == 0) class="overlaysms1"  @endif style="margin-top: 0px;">
                            <h3 style="margin-top: 0px;color: whitesmoke;text-align: center;font-size: 20px;font-weight: bolder;">Send SMS</h3>
                            <div id="message_response">
                            </div>
                            <div class="tabs">
                                <div class="tab-2">


                
                                    <label for="tab2-1"><i class="fa fa-inbox" aria-hidden="true"></i>  Inbox <span  id="sms_count_unread_inbox"></span></label>
                                    <input id="tab2-1" name="tabs-two" type="radio" checked="checked">
                                    <div>
                                        <h4>Inbox</h4>
                                        <div id="chat" class="panel-collapse collapse in">
                                            <div>

                                                


                                                <div class="portlet-body chat-widget" id="hiddenAfterSms" style="width: 100%; height: 100%;">
                                                    <?php if(!empty($sms_list->data)){ ?>
                                                        <?php  foreach ($sms_list->data as $key => $sms_data) { ?>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="media1" onclick="openDiv(<?php echo $sms_data->number ?>,<?php echo $sms_data->did ?>,<?php echo $sms_data->id; ?>);" style="color: whitesmoke;font-size: 15px;cursor: pointer;">
                                                                        <a class="pull-left" style="padding-right: 10px;padding-left: 0px" href="#">
                                                                            <img class="media-object img-circle img-chat" src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="">
                                                                        </a>

                                                                        <div class="media-body">
                                                                            <h4 class="media-heading" style="font-size: 15px;margin-top: 0px;"><?php  echo $sms_data->number ?>
                                                                                <span class="small pull-right">{{$sms_data->date}}</span>
                                                                            </h4>
                                                                            <p><?php
                                                                            $sms_detail = strlen($sms_data->message) > 50 ? substr($sms_data->message, 0, 50)."..." : $sms_data->message;
                                                                            echo $sms_detail; ?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                             <div class="form-group" id="loading_sms_<?php echo $sms_data->id; ?>" style="display: none;">
                                                <div class="col-sm-12">
                                                        <button style="font-size: 20px;margin-left: 80px;" type="button"  class="spinner-button btn btn-pri" disabled=""><i class="fa fa-circle-o-notch fa-spin"></i> </button>

                                                </div>
                                            </div>

                                                            

                                                            <hr style="margin-top: 0px;margin-bottom: 0px;">

                                                            <?php
                                                        }  
                                                    }  
                                                    ?>
                                                </div>
                                               


                                                 <div class="container_sms_"  id="container_sms_" style="display: none;">
                                                                 
                                                             </div>

                                                            

                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                



                                                <div id="contact_form_sms_data" class="form-horizontal footer_demo" style="display: none;" role="form">

                                                    <button style="font-size: 15px;" type="button" id="btnFetchSms" class="spinner-button btn btn-primary send-button" disabled=""><i class="fa fa-circle-o-notch fa-spin"></i> loading...</button>

                                                    <div  style="display: none;" id="show_text" class="form-group"><div class="col-sm-12"><span style="color:#f5f5f5;letter-spacing:2px;font-sixe:15px" id="message_data"></span><textarea style="background-color: white;color:black;" class="form-control" maxlength="160" rows="5" placeholder="MESSAGE" name="message" id="new_data_message" required></textarea><span style="color:#f5f5f5"><span id="rchars_msg">160</span>Character(s) Remaining</span></div></div><div class="form-group" id="hiddenDiv"><div class="col-sm-12">

                                                        <button class="btn btn-primary"  id="reply" value="Reply"><i class="fa fa-reply" aria-hidden="true"></i>  Reply</button>
                                                        <button class="btn btn-primary"  id="back" value="Back"><i class="fa fa-backward" aria-hidden="true"></i>  Back</button>
                                                </div></div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-2" class="containersms">
                                    <label for="tab2-2"><i class="fa fa-commenting" aria-hidden="true"></i> Compose</label>
                                    <input id="tab2-2" name="tabs-two" type="radio" >
                                    <div>
                                        <h4>Compose</h4>
                                        <div id="contact-form" class="form-horizontal" role="form" > 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    @if(empty($sms_number_list))
                                                    <span style="font-size: 16px;color: red;font-weight: bold;">You don't have any number asssigned for sms</span>
                                                    @endif
                                                    <span style="color:whitesmoke;letter-spacing: 2px;font-sixe:15px;" id="new_from_data"></span>
                                                    <select class="form-control" name="did" id="new_from">
                                                        <option value="">From</option>
                                                        @if(!empty($sms_number_list))
                                                        @foreach($sms_number_list as $did)
                                                            <option value="{{$did}}">{{$did}}</option>
                                                        @endforeach
                                                        @endif

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row no-gutter">
                                                    <span style="color:whitesmoke;letter-spacing: 2px;font-sixe:15px;" id="to_data"></span>

                                                <div class="col-sm-6">
                                                    <select class="form-control" name="countryCode" id="countryCode">
                                                        @if (is_array($phone_country))
                                                        @foreach($phone_country as $code)
                                                            <option @if($code->phone_code == 1) selected @endif  value={{$code->phone_code}}>{{$code->country_name}} (+{{$code->phone_code}})
                                                            </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="" class="form-control" maxlength="10" name="number" id="new_to" placeholder="Number"  value="" required>
                                                </div>
                                            </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <span style="color:whitesmoke;letter-spacing: 2px;font-sixe:15px;" id="message_data"></span>
                                                    <textarea class="form-control" maxlength="160" rows="10" placeholder="MESSAGE" name="message" id="new_message" required></textarea>
                                                    <span style="color:whitesmoke;"><span id="rchars">160</span> Character(s) Remaining</span>
                                                </div>
                                            </div>

                                            <div class="form-group" id="hiddenDiv">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-primary send-button" @if($client['sms'] == 1) id="sendNewSms" @endif type="submit" value="SEND">
                                                        <div class="alt-send-button">
                                                            <i class="fa fa-paper-plane"></i><span class="send-text">SEND</span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                             <div class="form-group" id="loading" style="display: none;">
                                                <div class="col-sm-12">
                                                        <button style="font-size: 15px;" type="button" id="btnFetch" class="spinner-button btn btn-primary send-button" disabled=""><i class="fa fa-circle-o-notch fa-spin"></i> loading...</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                  
                                </div>


                            </div>

                              @if($client['sms'] == 0)

                        <div class="overlaysms">
                            <a href="#" class="icon" title="User Profile">
                            <i style="font-size: 50px;margin-right: 13px;" class="fa fa-lock"></i></a>
                        </div>
                        @endif
                        </section>

                       
                    
                    </div>

                   



                    <div id="divCallCtrlFAX" >
                        <section id="contact" style="margin-top: 0px;">
                            <h3 style="color: whitesmoke;text-align: center;font-size: 20px;font-weight: bolder;">Send FAX</h3>
                            <div id="message_response">
                            </div>
                            <div class="tabsfax">
                                <div class="tab-3">
                                    <label for="tab3-1"><i class="fa fa-inbox" aria-hidden="true"></i>  Inbox <span style="background-color:red;" class="badge"></span></label>
                                    <input id="tab3-1" name="tabs-three" type="radio" >
                                    <div>
                                        <h4>Inbox</h4>
                                        <div id="chat" class="panel-collapse collapse in">
                                            <div>
                                                <div class="portlet-body chat-widget" style="width: 100%; height: 100%;">
                                                        @foreach($receiveFax as $key => $fax)
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="media" style="color: whitesmoke;font-size: 15px;">
                                                                        <a class="pull-left" style="padding-right: 10px;padding-left: 0px" href="#">
                                                                            <img class="media-object img-circle img-chat" src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="">
                                                                        </a>

                                                                        <div class="media-body">
                                                                            <h4 class="media-heading" style="font-size: 15px;margin-top: 10px;">{{$fax->callerid}}
                                                                                <span class="small pull-right">{{ date('Y-m-d h:i A',strtotime($fax->start_time)) }}</span>
                                                                            </h4>



                                                                            <h6>{{$fax->dialednumber}} <span  class="small pull-right"><a style="font-size: 15px;
    cursor: pointer;" href="{{'fax-list'}}/{{$fax->id}}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a></span></h6>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr style="margin-top: 5px;margin-bottom: 5px;">

                                                            @endforeach 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-3" class="containerfax">
                                    <label for="tab3-2"><i class="fa fa-commenting" aria-hidden="true"></i> Compose</label>
                                    <input id="tab3-2" name="tabs-three" type="radio" checked="checked">
                                    <div>
                                        <h4>Compose</h4>
                                        <form method="POST" enctype="multipart/form-data" @if($client['fax'] == 1) id="laravel-ajax-file-upload" @endif action="javascript:void(0)" >
                                        <div id="contact-form" class="form-horizontal" role="form" > 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                @if(empty($fax_did_list))
                                                    <span style="font-size: 16px;color: red;font-weight: bold;">You don't have any number asssigned for Fax</span>
                                                    @endif
                                                    <span style="color:whitesmoke;letter-spacing: 2px;font-sixe:15px;" id="new_from_data_fax"></span>
                                                    <select class="form-control" name="from_id" id="from_id" required="">
                                                        <option value="">From</option>
                                                        @if(!empty($fax_did_list))
                                                        @foreach($fax_did_list as $did)
                                                            <option value="{{$did->did}}">{{$did->did}}</option>
                                                        @endforeach
                                                        @endif

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row no-gutter">
                                                    <span style="color:whitesmoke;letter-spacing: 2px;font-sixe:15px;" id="to_id_data"></span>

                                                <div class="col-sm-6">
                                                    <select class="form-control" name="countryCode" id="countryCode">
                                                        @if (is_array($phone_country))
                                                        @foreach($phone_country as $code)
                                                            <option @if($code->phone_code == 1) selected @endif  value={{$code->phone_code}}>{{$code->country_name}} (+{{$code->phone_code}})
                                                            </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-sm-6">

                                                    <input type="" class="form-control" name="to_id" id="to_id" required="" placeholder="Recipient Number"  value="" required>
                                                </div>
                                            </div>
                                            </div>

                                            

                                            <div class="form-group">
                                                <div class="row no-gutter">
                                                

                                                <div class="col-sm-12">
                                                    <span style="color:whitesmoke;letter-spacing: 2px;font-sixe:15px;" id="to_data"></span>
                                                    <input class="form-control" type="file" name="pdf_file" placeholder="Choose File" id="pdf_file">

                                                    <span >Max. 10MB (Only pdf files are allowed)</span>
                                                </div>
                                            </div>
                                            </div>

                                            

                                            <div class="form-group" id="hiddenDivFax">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-primary send-button" id="sendNewFax1" type="submit" value="SEND">
                                                        <div class="alt-send-button">
                                                            <i class="fa fa-paper-plane"></i><span class="send-text">SEND</span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>


                                            

                                             <div class="form-group" id="loadingFax" style="display: none;">
                                                <div class="col-sm-12">
                                                        <button style="font-size: 15px;" type="button" id="btnFetchFax" class="spinner-button btn btn-primary send-button" disabled=""><i class="fa fa-circle-o-notch fa-spin"></i> loading...</button>

                                                </div>
                                            </div>


                                              <span id="btnFetchFaxError" style="font-size: 17px;color: whitesmoke;font-weight: bold;" style="display: none;"></span>
                                        </div>
                                    </form>
                                    </div>

                                    @if($client['fax'] == 0)

                        <div class="overlayfax">
                            <a href="#" class="icon" title="User Profile">
                            <i style="font-size: 50px;margin-right: 13px;" class="fa fa-lock"></i></a>
                        </div>
                        @endif
                                </div>

                                
                            </div>
                        </section>

                        
                    

                    

                    </div>

                     


                </div>
            </div>
        </div>


        <ul class="wrapper show_nev-red4">
            <li class="icon instagram" id="show_nev3" onclick="openNavFAX()"  style="background: black;color: #ffffff;">
                <span class="tooltip">Fax</span>
                <span><i class="fa fa-fax"></i></span>
                <span id="fax_count_unread"></span>

            </li>
        </ul>

        <ul class="wrapper show_nev-red3">
            <li class="icon facebook" id="show_nev2"  style="background: #1877F2;color: #ffffff;">
                <span class="tooltip ">Chat</span>
                <span><i class="fa fa-users"></i></span>
            </li>
        </ul>

        <ul class="wrapper show_nev-red2">
            <li class="icon github" id="show_nev1" onclick="openNavSMS()"  style="background: #40bf2a;color: #ffffff;">
                <span class="tooltip">Sms</span>
                <span><i class="fa fa-commenting" aria-hidden="true"></i></span>
                <span id="sms_count_unread"></span>
            </li>
        </ul>

        <style>
            .github {
  background-color: #555;
  color: white;
  text-decoration: none;
  padding: 15px 26px;
  position: relative;
  display: inline-block;
  border-radius: 2px;
}

.github:hover {
  background: red;
}

.github .badge {
  position: absolute;
  top: -10px;
  right: -10px;
  padding: 5px 10px;
  border-radius: 50%;
  background-color: red;
  color: white;
}

.instagram {
  background-color: #555;
  color: white;
  text-decoration: none;
  padding: 15px 26px;
  position: relative;
  display: inline-block;
  border-radius: 2px;
}

.instagram:hover {
  background: red;
}

.instagram .badge {
  position: absolute;
  top: -10px;
  right: -10px;
  padding: 5px 10px;
  border-radius: 50%;
  background-color: red;
  color: white;
}
</style>

        <ul class="wrapper show_nev-red1" id="wrapper_class">
            <li onclick="openNav()" id="show_nev" class="icon youtube" >
                <span class="tooltip">Webphone</span>
                <span><i class="fa fa-phone"></i></span>
            </li>
        </ul> 

        <audio id="audio_remote" autoplay="autoplay"></audio>
        <audio id="ringtone" loop src="{{URL::asset('asset/audio/ringtone.wav')}}"></audio>
        <audio id="ringbacktone" loop src="{{URL::asset('asset/audio/ringbacktone.wav')}}"></audio>
        <audio id="dtmfTone" src="{{URL::asset('asset/audio/dtmf.wav')}}"></audio>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script type="text/javascript">
$(document).ready(function (e) {
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$('#laravel-ajax-file-uploadss').submit(function(e) {
e.preventDefault();
var formData = new FormData(this);
$.ajax({
type:'POST',
url: "{{ url('sendFaxGet')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {
this.reset();
alert('File has been uploaded successfully');
console.log(data);
},
error: function(data){
console.log(data);
}
});
});
});
</script>
    <script>


        $(document).ready(function (e) {
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});


        $('#laravel-ajax-file-uploadsss').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            var jqXhr = $.ajax({
type:'POST',
url: "{{ url('sendFaxGet')}}",
data: formData,
cache:false,
contentType: false,
processData: false,
/*success: (data) => {
this.reset();
alert('File has been uploaded successfully');
console.log(data);
},
error: function(data){
console.log(data);
}*/
});



            $("#hiddenDiv").hide();
            $("#loading").show();


            
        });
        });


        $("#laravel-ajax-file-upload").on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData(this);
            

            var jqXhr = $.ajax({
                type:'POST',
url: "{{ url('sendFaxGet')}}",
data: formData,

                cache:false,
contentType: false,
processData: false,
dataType:'json',

            });

            $("#hiddenDivFax").hide();
            $("#loadingFax").show();


            jqXhr.done(function (data) {

                if (data.success == 'true') {

                    alert(data.success);
            

                    var div_close = "alert-success";
                    

                    $("#btnFetchFax").html('<i class="fa fa-check" aria-hidden="true"></i> Message Sent...');

                    setTimeout(function() {

                        $("#hiddenDivFax").show();
            $("#loadingFax").hide();
               
           }, 3000);
                   
                } else {
            $("#loadingFax").hide();


                        $("#btnFetchFaxError").show();


                     $("#btnFetchFaxError").html(data.message);

                 setTimeout(function() {


            

               
                        $("#hiddenDivFax").show();
                        $("#btnFetchFaxError").hide();

           }, 3000);




                }

               
            })
            
            
        })



        $("#sendNewSms").on('click', function () {
            $('#left_box').show();
            $('#right_new_msg').show();

            countryCode = $("#countryCode").val();
            to = $("#new_to").val();
            if (to == "") {
                $("#to_data").text('Please enter number');
                $("#new_to").focus();

                $('#left_box').hide();
                $('#right_new_msg').hide();
                return false;
            }

            from = $("#new_from").val();
            if (from == "") {
                $("#new_from_data").text('Please select DID');
                $('#left_box').hide();
                $('#right_new_msg').hide();
                return false;
            }

            message = $("#new_message").val();
            if (message == "") {
                $("#message_data").text('Please write message');
                $('#left_box').hide();
                $('#right_new_msg').hide();
                return false;
            }

            to = countryCode+to;
            var created_date = moment.utc().format('YYYY-MM-DD HH:mm:ss');

            var jqXhr = $.ajax({
                url: 'sendSms',
                type: 'get',
                dataType: "json",
                data: { 'from': from, 'to': to, 'message': message, 'created_date':created_date }
            });

            $("#hiddenDiv").hide();
            $("#loading").show();


            jqXhr.done(function (data) {
                $('#right_new_msg').hide();
                if (data.success == 'true') {
            

                    var div_close = "alert-success";
                    /*$("#message_response").html('<div style="background-color:#40bf2a !important;color:white !important" class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+data.message+'</div>');*/

                    $("#btnFetch").html('<i class="fa fa-check" aria-hidden="true"></i> Message Sent...');

                    setTimeout(function() {

                        $("#hiddenDiv").show();
            $("#loading").hide();
               
           }, 3000);
                    $("#new_to").val('');
                    $("#new_message").val('');
                    $("#to_data").text('');
                    $("#new_from_data").text('');
                    $("#message_data").text('');
                } else {
                    $("#message_response").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Alert!</h4>'+data.message+'</div>');
                    var div_close = "alert-danger";
                    $('#left_box').hide();
                }

                $("." + div_close).delay(4000).slideUp(1000, function () {
                    $(this).alert('close');
                });
            })
            .done(function (data) {                
                $.ajax({
                    url: 'recentSmsList',
                    type: 'get',
                    dataType: "json",
                    success: function (response_data) {
                        $('#hiddenAfterSms').html("");
                        var res_length = Object.keys(response_data.data).length
                        if (res_length > 0) {
                            $('#hiddenAfterSms').html("");
                            var elem = document.getElementById('hiddenAfterSms');
                            for (var i = 0; i < res_length; i++) {
                                var obj = response_data.data[i];
                                if (obj.status == '0') {
                                    status = 'unread';
                                } else {
                                    status = '';
                                }
                                var localTime = moment.utc(obj.date).local();
                                var created_date = localTime.format('YYYY-MM-DD HH:mm');
                                elem.innerHTML = elem.innerHTML + '<div class="row"><div class="col-lg-12"><div class="media1" @if($client['sms'] == 1) onclick="openDiv('+ obj.number +','+ obj.did +','+ obj.id +');" @endif style="color: whitesmoke;font-size: 15px;cursor:pointer;margin: 5px 1px 0px 0px;"><a class="pull-left" style="padding-right: 10px;padding-left: 0px;padding-top:0px;" href="#"><img class="media-object img-circle img-chat" src="https://bootdey.com/img/Content/avatar/avatar6.png" alt=""></a><div class="media-body"><h4 class="media-heading" style="font-size: 15px;margin-top: 0px;">'+ obj.number +'<span class="small pull-right">' + created_date + '</span></h4><p>' + obj.message + '</p></div><p class="label label-danger" id="unread" style="margin-left: 53px;margin-top: 0px;">'+status+'</p></div></div></div>  <div class="form-group" id="loading_sms_'+obj.id+'" style="display: none;"><div class="col-sm-12"><button style="font-size: 20px;margin-left: 80px;" type="button"  class="spinner-button btn btn-pri" disabled=""><i class="fa fa-circle-o-notch fa-spin"></i> </button></div></div><div class="container_sms_" id="container_sms_'+obj.id+'" style="display: none;"></div><hr style="margin-top: 0px;margin-bottom: 0px;">';
                            }
                        }
                        $('#left_box').hide();
                    }
                });
            })
            .done(function (data) {                
                $.ajax({
                    url: 'openSmsDetails',
                    data: { 'from': from, 'to': to },
                    type: 'get',
                    success: function (response) {
                        $('#div_number').val(to);
                        $('#div_did').val(from);
                        $("#sms_list").show();
                        $('#hide_new_message_div').hide();
                        for (var i = 0; i < response.length; i++) {
                            var obj = response[i];
                            var ele = document.getElementById('show');
                            if (obj.type == 'incoming') {
                                ele.innerHTML = ele.innerHTML + '<div class="direct-chat-msg"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left"><i style="color:green;" class="fa fa-arrow-down" aria-hidden="true"></i>' + obj.number + '</span><span class="direct-chat-timestamp pull-right">' + obj.date + '</span></div> <div class="user_img incomingcss">C</div> <div class="direct-chat-text">' + obj.message + '</div></div>';
                            }
                            if (obj.type == 'outgoing') {
                                ele.innerHTML = ele.innerHTML + '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right"><i style="color:green;" class="fa fa-arrow-up" aria-hidden="true"></i>' + obj.number + '</span><span class="direct-chat-timestamp pull-left">' + obj.date + '</span></div> <div class="user_img outgoingcss">A</div> <div class="direct-chat-text">' + obj.message + '</div></div>';
                            }
                        }
                    }
                });
            })
            .fail(function (xhr) {
                console.log('error callback 1', xhr);
            })
            .fail(function (xhr) {
                console.log('error callback 1', xhr);
            })
            .fail(function (xhr) {
                console.log(xhr);
                console.log('error common back', xhr);
            });
        })

    </script>

    <script src="{{ asset('asset/js/SIPml-api.js') }}"  type="text/javascript"></script>
    <script type="text/javascript">

        var sTransferNumber;
        var oRingTone, oRingbackTone;
        var oSipStack, oSipSessionRegister, oSipSessionCall, oSipSessionTransferCall;
        var videoRemote, videoLocal, audioRemote;
        var bFullScreen = false;
        var oNotifICall;
        var bDisableVideo = false;
        var viewVideoLocal, viewVideoRemote, viewLocalScreencast; // <video> (webrtc) or <div> (webrtc4all)
        var oConfigCall;
        var oReadyStateTimer;

        C = {
            divKeyPadWidth: 220
        };

        window.onload = function() {
            window.console && window.console.info && window.console.info("location=" + window.location);

            videoLocal = document.getElementById("video_local");
            videoRemote = document.getElementById("video_remote");
            audioRemote = document.getElementById("audio_remote");

            document.onkeyup = onKeyUp;
            document.body.onkeyup = onKeyUp;
            divCallCtrl.onmousemove = onDivCallCtrlMouseMove;

            // set debug level
            SIPml.setDebugLevel((window.localStorage && window.localStorage.getItem('org.doubango.expert.disable_debug') == "true") ? "error" : "info");
            loadCredentials();
            // loadCallOptions();

            // Initialize call button
            uiBtnCallSetText("Call");

            var getPVal = function(PName) {
                var query = window.location.search.substring(1);
                var vars = query.split('&');
                for (var i = 0; i < vars.length; i++) {
                    var pair = vars[i].split('=');
                    if (decodeURIComponent(pair[0]) === PName) {
                        return decodeURIComponent(pair[1]);
                    }
                }
                return null;
            }

            var preInit = function() {
                // set default webrtc type (before initialization)
                var s_webrtc_type = getPVal("wt");
                var s_fps = getPVal("fps");
                var s_mvs = getPVal("mvs"); // maxVideoSize
                var s_mbwu = getPVal("mbwu"); // maxBandwidthUp (kbps)
                var s_mbwd = getPVal("mbwd"); // maxBandwidthUp (kbps)
                var s_za = getPVal("za"); // ZeroArtifacts
                var s_ndb = getPVal("ndb"); // NativeDebug

                if (s_webrtc_type) SIPml.setWebRtcType(s_webrtc_type);

                // initialize SIPML5
                SIPml.init(postInit);

                // set other options after initialization
                if (s_fps) SIPml.setFps(parseFloat(s_fps));
                if (s_mvs) SIPml.setMaxVideoSize(s_mvs);
                if (s_mbwu) SIPml.setMaxBandwidthUp(parseFloat(s_mbwu));
                if (s_mbwd) SIPml.setMaxBandwidthDown(parseFloat(s_mbwd));
                if (s_za) SIPml.setZeroArtifacts(s_za === "true");
                if (s_ndb == "true") SIPml.startNativeDebug();

                //var rinningApps = SIPml.getRunningApps();
                //var _rinningApps = Base64.decode(rinningApps);
                //tsk_utils_log_info(_rinningApps);
            }

            oReadyStateTimer = setInterval(function() {
                    if (document.readyState === "complete") {
                        clearInterval(oReadyStateTimer);
                        // initialize SIPML5
                        preInit();
                    }
                },
                500);

            /*if (document.readyState === "complete") {
                preInit();
            }
            else {
                document.onreadystatechange = function () {
                    if (document.readyState === "complete") {
                        preInit();
                    }
                }
            }*/
        };

        function postInit() { // check for WebRTC support
            if (!SIPml.isWebRtcSupported()) { // is it chrome?
                if (SIPml.getNavigatorFriendlyName() == 'chrome') {
                    if (confirm("You're using an old Chrome version or WebRTC is not enabled.\nDo you want to see how to enable WebRTC?")) {
                        window.location = 'http://www.webrtc.org/running-the-demos';
                    } else {
                        window.location = "index.html";
                    }
                    return;
                } else {
                    if (confirm("webrtc-everywhere extension is not installed. Do you want to install it?\nIMPORTANT: You must restart your browser after the installation.")) {
                        window.location = 'https://github.com/sarandogou/webrtc-everywhere';
                    } else { // Must do nothing: give the user the chance to accept the extension
                            // window.location = "index.html";
                        }
                    }
            }

            // checks for WebSocket support
            if (!SIPml.isWebSocketSupported()) {
                if (confirm('Your browser don\'t support WebSockets.\nDo you want to download a WebSocket-capable browser?')) {
                    window.location = 'https://www.google.com/intl/en/chrome/browser/';
                } else {
                    window.location = "index.html";
                }
                return;
            }

            // FIXME: displays must be per session
            viewVideoLocal = videoLocal;
            viewVideoRemote = videoRemote;

            if (!SIPml.isWebRtcSupported()) {
                if (confirm('Your browser don\'t support WebRTC.\naudio/video calls will be disabled.\nDo you want to download a WebRTC-capable browser?')) {
                    window.location = 'https://www.google.com/intl/en/chrome/browser/';
                }
            }

            btnRegister.disabled = false;
            btnUnRegister.style.display = "none";

            document.body.style.cursor = 'default';
            oConfigCall = {
                audio_remote: audioRemote,
                video_local: viewVideoLocal,
                video_remote: viewVideoRemote,
                screencast_window_id: 0x00000000, // entire desktop
                bandwidth: {
                    audio: undefined,
                    video: undefined
                },
                video_size: {
                    minWidth: undefined,
                    minHeight: undefined,
                    maxWidth: undefined,
                    maxHeight: undefined
                },
                events_listener: {
                    events: '*',
                    listener: onSipEventSession
                },
                sip_caps: [{
                        name: '+g.oma.sip-im'
                    },
                    {
                        name: 'language',
                        value: '\"en,fr\"'
                    }
                ]
            };
        }


        // function loadCallOptions() {
        //     if (window.localStorage) {
        //         var s_value;
        //         if ((s_value = window.localStorage.getItem('org.doubango.call.phone_number'))) txtPhoneNumber.value = s_value;
        //         bDisableVideo = (window.localStorage.getItem('org.doubango.expert.disable_video') == "true");
        //
        //         // txtCallStatus.innerHTML = '<i>Video ' + (bDisableVideo ? 'disabled' : 'enabled') + '</i>';
        //     }
        // }

        function saveCallOptions() {
            if (window.localStorage) {
                // window.localStorage.setItem('org.doubango.call.phone_number', txtPhoneNumber.value);
                window.localStorage.setItem('org.doubango.expert.disable_video', bDisableVideo ? "true" : "false");
            }
        }

        function loadCredentials() {
            if (window.localStorage) {
                window.localStorage.setItem('org.doubango.identity.display_name', '<?php echo Session::get('display_name'); ?>');
                window.localStorage.setItem('org.doubango.identity.impi', '<?php echo Session::get('private_identity'); ?>');
                window.localStorage.setItem('org.doubango.identity.impu', '<?php echo Session::get('public_identity'); ?>');
                window.localStorage.setItem('org.doubango.identity.password', '<?php echo Session::get('password'); ?>');
                window.localStorage.setItem('org.doubango.identity.realm', '<?php echo Session::get('realm'); ?>');
                window.localStorage.setItem('org.doubango.expert.websocket_server_url', '<?php echo Session::get('websocket_server_url'); ?>');
                window.localStorage.setItem('org.doubango.expert.disable_video', "true");
                window.localStorage.setItem('org.doubango.expert.enable_rtcweb_breaker', "true");
                window.localStorage.setItem('org.doubango.expert.sip_outboundproxy_url', "");
                window.localStorage.setItem('org.doubango.expert.ice_servers', "<?php echo Session::get('ice_servers'); ?>");
                window.localStorage.setItem('org.doubango.expert.bandwidth', "");
                window.localStorage.setItem('org.doubango.expert.video_size', "");
                window.localStorage.setItem('org.doubango.expert.disable_early_ims', "true");
                window.localStorage.setItem('org.doubango.expert.disable_debug', "true");
                window.localStorage.setItem('org.doubango.expert.enable_media_caching', "true");
                window.localStorage.setItem('org.doubango.expert.disable_callbtn_options', "true");

            } else {
                console.log("localStorage error");
            }
        };

                    // sends SIP REGISTER request to login
                    function sipRegister() {
                        // catch exception for IE (DOM not ready)
                        try {
                            btnRegister.disabled = true;
                            btnRegister.style.display = "none";
                            btnUnRegister.style.display = "block";
                            if (!window.localStorage.getItem('org.doubango.identity.realm') || !window.localStorage.getItem('org.doubango.identity.impi') || !window.localStorage.getItem('org.doubango.identity.impu')) {
                                //txtRegStatus.innerHTML = '<b>Extension settings are incomplete!</b>';
                                txtRegStatus.innerHTML = '';

                                btnRegister.disabled = false;
                                btnRegister.style.display = "block";
                                btnUnRegister.style.display = "none";
                                return;
                            }
                            var o_impu = tsip_uri.prototype.Parse(window.localStorage.getItem('org.doubango.identity.impu'));
                            if (!o_impu || !o_impu.s_user_name || !o_impu.s_host) {
                                txtRegStatus.innerHTML = "<b>[" + window.localStorage.getItem('org.doubango.identity.impu') + "] is not a valid Public identity</b>";
                                btnRegister.disabled = false;
                                btnRegister.style.display = "block";
                                btnUnRegister.style.display = "none";
                                return;
                            }

                            // enable notifications if not already done
                            if (window.webkitNotifications && window.webkitNotifications.checkPermission() != 0) {
                                window.webkitNotifications.requestPermission();
                            }

                            // save credentials
                            // saveCredentials();

                            // update debug level to be sure new values will be used if the user haven't updated the page
                            SIPml.setDebugLevel((window.localStorage && window.localStorage.getItem('org.doubango.expert.disable_debug') == "true") ? "error" : "info");

                            // create SIP stack
                            oSipStack = new SIPml.Stack({
                                realm: window.localStorage.getItem('org.doubango.identity.realm'),
                                impi: window.localStorage.getItem('org.doubango.identity.impi'),
                                impu: window.localStorage.getItem('org.doubango.identity.impu'),
                                password: window.localStorage.getItem('org.doubango.identity.password'),
                                display_name: window.localStorage.getItem('org.doubango.identity.display_name'),
                                websocket_proxy_url: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.websocket_server_url') : null),
                                outbound_proxy_url: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.sip_outboundproxy_url') : null),
                                ice_servers: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.ice_servers') : null),
                                enable_rtcweb_breaker: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.enable_rtcweb_breaker') == "true" : false),
                                events_listener: {
                                    events: '*',
                                    listener: onSipEventStack
                                },
                                enable_early_ims: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.disable_early_ims') != "true" : true), // Must be true unless you're using a real IMS network
                                enable_media_stream_cache: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.enable_media_caching') == "true" : false),
                                bandwidth: (window.localStorage ? tsk_string_to_object(window.localStorage.getItem('org.doubango.expert.bandwidth')) : null), // could be redefined a session-level
                                video_size: (window.localStorage ? tsk_string_to_object(window.localStorage.getItem('org.doubango.expert.video_size')) : null), // could be redefined a session-level
                                sip_headers: [{
                                        name: 'User-Agent',
                                        value: 'IM-client/OMA1.0 sipML5-v1.2016.03.04'
                                    },
                                    {
                                        name: 'Organization',
                                        value: 'Doubango Telecom'
                                    }
                                ]
                            });

                            if (oSipStack.start() != 0) {
                                txtRegStatus.innerHTML = '<b>Failed to start the SIP stack</b>';
                            } else return;
                        } catch (e) {
                            txtRegStatus.innerHTML = "<b>2:" + e + "</b>";
                        }
                        btnRegister.disabled = false;
                        btnRegister.style.display = "block";
                        btnUnRegister.style.display = "none";
                    }

                    // sends SIP REGISTER (expires=0) to logout
                    function sipUnRegister() {
                        if (oSipStack) {
                            oSipStack.stop(); // shutdown all sessions
                            btnRegister.style.display = "block";
                            btnUnRegister.style.display = "none";
                        }
                    }

                    // makes a call (SIP INVITE)
                    function sipCall(s_type) {
                        if (oSipStack && !oSipSessionCall && !tsk_string_is_null_or_empty(txtPhoneNumber.value)) {
                            if (s_type == 'call-screenshare') {
                                if (!SIPml.isScreenShareSupported()) {
                                    alert('Screen sharing not supported. Are you using chrome 26+?');
                                    return;
                                }
                                if (!location.protocol.match('https')) {
                                    if (confirm("Screen sharing requires https://. Do you want to be redirected?")) {
                                        sipUnRegister();
                                        window.location = 'https://ns313841.ovh.net/call.htm';
                                    }
                                    return;
                                }
                            }
                            btnCall.disabled = true;
                            btnHangUp.disabled = false;

                            // if (window.localStorage) {
                            // oConfigCall.bandwidth = tsk_string_to_object(window.localStorage.getItem('org.doubango.expert.bandwidth')); // already defined at stack-level but redifined to use latest values
                            // oConfigCall.video_size = tsk_string_to_object(window.localStorage.getItem('org.doubango.expert.video_size')); // already defined at stack-level but redifined to use latest values
                            // }

                            // create call session
                            oSipSessionCall = oSipStack.newSession(s_type, oConfigCall);

                            // make call
                            var numberToCall = txtPhoneNumber.value;
                            if (numberToCall.length > 9) {
                                numberToCall = '91' + numberToCall;
                            }

                            if (oSipSessionCall.call(txtPhoneNumber.value) != 0) {
                                oSipSessionCall = null;
                                txtCallStatus.value = 'Failed to make call';
                                btnCall.disabled = false;
                                btnHangUp.disabled = true;
                                return;
                            }
                            saveCallOptions();
                        } else if (oSipSessionCall) {
                            txtCallStatus.innerHTML = '<i>Connecting...</i>';
                            oSipSessionCall.accept(oConfigCall);
                        }
                    }

                    // Share entire desktop aor application using BFCP or WebRTC native implementation
                    function sipShareScreen() {
                        if (SIPml.getWebRtcType() === 'w4a') {
                            // Sharing using BFCP -> requires an active session
                            if (!oSipSessionCall) {
                                txtCallStatus.innerHTML = '<i>No active session</i>';
                                return;
                            }
                            if (oSipSessionCall.bfcpSharing) {
                                if (oSipSessionCall.stopBfcpShare(oConfigCall) != 0) {
                                    txtCallStatus.value = 'Failed to stop BFCP share';
                                } else {
                                    oSipSessionCall.bfcpSharing = false;
                                }
                            } else {
                                oConfigCall.screencast_window_id = 0x00000000;
                                if (oSipSessionCall.startBfcpShare(oConfigCall) != 0) {
                                    txtCallStatus.value = 'Failed to start BFCP share';
                                } else {
                                    oSipSessionCall.bfcpSharing = true;
                                }
                            }
                        } else {
                            sipCall('call-screenshare');
                        }
                    }

                    // transfers the call
                    function sipTransfer() {
                        if (oSipSessionCall) {
                            var s_destination = prompt('Enter destination number', '');
                            if (!tsk_string_is_null_or_empty(s_destination)) {
                                btnTransfer.disabled = true;
                                if (oSipSessionCall.transfer(s_destination) != 0) {
                                    txtCallStatus.innerHTML = '<i>Call transfer failed</i>';
                                    btnTransfer.disabled = false;
                                    return;
                                }
                                txtCallStatus.innerHTML = '<i>Transfering the call...</i>';
                            }
                        }
                    }

                    // holds or resumes the call
                    function sipToggleHoldResume() {
                        if (oSipSessionCall) {
                            var i_ret;
                            btnHoldResume.disabled = true;
                            txtCallStatus.innerHTML = oSipSessionCall.bHeld ? '<i>Resuming the call...</i>' : '<i>Holding the call...</i>';
                            i_ret = oSipSessionCall.bHeld ? oSipSessionCall.resume() : oSipSessionCall.hold();
                            if (i_ret != 0) {
                                txtCallStatus.innerHTML = '<i>Hold / Resume failed</i>';
                                btnHoldResume.disabled = false;
                                return;
                            }
                        }
                    }

                    // Mute or Unmute the call
                    function sipToggleMute() {
                        if (oSipSessionCall) {
                            var i_ret;
                            var bMute = !oSipSessionCall.bMute;
                            txtCallStatus.innerHTML = bMute ? '<i>Mute the call...</i>' : '<i>Unmute the call...</i>';
                            i_ret = oSipSessionCall.mute('audio' /*could be 'video'*/ , bMute);
                            if (i_ret != 0) {
                                txtCallStatus.innerHTML = '<i>Mute / Unmute failed</i>';
                                return;
                            }
                            oSipSessionCall.bMute = bMute;
                            btnMute.value = bMute ? "Unmute" : "Mute";
                        }
                    }

                    // terminates the call (SIP BYE or CANCEL)
                    function sipHangUp() {
                        if (oSipSessionCall) {
                            txtCallStatus.innerHTML = '<i>Terminating the call...</i>';
                            oSipSessionCall.hangup({
                                events_listener: {
                                    events: '*',
                                    listener: onSipEventSession
                                }
                            });
                        }
                    }

                    function sipSendDTMF(c) {
                        if (oSipSessionCall && c) {
                            if (oSipSessionCall.dtmf(c) == 0) {
                                try {
                                    dtmfTone.play();
                                } catch (e) {}
                            }
                        }
                    }

                    function startRingTone() {
                        try {
                            ringtone.play();
                            openNav();
                        } catch (e) {}
                    }

                    function stopRingTone() {
                        try {
                            ringtone.pause();
                        } catch (e) {}
                    }

                    function startRingbackTone() {
                        try {
                            ringbacktone.play();
                        } catch (e) {}
                    }

                    function stopRingbackTone() {
                        try {
                            ringbacktone.pause();
                        } catch (e) {}
                    }

                    function toggleFullScreen() {
                        if (videoRemote.webkitSupportsFullscreen) {
                            fullScreen(!videoRemote.webkitDisplayingFullscreen);
                        } else {
                            fullScreen(!bFullScreen);
                        }
                    }

                    function openKeyPad() {
                        divKeyPad.style.visibility = 'visible';
                        divKeyPad.style.left = ((document.body.clientWidth - C.divKeyPadWidth) >> 1) + 'px';
                        divKeyPad.style.top = '70px';
                        // divGlassPanel.style.visibility = 'visible';
                    }

                    function closeKeyPad() {
                        divKeyPad.style.left = '0px';
                        divKeyPad.style.top = '0px';
                        divKeyPad.style.visibility = 'hidden';
                        // divGlassPanel.style.visibility = 'hidden';
                    }

                    function fullScreen(b_fs) {
                        bFullScreen = b_fs;
                        if (tsk_utils_have_webrtc4native() && bFullScreen && videoRemote.webkitSupportsFullscreen) {
                            if (bFullScreen) {
                                videoRemote.webkitEnterFullScreen();
                            } else {
                                videoRemote.webkitExitFullscreen();
                            }
                        } else {
                            if (tsk_utils_have_webrtc4npapi()) {
                                try {
                                    if (window.__o_display_remote) window.__o_display_remote.setFullScreen(b_fs);
                                } catch (e) {
                                    divVideo.setAttribute("class", b_fs ? "full-screen" : "normal-screen");
                                }
                            } else {
                                divVideo.setAttribute("class", b_fs ? "full-screen" : "normal-screen");
                            }
                        }
                    }

                    function showNotifICall(s_number) {
                        // permission already asked when we registered
                        if (window.webkitNotifications && window.webkitNotifications.checkPermission() == 0) {
                            if (oNotifICall) {
                                oNotifICall.cancel();
                            }
                            oNotifICall = window.webkitNotifications.createNotification('images/sipml-34x39.png', 'Incaming call', 'Incoming call from ' + s_number);
                            oNotifICall.onclose = function() {
                                oNotifICall = null;
                            };
                            oNotifICall.show();
                        }
                    }

                    function onKeyUp(evt) {
                        evt = (evt || window.event);
                        if (evt.keyCode == 27) {
                            fullScreen(false);
                        } else if (evt.ctrlKey && evt.shiftKey) { // CTRL + SHIFT
                            if (evt.keyCode == 65 || evt.keyCode == 86) { // A (65) or V (86)
                                bDisableVideo = (evt.keyCode == 65);
                                txtCallStatus.innerHTML = '<i>Video ' + (bDisableVideo ? 'disabled' : 'enabled') + '</i>';
                                window.localStorage.setItem('org.doubango.expert.disable_video', bDisableVideo);
                            }
                        }
                    }

                    function onDivCallCtrlMouseMove(evt) {
                        try { // IE: DOM not ready
                            if (tsk_utils_have_stream()) {
                                btnCall.disabled = (!tsk_utils_have_stream() || !oSipSessionRegister || !oSipSessionRegister.is_connected());
                                document.getElementById("divCallCtrl").onmousemove = null; // unsubscribe
                            }
                        } catch (e) {}
                    }

                    function uiOnConnectionEvent(b_connected, b_connecting) { // should be enum: connecting, connected, terminating, terminated
                        btnRegister.disabled = b_connected || b_connecting;
                        btnUnRegister.disabled = !b_connected && !b_connecting;
                        btnCall.disabled = !(b_connected && tsk_utils_have_webrtc() && tsk_utils_have_stream());
                        btnHangUp.disabled = !oSipSessionCall;

                        if (btnRegister.disabled) {
                            btnRegister.style.display = "none";
                            btnUnRegister.style.display = "block";
                        } else {
                            btnRegister.style.display = "block";
                            btnUnRegister.style.display = "none";
                        }

                        if (btnCall.disabled) {
                            btnCall.style.display = "none";
                            btnHangUp.style.display = "inline-block";
                        } else {
                            btnCall.style.display = "inline-block";
                            btnHangUp.style.display = "none";
                        }
                    }

                    function uiVideoDisplayEvent(b_local, b_added) {
                        var o_elt_video = b_local ? videoLocal : videoRemote;

                        if (b_added) {
                            o_elt_video.style.opacity = 1;
                            // uiVideoDisplayShowHide(true);
                        } else {
                            o_elt_video.style.opacity = 0;
                            fullScreen(false);
                        }
                    }

                    function uiVideoDisplayShowHide(b_show) {
                        if (b_show) {
                            tdVideo.style.height = '340px';
                            divVideo.style.height = navigator.appName == 'Microsoft Internet Explorer' ? '100%' : '340px';
                        } else {
                            tdVideo.style.height = '0px';
                            divVideo.style.height = '0px';
                        }
                        btnFullScreen.disabled = !b_show;
                    }

                    function uiDisableCallOptions() {
                        if (window.localStorage) {
                            window.localStorage.setItem('org.doubango.expert.disable_callbtn_options', 'true');
                            uiBtnCallSetText('Call');
                            alert('Use expert view to enable the options again (/!\\requires re-loading the page)');
                        }
                    }

                    function uiBtnCallSetText(s_text) {
                        switch (s_text) {
                            case "Call": {
                                var bDisableCallBtnOptions = (window.localStorage && window.localStorage.getItem('org.doubango.expert.disable_callbtn_options') == "true");
                                btnCall.value = btnCall.innerHTML = bDisableCallBtnOptions ? '<i class="fa fa-phone"></i>' : '<i class="fa fa-phone"></i>';
                                btnCall.setAttribute("class", bDisableCallBtnOptions ? "btn btn-primary" : "btn btn-primary dropdown-toggle");
                                btnCall.onclick = bDisableCallBtnOptions ? function() {
                                    sipCall('call-audio');
                                } : null;
                                // ulCallOptions.style.visibility = bDisableCallBtnOptions ? "hidden" : "visible";
                                if (!bDisableCallBtnOptions && ulCallOptions.parentNode != divBtnCallGroup) {
                                    divBtnCallGroup.appendChild(ulCallOptions);
                                } else if (bDisableCallBtnOptions && ulCallOptions.parentNode == divBtnCallGroup) {
                                    document.body.appendChild(ulCallOptions);
                                }

                                break;
                            }
                            default: {
                                btnCall.value = btnCall.innerHTML = s_text;
                                btnCall.setAttribute("class", "btn btn-primary");
                                btnCall.onclick = function() {
                                    sipCall('call-audio');
                                };
                                ulCallOptions.style.visibility = "hidden";
                                if (ulCallOptions.parentNode == divBtnCallGroup) {
                                    document.body.appendChild(ulCallOptions);
                                }
                                break;
                            }
                        }
                    }

                    function uiCallTerminated(s_description) {
                        uiBtnCallSetText("Call");
                        btnHangUp.value = 'HangUp';
                        btnHoldResume.value = 'hold';
                        btnMute.value = "Mute";
                        btnCall.disabled = false;
                        btnHangUp.disabled = true;
                        btnCall.style.display = "block";
                        btnHangUp.style.display = "none";

                        $("#offsetbutton").show();
                        $("#dialpad-backspace").show();

                        if (window.btnBFCP) window.btnBFCP.disabled = true;

                        oSipSessionCall = null;

                        stopRingbackTone();
                        stopRingTone();

                        txtCallStatus.innerHTML = "<i>" + s_description + "</i>";
                        // uiVideoDisplayShowHide(false);
                        divCallOptions.style.opacity = 0;

                        if (oNotifICall) {
                            oNotifICall.cancel();
                            oNotifICall = null;
                        }

                        // uiVideoDisplayEvent(false, false);
                        // uiVideoDisplayEvent(true, false);

                        setTimeout(function() {
                            if (!oSipSessionCall) txtCallStatus.innerHTML = '';
                        }, 2500);
                    }

                    // Callback function for SIP Stacks
                    function onSipEventStack(e /*SIPml.Stack.Event*/ ) {
                        console.log("onSipEventStack", e);
                        tsk_utils_log_info('==stack event = ' + e.type);
                        switch (e.type) {
                            case 'started': {
                                // catch exception for IE (DOM not ready)
                                try {
                                    // LogIn (REGISTER) as soon as the stack finish starting
                                    oSipSessionRegister = this.newSession('register', {
                                        expires: 200,
                                        events_listener: {
                                            events: '*',
                                            listener: onSipEventSession
                                        },
                                        sip_caps: [{
                                                name: '+g.oma.sip-im',
                                                value: null
                                            },
                                            //{ name: '+sip.ice' }, // rfc5768: FIXME doesn't work with Polycom TelePresence
                                            {
                                                name: '+audio',
                                                value: null
                                            },
                                            {
                                                name: 'language',
                                                value: '\"en,fr\"'
                                            }
                                        ]
                                    });
                                    oSipSessionRegister.register();
                                } catch (e) {
                                    txtRegStatus.value = txtRegStatus.innerHTML = "<b>1:" + e + "</b>";
                                    btnRegister.disabled = false;
                                    btnRegister.style.display = "block";
                                    btnUnRegister.style.display = "none";
                                }
                                break;
                            }
                            case 'stopping':
                            case 'stopped':
                            case 'failed_to_start':
                            case 'failed_to_stop': {
                                var bFailure = (e.type == 'failed_to_start') || (e.type == 'failed_to_stop');
                                oSipStack = null;
                                oSipSessionRegister = null;
                                oSipSessionCall = null;

                                uiOnConnectionEvent(false, false);

                                stopRingbackTone();
                                stopRingTone();

                                // uiVideoDisplayShowHide(false);
                                divCallOptions.style.opacity = 0;

                                txtCallStatus.innerHTML = '';
                                txtRegStatus.innerHTML = bFailure ? "<i>Disconnected: <b>" + e.description + "</b></i>" : "<i>Disconnected</i>";
                                break;
                            }

                            case 'i_new_call': {
                                if (oSipSessionCall) {
                                    // do not accept the incoming call if we're already 'in call'
                                    e.newSession.hangup(); // comment this line for multi-line support
                                } else {
                                    oSipSessionCall = e.newSession;
                                    // start listening for events
                                    oSipSessionCall.setConfiguration(oConfigCall);

                                    // uiBtnCallSetText('Answer');
                                    btnHangUp.value = 'Reject';
                                    btnCall.disabled = false;
                                    btnHangUp.disabled = false;
                                    btnCall.style.display = "block";
                                    btnHangUp.style.display = "inline-block";

                                    $("#offsetbutton").hide();
                                    $("#dialpad-backspace").hide();

                                    startRingTone();

                                    var sRemoteNumber = (oSipSessionCall.getRemoteFriendlyName() || 'unknown');
                                    txtCallStatus.innerHTML = "<i>Incoming call from [<b>" + sRemoteNumber + "</b>]</i>";
                                    showNotifICall(sRemoteNumber);
                                }
                                break;
                            }

                            case 'm_permission_requested': {
                                // divGlassPanel.style.visibility = 'visible';
                                break;
                            }
                            case 'm_permission_accepted':
                            case 'm_permission_refused': {
                                // divGlassPanel.style.visibility = 'hidden';
                                if (e.type == 'm_permission_refused') {
                                    uiCallTerminated('Media stream permission denied');
                                }
                                break;
                            }

                            case 'starting':
                            default:
                                break;
                        }
                    };

                    // Callback function for SIP sessions (INVITE, REGISTER, MESSAGE...)
                    function onSipEventSession(e /* SIPml.Session.Event */ ) {
                        tsk_utils_log_info('==session event = ' + e.type);
                        console.log("onSipEventSession", e);
                        switch (e.type) {
                            case 'connecting':
                            case 'connected': {
                                var bConnected = (e.type == 'connected');
                                if (e.session == oSipSessionRegister) {
                                    uiOnConnectionEvent(bConnected, !bConnected);
                                    txtRegStatus.innerHTML = "<i>" + e.description + "</i>";
                                } else if (e.session == oSipSessionCall) {
                                    btnHangUp.value = 'HangUp';
                                    btnCall.disabled = true;
                                    btnHangUp.disabled = false;
                                    btnTransfer.disabled = false;
                                    btnCall.style.display = "none";
                                    btnHangUp.style.display = "inline-block";

                                    $("#offsetbutton").show();
                                    $("#dialpad-backspace").show();

                                    if (window.btnBFCP) window.btnBFCP.disabled = false;

                                    if (bConnected) {
                                        stopRingbackTone();
                                        stopRingTone();

                                        if (oNotifICall) {
                                            oNotifICall.cancel();
                                            oNotifICall = null;
                                        }
                                    }

                                    txtCallStatus.innerHTML = "<i>" + e.description + "</i>";
                                    divCallOptions.style.opacity = bConnected ? 1 : 0;

                                    if (SIPml.isWebRtc4AllSupported()) { // IE don't provide stream callback
                                        uiVideoDisplayEvent(false, true);
                                        uiVideoDisplayEvent(true, true);
                                    }
                                }
                                break;
                            } // 'connecting' | 'connected'
                            case 'terminating':
                            case 'terminated': {
                                if (e.session == oSipSessionRegister) {
                                    uiOnConnectionEvent(false, false);

                                    oSipSessionCall = null;
                                    oSipSessionRegister = null;

                                    txtRegStatus.innerHTML = "<i>" + e.description + "</i>";
                                } else if (e.session == oSipSessionCall) {
                                    uiCallTerminated(e.description);
                                }
                                break;
                            } // 'terminating' | 'terminated'

                            case 'm_stream_video_local_added': {
                                if (e.session == oSipSessionCall) {
                                    uiVideoDisplayEvent(true, true);
                                }
                                break;
                            }
                            case 'm_stream_video_local_removed': {
                                if (e.session == oSipSessionCall) {
                                    uiVideoDisplayEvent(true, false);
                                }
                                break;
                            }
                            case 'm_stream_video_remote_added': {
                                if (e.session == oSipSessionCall) {
                                    uiVideoDisplayEvent(false, true);
                                }
                                break;
                            }
                            case 'm_stream_video_remote_removed': {
                                if (e.session == oSipSessionCall) {
                                    uiVideoDisplayEvent(false, false);
                                }
                                break;
                            }

                            case 'm_stream_audio_local_added':
                            case 'm_stream_audio_local_removed':
                            case 'm_stream_audio_remote_added':
                            case 'm_stream_audio_remote_removed': {
                                break;
                            }

                            case 'i_ect_new_call': {
                                oSipSessionTransferCall = e.session;
                                break;
                            }

                            case 'i_ao_request': {
                                if (e.session == oSipSessionCall) {
                                    var iSipResponseCode = e.getSipResponseCode();
                                    if (iSipResponseCode == 180 || iSipResponseCode == 183) {
                                        startRingbackTone();
                                        txtCallStatus.innerHTML = '<i>Remote ringing...</i>';
                                    }
                                }
                                break;
                            }

                            case 'm_early_media': {
                                if (e.session == oSipSessionCall) {
                                    stopRingbackTone();
                                    stopRingTone();
                                    txtCallStatus.innerHTML = '<i>Early media started</i>';
                                }
                                break;
                            }

                            case 'm_local_hold_ok': {
                                if (e.session == oSipSessionCall) {
                                    if (oSipSessionCall.bTransfering) {
                                        oSipSessionCall.bTransfering = false;
                                        // this.AVSession.TransferCall(this.transferUri);
                                    }
                                    btnHoldResume.value = 'Resume';
                                    btnHoldResume.disabled = false;
                                    txtCallStatus.innerHTML = '<i>Call placed on hold</i>';
                                    oSipSessionCall.bHeld = true;
                                }
                                break;
                            }
                            case 'm_local_hold_nok': {
                                if (e.session == oSipSessionCall) {
                                    oSipSessionCall.bTransfering = false;
                                    btnHoldResume.value = 'Hold';
                                    btnHoldResume.disabled = false;
                                    txtCallStatus.innerHTML = '<i>Failed to place remote party on hold</i>';
                                }
                                break;
                            }
                            case 'm_local_resume_ok': {
                                if (e.session == oSipSessionCall) {
                                    oSipSessionCall.bTransfering = false;
                                    btnHoldResume.value = 'Hold';
                                    btnHoldResume.disabled = false;
                                    txtCallStatus.innerHTML = '<i>Call taken off hold</i>';
                                    oSipSessionCall.bHeld = false;

                                    if (SIPml.isWebRtc4AllSupported()) { // IE don't provide stream callback yet
                                        uiVideoDisplayEvent(false, true);
                                        uiVideoDisplayEvent(true, true);
                                    }
                                }
                                break;
                            }
                            case 'm_local_resume_nok': {
                                if (e.session == oSipSessionCall) {
                                    oSipSessionCall.bTransfering = false;
                                    btnHoldResume.disabled = false;
                                    txtCallStatus.innerHTML = '<i>Failed to unhold call</i>';
                                }
                                break;
                            }
                            case 'm_remote_hold': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = '<i>Placed on hold by remote party</i>';
                                }
                                break;
                            }
                            case 'm_remote_resume': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = '<i>Taken off hold by remote party</i>';
                                }
                                break;
                            }
                            case 'm_bfcp_info': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = 'BFCP Info: <i>' + e.description + '</i>';
                                }
                                break;
                            }

                            case 'o_ect_trying': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = '<i>Call transfer in progress...</i>';
                                }
                                break;
                            }
                            case 'o_ect_accepted': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = '<i>Call transfer accepted</i>';
                                }
                                break;
                            }
                            case 'o_ect_completed':
                            case 'i_ect_completed': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = '<i>Call transfer completed</i>';
                                    btnTransfer.disabled = false;
                                    if (oSipSessionTransferCall) {
                                        oSipSessionCall = oSipSessionTransferCall;
                                    }
                                    oSipSessionTransferCall = null;
                                }
                                break;
                            }
                            case 'o_ect_failed':
                            case 'i_ect_failed': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = '<i>Call transfer failed</i>';
                                    btnTransfer.disabled = false;
                                }
                                break;
                            }
                            case 'o_ect_notify':
                            case 'i_ect_notify': {
                                if (e.session == oSipSessionCall) {
                                    txtCallStatus.innerHTML = "<i>Call Transfer: <b>" + e.getSipResponseCode() + " " + e.description + "</b></i>";
                                    if (e.getSipResponseCode() >= 300) {
                                        if (oSipSessionCall.bHeld) {
                                            oSipSessionCall.resume();
                                        }
                                        btnTransfer.disabled = false;
                                    }
                                }
                                break;
                            }
                            case 'i_ect_requested': {
                                if (e.session == oSipSessionCall) {
                                    var s_message = "Do you accept call transfer to [" + e.getTransferDestinationFriendlyName() + "]?"; //FIXME
                                    if (confirm(s_message)) {
                                        txtCallStatus.innerHTML = "<i>Call transfer in progress...</i>";
                                        oSipSessionCall.acceptTransfer();
                                        break;
                                    }
                                    oSipSessionCall.rejectTransfer();
                                }
                                break;
                            }
                        }
                    }
                </script>
                <script>
                    //Webphone dialpad
                    $(document).on('click', '.dialpad-number', function(e) {
                        var currentVal = $('#txtPhoneNumber').val();
                        var newVal = currentVal + $(this).val();
                        txtPhoneNumber.value = newVal;
                    });
                    //Webphone back button
                    $(document).on('click', '#dialpad-backspace', function(e) {
                        var currentVal = $('#txtPhoneNumber').val();
                        var newVal = currentVal.substring(0, currentVal.length - 1);
                        txtPhoneNumber.value = newVal;
                    });


                    $(document).ready(function() {

                        $("#btnUnRegister").click(function() {
                            $("#show_nev").removeClass("youtube1");
                            $("#show_nev").addClass("youtube");
                            $.ajax({
                                url: 'webphone/switch-access',
                                type: 'POST',
                                data: {
                                    "_token": $("#csrf-token").val(),
                                    is_checked: false
                                },
                                dataType: "json",
                                success: function(response) {
                                    toastr.success(response);
                                    if (response == "Webphone Enabled") {
                                        // window.open("{{url('/webphone')}}", "webphone", "menubar=0,resizable=0,width=300,height=580");
                                    }
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        });
                        setTimeout(function() {
                            //$("#btnRegister").trigger('click');
                            $("#btnRegister").click(function() {
                                $("#show_nev").removeClass("youtube");
                                $("#wrapper_class").removeClass("show_nev-red1");

                                
                                $("#show_nev").addClass("youtube1");
                                $("#wrapper_class").addClass("show_nev_green");

                                $.ajax({
                                    url: 'webphone/switch-access',
                                    type: 'POST',
                                    data: {
                                        "_token": $("#csrf-token").val(),
                                        is_checked: true
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        toastr.success(response);
                                        if (response == "Webphone Enabled") {
                                            // window.open("{{url('/webphone')}}", "webphone", "menubar=0,resizable=0,width=300,height=580");

                                        }
                                    },
                                    error: function(response) {
                                        console.log(response);
                                    }
                                });
                            });

                        }, 1000);
                        window.onbeforeunload = function(e) {
                            e.preventDefault();
                            if (oSipStack || oSipSessionCall) {
                                toastr.error("Don't close this page when you are in connection.");
                                return false;
                            }
                        };
                    });
                </script>

<script>
    var maxLength = 160;
    $('#new_message').keyup(function()
    {
        var textlen = maxLength - $(this).val().length;
        $('#rchars').text(textlen);
    });

    $('#new_data_message').keyup(function()
    {
        var textlen = maxLength - $(this).val().length;
        $('#rchars_msg').text(textlen);
    });

</script>

<script>

    function openNav() {
        $("#divCallCtrl").show();
        $("#divCallCtrlSMS").hide();
        $("#divCallCtrlFAX").hide();

        // document.getElementById("mySidenav").style.width = "280px";
        $("#dash-dailer").removeClass("dash-dailer");
        $("#dash-dailer").addClass("dash-dailer1");
        $("#dialer-key-wrapper").removeClass("dialer-key-wrapper");
        $("#dialer-key-wrapper").addClass("dialer-key-wrapper1");
        $("#show_nev").hide();
        $("#show_nev1").hide();
        $("#show_nev2").hide();
        $("#show_nev3").hide();

        $("#show_nevsms").hide();
    }

    function closeNav() {
        // document.getElementById("mySidenav").style.width = "0";
        $("#dash-dailer").addClass("dash-dailer");
        $("#dash-dailer").removeClass("dash-dailer1");
        $("#dialer-key-wrapper").addClass("dialer-key-wrapper");
        $("#dialer-key-wrapper").removeClass("dialer-key-wrapper1");
        $("#show_nev").show();
        $("#show_nev1").show();
        $("#show_nev2").show();
        $("#show_nev3").show();
        $("#show_nevsms").show();
    }

    function openNavSMS() {
        $("#divCallCtrl").hide();
        $("#divCallCtrlSMS").show();
        $("#divCallCtrlFAX").hide();

        $("#dash-dailer").removeClass("dash-dailer");
        $("#dash-dailer").addClass("dash-dailer1");
        $("#dialer-key-wrapper").removeClass("dialer-key-wrapper");
        $("#dialer-key-wrapper").addClass("dialer-key-wrapper1");
        $("#show_nev").hide();
        $("#show_nev1").hide();
        $("#show_nev2").hide();
        $("#show_nev3").hide();
        $("#show_nevsms").hide();

        $.ajax({
                    url: 'recentSmsList',
                    type: 'get',
                    dataType: "json",
                    success: function (response_data) {
                        $('#hiddenAfterSms').html("");
                        var res_length = Object.keys(response_data.data).length
                        if (res_length > 0) {
                            $('#hiddenAfterSms').html("");
                            var elem = document.getElementById('hiddenAfterSms');
                            for (var i = 0; i < res_length; i++) {
                                var obj = response_data.data[i];
                                if (obj.status == '0') {
                                    status = 'unread';
                                } else {
                                    status = '';
                                }
                                var localTime = moment.utc(obj.date).local();
                                var created_date = localTime.format('YYYY-MM-DD HH:mm');
                                elem.innerHTML = elem.innerHTML + '<div class="row"><div class="col-lg-12"><div class="media1" @if($client['sms'] == 1) onclick="openDiv('+ obj.number +','+ obj.did +','+ obj.id +');" @endif style="color: whitesmoke;font-size: 15px;cursor:pointer;margin: 5px 1px 0px 0px;"><a class="pull-left" style="padding-right: 10px;padding-left: 0px;padding-top:0px;" href="#"><img class="media-object img-circle img-chat" src="https://bootdey.com/img/Content/avatar/avatar6.png" alt=""></a><div class="media-body"><h4 class="media-heading" style="font-size: 15px;margin-top: 0px;">'+ obj.number +'<span class="small pull-right">' + created_date + '</span></h4><p>' + obj.message + '</p></div><p class="label label-danger" id="unread" style="margin-left: 53px;margin-top: 0px;">'+status+'</p></div></div></div><div class="form-group" id="loading_sms_'+obj.id+'" style="display: none;"><div class="col-sm-12"><button style="font-size: 20px;margin-left: 80px;" type="button"  class="spinner-button btn btn-pri" disabled=""><i class="fa fa-circle-o-notch fa-spin"></i> </button></div></div><div class="container_sms_" id="container_sms_'+obj.id+'" style="display: none;"></div><hr style="margin-top: 0px;margin-bottom: 0px;">';
                            }
                        }
                        $('#left_box').hide();
                    }
                });
    }

    function openNavFAX() {
        $("#divCallCtrl").hide();
        $("#divCallCtrlSMS").hide();
        $("#divCallCtrlFAX").show();

        $("#dash-dailer").removeClass("dash-dailer");
        $("#dash-dailer").addClass("dash-dailer1");
        $("#dialer-key-wrapper").removeClass("dialer-key-wrapper");
        $("#dialer-key-wrapper").addClass("dialer-key-wrapper1");
        $("#show_nev").hide();
        $("#show_nev1").hide();
        $("#show_nev2").hide();
        $("#show_nev3").hide();
        $("#show_nevsms").hide();
    }

</script>

<script>
    //$(".media1").click(function(){

        $("#back").click(function(){
        $("#show_text").hide();
        $("#btnFetchSms").html("");

                    $("#btnFetchSms").html('<i class="fa fa-circle-o-notch fa-spin"></i> loading...');


        $("#btnFetchSms").show();



        $("#hiddenAfterSms").show();
        $("#container_sms_").hide();
        $("#contact_form_sms_data").hide();

            $("#reply").html('<i class="fa fa-reply" aria-hidden="true"></i> Reply');





        })

        $("#reply").click(function(){

            $("#reply").html('<i class="fa fa-reply" aria-hidden="true"></i> Send');

        $("#show_text").toggle();

        

    message = $("#new_data_message").val();
    sms_number = $("#sms_number").val();
    sms_did = $("#sms_did").val();
    sms_id = $("#sms_id").val();

   /* if(message == '')
    {
            $("#reply").html('<i class="fa fa-reply" aria-hidden="true"></i> Reply');

    }*/




    if(message !=''){


        $("#btnFetchSms").show();


    var created_date = moment.utc().format('YYYY-MM-DD HH:mm:ss');

            var jqXhr = $.ajax({
                url: 'sendSms',
                type: 'get',
                dataType: "json",
                data: { 'from': sms_did, 'to': sms_number, 'message': message, 'created_date':created_date }
            });

           


            jqXhr.done(function (data) {
                $('#right_new_msg').hide();

                id=sms_id;



        openDiv(sms_number, sms_did,sms_id);


                if (data.success == 'true') {


            

                    var div_close = "alert-success";
                    /*$("#message_response").html('<div style="background-color:#40bf2a !important;color:white !important" class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+data.message+'</div>');*/

                    $("#btnFetchSms").html('<i class="fa fa-check" aria-hidden="true"></i> Message Sent...');



                    setTimeout(function() {


        $("#btnFetchSms").hide();

               
           }, 3000);
                    $("#new_to").val('');
                    $("#new_data_message").val('');
            $("#reply").html('<i class="fa fa-reply" aria-hidden="true"></i> Reply');

                    $("#to_data").text('');
                    $("#new_from_data").text('');
                    $("#message_data").text('');
                } else {
                    $("#message_response").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Alert!</h4>'+data.message+'</div>');
                    var div_close = "alert-danger";
                    $('#left_box').hide();
                }

                $("." + div_close).delay(4000).slideUp(1000, function () {
                    $(this).alert('close');
                });
            });

        }


       




        })

        function openDiv(number, did,id) {

        $("#contact_form_sms_data").show();


            $("#loading_sms_"+id).show();

            $("#unread").hide();
           // $(".container_sms_").hide();
            $(".container_sms_").html("");

        $("#container_sms_").show();

        $("#hiddenAfterSms").hide();

   

    var did=did;
    var number=number;

   


    $.ajax({
        url: 'openSmsDetails',
        data: { 'from': did, 'to': number },
        type: 'get',
        success: function (response) {

            //reverse_data =  response.reverse();
            reverse_data =  response;



        $("#btnFetchSms").hide();

            $("#loading_sms_"+id).hide();

            $("#" + number + '_' + did).removeAttr("style");
                var ele = document.getElementById('container_sms_');
                    ele.innerHTML = ele.innerHTML + '<h4>'+ number +'</h4>';

                    ele.innerHTML = ele.innerHTML + '<input type="hidden" value="'+number+'" id="sms_number" name="number" /> ';
                    ele.innerHTML = ele.innerHTML + '<input type="hidden" value="'+did+'" id="sms_did" name="did" /> ';
                    ele.innerHTML = ele.innerHTML + '<input type="hidden" value="'+id+'" id="sms_id" name="id" /> ';



            for (var i = 0; i < reverse_data.length; i++) {
                var obj = reverse_data[i];


                if (obj.type == 'incoming') {
                    ele.innerHTML = ele.innerHTML + '<div class="yours messages"><div class="message last">'+ obj.message +'</div></div>';
                }


                if (obj.type == 'outgoing') {
                    ele.innerHTML = ele.innerHTML + '<div class="mine messages"><div class="message last">'+ obj.message +'</div></div>';
                }
                // alert(obj.id);
            }

           // ele.innerHTML = ele.innerHTML + '';
        }
    });
}

  //  });
</script>

<style>
    .footer_demo {
   position: fixed;
   bottom: 0;
   width: 17%;
   color: white;
   text-align: center;
  
}

 .footer_demo_top {
   position: fixed;
   top: 0;
   width: 17%;
   color: white;
   text-align: center;
  
}
</style>




@if(!empty($sms_number_list))
<script src="{{ asset('asset/js/angular.min.js') }}"></script>
<div ng-app="myApp" ng-controller="myCtrl"></div>

<script>

    var callbackReminderSetting = 1;
    //alert(callbackReminderSetting);
    var app = angular.module('myApp', []);
    app.controller('myCtrl', function ($scope, $http, $interval)
    {
        i=0;
        if (callbackReminderSetting == 1) {
        interval = $interval(function ()
        {
            $scope.displayData();  
        }, 1000);
    }

        $scope.displayData = function()
        {  
         var reminderHtml = '';
        var interval;


            $(".display").hide();
            $http.post("/sms-counts-unread")
            .success(function(response)
            {        
                console.log(response);

                if(response.countRow != 0)
                {
                    $("#sms_count_unread").html('<span class="badge" >'+response.countRow+'</span>');
                    //$("#sms_count_unread_inbox").html('<span style="background-color:red;" class="badge" >'+response.countRow+'</span>');
                    //$("#fax_count_unread").html('<span class="badge" >'+response.countRow+'</span>');
                }
                else
                {
                    $("#sms_count_unread").html('');
                    $("#sms_count_unread_inbox").html('');



                }
            });  
        }  
    });

</script>
@endif
</body>

</html>