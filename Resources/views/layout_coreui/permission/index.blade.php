@extends('theme::layout_coreui.master') 
@section('head')
  
@stop 
@section('title') {{ trans('user::messages.permissions') }} 
@stop 
@section('content')




<a class="btn btn-info" href="/admin/user/permission/create">
<i class="fa fa-plus-square">
</i>
    {{ trans('user::messages.new') }}
</a> 

<a href="/admin/user/generate" class="btn btn-info">
{{trans('user::messages.generate_permission')}}
</a>

@if ($permissions->isEmpty())
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
                {{ trans('user::messages.name') }}
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
        @foreach($permissions as $permission)
        <tr>
            <td>
                <a class="btn btn-info fa fa-edit" href="/admin/user/permission/edit?id={{$permission->id}}">
                        </a>

                <a class="btn btn-danger fa fa-trash" data-toggle="confirmation" href="/admin/user/permission/destroy?id={{$permission->id}}">
                        </a>
            </td>
            <td>
                {{$permission->name}}
            </td>

            <td>
                {{$permission->display_name}}
            </td>

            <td>
                {{$permission->description}}
            </td>

            <td>
                {{jDate::forge($permission -> created_at)->format('%d %B, %Y')}}
            </td>
            <td>
                {{jDate::forge($permission -> updated_at)->format('%d %B, %Y')}}
            </td>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@stop