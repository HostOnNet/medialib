<h1>Tag Cloud</h1>

<div id="tagcloud">
<?php

foreach ($tags as $tag)
{
	$tag_this = $tag->tag;

    if (strpos($tag_this, ']') !== false)
    {
        continue;
    }

	$tag_count_this = $tag->tag_count;
    $percent = ($tag_count_this / $max_tag_count) * 10;
	echo '<a href="/tag/' . $tag->id . '" rel="' . $percent . '">' . $tag_this . '</a>  &nbsp; ';
}

?>
</div>


<script src="/js/jquery.tagcloud.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">

$.fn.tagcloud.defaults = {
  size: {start: 14, end: 30, unit: 'pt'},
  color: {start: '#0000ff', end: '#ff0000'}
};

$(function () {
  $('#tagcloud a').tagcloud();
});

</script>

<style type="text/css">

</style>
