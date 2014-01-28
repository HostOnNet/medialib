<h1>Playlists</h1>

<a href=/tag/todo>todo</a> &nbsp; <a href=/playlist/0>watch</a><br>

<?php

foreach($playlists as $playlist)
{
    echo "<a href=/playlist/" . $playlist->id . ">" . $playlist->name  . "</a> (" . $playlist->total . ") <a href=/playlist/empty/" . $playlist->id . ">x</a><br>";
}

?>

<h1>Build Playlists</h1>

<a href=/playlist_make/2>Build Best Medias</a><br />
<a href=/playlist_make/1>Build Best Tags</a><br />
<a href=/settings/todays>Build Todays</a><br />
