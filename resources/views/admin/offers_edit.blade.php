@extends('layouts.admin')

@section('content')

    {{-- TODO Move styles to file --}}

<div class="container">
    <form method="POST" action={{action('admin\Offers@update', $offer['id'])}} enctype="multipart/form-data">
        {{csrf_field()}}
        <input name="_method" type="hidden" value="PATCH">
        <div class="form-group row">
            <label for="input_display" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" required name="name" class="form-control" id="input_display" value="{{ old('name',$offer['name']) }}" placeholder="Name">

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row">
            <label for="input_desc" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input type="text" required name="description" class="form-control" id="input_desc" value="{{ old('description',$offer['description']) }}" placeholder="Description">

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
                <input type="number" required step="any" min="0.01" name="price" class="form-control" id="input_desc" value="{{ old('price',$offer['price']) }}" placeholder="Price">

                @if ($errors->has('price'))
                    <span class="help-block">
                        <strong>{{ $errors->first('price') }}</strong>
                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row">
            <label for="input_desc" class="col-sm-2 col-form-label">Current Image</label>
            <div class="col-sm-10">
                @if(empty($offer['image']))

                    Empty

                @else

                    <img src="{{ config('constants.image_host')}}{{ $offer['image'] }}" style="height: 150px;" />

                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="input_desc" class="col-sm-2 col-form-label">Upload Image</label>
            <div class="col-sm-10">
                <input type="file" style="height: auto;" name="offer" class="form-control" id="input_desc">

                @if ($errors->has('offer'))
                    <span class="help-block">
                        <strong>{{ $errors->first('offer') }}</strong>
                    </span>
                @endif

            </div>
        </div>

        <div class="form-group row">
            <button type="submit" class="btn btn-success">Update Offer</button>
        </div>
    </form>
    <a class="btn btn-small btn-primary" href="{{ URL::to('admin/offers/') }}">Back to Offers</a>
</div>
@endsection
