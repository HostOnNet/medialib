@extends('layouts.master')

@section('content')

<h1>Add Records</h1>

<form action="/add/save" method="post">File Name : <br />
<input type="text" name="file_name" value=""  size="100" autocomplete="off" /> <br />
<br />
<input type="submit" name="submit" value="Add" class="btn-custom" />
</form>

@include('tools.links')

@stop
