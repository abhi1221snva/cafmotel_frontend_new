@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

         <section class="content-header">
                <h1>
                   <b>Add Marketing Campaign</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Marketing Campaigns</li>
                    
                    <li class="active">Add Marketing Campaign</li>
                </ol>
        </section>
        <section class="content-header">
           
                   <div class="text-right mt-5 mb-3"> 
           
               <a href="{{ url('/marketing-campaigns') }}" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Show Marketing Campaign</a>
           </div>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <form method="post" name="userform" id="userform" action="">
                            @csrf
                            <div class="box-body">
                                <div class="modal-body">
                                    <div class="form-group m-b-10">

                                        <div class="col-md-12">
                                            <label>Title</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <input type="text" class="form-control" name="title" id="title">
                                            </div>
                                        </div>
                                        
                                      


                                        <div class="col-md-12">
                                            <label>Description</label>
                                            <div class="input-daterange input-group col-md-12">
                                                <textarea type="text" class="form-control" name="description" value=""></textarea>
                                            </div>
                                        </div>


                                    </div>


                                </div>
                                <div style="clear: both"></div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit" value="add" class="btn btn btn-primary waves-effect waves-light">Save</button>
                                </div>
                            </div><!-- /.box-body -->
                        </form>
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection



