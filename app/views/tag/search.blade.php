<div class="row">
    <div class="col-sm-10">
        <form method="post" action="/tag/watch">
            <input type="hidden" name="tag_name" value="<?php echo $tag ?>" />
            <input type="submit" name="submit" value="WATCH" class="btn btn-sm btn-success"  />
            <select name="num_media">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="40">40</option>
                <option value="1000" selected>ALL</option>
            </select>
            <span class="badge badge-success"><?php echo $tag ?></span>
        </form>
    </div>
</div>

<br/>

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
			<a href=\"/watch/$media_id/tag-$tag_id\" class=\"thumbnail\" data-placement=\"bottom\" title=\"" . Tags::get_keywords($media->description,50) . "\">
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
