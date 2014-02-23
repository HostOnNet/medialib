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

<div class="row">
<?php

$thumb_folder = Config::get('app.thumb_folder');

foreach ($media_list as $media)
{
    $media_id = $media->id;
    $description = $media->description;

	$thumb_uri =  '/' . $thumb_folder . $media_id . '.jpg';

	echo "
		<div class=\"col-xs-6 col-md-3\">
			<a href=\"/watch/$media_id/tag-$tag\" class=\"thumbnail\" data-placement=\"bottom\" title=\"" . Tags::get_keywords($media->description,50) . "\">
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