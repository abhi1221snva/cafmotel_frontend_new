@extends('layouts.app')
@section('title', 'Components List')
@section('content')

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

      <section class="content-header">
                <h1>
                   <b>Components List</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active"> System Configuration</li>
                    
                    <li class="active">Components List</li>
                </ol>
        </section>
       

    <!-- Main content -->

    <section class="content">
        <div class="row">
           
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <ul data-widget="treeview" style="font-size: 20px;">
                            @if(!empty($modules))
                                @foreach($modules as $key => $module)
                                @if($module->parent_key =='' )
                            <li><i class="{{$module->logo}}"></i> &nbsp;<a target="_blank" href="/{{$module->url}}">{{$module->name}}</a></li>
                            @endif
                            <li class="treeview">
                                @if($module->url =='')
                                <a href="#">{{$module->name}}</a>
                                @endif
                                     @foreach($sub_menu as $key => $sub)
                                <ul class="treeview-menu">
                                    @if($sub->parent_key == $module->key)
                                    <li><i class="{{$sub->logo}}"></i> &nbsp;<a target="_blank" href="/{{$sub->url}}">{{$sub->name}}</a></li>
                                    @endif
                                    


                                </ul>
                                    @endforeach
                            </li>
                            @endforeach
                            @endif
                        </ul>
                        
                        
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>

@endsection