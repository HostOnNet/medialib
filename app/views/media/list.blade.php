<?php

echo $page_links;

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

<br />

<div style="clear:both"></div>

<?php echo $page_links; ?>

<script language="JavaScript" type="text/javascript" src="/js/video_queue.js"></script>
