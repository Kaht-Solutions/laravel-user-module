@extends('theme::layout_adminlte.master')
@section('title') {{ $title=trans('user::messages.dashboard') }}
@stop
@section('content')

@include('theme::layout_adminlte.components.bootstrap_table')

<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    @if(Auth::user()->hasRole('admin'))
    <div class="row">
        <div class="col-lg-3 col-xs-12">
            <a class="" href="/admin/user?type=admin">
                <div class="small-box bg-aqua text-right">
                    <div class="inner">
                        <h3>


                            {{trans('user::messages.admins')}}
                        </h3>
                        <div class="icon">
                            <i class="fa fa-user-lock">
                            </i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-xs-12">
            <a class="" href="/admin/buymore/buy/buyer">
                <div class="small-box bg-red text-right">
                    <div class="inner">
                        <h3>
                            {{trans('user::messages.buyers')}}
                        </h3>
                        <div class="icon">
                            <i class="fa fa-user">
                            </i>
                        </div>

                    </div>




                </div>
            </a>
        </div>

        <div class="col-lg-3 col-xs-12">
            <a class="" href="/admin/buymore/sell/seller">
                <div class="small-box bg-blue text-right">
                    <div class="inner">
                        <h3>
                            {{trans('user::messages.sellers')}}
                        </h3>
                        <div class="icon">
                            <i class="fa fa-building">
                            </i>
                        </div>

                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-xs-12">
            <a class="" href="/admin/buymore/buy/exchanges">
                <div class="small-box bg-red text-right">
                    <div class="inner">
                        <h3>
                            {{trans('user::messages.exchanges')}}
                        </h3>
                        <div class="icon">
                            <i class="fa fa-handshake">
                            </i>
                        </div>

                    </div>
                </div>
            </a>
        </div>







    </div>
    @endif




</section>













@stop