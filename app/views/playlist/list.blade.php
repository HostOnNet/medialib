<h1>Playlists</h1>



<?php

foreach($playlists as $playlist)
{
    echo "<div class=\"btn-group\">";
    echo "<a href=/playlist/" . $playlist->id . " class=\"btn btn-success\">" . $playlist->name  . " <span class=\"badge\">" . $playlist->total . "</span></a>";
    echo "<a href=/playlist/empty/" . $playlist->id . " class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></a>";
    echo "</div>";
    echo "<br> <br>";
}

?>

<h1>Build Playlists</h1>

<a href=/playlist_make/2 class="btn btn-success">Best Medias</a>
<a href=/playlist_make/1 class="btn btn-success">Best Tags</a>
<a href=/settings/todays  class="btn btn-success">Build Todays</a>

<?php

$tag_id = Tags::getId('todo');
if ($tag_id) {
    echo '<a href=/tag/' . $tag_id . ' class="btn btn-success">Todo</a>';
}

?>

