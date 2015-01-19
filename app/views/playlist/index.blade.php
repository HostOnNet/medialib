@extends('layouts.master')

@section('content')

<div class="container">


<div class="page-header">
    <h1>Playlists</h1>
</div>

<a href="/playlist/0" class="btn btn-lg btn-success">Watch</a>
<a href=/playlist_make/2 class="btn btn-lg btn-success">Best Medias</a>
<a href=/playlist_make/1 class="btn btn-lg btn-success">Best Tags</a>
<?php

$tag_id = Tags::getId('todo');
if ($tag_id) {
    echo '<a href=/tag/' . $tag_id . ' class="btn btn-lg btn-success">Todo</a>';
}

?>

<br>

<br>


<div class="page-header">
    <h1>
        Playlist Seeds
        <a href="/playlist_seed_add" class="btn btn-success pull-right">New</a>
    </h1>
</div>

<div class="row">
    <div class="col-md-12">

        <table class="table table-bordered table-hover">
            <tr class="bg-info">
                <td>PlayList Seed</td>
                <td width="20%" class="text-center">Actions</td>
            </tr>

            @foreach ($seeds as $seed)

            <tr>
                <td><a href="/playlist_seed_generate/{{ $seed->id }}" class="btn btn-success btn-block">{{ $seed->name }}</a></td>
                <td class="text-center"><a href="/playlist_seed_edit/{{ $seed->id }}" class="btn btn-warning btn-block">Edit</a></td>
            </tr>

            @endforeach

        </table>

    </div>
</div>


<div class="page-header">
    <h1>Recent</h1>
</div>

<div class="row">
    <div class="col-md-12">

        @foreach ($playlists as $playlist)

        <div class="btn-group">
            <a href="/playlist/{{ $playlist->id }}" class="btn btn-info">
                {{ $playlist->name }} <span class="badge">{{ $playlist->total }}</span>
            </a>

            <a href="/playlist-browse/{{ $playlist->id }}" class="btn btn-info">
                B
            </a>
            <a href="/playlist/empty/{{ $playlist->id }}" class="btn btn-danger">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </a>
        </div> &nbsp;  &nbsp;

        @endforeach

    </div>
</div>

</div>

@stop
