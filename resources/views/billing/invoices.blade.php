@extends('layouts.app')
@section('title', 'Invoices')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
         <section class="content-header">
                <h1>
                   <b>Invoices</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Billing</li>
                    
                    <li class="active">Invoices</li>
                </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('layouts.messaging')
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="user_packages" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subscriptions</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Placed On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $key => $order)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$order->subscriptions}}</td>
                                        <td>${{$order->gross_amount}}</td>
                                        <td>{{ucfirst($order->status)}}</td>
                                        <td>{{\Carbon\Carbon::parse($order->created_at)->format('dS M Y')}}</td>
                                        <td>
                                            <a style="cursor:pointer;color:blue;" href="{{url('/invoice')}}/{{$order->id}}" title="View Orders Details" class=''  ><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="text/javascript">
            $('#user_packages').DataTable();
    </script>
@endsection
