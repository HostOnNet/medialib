<h1>Playlists</h1>

<a href=/playlist/0>watch</a><br />

<?php

$tag_id = Tags::getId('todo');

if ($tag_id)
{
    echo '<a href=/tag/' . $tag_id . '>todo</a><br />';
}

foreach($playlists as $playlist)
{
    echo "<a href=/playlist/" . $playlist->id . ">" . $playlist->name  . "</a> (" . $playlist->total . ") <a href=/playlist/empty/" . $playlist->id . ">x</a><br>";
}

?>

<h1>Build Playlists</h1>

<a href=/playlist_make/2 class="btn btn-success">Best Medias</a>
<a href=/playlist_make/1 class="btn btn-success">Best Tags</a>
<a href=/settings/todays  class="btn btn-success">Build Todays</a>
