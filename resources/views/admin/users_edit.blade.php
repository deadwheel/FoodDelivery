@extends('layouts.admin')

@section('content')
<div class="container">
        <table class="table table-dark">
            <tbody>
                <tr>
                    <td>ID:</td>
                    <td>{{ $user['id'] }}</td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td>{{ $user['name'] }}</td>
                </tr>
                <tr>
                    <td>E-mail:</td>
                    <td>{{ $user['email'] }}</td>
                </tr>
                <tr>
                    <td>Roles:</td>
                    <td>

                        @if (!empty($user->roles->first()))

                            @foreach($user->roles as $item)

                            {{ $item['display_name'] }} ,


                            @endforeach
                        @else
                            Standard User
                        @endif

                    </td>
                </tr>
            </tbody>
        </table>
    <form method="POST" action={{action('admin\list_users@update', $id)}}>
        {{csrf_field()}}
        <input name="_method" type="hidden" value="PATCH">
        <div class="form-group">
            <label for="exampleFormControlSelect2">Add Roles</label>
            <select multiple class="form-control" name="add_rols[]" id="exampleFormControlSelect2">
                @foreach($roles_ave as $item)

                    <option value="{{ $item['id'] }}">{{ $item['display_name'] }}</option>

                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlSelect3">Remove Roles</label>
            <select multiple class="form-control" name="delete_rols[]" id="exampleFormControlSelect3">
                @foreach($user->roles as $item)

                    <option value="{{ $item['id'] }}">{{ $item['display_name'] }}</option>


                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Update User</button>
        </div>
    </form>
    <a class="btn btn-small btn-primary" href="{{ URL::to('admin/users/') }}">Back to Users</a>
</div>
@endsection
