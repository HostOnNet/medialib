@extends('layouts.master')

@section('content')

<div class="container">
    <div class="page-header">
        <h1>Edit Playlist Seed - {{ $playListSeed->name }}</h1>
    </div>

    <form class="form-horizontal" action="" method="post">

        <div class="form-group">
            <div class="col-md-12">
                <textarea name="seed" id="seed" class="form-control" rows="20">{{ $playListSeed->seed }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <button class="btn btn-block btn-success btn-lg" class="form-control">Save</button>
            </div>
        </div>

    </form>
</div>

@stop
