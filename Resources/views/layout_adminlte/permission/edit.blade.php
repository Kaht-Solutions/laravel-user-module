@extends('theme::layout_adminlte.master') 
@section('title') {{ $title=trans('user::messages.edit').' '.trans('user::messages.permission') }} 
@stop 
@section('content')
@foreach($errors->all() as $error)
<div class="alert alert-warning">
    {{$error}}
    <a class="close" data-dismiss="alert" href="#">
        ×
    </a>
</div>
@endforeach {!! Form::open(['url'=>['/admin/user/permission/update'],'class'=>'form-horizontal']) !!}
<input type="hidden" name="id" value="{{$permission->id}}" />
<div class="form-group">
    {!! Form::label('',trans('user::messages.name'),['class'=>'col-md-2 control-label']) !!}
    <div class="col-md-8 col-lg-4">
        {!! Form::text('name',Request::old('name',$permission->name),array('class'=>'form-control')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('',trans('user::messages.display_name'),['class'=>'col-md-2 control-label']) !!}
    <div class="col-md-8 col-lg-4">
        {!! Form::text('display_name',Request::old('display_name',$permission->display_name),array('class'=>'form-control')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('',trans('user::messages.description'),['class'=>'col-md-2 control-label']) !!}
    <div class="col-md-8 col-lg-4">
        {!! Form::text('description',Request::old('description',$permission->description),array('class'=>'form-control')) !!}
    </div>
</div>

{!! Form::submit(trans('user::messages.create'),['class'=>'btn btn-primary text-right']) !!}
<button class="btn btn-danger text-right" type="reset">
    {{trans('user::messages.clear') }}
</button> {!! Form::close() !!} 
@stop