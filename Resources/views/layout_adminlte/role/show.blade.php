@extends('theme::layout_adminlte.master') 
@section('title')
{{ trans('user::messages.show').' '.trans('user::messages.role') }}
@stop
@section('page_header')

{{ trans('user::messages.show').' '.trans('user::messages.role') }}
<a href="/adminpanel/users" class="pull-right btn fa fa-reply back_button"> {{ trans('user::messages.list').' '.trans('user::messages.users') }} </a>

@stop
@section('content')
<table class="table table-hover tablesorter table-striped table-borderd" id="myTable">
	<thead>
		<tr class="info">
			<th class="col-sm-4">{{ trans('user::messages.name') }}</th>
			<th class="col-sm-4">{{ trans('user::messages.display_name') }}</th>

			<th>{{ trans('user::messages.created_at') }}</th>
			<th>{{ trans('user::messages.updated_at') }}</th>
			<th>{{ trans('user::messages.accesslevel') }}</th>

			<th>{{ trans('user::messages.edit') }}</th>
			<th>{{ trans('user::messages.delete') }}</th>
		</tr>
	</thead>
	<tbody class="filterlist">

		<tr>
			
<td>{{$role->name}}</td>
<td>{{$role->display_name}}</td>
			<td>{{$role->created_at}}</td>
			<td>{{$role->updated_at}}</td>
			<td> @foreach($permissions as $permission)
			<div class="checkbox checkbox-primary">

				<input id="{{$permission->name.$role->id}}" class="styled setrole" type="checkbox" value="{{$permission->id}}"  {{ $role->
				perm_ch($permission->id) ? 'checked' : '' }}> <label for="{{$permission->name.$role->id}}"> {{$permission->display_name}} </label>
			</div> @endforeach <a href="#" class="submit_setrole btn btn-primary ladda-button" data-style="expand-right" data-size="l" role="{{$role->id}}" style="display:none;"> <span class="ladda-label"> {{ trans('user::messages.edit') }} </span> </a></td>
			<td><a href="/admin/roles/edit?id={{$role->id}}" class='btn btn-info fa fa-edit'  > </a></td>
			<td><a href="/admin/roles/delete?id={{$role->id}}" class='btn btn-danger fa fa-trash' data-toggle='confirmation' > </a></td>

		</tr>

	</tbody>
</table>

<script type="text/javascript">
		$('.setrole').change(function(e) {
	$(this).closest('td').find('.submit_setrole').css('display', 'block');

	});
	$('.submit_setrole').click(function(e) {
	var perm = $(this).closest('td').find('input:checkbox:checked').map(function() {
	return this.value;
	}).get();
	var role = $(this).attr('role');

	e.preventDefault();
	var l = Ladda.create(this);
	l.start();

	$.ajax({
	type : "POST",
	url : "{{Request::root()}}/users/setperm",
	data : {
	role : role,
	perm : perm,
	_token : '{!! csrf_token() !!}'
		
		},
		success : function(data) {
console.log(role);
		},
		error : function(xhr, desc, err) {
		console.log(xhr);
		console.log("Details: " + desc + "\nError:" + err);
		console.log("responseText: " + xhr.responseText);
		$('#ajax_response').html(xhr.responseText);
		}
		}, 'json').always(function() {
		l.stop();
		});
		$(this).fadeOut("slow");

		});
</script>

<div id="ajax_response"></div>

@stop