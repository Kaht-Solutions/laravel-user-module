@extends('theme::layout_coreui.master')
@section('head')
@stop
@section('title') {{ $title=trans('user::messages.users') }}
@stop
@section('content')



<a class="btn btn-info" href="/admin/user/create">
    <i class="fa fa-plus-square">
    </i>
    {{ trans('user::messages.new') }}
</a>

<a href="/admin/user/generate" class="btn btn-info">
    {{trans('user::messages.generate_permission')}}
    </a> 

    
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
            </td>

            <td>
                {{$user->mobile}}
            </td>


            <td>


                

                @foreach($roles as $role)
                <div class="checkbox checkbox-primary col-md-12 text-right">
                    <input $user-="" class="styled setrole" id="{{$user->email.$role->id}}" type="radio"
                        name="role{{$user->id}}" value="{{$role->id}}"
                        {{ $user->role_ch($role->name) ? 'checked' : '' }} />
                    <label for="{{$user->email.$role->id}}">
                        {{$role->display_name}}
                    </label>
                </div>
                @endforeach
                <a class="submit_setrole btn btn-primary ladda-button" data-size="l" data-style="zoom-out" href="#"
                    style="display:none;" user="{{$user->id}}">
                    <span class="ladda-label">
                        {{ trans('user::messages.edit') }}
                    </span>
                </a>

                <button class="btn btn-info show_permissions">نمایش اجازه های دسترسی</button>

                <div class="block_permissions" style="display:none;">
                    @foreach($permissions as $permission)
                    @if($user->hasPermissionTo($permission->id))
                    <div class="checkbox checkbox-primary col-md-12 text-right">
                        <span class="fal fa-check text-success"></span>
                        <label>
                            {{$permission->display_name}}
                        </label>

                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- @foreach($permissions as $permission)
                <div class="checkbox checkbox-primary col-md-12 text-right">
                    <input $user-="" class="styled setpermission" id="{{$user->email.$permission->id}}" type="checkbox"
                name="permission{{$user->id}}" value="{{$permission->id}}"
                {{ $user->hasPermissionTo($permission->id) ? 'checked' : '' }} />
                <label for="{{$user->email.$permission->id}}">
                    {{$permission->display_name}}
                </label>

                </div>
                @endforeach
                {{-- <a class="submit_setpermission btn btn-primary ladda-button" data-size="l" data-style="zoom-out"
                    href="#" style="display:none;" user="{{$user->id}}">
                <span class="ladda-label">
                    {{ trans('user::messages.edit') }}
                </span>
                </a> --}}
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
    $('.setrole').change(function(e) {
		$(this).closest('td').find('.submit_setrole').css('display', 'block');

	});
	$('.submit_setrole').click(function(e) {
		var role = $(this).closest('td').find('input:radio:checked').map(function() {
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


<script type="text/javascript">
    $( document ).ready(function() {

    $(".show_permissions").click(function(){
      
        $(this).siblings('.block_permissions').fadeToggle();
        
});
    });
</script>

{{-- <script type="text/javascript">
    $('.setpermission').change(function(e) {
		$(this).closest('td').find('.submit_setpermission').css('display', 'block');

	});
	$('.submit_setpermission').click(function(e) {
		var permission = $(this).closest('td').find('input:checkbox:checked').map(function() {
			return this.value;
		}).get();

        
        var user = $(this).attr('user');
        

		e.preventDefault();
		var l = Ladda.create(this);
		l.start();

		$.ajax({
			type : "POST",
			url : "{{Request::root()}}/admin/user/setpermission",
data : {
user : user,
permission : permission,
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

</script> --}}





@stop