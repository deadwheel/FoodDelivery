@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
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
                    <td>Role:</td>
                    <td>

                        @if (isset($roles[0]))
                            {{ $roles[0]['display_name'] }}
                        @else
                            Standard User
                        @endif

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
