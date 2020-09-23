@extends('theme::layout_coreui.master') 
@section('title')
{{ trans('user::messages.show').' '.trans('user::messages.permission') }}
@stop
@section('page_header')

{{ trans('user::messages.show').' '.trans('user::messages.permission') }}
<a href="/adminpanel/users" class="pull-right btn fa fa-reply back_button"> {{ trans('user::messages.list').' '.trans('user::messages.users') }} </a>

@stop
@section('content')
<table class="table table-hover tablesorter table-striped table-borderd" id="myTable">
	<thead>
		<tr class="info">
			<th class="col-sm-4">{{ trans('user::messages.name') }}</th>
			<th class="col-sm-4">{{ trans('user::messages.display_name') }}</th>

			<th data-sortable="true">{{ trans('user::messages.created_at') }}</th>
			<th data-sortable="true">{{ trans('user::messages.updated_at') }}</th>
			<th data-sortable="true">{{ trans('user::messages.accesslevel') }}</th>

			<th data-sortable="true">{{ trans('user::messages.edit') }}</th>
			<th data-sortable="true">{{ trans('user::messages.delete') }}</th>
		</tr>
	</thead>
	<tbody class="filterlist">

		<tr>
			
<td>{{$permission->name}}</td>
<td>{{$permission->display_name}}</td>
			<td>{{$permission->created_at}}</td>
			<td>{{$permission->updated_at}}</td>
			<td> @foreach($permissions as $permission)
			<div class="checkbox checkbox-primary">

				<input id="{{$permission->name.$permission->id}}" class="styled setpermission" type="checkbox" value="{{$permission->id}}"  {{ $permission->
				perm_ch($permission->id) ? 'checked' : '' }}> <label for="{{$permission->name.$permission->id}}"> {{$permission->display_name}} </label>
			</div> @endforeach <a href="#" class="submit_setpermission btn btn-primary ladda-button" data-style="expand-right" data-size="l" permission="{{$permission->id}}" style="display:none;"> <span class="ladda-label"> {{ trans('user::messages.edit') }} </span> </a></td>
			<td><a href="/admin/permissions/edit?id={{$permission->id}}" class='btn btn-info fa fa-edit'  > </a></td>
			<td><a href="/admin/permissions/delete?id={{$permission->id}}" class='btn btn-danger fa fa-trash' data-toggle='confirmation' > </a></td>

		</tr>

	</tbody>
</table>

<script type="text/javascript">
		$('.setpermission').change(function(e) {
	$(this).closest('td').find('.submit_setpermission').css('display', 'block');

	});
	$('.submit_setpermission').click(function(e) {
	var perm = $(this).closest('td').find('input:checkbox:checked').map(function() {
	return this.value;
	}).get();
	var permission = $(this).attr('permission');

	e.preventDefault();
	var l = Ladda.create(this);
	l.start();

	$.ajax({
	type : "POST",
	url : "{{Request::root()}}/users/setperm",
	data : {
	permission : permission,
	perm : perm,
	_token : '{!! csrf_token() !!}'
		
		},
		success : function(data) {
console.log(permission);
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