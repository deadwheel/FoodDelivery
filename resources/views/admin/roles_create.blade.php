@extends('layouts.admin')

@section('content')
<div class="container">
    <form method="POST" action={{action('admin\Roles@store')}}>
        {{csrf_field()}}
        <div class="form-group row">
            <label for="input_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" name="input_name" required class="form-control" id="input_name" placeholder="Name">
            </div>
        </div>
        <div class="form-group row">
            <label for="input_display" class="col-sm-2 col-form-label">Display name</label>
            <div class="col-sm-10">
                <input type="text" name="display_name" class="form-control" id="input_display" placeholder="Display name">
            </div>
        </div>
        <div class="form-group row">
            <label for="input_desc" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input type="text" name="description" class="form-control" id="input_desc" placeholder="Description">
            </div>
        </div>
        <div class="form-group row">
            <button type="submit" class="btn btn-success">Create Role</button>
        </div>
    </form>
    <a class="btn btn-small btn-primary" href="{{ URL::to('admin/roles/') }}">Back to Roles</a>
</div>
@endsection
