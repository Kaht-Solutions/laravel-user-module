@extends('theme::layout_coreui.master') 
@section('title') {{ $title=trans('user::messages.create').' '.trans('user::messages.user') }} 
@stop

@section('content') 




{!! Form::open(['url'=>['admin/user/store'],'class'=>'form-horizontal']) !!}
<div class="form-group">
	{!! Form::label('',trans('user::messages.name'),['class'=>'col-md-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::text('name',Request::old('name'),array('class'=>'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('',trans('user::messages.family'),['class'=>'col-md-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::text('family',Request::old('family'),array('class'=>'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('',trans('user::messages.mobile'),['class'=>'col-md-2 control-label']) !!}
	<div class="col-sm-4">
		{!! Form::text('mobile',Request::old('mobile'),array('class'=>'form-control')) !!}
	</div>
</div>


<div class="form-group">
	{!! Form::label('',trans('user::messages.password'),['class'=>'col-md-2 control-label']) !!}
	<div class="col-sm-4">
		<input class="form-control" type="password" name="password">
	</div>
</div>

<div class="form-group">
	{!! Form::label('',trans('user::messages.role'),['class'=>'col-md-2 control-label']) !!}
	<div class="col-sm-4">
		<select class="form-control" name="role">
			<option value="0"></option>
@foreach($roles as $role)
<option value="{{$role->id}}">{{$role->display_name}}</option>

@endforeach
<select>
</div>
</div>

<div class="form-group">
	{!! Form::label('','استان مورد نظر',['class'=>'col-md-2 control-label']) !!}
	<div class="col-sm-4">
		<select name="province_id" class="form-control">
			<option value="">همه استان ها</option>
			@foreach ($provinces as $province)
		<option value="{{$province->id}}">{{$province->name}}</option>	
			@endforeach
		</select>
	</div>
</div>

{!! Form::submit(trans('user::messages.new'),['class'=>'btn btn-primary text-right']) !!}

<button type="reset" class="btn btn-danger text-right">
	{{trans('user::messages.clear') }}
</button>
{!! Form::close() !!}




@stop