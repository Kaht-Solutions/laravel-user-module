@extends('theme::layout_adminlte.master')
@section('title')
{{ $title=trans('user::messages.edit').' '.trans('user::messages.user') }}
@stop


@section('content')



{!! Form::open(['url'=>['/admin/user/update'],'class'=>'form-horizontal ']) !!}
<input type="hidden" name="id" value="{{$user->id}}"/>
<div class="form-group">
    {!! Form::label('',trans('user::messages.name'),['class'=>'col-md-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('name',Request::old('name',$user->name),array('class'=>'form-control')) !!}
    </div>
</div>

<div class="form-group">
        {!! Form::label('',trans('user::messages.family'),['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-4">
            {!! Form::text('family',Request::old('family',$user->family),array('class'=>'form-control')) !!}
        </div>
    </div>

<div class="form-group">
    {!! Form::label('',trans('user::messages.mobile'),['class'=>'col-md-2 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::text('mobile',Request::old('name',$user->mobile),array('class'=>'form-control')) !!}
    </div>
</div>


<div class="form-group">
        {!! Form::label('',trans('user::messages.password'),['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-4">
            <input class="form-control" type="password" name="password">
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('','استان مورد نظر',['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-4">
            <select name="province_id" class="form-control">
                    <option value="">همه استان ها</option>
                @foreach ($provinces as $province)
            <option value="{{$province->id}}" @if($user->province_id==$province->id) selected @endif>{{$province->name}}</option>	
                @endforeach
            </select>
        </div>
    </div>

{!! Form::submit(trans('user::messages.edit'),['class'=>'btn btn-primary text-right']) !!}
<button class="btn btn-danger text-right" type="reset">
    {{trans('user::messages.clear') }}
</button>
{!! Form::close() !!}

@stop
