@extends('layouts.master')

@section('content')

<div class="container">


<div class="page-header">
    <h1>Playlists</h1>
</div>


<a href=/playlist_seeds  class="btn btn-lg btn-success">Playlist Seeds</a>
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


<h2>Recent</h2>

<div class="row">
    <div class="col-md-12">

        @foreach ($playlists as $playlist)

        <div class="btn-group">
            <a href="/playlist/{{ $playlist->id }}" class="btn btn-info">
                {{ $playlist->name }} <span class="badge">{{ $playlist->total }}</span>
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
