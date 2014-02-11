<div class="span8  center-block">
    <?php echo $page_links; ?>
</div>

<div class="span12">

<?php

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$thumb_folder =  '/' . Config::get('app.thumb_folder');

foreach ($media_list as $media)
{
	$id = $media->id;
	$thumb_name = $thumb_folder . $id . '.jpg';

	echo "
	<div class=\"plitem\">
		<a href=/watch/$id/media-" . $page . " class=video_player>
			<img src=$thumb_name width=384 height=216>
		</a>
		<div class=pitem_txt>" .  Tags::get_keywords($media->description,50) . "</div>
	</div>
	";
}

?>

</div>

<div class="span12">
    <?php echo $page_links; ?>
</div>
