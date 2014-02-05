<?php

if (!empty($media_list)){
	echo "<style>
	div#search_box {
		margin:1em auto;
	}
	</style>";
}

?>
		<div id="search_box">
			<form method="post">
				<input type="text" name="search_string" size="50" value="<?php echo Cookie::get('search_string'); ?>" />

                <select name="order_by">
                    <option value="">DEFAULT</option>
                    <option <?php if (isset($_POST['order_by']) && $_POST['order_by'] == 'likes') echo 'selected'; ?> value="likes">LIKES</option>
                    <option <?php if (isset($_POST['order_by']) && $_POST['order_by'] == 'random') echo 'selected'; ?> value="random">RANDOM</option>
                </select>

				<select name="search_action">
					<option value="" selected>DISPLAY</option>
					<option value="add">ADD</option>
					<option value="append">APPEND</option>
				</select>
				<input type="submit" name="submit" value="Search" class="btn-success" />
			</form>
		</div>

<?php

$thumb_folder =  Config::get('app.thumb_folder');

$have_result = 0;

foreach ($media_list as $row)
{
	$have_result++;
	$id = $row->id;
	$thumb_name = $thumb_folder . $id . '.jpg';

	echo "
		<div class=\"plitem\">
			<a href=/watch/$id/search-1 class=video_player>
				<img src=$thumb_name width=384 height=216>
			</a>
			<div class=pitem_txt>" .  Tags::get_keywords($row->description, 50) . "</div>
		</div>

	";

	if ($have_result > 60) break;
}


if ($is_search_result == 1 && $have_result == 0)
{
	echo '<p>No search result found</p>';
}

?>