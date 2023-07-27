@extends('layouts.app')
@section('title', 'Show History')
@section('content')


<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <b>Show History</b>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">History</li>
      <li class="active">List</li>
    </ol>
  </section>

  <section class="content-header">
    <div class="text-right mt-5 mb-3">
      <a href="{{ url('/show-upload-history') }}" class="btn btn-sm btn-primary">Back</a>
    </div>
  </section>

  <!-- Search Section -->
  <section class="content"style="margin-bottom:-170px;">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <form method="GET" action="">
              <div class="text-left"style="margin-top:10px;">
              <div class="row col-md-12">
              <div class="form-group col-md-3">
                <label>Start date:</label>
                <input type="date" name="start_date" id="search" placeholder="" value="{{$start_date}}">
             </div>
             <div class="form-group col-md-3">
                <label style="margin-left:10px;">End date:</label>
                <input type="date" name="end_date" id="search" placeholder="" value="{{$end_date}}">
            </div>

            
            <div class="form-group col-md-3">
                <label for="url_title" >Url title:</label>
                <select name="url_title" id="url_title" value="{{$url_title}}"style="width:130px;">
                  <option value="">All</option>
                  @foreach($uniqueUrlTitles as $urlTitle)
                  <option value="{{$urlTitle}}" @if($url_title == $urlTitle) selected @endif>{{$urlTitle}}</option>
                  @endforeach
                </select>
              </div>
            
           
          </div>
        </div>
   
      </div>
      <div class="text-right"style="margin:10px;">
      <button style="margin-bottom:20px;" type="submit"class="btn btn-primary">Search</button>
     </div>
    </div>
    </div>

  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <?php
      $url_page = explode('?', str_replace('/', '', $_SERVER['REQUEST_URI']));
      $url = $url_page[0];

      if ($page == 1) {
        $currentPage = 1;
      } else {
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
      }

      $perPage = $show;
      $paginator = new Illuminate\Pagination\LengthAwarePaginator($show_history, $record_count, $perPage, $currentPage, ['path' => url($url)]);
      $record_count = $paginator->total();
      ?>

      <div class="col-xs-12">
        <div class="box">
          <div class="box-body">
            <b>Total Rows: <?= $record_count ?></b><br><br>
            <label for="show">Show:</label>
              <select name="show" onchange="this.form.submit()">
                <option value="10" {{ request('show') == 10 ? 'selected' : '' }} selected>10</option>
                <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
              </select>
              <label for="entries">entries</label>
            </form>
            <table id="example" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>User</th>
                  <th>File Name</th>
                  <th>Upload Url</th>
                  <th>Url Title</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach($show_history as $key => $label_data)
                <tr>
                  <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                  <td>{{$label_data->user_name}}</td>
                  <td>{{$label_data->file_name}}</td>
                  <td>{{$label_data->upload_url}}</td>
                  <td>{{$label_data->url_title}}</td>
                  @if(!empty($label_data->created_at))
                  <td>{{ date('d-m-Y', strtotime($label_data->created_at)) }}</td>
                  @else
                  <td></td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="text-right">
              {{$paginator->appends(Request::all())->links()}}
            </div>
          </div>
          <!-- /.box-body -->
          @if ($paginator->total() > 0)
          <div class="text-left mt-10" style=margin-left:10px;>
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries.
          </div>
          @endif
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
      <?php  ?>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection
