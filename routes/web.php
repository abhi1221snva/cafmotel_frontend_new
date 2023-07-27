<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', 'ApiLoginController@index')->name("login");
Route::post('/', 'ApiLoginController@apilogin');
Route::get('/otp', 'ApiLoginController@otp');
Route::post('/otp', 'ApiLoginController@otp');


Route::get('logout', 'ApiLoginController@logout');

//sms api receiveing from external
Route::any('receive-sms', 'ApiSmsController@smsResponse');

//pusher api with basic auth
Route::get('push-event/{api_key}/{platform}/{from}/{to}/{event?}', 'PusherController@index');


Route::group(['middleware' => 'session'], function () {
    Route::get('forgot-password', 'AdminController@forgotPassword');
    Route::get('resetPassword', 'AdminController@resetPassword');

    Route::post('/switch-client/{clientId}', 'ApiLoginController@switchClient');
    Route::post('image-upload', 'AdminController@imageUploadPost')->name('image.upload.post');

    Route::get('checkEmail', 'AdminController@checkEmail');



    Route::get('/profile', 'ApiUserController@userProfile');


    /* Route::get('/profile', function () {
      return view('users.profile');
      })->name('profile'); */

    Route::post('/profile', 'ApiUserController@changePassword');

    Route::post('/updateProfile', 'ApiUserController@updateProfile');
    Route::post('/updateTimezone', 'ApiUserController@updateTimezone');


    Route::get('/deleteVoiceMail/{id}/{voicemail_id}', 'ApiUserController@deleteVoiceMail');
    Route::get('/profile/edit-voicemail/{voicemail_id}', 'ApiUserController@editVoiceMail');

    Route::post('/profile/edit-voicemail/{voicemail_id}', 'ApiUserController@updateVoiceMail');


    Route::get('/assignable-roles/{id}', [
        'as' => 'assignableRoles',
        'uses' => 'ApiExtensionController@getAssignableRoles'
    ]);
    Route::post('/save-user-roles', [
        'as' => 'saveUserRoles',
        'uses' => 'ApiExtensionController@saveUserRoles'
    ]);

    Route::post('/hangup-conferencce', [
        'as' => 'hangupConferences',
        'uses' => 'ApiExtensionController@hangupConferences'
    ]);


//Reporting
    Route::get('/report', 'ApiReportController@getReport');
    Route::post('/report', 'ApiReportController@getReport');
    Route::get('/live-call', 'ApiReportController@getLiveCall');
    Route::post('/live-call', 'ApiReportController@getLiveCall');
    Route::get('/transfer-report', 'ApiReportController@getTransferReport');
    Route::post('/transfer-report', 'ApiReportController@getTransferReport');
    Route::get('/report/{number}', 'ApiReportController@getReportByNumber');
    Route::get('/call-report/{lead_id}', 'ApiReportController@getReportByLeadId');
    Route::post('/listen-call', 'ApiReportController@listenCall');
    Route::post('/barge-call', 'ApiReportController@bargeCall');

    //login history

    Route::get('/login-history', 'ApiReportController@loginHistory');
    Route::post('/login-history', 'ApiReportController@loginHistory');



//Dialler
    //Route::any('start-dialing', 'ApiDialerController@index');

    Route::any('start-dialing-new', 'ApiDialerController@indexStartDialingNew');
    Route::any('start-dialing', 'ApiDialerController@indexStartDialingNew');

    Route::any('dialer-new', 'ApiDialerController@indexDemo');

    Route::post('/call-number', 'ApiDialerController@callNumber');
    Route::post('/hang-up', 'ApiDialerController@hangUp');
    Route::post('/dtmf', 'ApiDialerController@dtmf');
    Route::post('/voicemail-drop', 'ApiDialerController@voicemailDrop');
    Route::post('/get-lead', 'ApiDialerController@getLead');
    Route::post('/save-disposition', 'ApiDialerController@saveDisposition');
    Route::post('/redial-call', 'ApiDialerController@redialCall');

    Route::post('/lead/{leadId}', [
        'as' => 'updateLeadData',
        'uses' => 'ApiDialerController@updateLeadData'
    ]);
    Route::get('/get-csrf-token', 'ApiDialerController@getCsrfToken');
    Route::get('/hopper-count/{campaignId}', 'ApiDialerController@getHopperCount');

    Route::post('add-new-lead-pd', 'ApiDialerController@add_new_lead_pd');
    Route::post('webphone/switch-access', "ApiDialerController@switchSoftphoneUse");
    Route::get('webphone', "ApiDialerController@openWebPhone");

//Route::post('/send-to-crm', 'ApiDialerController@sendToCrm');
    Route::any('sendToCrm', 'ApiDidController@sendToCrmUser');

    Route::get('send-to-crm-post', 'ApiDidController@sendToCrmUserPost');


//Group
    Route::get('/extension-group', 'ApiExtensionController@getGroup');
    Route::get('/mapExtensionGroup', 'ApiExtensionController@mapExtensionGroup');

    Route::post('extension-group', 'ApiExtensionController@storeExtensionGroup');
    Route::get('/addGroup/{title}/{extensions}', 'ApiExtensionController@addGroup');

    Route::get('/deleteExtensionGroup/{id}', 'ApiExtensionController@deleteExtensionGroup');

//marketing campaigns
    Route::get('marketing-campaigns', 'ApiMarketingCampaignController@index');
    Route::post('marketing-campaigns', 'ApiMarketingCampaignController@addNew');
    Route::get('marketing-campaign/{id}', 'ApiMarketingCampaignController@show');

// marketing campaign schedule
    Route::get('marketing-campaign/{id}/schedules', 'MarketingCampaignScheduleController@index');
    Route::get('marketing-campaign-schedule/{id}', 'MarketingCampaignScheduleController@show');
    Route::post('marketing-campaign-schedule/{id}', 'MarketingCampaignScheduleController@update');
    Route::get('marketing-campaign/{campaignId}/schedule/{scheduleId}/logs', 'MarketingCampaignScheduleController@getLogs');
    Route::post('marketing-campaign/{campaignId}/schedule/{scheduleId}/log/{logId}/retry', 'MarketingCampaignScheduleController@retryLog');

    Route::get('findListHeader/{list_id}', 'MarketingCampaignScheduleController@findListHeader');
    Route::post('add-marketing-schedule', ['as' => 'addMarketingSchedule','uses' => 'MarketingCampaignScheduleController@addMarketingSchedule']);
    Route::post('add-marketing-schedule-sms', ['as' => 'addMarketingScheduleSMS','uses' => 'MarketingCampaignScheduleController@addMarketingScheduleSMS']);
    Route::post('/deleteSchedule', ['as' => 'deleteSchedule','uses' => 'MarketingCampaignScheduleController@deleteSchedule']);
    Route::post('/abortSchedule', ['as' => 'abortSchedule','uses' => 'MarketingCampaignScheduleController@abortSchedule']);

//Campaign
    Route::get('/campaign', 'ApiCampaignController@getCampaign');
    Route::get('/add-campaign', 'ApiCampaignController@storeCampaign');
    Route::post('/add-campaign', 'ApiCampaignController@storeCampaign');
    Route::get('/campaign/{id}', 'ApiCampaignController@showEditCampaign');
    Route::post('/campaign/{id}', 'ApiCampaignController@editCampaign');
    Route::get('copy-campaign/{id}', 'ApiCampaignController@copyCampaign');
    Route::get('/campaign/{list}/{id}', 'ApiCampaignController@getCampaignList');
    Route::post('/campaign/{list}/{id}', 'ApiCampaignController@recycleListDisposition');
    Route::get('/deleteCampaign/{id}', 'ApiCampaignController@deleteCampaign');
    Route::get('/listDisposition/{list_id}', 'ApiCampaignController@listDisposition');
    Route::get('/reload-hopper/{campaign}', [
        'as' => 'reloadHopper',
        'uses' => 'ApiCampaignController@reloadHopper'
    ]);
    Route::get('/campaignAddGroup/{title}/{extensions}', 'ApiCampaignController@addGroup');

    //show upload history
    Route::get('/show-upload-history', 'ShowHistoryController@showHistory');

    //Campaign Type
    Route::get('campaign-type', 'ApiCampaignTypeController@index');
    Route::post('campaign-type', 'ApiCampaignTypeController@create');
     Route::get('/campaign-type/{id}', 'ApiCampaignTypeController@show');
     Route::post('campaign-type/{id}', 'ApiCampaignTypeController@update');
     Route::get('delete-campaign-type/{id}', 'ApiCampaignTypeController@delete');
     //crm_lists
    Route::get('crm-list', 'ApiCrmListController@index');
    Route::post('crm-list', 'ApiCrmListController@create');
    Route::get('/crm-list/{id}', 'ApiCrmListController@show');
    Route::post('crm-list/{id}', 'ApiCrmListController@update');
    Route::get('delete-crm-list/{id}', 'ApiCrmListController@delete');
    //sip_channel_provider
    Route::get('sip-channel-provider', 'ApiSipChannelController@index');
    Route::post('sip-channel-provider', 'ApiSipChannelController@create');
    Route::get('/sip-channel-provider/{id}', 'ApiSipChannelController@show');
    Route::post('sip-channel-provider/{id}', 'ApiSipChannelController@update');
    Route::get('delete-sip-channel-provider/{id}', 'ApiSipChannelController@delete');


//extension
    Route::get('/extension', 'ApiExtensionController@getExtension');
    Route::post('/extension', 'ApiExtensionController@changePasswordAgent');
    Route::get('/add-extension', 'ApiExtensionController@index');

    //TODO: Remove this endpoint after verifying, i think it is not in use.
    Route::post('/add-extension', 'ApiExtensionController@storeExtension');

    Route::get('/extension/{id}', 'ApiExtensionController@editExtension');
    Route::post('/extension/{id}', 'ApiExtensionController@storeExtension');
    Route::get('/deleteExtension/{extension}', 'ApiExtensionController@deleteExtension');
    Route::get('checkExtension/{extension_name}', 'ApiExtensionController@checkExtension');
    Route::get('checkAltExtension/{alt_extension_name}', 'ApiExtensionController@checkAltExtension');
    Route::post('updateEmail', 'ApiExtensionController@updateEmail');




// Created by : Pankaj
    Route::post('save-edit-extension', 'ApiExtensionController@saveEditExtension');
    Route::post('new-extension-save', 'ApiExtensionController@saveNewExtension');

//disposition
    Route::get('disposition', 'ApiDispositionController@getDisposition');
    Route::post('disposition', 'ApiDispositionController@storeDisposition');
    Route::get('/editDisposition/{id}', 'ApiDispositionController@editDisposition');
    Route::get('/deleteDisposition/{id}', 'ApiDispositionController@deleteDisposition');
    Route::get('/addDisposition/{title}/{d_type}/{enable_sms}', 'ApiDispositionController@addDisposition');


//Label
    Route::get('label', 'ApiLabelController@getLabel');
    Route::post('label', 'ApiLabelController@storeLabel');
    Route::get('/label/{id}', 'ApiLabelController@editLabel');
    Route::get('/deleteLabel/{id}', 'ApiLabelController@deleteLabel');

//DNC
    Route::get('dnc', 'ApiDncController@getDNC');
    Route::post('dnc', 'ApiDncController@storeDNC');
    Route::get('/editDnc/{id}', 'ApiDncController@editDnc');
    Route::get('/deleteDnc/{id}', 'ApiDncController@deleteDnc');
    // routes/web.php
    Route::get('/dnc/search', 'ApiDncController@searchDNC')->name('dnc.search');



// exclude number
    Route::get('exclude-from-list', 'ApiExcludeNumberController@getExcludeNumber');
    Route::post('exclude-from-list', 'ApiExcludeNumberController@storeExcludeNumber');
    Route::get('/deleteExcludeNo/{id}/{campaign}', 'ApiExcludeNumberController@deleteExcludeNo');
    Route::get('/editExcludeNumber/{id}/{campaign}', 'ApiExcludeNumberController@editExcludeNumber');

//API
    Route::get('/api-data', 'ApiApiController@getApiList');
    Route::get('add-api', 'ApiApiController@storeApi');
    Route::post('add-api', 'ApiApiController@storeApi');
    Route::get('edit-api/{id}', 'ApiApiController@editApi');
    Route::post('edit-api/{id}', 'ApiApiController@edit_save');
    Route::get('copy-api/{id}', 'ApiApiController@copyApi');
    Route::get('/deleteApi/{id}', 'ApiApiController@deleteApi');

//logo setting
    Route::get('logo-setting', 'ApiLogoSetting@index');
    Route::post('logo-setting', 'ApiLogoSetting@updateEmailSetting');
    Route::post('logo-upload', 'ApiLogoSetting@imageUploadPost')->name('logo.upload.post');
    Route::post('profile-voice', 'ApiUserController@voiceMail')->name('voice.mail.post');


//lists
    Route::get('list ', 'ApiListController@getListList');
    Route::post('list', 'ApiListController@storeList');
    Route::get('list/{id}/content', 'ApiListController@getListContent');
    Route::get('/editList/{id}/{campaign}', 'ApiListController@editList');
    Route::post('/editList/{id}/{campaign}', 'ApiListController@editList');
    Route::get('/deleteListData/{id}/{campaign}', 'ApiListController@deleteListData');
    Route::get('/updateList/{id}/{status}', 'ApiListController@updateList');
    Route::get('/updateListStatus/{id}/{status}', 'ApiListController@updateListStatus');
    Route::get('/updateCampaignList/{campaign_id}/{list_id}/{status}/{check_url}', 'ApiListController@updateCampaignList');


    Route::get('lead', 'ApiListController@searchLeads');
    Route::post('lead', 'ApiListController@searchLeadColumn');
    Route::get('searchListHeader/{list_id}', 'ApiListController@searchListHeader');

    Route::get('lead-activity', 'ApiListController@showLeadActivityPage');
    Route::get('lead-data/{id?}/{number}', 'ApiListController@showEditLeadDataPage');
    Route::post('update-lead-data', 'ApiListController@updateLeadData');

    Route::get('recycle-rule', 'ApiRecycleController@getRecycleList');
    Route::post('recycle-rule', 'ApiRecycleController@searchRecycleRule');
    Route::get('add-recycle', 'ApiRecycleController@storeRecycle');
    Route::post('add-recycle', 'ApiRecycleController@storeRecycle');
    Route::get('recycle-rule/{list_id}/{disposition_id}', 'ApiRecycleController@findRecycleListDelete');
    Route::get('edit-recycle/{id}', 'ApiRecycleController@editRecycleRule');
    Route::post('edit-recycle/{id}', 'ApiRecycleController@editRecycleRule');
    Route::get('/deleteRecycleRule/{id}', 'ApiRecycleController@deleteRecycleRule');

// sms
    Route::get('inbox', 'ApiSmsController@getSms');
    Route::post('sendSms', 'ApiSmsController@sendSms');
    Route::get('sendSms', 'ApiSmsController@sendSms');

    Route::get('/editSms/{id}', 'ApiSmsController@editSms');
    Route::get('openSmsDetails', 'ApiSmsController@openSmsDetails');
    Route::get('recentSmsList', 'ApiSmsController@recentSmsList');

//sms templete
    Route::get('sms-templete', 'ApiSmsTempleteController@getSmsTemplete');
    Route::get('/add-sms-templete', 'ApiSmsTempleteController@storeSmsTemplete');
    Route::post('/add-sms-templete', 'ApiSmsTempleteController@storeSmsTemplete');
    Route::post('/editSmsTemplete/{id}', 'ApiSmsTempleteController@storeSmsTemplete');
    Route::get('/editSmsTemplete/{id}', 'ApiSmsTempleteController@editSmsTemplete');
    Route::get('/deleteSmsTemplete/{id}/{status}', 'ApiSmsTempleteController@deleteSmsTemplete');

    Route::post('/sms-template-delete/{id}', 'ApiSmsTempleteController@delete');


    Route::get('add-admin', 'AdminController@index');
    Route::post('add-admin', 'AdminController@addAdmin');

// fax
    Route::any('/fax-list', 'ApiFaxController@getFax');
    Route::any('/fax-list/{id}', 'ApiFaxController@getFaxPdf');

    Route::get('send-fax', 'ApiFaxController@addFax');
    Route::post('save-fax', 'ApiFaxController@saveFax');
    Route::post('sendFaxGet', 'ApiFaxController@sendFaxGet');

    Route::any('receive-fax', 'ApiFaxController@receiveFax');
    Route::any('sending-failed-fax', 'ApiFaxController@sendingFailedFax');

//callback
    Route::get('/callback', 'APiCallBackController@getCallBack');
    Route::post('/callback/reminder', 'APiCallBackController@getReminderCallBacks');
    Route::post('/callback', 'APiCallBackController@getCallBack');
    Route::post('callback/edit', 'APiCallBackController@editCallback');
    Route::get('callback-reminder/stop', "APiCallBackController@stopReminder");
    Route::get('callback-reminder/show', "APiCallBackController@showReminder");
    Route::get('callback-reminder/status', "APiCallBackController@getReminderStatus");

// ivr
    Route::get('ivr', 'ApiIvrController@getIvr');
    Route::post('ivr', 'ApiIvrController@storeIvr');
    Route::get('/editIvr/{id}', 'ApiIvrController@editIvr');
    Route::get('/edit-ivr/{id?}', 'ApiIvrController@editIvrForm');
    Route::get('/deleteIvr/{id}/{ivr_id}', 'ApiIvrController@deleteIvr');
    Route::post('/get-voice-name-on-lanugage', 'ApiIvrController@getVoiceNameOnLanugage');
    Route::post('/get-audio-on-text', 'GoogleClientController@getAudioOnText');

    Route::post('/save-recorded-audio', 'ApiIvrController@saveRecordedAudio');


//audio message

    Route::get('audio-message', 'AudioMessageController@index');
    Route::post('audio-message', 'AudioMessageController@storeAudioMessage');

    Route::get('/edit-audio-message/{id?}', 'AudioMessageController@editIvrForm');


    



// ivr menu
    Route::get('ivr-menu/{id?}', 'ApiIvrMenuController@getIvrMenu');
    Route::get('/deleteIvrMenu/{id}', 'ApiIvrMenuController@deleteIvrMenu');
    Route::post('edit-ivr-menu', 'ApiIvrMenuController@editIvrMenu');

    Route::get('add-ivr-menu', 'ApiIvrMenuController@storeIvrMenu');
    Route::post('add-ivr-menu', 'ApiIvrMenuController@storeIvrMenu');
    Route::get('edit-ivr-menu/{id}', 'ApiIvrMenuController@editIvrMenu');
    Route::get('/checkDestType/{dest_id}', 'ApiIvrMenuController@checkDestType');

//Did
    Route::get('did', 'ApiDidController@getListList');
    Route::post('did/saveDid', 'ApiDidController@storeDid');
    Route::get('edit-did/{id}', 'ApiDidController@editList');
    Route::post('did/saveEditDid', 'ApiDidController@saveEditDid');
    Route::get('deleteDidData/{id}', 'ApiDidController@deleteDidData');
    Route::post('did/uploadDid', 'ApiDidController@upload');


    Route::get('listdid', 'ApiDidController@getListDid');
    Route::get('listdid/{cid}/{sid}/{npa}/{nxx}', 'ApiDidController@getDIDForSale');

    Route::get('did/call-timings-listing', 'ApiDidController@showOfficeHours');
    Route::get('did/call-timings/{dept_id?}', 'ApiDidController@showOfficeHoursForm');
    Route::post('did/save-call-timings', 'ApiDidController@saveOfficeHours');

    Route::get('did/holidays/{id?}', 'ApiDidController@showHolidays');
    Route::post('did/save-holiday', 'ApiDidController@saveHoliday');
    Route::get('did/delete-holiday/{id}', 'ApiDidController@deleteHoliday');

    Route::get('show-buy-did', 'ApiDidController@showBuyDidPage');
    Route::get('show-buy-did-plivo', 'ApiDidController@showBuyDidPagePlivo');

    Route::post('get-did-list-from-sale', 'ApiDidController@getDidListFromSale');
    Route::post('get-did-list-from-plivo', 'ApiDidController@getDidListFromPlivo');

    Route::post('buy-did', 'ApiDidController@buyDid');
    Route::post('buy-did-plivo', 'ApiDidController@buyDidPlivo');

    Route::post('/save-recorded-audio-announcements', 'ApiDidController@saveRecordedAudio');
    

// ring group
    Route::get('ring-group', 'ApiRingGroupController@getRingGroup');
    Route::post('ring-group', 'ApiRingGroupController@storeRingGroup');
    Route::get('/editRingGroup/{id}', 'ApiRingGroupController@editRingGroup');
    Route::get('/deleteRingGroup/{id}', 'ApiRingGroupController@deleteRingGroup');


    Route::get('/mailbox', 'ApiMailboxController@getMailbox');
    Route::post('/mailbox', 'ApiMailboxController@getMailbox');
    Route::get('/deleteMailbox/{id}', 'ApiMailboxController@deleteMailbox');
    Route::delete('deleteAll', 'ApiMailboxController@deleteAll');
    Route::get('/statusMailBox/{status}/{id}', 'ApiMailboxController@statusMailBox');

// Dashboard
    Route::any('/ajax_call_report', 'ApiDashboardController@getCallDetail');
    Route::post('/change-session-value-for-timezone', 'ApiDashboardController@setSessionValue');

    Route::any('/sms-counts', 'ApiDashboardController@getSmsCounts');
    Route::any('/sms-counts-unread', 'ApiDashboardController@getSmsCountsunread');

    Route::any('/get-call-chart-data', 'ApiDashboardController@getCdrChartData');
    Route::get('/dashboard', 'ApiDashboardController@index');
    Route::post('/dashboard', 'ApiSmsController@sendSms');

//smtp setting
    Route::get('smtps', 'ApiSmtpSetting@index');
    Route::get('smtp', 'ApiSmtpSetting@showNew');
    Route::post('smtp', 'ApiSmtpSetting@addNew');
    Route::get('copy-smtp/{id}', 'ApiSmtpSetting@copySmtp');

    Route::get('/smtp/{id}', 'ApiSmtpSetting@show')->name("smtp.edit");
    Route::post('/smtp/{id}', 'ApiSmtpSetting@update');
    Route::post('/smtp-delete/{id}', 'ApiSmtpSetting@delete');
    Route::post('/checkSMTPSetting', 'ApiSmtpSetting@checkSMTPSetting');

    //sms setting
    Route::get('sms-settings', 'SmsSettingController@index');
    Route::get('setting-sms',  'SmsSettingController@showNew');
    Route::post('setting-sms', 'SmsSettingController@addNew');
    Route::get('/setting-sms/{id}', 'SmsSettingController@show')->name("sms-setting.edit");
    Route::post('/setting-sms/{id}', 'SmsSettingController@update');
    Route::post('/sms-delete/{id}', 'SmsSettingController@delete');
    Route::post('/checkSMSSetting', 'SmsSettingController@checkSMSSetting');






    

// ip setting
    Route::get('ip-setting', 'ApiIpSettingController@getIpSetting');
    Route::post('ip-approve-whitelist', 'ApiIpSettingController@approveWhitelist');
    Route::post('ip-reject-whitelist', 'ApiIpSettingController@rejectWhitelist');

    Route::get('whitelist-ip', 'ApiIpSettingController@whitelistIp');
    Route::post('whitelist-ip', 'ApiIpSettingController@whitelistIpSave');
    Route::post('query-whitelist', 'ApiIpSettingController@queryWhitelist');

//conferencing
    Route::get('conferencing', 'ApiConferencingController@getConferencing');
    Route::post('conferencing', 'ApiConferencingController@storeConferencing');
    Route::post('conferencing/save-recorded-audio', 'ApiConferencingController@saveRecordedAudio');
    Route::get('/editConferencing/{id}', 'ApiConferencingController@editConferencing');
    Route::get('/deleteConferencing/{id}', 'ApiConferencingController@deleteConferencing');

    //Billing
    Route::get('invoice', 'InvoiceController@index');
    Route::get('invoice/{orderId}', 'InvoiceController@show');
    Route::get('invoice/pdf/{orderId}', 'InvoiceController@generatePdf');

// send email report
    Route::get('sendCronEmailCalls', 'ApiCronSendReportController@sendCronEmailCalls');
    Route::get('/country/{country_id}', 'InheritApiController@getState');


//notification
    Route::any('get-notification', 'InheritApiController@getNotification');
    Route::get('extension_live', 'ApiLabelController@getLiveExtension');
    Route::get('deleteExtLiv/{id}', 'ApiLabelController@deleteExtLiv');
    Route::any('get-notification-counts', 'InheritApiController@getNotificationCounts');

//clients
    Route::get('clients', 'ClientController@index');
    Route::get('/client/{id}', 'ClientController@show');
    Route::post('client/manual-subscription', 'ClientController@performManualSubscription');
    Route::post('client/credit-wallet', 'ClientController@creditWallet');
    Route::post('/client/{id}', 'ClientController@update');
    Route::get('client', 'ClientController@showNew');
    Route::post('client', 'ClientController@addNew');

    //Route::post('client/save-sms-provider', 'ClientController@storeSMSProvider');


//email-templates
    Route::get('email-templates', 'EmailTempleteController@index');
    Route::get('email-template', 'EmailTempleteController@showNew');
    Route::post('email-template', 'EmailTempleteController@addNew');
    Route::get('/email-template/{id}', 'EmailTempleteController@show');
    Route::post('/email-template/{id}', 'EmailTempleteController@update');
    Route::get('check-email', 'EmailTempleteController@checkMail');

    Route::post('/email-template-delete/{id}', 'EmailTempleteController@delete');
    Route::get('/deleteEmailTemplete/{id}/{status}', 'EmailTempleteController@deleteEmailTemplete');



//send email
    Route::get('send-mail', 'AdminController@sendMail');
    Route::post('send-mail', 'MailController@sendMail')->name('mail');

// Route for load sms template record
    Route::post('messaging_modal_box_data', 'ApiSmsController@load_message_popup');
    Route::post('messaging_modal_sms_template_review', 'ApiSmsController@load_popup_sms_preview');
    Route::post('send_sms_dialer', 'ApiSmsController@send_sms_dialer');


    //mail controller
    Route::get('email', 'MailController@email');

    Route::get('mailer/', 'MailController@mailer');
    Route::post('mailer/', 'MailController@email');
    Route::post('send-email/generic', 'MailController@email');

    Route::post('openMailModal', 'MailController@openMailModal');
    Route::post('change-disposition', 'ApiListController@changeDisposition');

    Route::get('/getTemplate/{id}/{list_id}/{lead_id}', 'MailController@getTemplate');
    Route::get('/getLabelValue/{label_id}/{list_id}/{lead_id}', 'MailController@getLabelValue');
    Route::get('/getSenderValue/{sender_id}', 'MailController@getSenderValue');
    Route::get('checkEmail/{email}', 'ApiExtensionController@checkEmail');

    //Coupons
    Route::get('coupons', 'ApiCouponsController@index');
    Route::get('coupon-detail/{id?}', 'ApiCouponsController@detail');
    Route::post('coupon-edit', 'ApiCouponsController@edit');

    // Show user packages
    Route::get('user-packages', 'ApiUserPackagesController@getUsersByClientId');
    Route::post('/user-package/update/{packageKey}', 'ApiUserPackagesController@updateUserPackage');
    Route::post('/user-package/delete/{packageKey}', 'ApiUserPackagesController@deleteUserPackage');

    //client Plans
    Route::get('active-plans', 'ClientPackageController@activePlans');
    Route::get('plan-history', 'ClientPackageController@planHistory');
    Route::get('packages', 'ClientPackageController@upgradePlan');

    //wallet
    Route::get('wallet/transactions', 'WalletController@getWalletTransactions');

    //cart
    Route::get('cart', 'CartController@getCartItems');
    Route::get('cart/count', 'CartController@getCartCount');
    Route::post('cart/add/{packageName}', 'CartController@addToCart');
    Route::post('cart/update/{cartId}', 'CartController@updateCart');
    Route::post('cart/delete/{cartId}', 'CartController@deleteCart');

    Route::group(['middleware' => ["auth.superadmin"]], function () {
        //subscription packages
        Route::get('super/packages', 'PackageController@index');
        Route::get('super/package', 'PackageController@showNew');
        Route::post('super/package', 'PackageController@addNew');
        Route::get('super/package/{key}', 'PackageController@show');
        Route::post('super/package/{key}', 'PackageController@update');
        Route::get('super/package/copy/{key}', 'PackageController@copy');
        Route::post('super/package/copy/{key}', 'PackageController@addNew');
        Route::get('super/package/view/{key}', 'PackageController@view');

        Route::get('super/package/rate/add', 'PackageController@addRate');
        Route::post('super/package/rate/add', 'PackageController@addNewRate');
        Route::get('super/package/rate/{key}', 'PackageController@rate');


        Route::get('super/package/rate/edit/{id}', 'PackageController@editShow');
        Route::post('super/package/rate/edit/{id}', 'PackageController@updateRate');



        //modules
        Route::get('super/modules', 'ModulesController@index');
        Route::get('super/module',  'ModulesController@showNew');
        Route::post('super/module', 'ModulesController@addNew');
        Route::get('super/module/{key}', 'ModulesController@show');
        Route::post('super/module/{key}', 'ModulesController@update');
        Route::get('super/components', 'ModuleComponentController@index');
    });

  //Stripe
    Route::get('payment-method-list', 'PaymentController@showPaymentMethodList');
    Route::get('edit-payment-method', 'PaymentController@editPaymentMethod');
    Route::post('stripe/save-card', 'PaymentController@saveCard');
    Route::get('update-payment-method/{id}', 'PaymentController@updatePaymentMethod');
    Route::post('stripe/update-card', 'PaymentController@updateCard');
    Route::get('delete-payment-method/{id}', 'PaymentController@deletePaymentMethod');
    Route::get('recharge', 'PaymentController@recharge');
    Route::post('stripe/create-customer-payment-method', 'StripeController@createStripeCustomerPaymentMethod');
    Route::post('stripe/attach-customer-and-payment-method', 'StripeController@attachCustomerAndPaymentMethod');
    Route::post('pay', 'StripeController@pay');

    Route::get('checkout', 'PaymentController@checkout');
    Route::post('checkout', 'StripeController@processCheckout');

    //opening questions
    Route::get('opening-questions/hide', "OpeningQuestionsController@doNotShow");
    Route::get('opening-questions/hide/permanently', "OpeningQuestionsController@hideQuestionsPermanently");
    Route::get('opening-questions/show/permanently', "OpeningQuestionsController@showQuestionsPermanently");
    Route::get('flash-panel', "OpeningQuestionsController@flashPanel");

    //super admin links
    Route::get('/super-admins', 'SuperAdminController@index');
    Route::get('/super-admin/{id}', 'SuperAdminController@show');
    Route::post('/super-admin/{id}', 'SuperAdminController@update');

    Route::get('contact-book', 'ApiContactsController@getCompanyUsers');

    //Chat application
    Route::post('/favorites', 'MessagesController@getFavorites')->name('favorites');
    Route::get('/search', 'MessagesController@search')->name('search');
    Route::post('/idInfo', 'MessagesController@idFetchData');
    Route::post('/shared', 'MessagesController@sharedPhotos')->name('shared');
    Route::post('/fetchMessages', 'MessagesController@fetch')->name('fetch.messages');
    Route::post('/sendMessage', 'MessagesController@send')->name('send.message');
    Route::post('/updateContacts', 'MessagesController@updateContactItem')->name('contacts.update');
    Route::post('/makeSeen', 'MessagesController@seen')->name('messages.seen');
    Route::get('/getContacts', 'MessagesController@getContacts')->name('contacts.get');
    Route::get('/chat', 'MessagesController@index');
    Route::post('/chat/auth', 'MessagesController@pusherAuth')->name('pusher.auth');
    Route::post('/setActiveStatus', 'MessagesController@setActiveStatus')->name('activeStatus.set');
    Route::post('/updateSettings', 'MessagesController@updateSettings')->name('avatar.update');
    Route::post('/star', 'MessagesController@favorite')->name('star');
    Route::post('/deleteConversation', 'MessagesController@deleteConversation')->name('conversation.delete');
    Route::get('/download/{fileName}', 'MessagesController@download');
    Route::get('/chat/{id}', 'MessagesController@index')->name('user');

    //TODO: need to work on group chat functionality
    Route::get('/group/{id}', 'MessagesController@index')->name('group');

    //Tariff Label
    Route::get('tariff-labels', 'TariffLabelController@index');
    Route::post('tariff-labels', 'TariffLabelController@create');
    Route::get('/tariff-label/{id}', 'TariffLabelController@show');
    Route::post('/tariff-label/{id}', 'TariffLabelController@update');
    Route::get('/delete-tariff-label/{id}', 'TariffLabelController@delete');

    //Tariff Label Values
    Route::get('tariff-label-values', 'TariffLabelValuesController@index');
    Route::get('tariff-label-value', 'TariffLabelValuesController@create');
    Route::post('tariff-label-value', 'TariffLabelValuesController@addNew');
    Route::get('/tariff-label-value/{id}', 'TariffLabelValuesController@show');
    Route::post('/tariff-label-value/{id}', 'TariffLabelValuesController@update');
    Route::get('/delete-tariff-label-value/{id}', 'TariffLabelValuesController@delete');

    //Allowed IPs
    Route::get('allowed-ips', 'AllowedIpController@index');
    Route::post('allowed-ips', 'AllowedIpController@create');
    Route::get('/allowed-ip/{id}', 'AllowedIpController@show');
    Route::post('/allowed-ip/{id}', 'AllowedIpController@update');
    Route::get('/delete-allowed-ip/{id}', 'AllowedIpController@delete');

    //voip configuration
    Route::get('voip-configurations', 'VoipConfigurationController@index');
    Route::get('voip-configuration', 'VoipConfigurationController@create');
    Route::post('voip-configuration', 'VoipConfigurationController@addNew');
    Route::get('/voip-configuration/{id}', 'VoipConfigurationController@show');
    Route::post('/voip-configuration/{id}', 'VoipConfigurationController@update');
    Route::get('/delete-voip-configuration/{id}', 'VoipConfigurationController@delete');
    
    //Custom Field Label
    Route::get('custom-field-labels', 'CustomFieldLabelController@index');
    Route::post('custom-field-labels', 'CustomFieldLabelController@create');
    Route::get('/custom-field-label/{id}', 'CustomFieldLabelController@show');
    Route::post('/custom-field-label/{id}', 'CustomFieldLabelController@update');
    Route::get('/delete-custom-field-label/{id}', 'CustomFieldLabelController@delete');


    //Custom Field Label Values
    Route::get('custom-fields-values', 'CustomFieldLabelsValuesController@index');
    Route::post('custom-fields-values', 'CustomFieldLabelsValuesController@create');
    Route::get('/custom-field-value/{id}', 'CustomFieldLabelsValuesController@show');
    Route::post('/custom-field-value/{id}', 'CustomFieldLabelsValuesController@update');
    Route::get('/getCustomFieldValue/{id}', 'CustomFieldLabelsValuesController@getCustomFieldValue');
    Route::get('/delete-custom-field-value/{id}', 'CustomFieldLabelsValuesController@delete');

    //cli report

    Route::get('cli-report', 'CliReportController@index');
    Route::post('cli-report', 'CliReportController@index');

    Route::get('cli-report-manually/{phone_number}', 'CliReportController@callManually');
    Route::get('cli-report-manually-cnam/{phone_number}/{did_value}', 'CliReportController@callManuallyCNAM');

    Route::get('find-cli-report/{phone_number}', 'CliReportController@findCliReport');

    Route::get('/cli-report/fetch_data', 'CliReportController@fetch_data');




});

Route::get('/voice-audio', 'GoogleClientController@voiceAudio');

Route::any('receiver-fax', 'ApiFaxController@receiverFax');
Route::any('receiver-fax-ring', 'ApiFaxController@receiverFaxRing');
Route::post('start-dialing/upload', 'MailController@upload')->name('start-dialing.upload');

//voicemail api receiveing from external
Route::get('voice-mail-receiveing', 'ApiSmsController@voiceMailReceiver');

Route::get('extension-live-call-status', 'ApiSmsController@gextensionLiveCallStatus');


//conferenceing

Route::get('live-conference', 'LiveConferenceController@index');
Route::get('recording-conference', 'RecordingConferenceController@index');

//Lead Source Config
Route::get('lead-source-configs', 'LeadSourceConfigController@index');
Route::get('lead-source-config', 'LeadSourceConfigController@showNew');
Route::post('lead-source-config', 'LeadSourceConfigController@addNew');
Route::get('getLeadHeader/{list_id}', 'LeadSourceConfigController@getLeadHeader');
Route::get('deleteLeadConfig/{api_key}', 'LeadSourceConfigController@deleteLeadConfig');

//sms api receiveing from external
Route::get('insertLeadSource', 'LeadSourceConfigController@insertLeadSource');

//add did
Route::get('add-did', 'ApiDidController@addDid');
Route::get('findDefaultDid/{default_did}', 'ApiDidController@findDefaultDid');

Route::post('call-lead', "ApiListController@callLead");
Route::post('live-call-activity', "ApiListController@liveCallActivity");

//forgot password
Route::get('send-email-to-forgot-password', 'ApiUserController@sendEmailToForgotPasswor');
Route::get('forgot-password/{token}', 'ForgotPasswordController@index');
Route::post('forgot-password/{token}', 'ForgotPasswordController@changePassword');

Route::post('inbound-call-popup', 'InboundCallPopupController@index');
Route::post('forgot-password', 'ApiUserController@forgotPassword');

Route::get('verify-token/{token}', 'ApiUserController@verifyToken')->name('verify-token');
Route::post('resetPasswordUser', 'ApiUserController@resetPasswordUser');
Route::post('forgot-password-mobile', 'ApiUserController@forgotPasswordMobile');
Route::get('verify-token-mobile/{otp_id}', 'ApiUserController@verifyTokenMobile');

Route::post('verify-token-mobile/{otp_id}', 'ApiUserController@verifyTokenMobile');
Route::post('resetPasswordUserMobile', 'ApiUserController@resetPasswordUserMobile');
Route::post('resend-otp', 'ApiUserController@resendOtp');
Route::post('verify-token-mobile/{otp_id}', 'ApiUserController@verifyTokenMobile');



//voice templete

Route::get('voice-templete', 'VoiceTempleteController@getVoiceTemplete');
Route::get('/add-voice-templete', 'VoiceTempleteController@storeVoiceTemplete');
Route::post('/add-voice-templete', 'VoiceTempleteController@storeVoiceTemplete');
Route::post('/editVoiceTemplete/{id}', 'VoiceTempleteController@storeVoiceTemplete');
Route::get('/editVoiceTemplete/{id}', 'VoiceTempleteController@editVoiceTemplete');
Route::get('/deleteVoiceTemplate/{id}/{status}', 'VoiceTempleteController@deleteVoiceTemplate');
Route::post('/voice-template-delete/{id}', 'VoiceTempleteController@delete');

//count list

Route::get('count-list', 'DialerAllCountController@list');
Route::post('count-list', 'DialerAllCountController@list');

#hubspot

Route::get('/campaign/list/contact/{list_id}', 'HubspotController@getContactInAList');









