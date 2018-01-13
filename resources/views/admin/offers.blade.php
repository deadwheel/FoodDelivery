@extends('layouts.admin')

@section('content')
<div class="container">
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif
    <div class="row">
        <a class="btn btn-small btn-info" href="{{ URL::to('admin/offers/create') }}">Create new Offer</a>
        <table class="table table-dark">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Image</th>
                <th scope="col">Price</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($offers as $key => $value)

                <tr>
                    <th scope="row">{{ $value->id }}</th>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->description }}</td>
                    <td>

                        @if(empty($value->image))

                            Empty

                        @else

                            <img src="{{ config('constants.image_host')}}{{ $value->image }}" style="height: 50px;" />

                        @endif

                    </td>
                    <td>{{ $value->price }}</td>
                    <td>

                        <a class="btn btn-small btn-info" href="{{ URL::to('admin/offers/'.$value->id.'/edit') }}">Edit</a>

                        <form class="form-inline" style="display: inline-block;" method="POST" action="{{action('admin\Offers@destroy', $value->id)}}">


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
