@extends('layouts.app')
@section('title', 'Edit Super Admin user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php //echo "<pre>";print_r($permission);die; ?>

     <section class="content-header">
                <h1>
                   <b>Edit Super Admin User</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Super Admins</li>
                    <li class="active">User</li>
                </ol>
        </section>


    <section class="content-header">

                   <div class="text-right mt-5 mb-3">

           <a href="{{ url('/super-admins') }}"  type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye fa-lg"></i> Show Super Admin</a>

      </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body">
                         <form method="post" name="userform" id="userform" >
                            @csrf

                            <div class="modal-body">
                    
                               <div class="col-md-6">
                            <div class="form-group">
                              <label>Clients Name <i data-toggle="tooltip" data-placement="right" title="Select the client show for this user " class="fa fa-info-circle" aria-hidden="true"></i> <span style="color:red;"></span></label>
                              <select class="select2" multiple="multiple" name="clients_name[]" autocomplete="off" data-placeholder="Select Group" style="width: 100%;">

                                @foreach($clients as $list)
                                     <option    @if(in_array($list->company_name, $mapping))  selected  @endif value="{{$list->id}}">{{$list->company_name}}</option>
                                @endforeach;
                              </select>
                            </div>
                            <!-- /.form-group -->

                            <!-- /.form-group -->
                          </div>


                    </div>
                   
                    <div class="form-group m-b-10">

                    <div style="clear: both"></div>
                </div>
                <div class="modal-footer">
                   <!--  <input type="hidden" name="extension" value="" id="extension-edit-id"/> -->
                   <a href="/super-admins" type="button" class="btn btn btn-danger waves-effect waves-light" data-dismiss="modal"><i class="fa fa-reply fa-lg"></i> Cancel</a>

                   <a onclick="window.location.reload();" type="button" class="btn btn btn-warning waves-effect waves-light" data-dismiss="modal"><i class="fa fa-refresh fa-lg"></i> Reset</a>

                    <button type="submit" name ="submit" value="add" class="btn btn btn-primary waves-effect waves-light"><i class="fa fa-check-square-o fa-lg"></i> Update</button>
                </div>
            </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->


            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>




@endsection
