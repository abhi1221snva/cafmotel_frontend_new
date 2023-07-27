<html>
<title>Web Phone</title>
<head>
    <link rel="stylesheet" href="{{ asset('asset/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/font-awsome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{asset('asset/css/toastr.min.css')}}">
    <script src="{{ asset('asset/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <style>
        body {
            background: #222d32;
        }

        .webphone-container {
            width: 260px;
            margin: 0 auto;
            padding: 10px 15px;
        }

        #txtRegStatus, #txtCallStatus {
            color: #b8c7ce;
        }

        .dialpad-number, #dialpad-backspace {
            border: 1px solid #d9d9d9;
            color: #fff;
            margin: 3px;
            font-size: 20px;
            border-radius: 50%;
            background-color: #39cccc00;
            width: 65px;
            height: 65px;
        }

        #btnCall {
            width: 65px;
            height: 65px;
            background-color: #00a65a;
            border-radius: 50%;
            border: none;
            font-size: 20px;
            outline: none;
            margin: 3px;
        }

        #btnHangUp {
            width: 65px;
            height: 65px;
            background-color: #dd4b39;
            border-radius: 50%;
            border: none;
            font-size: 20px;
            outline: none;
            display: none;
        }

        #btnHangUp i {
            -webkit-transform: rotate(
                134deg);
            -ms-transform: rotate(180deg);
            transform: rotate(
                134deg);
        }

        #dialpad-backspace {
            border: none;
        }

        #btnRegister, #btnUnRegister {
            width: 100%;
        }

        .sipml5-phone-no-input {
            border-radius: 5px;
        }

        input.dialpad-number:hover {
            background-color: #12171a;
        }

        #btnUnRegister, .tdVideo, #btnFullScreen, #btnKeyPad, #btnTransfer {
            display: none;
        }

        button#btnCall:hover {
            background-color: #028f4f;
        }

        button#btnHangUp:hover {
            background-color: #cb4231;
        }

        .control-sidebar-bg, .control-sidebar {
            top: 0;
            right: -250px;
            width: 250px;
        }
    </style>
</head>
<body>
<div class="webphone-container">
    <div id="divCallCtrl">
        <label style="width: 100%;text-align:center;" id="txtRegStatus"></label>
        <label style="width: 100%;text-align:center;" id="txtCallStatus"></label>
        <input type="text" class="form-control sipml5-phone-no-input" id="txtPhoneNumber" value=""
               placeholder="Enter Phone Number">
        <div class="dialpad-area">
            <table style="margin: 10px auto;">
                <tr>
                    <td><input type="button" class="dialpad-number" value="1" onclick="sipSendDTMF('1');"/>
                        <input type="button" class="dialpad-number" value="2" onclick="sipSendDTMF('2');"/>
                        <input type="button" class="dialpad-number" value="3" onclick="sipSendDTMF('3');"/></td>
                </tr>
                <tr>
                    <td><input type="button" class="dialpad-number" value="4" onclick="sipSendDTMF('4');"/>
                        <input type="button" class="dialpad-number" value="5" onclick="sipSendDTMF('5');"/>
                        <input type="button" class="dialpad-number" value="6" onclick="sipSendDTMF('6');"/></td>
                </tr>
                <tr>
                    <td><input type="button" class="dialpad-number" value="7" onclick="sipSendDTMF('7');"/>
                        <input type="button" class="dialpad-number" value="8" onclick="sipSendDTMF('8');"/>
                        <input type="button" class="dialpad-number" value="9" onclick="sipSendDTMF('9');"/></td>
                </tr>
                <tr>
                    <td><input type="button" class="dialpad-number" value="*" onclick="sipSendDTMF('*');"/>
                        <input type="button" class="dialpad-number" value="0" onclick="sipSendDTMF('0');"/>
                        <input type="button" class="dialpad-number" value="#" onclick="sipSendDTMF('#');"/></td>
                </tr>
            </table>
        </div>
        <table style="margin: 10px auto;">
            <tr>
                <td>
                    <button id="offsetbutton"
                            style="display: inline-block;visibility: hidden;width: 65px;margin: 3px;"></button>
                    <div id="divBtnCallGroup" class="btn-group">
                        <button id="btnCall" class=""><i class="fa fa-phone"></i></button>
                    </div>
                    <button type="button" id="btnHangUp" class="btn btn-primary" onclick='sipHangUp();'><i
                            class="fa fa-phone"></i></button>
                    <button id="dialpad-backspace"><i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
        </table>
        <hr/>
        <div>
            <input type="button" class="btn btn-success" id="btnRegister" value="Enable Webphone" disabled
                   onclick='sipRegister();'/>
            <input type="button" class="btn btn-danger" id="btnUnRegister" value="Disable Webphone" disabled
                   onclick='sipUnRegister();'/>
        </div>
        <div id='divCallOptions' class='call-options' style='display:none;opacity: 0; margin-top: 10px'>
            <input type="button" class="btn btn-primary" style="" id="btnFullScreen" value="FullScreen" disabled
                   onclick='toggleFullScreen();'/>
            <input type="button" class="btn btn-primary" style="" id="btnMute" value="Mute" onclick='sipToggleMute();'/>
            <input type="button" class="btn btn-primary" style="" id="btnHoldResume" value="Hold"
                   onclick='sipToggleHoldResume();'/>
            <input type="button" class="btn btn-primary" style="" id="btnTransfer" value="Transfer"
                   onclick='sipTransfer();'/>
            <input type="button" class="btn btn-primary" style="" id="btnKeyPad" value="KeyPad"
                   onclick='openKeyPad();'/>
        </div>
        <table style='display:none;width: 100%;'>
            <tr>
                <td id="tdVideo" class='tab-video'>
                    <div id="divVideo" class='div-video'>
                        <div id="divVideoRemote"
                             style='position:relative; height:100%; width:100%; z-index: auto; opacity: 1'>
                            <video class="video" width="100%" height="100%" id="video_remote" autoplay="autoplay"
                                   style="opacity: 0;
                                            background-color: #000000; -webkit-transition-property: opacity; -webkit-transition-duration: 2s;"></video>
                        </div>

                        <div id="divVideoLocalWrapper" style="margin-left: 0px; border:0px solid #009; z-index: 1000">
                            <iframe class="previewvideo" style="border:0px solid #009; z-index: 1000"></iframe>
                            <div id="divVideoLocal" class="previewvideo" style=' border:0px solid #009; z-index: 1000'>
                                <video class="video" width="100%" height="100%" id="video_local" autoplay="autoplay"
                                       muted="true" style="opacity: 0;
                                                background-color: #000000; -webkit-transition-property: opacity;
                                                -webkit-transition-duration: 2s;"></video>
                            </div>
                        </div>
                        <div id="divScreencastLocalWrapper"
                             style="margin-left: 90px; border:0px solid #009; z-index: 1000">
                            <iframe class="previewvideo" style="border:0px solid #009; z-index: 1000"></iframe>
                            <div id="divScreencastLocal" class="previewvideo"
                                 style=' border:0px solid #009; z-index: 1000'></div>
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
    </div>
</div>

<script src="{{asset('asset/js/SIPml-api.js')}}" type="text/javascript"></script>
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

    C =
        {
            divKeyPadWidth: 220
        };

    window.onload = function () {
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

        var getPVal = function (PName) {
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

        var preInit = function () {
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

        oReadyStateTimer = setInterval(function () {
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

    function postInit() {
        // check for WebRTC support
        if (!SIPml.isWebRtcSupported()) {
            // is it chrome?
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
                } else {
                    // Must do nothing: give the user the chance to accept the extension
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
            bandwidth: {audio: undefined, video: undefined},
            video_size: {minWidth: undefined, minHeight: undefined, maxWidth: undefined, maxHeight: undefined},
            events_listener: {events: '*', listener: onSipEventSession},
            sip_caps: [
                {name: '+g.oma.sip-im'},
                {name: 'language', value: '\"en,fr\"'}
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
            window.localStorage.setItem('org.doubango.identity.display_name', '<?php echo Session::get('display_name');?>');
            window.localStorage.setItem('org.doubango.identity.impi', '<?php echo Session::get('private_identity');?>');
            window.localStorage.setItem('org.doubango.identity.impu', '<?php echo Session::get('public_identity');?>');
            window.localStorage.setItem('org.doubango.identity.password', '<?php echo Session::get('password');?>');
            window.localStorage.setItem('org.doubango.identity.realm', '<?php echo Session::get('realm');?>');
            window.localStorage.setItem('org.doubango.expert.websocket_server_url', '<?php echo Session::get('websocket_server_url');?>');
            window.localStorage.setItem('org.doubango.expert.disable_video', "true");
            window.localStorage.setItem('org.doubango.expert.enable_rtcweb_breaker', "true");
            window.localStorage.setItem('org.doubango.expert.sip_outboundproxy_url', "");
            window.localStorage.setItem('org.doubango.expert.ice_servers', "<?php echo Session::get('ice_servers');?>");
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
                txtRegStatus.innerHTML = '<b>Extension settings are incomplete!</b>';
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
                    events_listener: {events: '*', listener: onSipEventStack},
                    enable_early_ims: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.disable_early_ims') != "true" : true), // Must be true unless you're using a real IMS network
                    enable_media_stream_cache: (window.localStorage ? window.localStorage.getItem('org.doubango.expert.enable_media_caching') == "true" : false),
                    bandwidth: (window.localStorage ? tsk_string_to_object(window.localStorage.getItem('org.doubango.expert.bandwidth')) : null), // could be redefined a session-level
                    video_size: (window.localStorage ? tsk_string_to_object(window.localStorage.getItem('org.doubango.expert.video_size')) : null), // could be redefined a session-level
                    sip_headers: [
                        {name: 'User-Agent', value: 'IM-client/OMA1.0 sipML5-v1.2016.03.04'},
                        {name: 'Organization', value: 'Doubango Telecom'}
                    ]
                }
            );

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
            if(numberToCall.length > 9){
                numberToCall = '91'+numberToCall;
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
            i_ret = oSipSessionCall.mute('audio'/*could be 'video'*/, bMute);
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
            oSipSessionCall.hangup({events_listener: {events: '*', listener: onSipEventSession}});
        }
    }

    function sipSendDTMF(c) {
        if (oSipSessionCall && c) {
            if (oSipSessionCall.dtmf(c) == 0) {
                try {
                    dtmfTone.play();
                } catch (e) {
                }
            }
        }
    }

    function startRingTone() {
        try {
            ringtone.play();
        } catch (e) {
        }
    }

    function stopRingTone() {
        try {
            ringtone.pause();
        } catch (e) {
        }
    }

    function startRingbackTone() {
        try {
            ringbacktone.play();
        } catch (e) {
        }
    }

    function stopRingbackTone() {
        try {
            ringbacktone.pause();
        } catch (e) {
        }
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
            oNotifICall.onclose = function () {
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
        } catch (e) {
        }
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
                btnCall.onclick = bDisableCallBtnOptions ? function () {
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
                btnCall.onclick = function () {
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

        setTimeout(function () {
            if (!oSipSessionCall) txtCallStatus.innerHTML = '';
        }, 2500);
    }

    // Callback function for SIP Stacks
    function onSipEventStack(e /*SIPml.Stack.Event*/) {
        console.log("onSipEventStack", e);
        tsk_utils_log_info('==stack event = ' + e.type);
        switch (e.type) {
            case 'started': {
                // catch exception for IE (DOM not ready)
                try {
                    // LogIn (REGISTER) as soon as the stack finish starting
                    oSipSessionRegister = this.newSession('register', {
                        expires: 200,
                        events_listener: {events: '*', listener: onSipEventSession},
                        sip_caps: [
                            {name: '+g.oma.sip-im', value: null},
                            //{ name: '+sip.ice' }, // rfc5768: FIXME doesn't work with Polycom TelePresence
                            {name: '+audio', value: null},
                            {name: 'language', value: '\"en,fr\"'}
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
    function onSipEventSession(e /* SIPml.Session.Event */) {
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
                    var s_message = "Do you accept call transfer to [" + e.getTransferDestinationFriendlyName() + "]?";//FIXME
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
    $(document).on('click', '.dialpad-number', function (e) {
        var currentVal = $('#txtPhoneNumber').val();
        var newVal = currentVal + $(this).val();
        txtPhoneNumber.value = newVal;
    });
    //Webphone back button
    $(document).on('click', '#dialpad-backspace', function (e) {
        var currentVal = $('#txtPhoneNumber').val();
        var newVal = currentVal.substring(0, currentVal.length - 1);
        txtPhoneNumber.value = newVal;
    });
    $(document).ready(function () {
        setTimeout(function () {
            $("#btnRegister").trigger('click');
        }, 1000);
        window.onbeforeunload = function (e) {
            e.preventDefault();
            if (oSipStack || oSipSessionCall) {
                toastr.error("Don't close this page when you are in connection.");
                return false;
            }
        };
    });
</script>
<!-- Audios -->
<audio id="audio_remote" autoplay="autoplay"></audio>
<audio id="ringtone" loop src="{{URL::asset('asset/audio/ringtone.wav')}}"></audio>
<audio id="ringbacktone" loop src="{{URL::asset('asset/audio/ringbacktone.wav')}}"></audio>
<audio id="dtmfTone" src="{{URL::asset('asset/audio/dtmf.wav')}}"></audio>
</body>
</html>
