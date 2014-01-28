<div>
<form method="post" action="/tag/watch">
	<input type="hidden" name="tag_name" value="<?php echo $tag ?>" />
	<input type="submit" name="submit" value="WATCH" class="btn-custom"  />
	<select name="order_by">
        <option value="tag_likes">Tag Likes</option>
        <option value="likes">Likes</option>
        <option value="random">RANDOM</option>
	</select>
    <select name="num_media">
        <option value="10">10</option>
        <option value="15" selected="selected">15</option>
        <option value="1000">ALL</option>
    </select>
</form>
</div>

<?php

$thumb_folder = Config::get('app.thumb_folder');

foreach ($media_list as $media)
{
    $media_id = $media->media_id;
    $description = $media->description;
	$file_name = $media->file_name;
	$thumb_uri =  '/' . $thumb_folder . $media_id . '.jpg';

	echo "
		<div class=\"plitem\">
			<a href=\"/watch/$media_id/tag-$tag\" class=video_player>
				<img src=$thumb_uri width=384 height=216>
			</a>
			<div> " . Tags::get_keywords($description,50) . "</div>
		</div>";
}
