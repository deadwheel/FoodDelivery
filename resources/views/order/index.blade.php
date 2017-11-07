<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<form method="post" action="">
<table class='table table-striped table-bordered'>
  <thead>
      <tr>
        <th>Użytkownik</th>
        <th>Dostawca:</th>
        <th>State</th>
        <th>Lokalizacja</th>
       </tr>
  </thead>
  <tbody>
  
    @foreach($orders as $row)
	
      <tr>
	  
        <td>{{$row->user_id}}</td>
			 
        <td>
			<select>
					<option value="{{$row->deliverer_id}}">{{$row->deliverer_id}}</option>
			</select>
		
		</td>
        <td>{{$row->state}}</td>
		<td>{{$row->location}}</td>
        <td>
		<!-- To make sure we have read access, wee need to validate the privilege -->
          @if(CRUDBooster::isUpdate() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("edit/$row->id")}}'>Edit</a>
          @endif
          
          @if(CRUDBooster::isDelete() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("delete/$row->id")}}'>Delete</a>
          @endif
        </td>
       </tr>
    @endforeach
  </tbody>
</table>
</form>
<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>
@endsection