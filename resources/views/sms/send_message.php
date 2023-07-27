<!--<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal_open">Message
    popup</button>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<div class="modal fade" id="myModal_open" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
<!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span>

                </button>-->
                <h3 class=" panel-title" id="heading_sms">SMS </h3>

            </div>
            <div class="modal-body">
                <div role="tabpanel">
                    <!-- Nav tabs -->
<!--                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#uploadTab" aria-controls="uploadTab" role="tab"
                                data-toggle="tab">SMS</a>

                        </li>
                        <li role="presentation"><a href="#browseTab" aria-controls="browseTab" role="tab"
                                data-toggle="tab">Email</a>

                        </li>
                    </ul>-->
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="uploadTab">

                            <div class="middlePage">

                                <div class="panel panel-info">
<!--                                    <div class="panel-heading">
                                        <h3 class="panel-title">Send Sms</h3>
                                    </div>-->
                                    <div class=" sms_popup_send" role="alert"></div>
                                    <div class="panel-body">

                                        <div class="row" id="sms_box_1">

<!--                                            <div class="col-md-5">
                                                <h4>Preview</h4>
                                                <span id="preview_id" style=" word-break: break-word;"></span>
                                            </div>-->

                                            <div class="col-md-12" style="border-left:1px solid #ccc;height:250px">
                                                <form class="form-horizontal">

                                                    <fieldset>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class=""><b>Lead Contact No.</b></i></span>
                                                            <input id="send_phone" type="text" class="form-control"
                                                                name="send_phone" id="send_phone" readonly="true" value=""
                                                                placeholder="Enter sender conatct number">
                                                        </div>
                                                        <div class="spacing"></div>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i  class=""><b>Agent Contact No.</b></i></span>
                                                            <Select id="agent_phone" class="form-control"
                                                                name="agent_phone">
                                                                <option value="">--SELECT NUMBER--</option>
                                                            </Select>
                                                        </div>
                                                        <div class="spacing"></div>
                                                        <div class="input-group">
                                                            <span class="input-group-addon" style="min-width: 130px;"><i  class=""><b>Template</b></i></span>
                                                            <Select id="sms_template_list" class="form-control"
                                                                name="sms_template_list">
                                                                <option value="">--SELECT SMS TEMPLATE--</option>
                                                            </Select>
                                                        </div>
                                                        <div class="spacing"></div>
                                                        <div class="input-group">
                                                            <textarea name="preview_sms_id" maxlength="190" id="preview_sms_id" style="width:540px;height: 94px;"></textarea>
                                                        </div>
                                                       <!-- <div class="spacing"><br /></div>
                                                        <button  type="button" name="singlebutton"
                                                            class="btn btn-info btn-sm pull-right send_sms_button">Send</button>-->
                                                        <div class="count_sms_len" style=""><i id="count_word">192</i>Characters left </div>


                                                    </fieldset>
                                                </form>
                                            </div>

                                        </div>

										<div class="row" id="sms_box_2" style="display:none">

										 <div class="err_sms">You don't have any DID associated with your extension.</div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="browseTab"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="singlebutton" type="button" class="btn btn-primary save">Send Message</button>
            </div>
        </div>
    </div>
</div>
</div>
<style>
.spacing {
    padding-top: 7px;
    padding-bottom: 7px;
}

#count_word{
    margin-right: 6px;
    color: blue;
    font-weight: bold;
}
.count_sms_len{
    float: left;
    width: 164px;
    height: 25px;
    text-align: center;
    font-weight: bold;
    font-size: 15px;
    }

    #heading_sms{
        width: 100%;
    background-color: #3c8dbc;
    height: 31px;
    line-height: 28px;
    text-align: center;
    font-weight: bold;
    color: #ffffff;
    }
    .close{
        display:none;
    }

    .sms_popup_send{
    display: none;
    visibility: hidden;
    height: 30px;
    margin-bottom: 10px;
    text-align: center;
    line-height: 27px;
    font-weight: bold;
    }
	.err_sms{
		 width: 100%;
    text-align: center;
    font-size: 17px;
    font-weight: bold;
    color: red;
	}
</style>
