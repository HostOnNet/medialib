@extends('layouts.master')

@section('content')

<div class="page-header">
    <h1>Add Records</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <form action="/add/save" method="post"  class="form-horizontal">
            <div class="form-group">
                <label class="col-md-3" for="">File Name</label>
                <div class="col-md-4">
                    <input type="text" name="file_name" value="" autocomplete="off" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-7">
                    <input type="submit" name="submit" value="Add" class="btn btn-success btn-block">
                </div>
            </div>

        </form>
    </div>
</div>

@include('tools.links')

@stop
