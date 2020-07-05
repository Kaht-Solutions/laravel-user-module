@extends('theme::layout_adminlte.master')
@section('title')
{{ $title=trans('user::messages.user').' '.trans('user::messages.create') }}
@stop

@section('content')
<table class="table table-hover tablesorter table-striped table-borderd" id="myTable">
    <thead>
        <tr class="info">
            <th class="col-sm-4">
                {{ trans('user::messages.name') }}
            </th>
            <th>
                {{ trans('user::messages.created_at') }}
            </th>
            <th>
                {{ trans('user::messages.updated_at') }}
            </th>
            <th>
                {{ trans('user::messages.accesslevel') }}
            </th>
            <th>
                {{ trans('user::messages.edit') }}
            </th>
            <th>
                {{ trans('user::messages.delete') }}
            </th>
        </tr>
    </thead>
    <tbody class="filterlist">
        <tr>
            <td>
                {{$user->email}}
            </td>
            <td>
                {{\Morilog\Jalali\Jalalian::forge($user -> created_at)->format('%d %B, %Y')}}
            </td>
            <td>
                {{\Morilog\Jalali\Jalalian::forge($user -> updated_at)->format('%d %B, %Y')}}
            </td>
            <td>
                @foreach($roles as $role)
                <div class="checkbox checkbox-primary">
                    <input $user-="" class="styled setrole" id="{{$user->email.$role->id}}" type="checkbox" 
                    value="{{$role->id}}"
                        {{ $user->role_ch($role->name) ? 'checked' : '' }}>
                        <label for="{{$user->email.$role->id}}">
                            {{$role->display_name}}
                        </label>
                    </input>
                </div>
                @endforeach
                <a class="submit_setrole btn btn-primary ladda-button" data-size="l" data-style="zoom-out" href="#" style="display:none;" user="{{$user->id}}">
                    <span class="ladda-label">
                        {{ trans('user::messages.edit') }}
                    </span>
                </a>
            </td>
            <td>
                <a class="btn btn-info fa fa-edit" href="/admin/user/edit?id={{$user->id}}">
                </a>
            </td>
            <td>
                <a class="btn btn-danger fa fa-trash" data-toggle="confirmation" href="/admin/user/delete?id={{$user->id}}">
                </a>
            </td>
        </tr>
    </tbody>
</table>
<script type="text/javascript">
    $('.setrole').change(function(e) {
		$(this).closest('td').find('.submit_setrole').css('display', 'block');

	});
	$('.submit_setrole').click(function(e) {
		var role = $(this).closest('td').find('input:checkbox:checked').map(function() {
			return this.value;
		}).get();
		var user = $(this).attr('user');

		e.preventDefault();
		var l = Ladda.create(this);
		l.start();

		$.ajax({
			type : "POST",
			url : "{{Request::root()}}/admin/user/setrole",
			data : {
				user : user,
				role : role,
				_token : '{!! csrf_token() !!}'

			},
			success : function(data) {
				console.log(data);
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
<div id="ajax_response">
</div>
@stop
