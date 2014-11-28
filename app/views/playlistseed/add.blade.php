@extends('layouts.master')

@section('content')

<div class="container">

    <div class="page-header">
        <h1>Add New Playlist Seed</h1>
    </div>

    <form class="form-horizontal" action="" method="post">

        <div class="form-group">
            <label class="control-label col-md-2" for="seedName">Seed Name</label>
            <div class="col-md-8">
                <input type="text" name="seedName" id="seedName" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-4 col-md-offset-3">
                <button class="btn btn-block btn-success btn-lg" class="form-control">Save</button>
            </div>
        </div>

    </form>

</div>

@stop
