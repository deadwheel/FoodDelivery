@extends('layouts.admin')

@section('content')
<div class="container">
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif
    <div class="row">
        <table class="table table-dark">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Roles</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $key => $value)

                @php

                $user_roles = App\User::find($value->id)->roles()->get();

                @endphp

                <tr>
                    <th scope="row">{{ $value->id }}</th>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->email }}</td>
                    <td>

                    @if (!empty($user_roles->first()))

                        @foreach($user_roles as $item)

                                {{ $item['display_name'] }} ,

                        @endforeach

                    @else

                        Standard User

                    @endif


                    </td>
                    <td>

                        <a class="btn btn-small btn-info" href="{{ URL::to('admin/users/edit/'.$value->id.'') }}">Edit</a>

                        <form class="form-inline" style="display: inline-block;" method="POST" action="{{action('admin\list_users@destroy', $value->id)}}">


                            {{csrf_field()}}
                            <input name="_method" type="hidden" value="DELETE">
                            <button class="btn btn-danger" type="submit">Delete</button>


                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
