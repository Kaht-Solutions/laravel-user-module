@extends('theme::layout_coreui.master')
@section('head')
@stop
@section('title') {{ trans('user::messages.roles') }}
@stop
@section('content')




<a class="btn btn-info" href="/admin/user/role/create">
    <i class="fa fa-plus-square">
    </i>
    {{ trans('user::messages.new') }}
</a>

<a href="/admin/user/generate" class="btn btn-info">
    {{trans('user::messages.generate_permission')}}
</a>

@if ($roles->isEmpty())
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
            </th>
            <th data-sortable="true">
                {{ trans('user::messages.display_name') }}
            </th>
            <th data-sortable="true">
                {{ trans('user::messages.content') }}
            </th>
            <th data-sortable="true">
                {{ trans('user::messages.created_at') }}
            </th>
            <th data-sortable="true">
                {{ trans('user::messages.updated_at') }}
            </th>
        </tr>
    </thead>
    <tbody class="filterlist">
        @foreach($roles as $role)
        <tr>
            <td>
                <a class="btn btn-info fa fa-edit" href="/admin/user/role/edit?id={{$role->id}}">
                </a>

                <a class="btn btn-danger fa fa-trash" data-toggle="confirmation"
                    href="/admin/user/role/destroy?id={{$role->id}}">
                </a>
            </td>


            <td>
                {{$role->display_name}}



                <button class="btn btn-info show_permissions">نمایش اجازه های دسترسی</button>
                <hr />
                <div class="block_permissions" style="display:none;">
                    <div class="checkbox checkbox-primary col-md-12 text-right">

                        <a class="submit_setpermission btn btn-primary ladda-button" data-size="l" data-style="zoom-out"
                            href="#" style="display:none;" role="{{$role->id}}">
                            <span class="ladda-label">
                                {{ trans('user::messages.edit') }}
                            </span>
                        </a>
                        {{-- <button class="btn btn-primary check_all">اضافه کردن تمام اجازه های دسترسی</button> --}}
                        <br />

                        @foreach($permissions as $permission)

                        <div class="form-group">
                            <label class="control-label" for="{{$role->email.$permission->id}}">
                                {{$permission->display_name}} <i class="fa fa-arrow-left"></i>
                            </label>
                            <input $role-="" class="form-control setpermission" id="{{$role->email.$permission->id}}"
                                type="checkbox" name="permission{{$role->id}}" value="{{$permission->id}}"
                                {{ $role->hasPermissionTo($permission->id) ? 'checked' : '' }} />
                        </div>

                        @endforeach
                        <a class="submit_setpermission btn btn-primary ladda-button" data-size="l" data-style="zoom-out"
                            href="#" style="display:none;" role="{{$role->id}}">
                            <span class="ladda-label">
                                {{ trans('user::messages.edit') }}
                            </span>
                        </a>
                    </div>

            </td>


            <td>
                {{$role->description}}
            </td>

            <td>
                {{\Morilog\Jalali\Jalalian::forge($role -> created_at)->format('%d %B, %Y')}}
            </td>
            <td>
                {{\Morilog\Jalali\Jalalian::forge($role -> updated_at)->format('%d %B, %Y')}}
            </td>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif



<script type="text/javascript">
    $( document ).ready(function() {

    $(".show_permissions").click(function(){
      
        $(this).siblings('.block_permissions').fadeToggle();
        
});
    });
</script>

<script type="text/javascript">
    $('.setpermission').change(function(e) {
		$(this).closest('td').find('.submit_setpermission').css('display', 'block');

	});
	$('.submit_setpermission').click(function(e) {
		var permission = $(this).closest('td').find('input:checkbox:checked').map(function() {
			return this.value;
		}).get();

        
        var role = $(this).attr('role');
        

		e.preventDefault();
		var l = Ladda.create(this);
		l.start();

		$.ajax({
			type : "POST",
			url : "{{Request::root()}}/admin/user/setpermission",
			data : {
				role : role,
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

</script>

@stop