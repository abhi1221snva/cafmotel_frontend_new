@extends('layouts.app')
@section('title', 'Flash Panel')
@section('content')
<div class="content-wrapper" style="min-height:500px !important">
        <section class="content-header">
            <h1 style="display:inline-block;">
                Flash Panel
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Flash Panel</li>
            </ol>
            <div class="question-setup">
                <label>
                    <input type="checkbox"  id="enable-opening-questions" @if($openingQuestionsStatus->status == 0) checked @endif> Start Questions Alert!
                </label>
            </div>
        </section>
        <section class="content">
            <div class="row row-gap">
                @include('layouts.messaging')
                <div class="flash-panel col-md-12">
                    @foreach($openingQuestionsData as $key => $openingQuestionData)
                        <div class="col-xs-12 col-md-6 col-sm-6 col-lg-3">
                            <a href="{{url($openingQuestionData->path)}}">
                                <div class="panel-box">
                                    <div class="count">{{$openingQuestionData->count}}</div>
                                    <div class="title">{{$openingQuestionData->title}} <i class="{{$openingQuestionData->icon}}"></i></div>
                                    @if($openingQuestionData->count == 0)
                                        <button class="not-configured">Not Setup Yet <i class="fa fa-arrow-circle-right"></i></button>
                                    @else
                                        <button class="configured">Configure <i class="fa fa-arrow-circle-right"></i></button>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
<style>
    .flash-panel {
        padding: 0 10px 50px;
    }
    .panel-box {
        margin: 1px;
        border: 1px solid #2220;
        padding: 20px 10px;
        background: #3c8dbc;
        min-height: 180px;
        max-height: 180px;
    }
    .panel-box:hover {
        background: #0c74b0;
    }
    .panel-box .not-configured{
        float: right;
        padding: 4px 10px;
        font-size: 22px;
        color: #111;
        background-color: #ffc107;
        border-radius: 25px;
        border: unset;
    }
    .panel-box .configured{
        float: right;
        padding: 4px 10px;
        font-size: 22px;
        color: #111;
        background-color: #fff;
        border-radius: 25px;
        border: unset;
    }
    .panel-box .count{
        font-size: 30px;
    }
    .panel-box .title{
        font-size: 23px;
        padding-bottom: 30px;
        min-height: 45px;
    }
    .panel-box .count,.panel-box .title{
        color: #fff;
    }
    .question-setup label{
        float: right;
    }
    input[type='checkbox'] {
        width:15px;
        height:15px;
        background:white;
        border-radius:5px;
    }
    input[type='checkbox']:checked {
        background: #111;
    }
    .row-gap .col-xs-12.col-md-6.col-sm-6.col-lg-3{
        padding-right:1px!important;
        padding-left:1px!important;
    }
</style>
<script>
    jQuery(document).ready(function () {
        $("#enable-opening-questions").change(function() {
            if(this.checked){
                $.ajax({
                    url: 'opening-questions/show/permanently',
                    type: 'GET',
                    success: function (response){
                        window.location.reload(1);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            } else {
                $.ajax({
                    url: 'opening-questions/hide/permanently',
                    type: 'GET',
                    success: function (response){
                        window.location.reload(1);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            }
        });
    });
</script>
@endsection
