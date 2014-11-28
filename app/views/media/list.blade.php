@extends('layouts.master')

@section('content')

<div class="span8  center-block">
    <?php echo $page_links; ?>
</div>

<div class="row">

<?php

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$thumb_folder =  '/' . Config::get('app.thumb_folder');

foreach ($media_list as $media)
{
	$id = $media->id;
	$thumb_name = $thumb_folder . $id . '.jpg';

	echo "
	<div class=\"col-xs-6 col-md-3\">
		<a href=/watch/$id/media-" . $page . " class=thumbnail  data-placement=\"bottom\" title=\"" . Tags::get_keywords($media->description,50) . "\">
			<img src=$thumb_name>
		</a>
	</div>
	";
}

?>

</div>

<div class="span12">
    <?php echo $page_links; ?>
</div>

<script>
    $(document).ready(function(){
        $('a.thumbnail').tooltip();
    });
</script>

@stop
