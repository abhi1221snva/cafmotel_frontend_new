<a href="{{ url('/send-fax') }}" class="btn btn-primary btn-block margin-bottom">Compose</a>

<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Folders</h3>

        <div class="box-tools">
            <button type="button"  class="btn btn-box-tool" data-widget="collapse">
            </button>
        </div>
    </div>

    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <li @if (Request::is('receive-fax'))  class="active"  @endif><a href="{{ url('/receive-fax') }}" ><i class="fa fa-inbox"></i> Inbox</a></li>
            <li @if (Request::is('fax-list'))  class="active"  @endif><a href="{{ url('/fax-list') }}"><i class="fa fa-envelope-o"></i> Sent</a></li>
            <!-- <li @if (Request::is('sending-failed-fax'))  class="active"  @endif><a href="{{ url('/sending-failed-fax') }}"><i class="fa fa-file-text-o"></i> Sending Failed </a></li>
 -->
        </ul>
    </div>
    <!-- /.box-body -->
</div>
<!-- /. box -->

<!-- /.box -->

