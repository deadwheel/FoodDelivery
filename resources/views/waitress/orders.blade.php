@extends('layouts.waitress')

@section('content')
<div class="container">
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif
    <div class="row">
        <form method="POST" action="{{ URL::to('waitress/orders/test') }}">
            {{csrf_field()}}
        <table class="table table-dark">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">State</th>
                <th scope="col">Driver</th>
                <th scope="col">Details</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $value)
                <tr>
                    <th scope="row"><input class="checkx" type="checkbox" name="ids[]" value="{{ $value->id }}"> {{ $value->id }}</th>
                    <td>{{ $value->state }}</td>
                    <td>


                        <div class="form-group">
                            <select class="form-control" name="drivers[{{ $value->id }}]" id="exampleFormControlSelect1">
                                        <option value=""></option>
                                @foreach($drivers as $key2 => $value2)

                                        <option @if ( !is_null($value->Rdriver) && $value2->id == $value->Rdriver->deliverer_id) selected="selected" @endif value="{{ $value2->id }}">{{ $value2->name }}</option>


                                @endforeach
                            </select>
                        </div>


                    </td>
                    <td>

                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample_{{$value->id}}" aria-expanded="false" aria-controls="collapseExample">
                            Details</button>


                    </td>
                </tr>
                <tr class="collapse" id="collapseExample_{{$value->id}}">
                    <td class="card card-body">
                        <ul class="list-group">
                            @foreach ($value->offers as $key4 => $value4)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $value4->name }}
                                <span class="badge badge-primary badge-pill">{{  $value4->pivot->quantity }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
            <button class="btn btn-danger" type="submit">Submit</button>
        </form>
    </div>
</div>
@endsection
