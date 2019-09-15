@extends('layouts.backend')
@section('title', 'Dashboard')
@section('content')
{{--    <div class="row row-cards-pf">--}}
{{--        <div class="row-cards-pf card-pf">--}}
{{--            <ol class="breadcrumb">--}}
{{--                <li class="active">--}}
{{--                    <span class="pficon pficon-home"></span>--}}
{{--                    <a href="{{url('home')}}">Dashboard</a>--}}
{{--                </li>--}}
{{--            </ol>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="row text-center">
        <div class="col-xs-12">
            <img width="150" src="{{ asset('images/logoyogya.png') }}" alt=" logo" style="margin-top: 30px"/>
            <h2><b>[Project Name]</b></h2>
            <p style="font-size: 1.2em;">Hi, <b><span class="text-capitalize">{{ Auth::user()->full_name }}</span></b>! Let's get started.</p><br>
        </div>
    </div>

    <div class="row row-cards-pf">
        <!-- Important:  if you need to nest additional .row within a .row.row-cards-pf, do *not* use .row-cards-pf on the nested .row  -->
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="card-pf card-pf-accented card-pf-view card-pf-view-select card-pf-view-single-select">
                <div class="card-pf-body">
                    <div class="card-pf-top-element">
                        <a href="{{url('/menu1')}}">
                            <img src="{{ asset('images/ex1.png') }}"
                                 class="img-responsive center-block" width="100">
                        </a>
                    </div>
                    <h2 class="card-pf-title text-center">
                        Menu #1
                    </h2>
                    <p class="card-pf-info text-center">
                        <strong>Lorem ipsum dolor sit amet, consectetur adipiscing.</strong>
                    </p>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="card-pf card-pf-accented card-pf-view card-pf-view-select card-pf-view-single-select">
                <div class="card-pf-body">
                    <div class="card-pf-top-element">
                        <a href="{{url('/menu2')}}">
                            <img src="{{ asset('images/ex2.png') }}"
                                 class="img-responsive center-block" width="100">
                        </a>
                    </div>
                    <h2 class="card-pf-title text-center">
                        Menu #2
                    </h2>
                    <p class="card-pf-info text-center">
                        <strong>Lorem ipsum dolor sit amet, consectetur adipiscing.</strong>
                    </p>
                </div>
            </div>

        </div>
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="card-pf card-pf-accented card-pf-view card-pf-view-select card-pf-view-single-select">
                <div class="card-pf-body">
                    <div class="card-pf-top-element">
                        <a href="{{url('/menu3')}}">
                            <img src="{{ asset('images/ex3.png') }}"
                                 class="img-responsive center-block" width="100">
                        </a>
                    </div>
                    <h2 class="card-pf-title text-center">
                        Menu #3
                    </h2>
                    <p class="card-pf-info text-center">
                        <strong>Lorem ipsum dolor sit amet, consectetur adipiscing.</strong>
                    </p>
                </div>
            </div>

        </div>
    </div><!-- /row -->
@endsection