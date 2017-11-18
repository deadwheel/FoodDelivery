@extends('layouts.admin')

@section('content')
<div class="container">
    <form method="POST" action={{action('admin\Roles@update', $role['id'])}}>
        {{csrf_field()}}
        <input name="_method" type="hidden" value="PATCH">
        <div class="form-group row">
            <label for="input_display" class="col-sm-2 col-form-label">Display name</label>
            <div class="col-sm-10">
                <input type="text" name="display_name" class="form-control" id="input_display" value="{{ $role['display_name'] }}" placeholder="Display name">
            </div>
        </div>
        <div class="form-group row">
            <label for="input_desc" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input type="text" name="description" class="form-control" id="input_desc" value="{{ $role['description'] }}" placeholder="Description">
            </div>
        </div>
        <div class="form-group row">
            <button type="submit" class="btn btn-success">Update Role</button>
        </div>
    </form>
    <a class="btn btn-small btn-primary" href="{{ URL::to('admin/roles/') }}">Back to Roles</a>
</div>
@endsection
