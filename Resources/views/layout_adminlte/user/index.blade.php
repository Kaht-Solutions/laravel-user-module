@extends('theme::layout_adminlte.master')
@section('title') {{ $title=trans('user::messages.users') }}
@stop
@section('content')

@include('theme::layout_adminlte.components.bootstrap_table')

<style>
    .btn {
        margin-top: 5px;
        margin-bottom: 5px;
    }
</style>
<a class="btn btn-info" href="/admin/user/create">
    <i class="fa fa-plus-square">
    </i>
    {{ trans('user::messages.new') }}
</a>
<br />
@foreach ($roles as $role)

<a class="btn btn-primary" href="/admin/user?type={{$role->name}}">

    {{$role->display_name}}
</a>

@endforeach

<a class="btn btn-primary" href="/admin/user">

    همه کاربران
</a>


@if ($users->isEmpty())
<h3>
    {{ trans('user::messages.empty') }}
</h3>
@else


<table data-toggle="table" data-pagination="true" data-search="true" data-use-row-attr-func="true"
    data-reorderable-rows="false" data-locale="fa-IR"
    class="table table-hover tablesorter table-striped table-borderd text-center">
    <thead>
        <tr class="info">
            <th data-sortable="true">
                <i class="fa fa-ellipsis-v"></i>
            <th data-sortable="true">
                {{ trans('user::messages.name').' '.trans('user::messages.family') }}
            </th>

            <th data-sortable="true">
                {{ trans('user::messages.mobile') }}
            </th>

            <th data-sortable="true">
                {{ trans('user::messages.roles') }}
            </th>



            <th data-sortable="true">
                {{ trans('user::messages.created_at') }}
            </th>

        </tr>
    </thead>
    <tbody class="filterlist">
        @foreach($users as $user)
        <tr>
            <td>


                <a data-toggle="tooltip" title="{{trans('user::messages.edit')}}" class="btn btn-info fa fa-edit"
                    href="/admin/user/edit?id={{$user->id}}">
                </a>

                <a data-toggle="confirmation" href="/admin/user/destroy?id={{$user->id}}">
                    <button class="btn btn-danger fa fa-trash" data-toggle="tooltip"
                        title="{{trans('user::messages.delete')}}"></button>
                </a>

            </td>
            <td>
                {{$user->name.' '.$user->family}}@if($user->remember_token)
                <i class="fa fa-circle" style="color:#8BC34A">
                </i> @else
                <i class="fa fa-circle" style="color:#F44336">
                </i> @endif
                @if(config('app.admin_log_enabled'))
                <a class="btn btn-warning" href="/admin/activity?causer_id={{$user->id}}"><i
                        class="fa fa-clock"></i></a>
                @endif
            </td>

            <td>
                {{$user->mobile}}
            </td>


            <td>




                @foreach($roles as $role)
                <div class="checkbox checkbox-primary col-md-12 text-right">
                    <input class="styled setrole" id="{{$user->email.$role->id}}" type="radio" name="role{{$user->id}}"
                        value="{{$role->id}}" {{ $user->role_ch($role->name) ? 'checked' : '' }} />
                    <label for="{{$user->email.$role->id}}">
                        {{$role->display_name}}
                    </label>
                </div>
                @endforeach

                <a class="submit_setrole btn btn-primary" href="#" user="{{$user->id}}">

                    {{ trans('user::messages.edit') }}

                </a>

                <button class="btn btn-info show_permissions">نمایش اجازه های دسترسی</button>

                <div class="block_permissions" style="display:none;">
                    @if(isset($user->roles[0]))
                    @foreach($user->roles[0]->permissions as $permission)
                    <div class="checkbox checkbox-primary col-md-12 text-right">
                        <span class="fal fa-check text-success"></span>
                        <label>
                            {{$permission->display_name}}
                        </label>

                    </div>
                    @endforeach
                    @endif
                </div>

            </td>



            <td>
                {{\Morilog\Jalali\Jalalian::forge($user -> created_at)->format('%d %B, %Y')}}
            </td>

        </tr>
        @endforeach
    </tbody>
</table>
@endif




<script type="text/javascript">
    $( document ).ready(function() {
    
	$('.submit_setrole').click(function(e) {
		var role = $(this).closest('td').find('input:radio:checked').map(function() {
			return this.value;
		}).get();

        
		var user = $(this).attr('user');

		

		$.ajax({
			type : "POST",
			url : "{{Request::root()}}/admin/user/setrole",
			data : {
				user : user,
				role : role,
				_token : '{!! csrf_token() !!}'

			},
			success : function(data) {
                Swal.fire({

type: 'success',
title: '{{trans("theme::routing.done")}}',
confirmButtonText:
'<i class="fal fa-check"></i>',
confirmButtonColor: '#3085d6',
timer: 150000
})
			},
			error : function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
				console.log("responseText: " + xhr.responseText);
				$('#ajax_response').html(xhr.responseText);
			}
		}, 'json').always(function() {
			
		});
		

	});


    

    $(".show_permissions").click(function(){
      
        $(this).siblings('.block_permissions').toggle();
        
});
    });
</script>



@stop