@extends('layouts.master')

@section('content')


<div class="row">
    <?php

    $thumb_folder = Config::get('app.thumb_folder');

    foreach ($medias as $media) {
        $media_id = $media->id;
        $description = $media->description;
        $playlist_position = $media->pm_id;

        $thumb_uri =  '/' . $thumb_folder . $media_id . '.jpg';

        echo "
		<div class=\"col-xs-6 col-md-3\">
			<a href=\"/watch/$media_id/browse-{$playlist_id}x$playlist_position\" class=\"thumbnail\" data-placement=\"bottom\" title=\"" . Tags::get_keywords($media->description,50) . "\">
				<img src=$thumb_uri>
			</a>
        </div>";
    }
    ?>

</div>

<script>
    $(document).ready(function(){
        $('a.thumbnail').tooltip();
    });
</script>

@stop
