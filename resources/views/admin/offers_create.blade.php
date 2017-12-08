@extends('layouts.admin')

@section('content')
<div class="container">
    <form method="POST" action={{action('admin\Offers@store')}} enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} row">
            <label for="input_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" required name="name" class="form-control" id="input_name" value="{{ old('name') }}" placeholder="Name">

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row">
            <label for="input_display" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input type="text" required name="description" class="form-control" id="input_display" value="{{ old('description') }}" placeholder="Description">

                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row">
            <label for="input_desc" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-10">
                <input type="number" required step="any" name="price" value="{{ old('price') }}" class="form-control" id="input_desc" placeholder="Price">

                @if ($errors->has('price'))
                    <span class="help-block">
                        <strong>{{ $errors->first('price') }}</strong>
                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row">
            <label for="input_desc" class="col-sm-2 col-form-label">Image</label>
            <div class="col-sm-10">
                <input type="file" style="height: auto;" name="offer" value="{{ old('offer') }}" class="form-control" id="input_offer">

                @if ($errors->has('offer'))
                    <span class="help-block">
                        <strong>{{ $errors->first('offer') }}</strong>
                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row">
            <button type="submit" class="btn btn-success">Create Offer</button>
        </div>
    </form>
    <a class="btn btn-small btn-primary" href="{{ URL::to('admin/offers/') }}">Back to Offers</a>
</div>
@endsection
