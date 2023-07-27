@extends('layouts.app')
@section('title', 'Wallet Transactions')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height:500px !important">
        <!-- Content Header (Page header) -->
       <section class="content-header">
                <h1>
                   <b>Wallet Transactions</b>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Billing</li>
                    
                    <li class="active">Wallet Transactions</li>
                </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                @include('layouts.messaging')
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Amount</th>
                                    <th>Transaction Type</th>
                                    <th>Description</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($walletTransactions))
                                    @foreach($walletTransactions as $key => $walletTransaction)
                                        <tr>
                                            <td>{{$walletTransaction->id}}</td>
                                            <td>${{bcdiv($walletTransaction->amount,1,2)}}</td>
                                            <td>{{ucfirst($walletTransaction->transaction_type)}}</td>
                                            <td>{{$walletTransaction->description}}</td>
                                            <td>{{$walletTransaction->created_at}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
