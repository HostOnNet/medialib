<script type="text/javascript">var media_volume = {{ $media->volume }};</script>
<script language="JavaScript" type="text/javascript" src="/js/watch.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/textarea_caret.js"></script>

<p>
    <?php echo $media->file_name; ?> [<span id="media_id">{{ $media->id }}</span>]
    [Likes: {{ $media->likes }}]
    [Views: {{ $media->views }}]
    <?php if ($ref_page == 'random-search') echo ' ' . Playlist::getTotalVideos(Playlist::getId('WATCH')); ?>
    <span id="edit_button" class="btn btn-success btn-sm">Edit</span>
</p>


{{ Form::open(['method' => 'post', 'id' => 'media_edit', 'class' => 'form-horizontal', 'url' =>  '/media/save' ]) }}
{{ Form::hidden('media_id', $media->id) }}
{{ Form::hidden('ref_page', $ref_page) }}

<div id="watch_col_form">
    <textarea name="description" id="txt_description" rows="5" cols="70" disabled>{{ Tags::sort_bookmark($media->description) }}</textarea>
</div>

<div id="watch_col_player">
    <embed type="application/x-vlc-plugin" name="VLC" id="vlcp"
           autoplay="no"
           loop="no"
           toolbar="no"
           volume="{{ $media->volume }}"
           width="720"
           height="406"
           target="file:///{{ Config::get('app.video_folder') }}/tmp/live.m3u">

	<br />

	<a id=playbutton href="#">Play</a> &nbsp;
	<a id=pausebutton href="#">Pause</a> &nbsp;
	<a id=seekten href="#">Seek 10</a> &nbsp;
	<a id=seek_ten href="#">Seek -10</a> &nbsp;
	<a id=thumb_link href="#">Thumb</a> &nbsp;

	<div id="watch_time">
        <div id="currentTime">00:00:00.000</div> &nbsp; &nbsp;
        <div id="wt_2"><?php echo "$media->time_start_hms - $media->time_end_hms"; ?>  &nbsp; {{ $last_viewed }}</div>
        <div id="skip_time_remaining"></div>
        <div id="skip_time_more"></div>
    </div>

    <div>{{ Tags::get_bookmarks($media->description, $media->id) }}</div>

    <div id="watch_controls">
        <div class="form-inline">
            <input type="number" id="volume_input" name="volume" value="{{ $media->volume }}" min=20 max=200 class="form-control" style="width:90px">
            <input type="submit" name="submit" value="Next >>" class="btn btn-default">
            {{ $videos_in_playlist }}
        </div>
    </div>

</div>

{{ Form::close() }}

<script type="text/javascript">
jQuery(function () {
	var ac_tags;
    ac_tags = $('#txt_description').autocomplete({
        width: 150,
        delimiter: /(,|;|=)\s*/,
	    serviceUrl: '/ajax_tag_suggest'
    });
});
</script>


<?php

$auto_play_time_hms = Medias::isAutoPlay($media, $ref_page);

if ($auto_play_time_hms === false) {
    $time_start_ms = 0;
    $time_end_ms = 0;
} else {
    $time_start_ms = Time::hmsms2ms($auto_play_time_hms);
    $time_end_ms = $time_start_ms + (Settings::get('auto_forward_duration') * 1000);
}

if (! Settings::get('auto_forward')) {
    $time_end_ms = 0;
}

?>

@if ($time_start_ms > 10)
<script>
    jQuery(function () {
        var gotEvent = false;
        player.playlist.play();
        $("#playbutton").html('Stop');

        function seekOnStart(event) {
            gotEvent = true;
            setTimeout(function() { player.input.time = {{ $time_start_ms }};  }, 400);
        }

        player.addEventListener('MediaPlayerOpening', seekOnStart, false);

        // check if we got event, if not reload
        setTimeout(function() {  if (gotEvent==false) location.reload(); }, 200);

        // highlight current auto play time.

        var description = "{{ $media->description }}";
        var current_playlist_tag_time = "{{ $auto_play_time_hms  }}";
        var bookmarks = description.split("|");
        var bookmark = '';
        var num_bookmakrs = bookmarks.length;

        for (var i = 0; i < num_bookmakrs; i++) {
            bookmark = bookmarks[i];
            var bookmark_parts = bookmark.split("=");
            var bookmark_time = $.trim(bookmark_parts[0]);
            if (bookmark_time == current_playlist_tag_time) {
                $("a[alt='" + bookmark_time + "']").addClass("current_playlist_tag");
            }
        }

    });
</script>
@endif

@if ($time_end_ms > 0)
<script>
end_time = {{ $time_end_ms }};
$('#skip_time_more').html('more');
$('#skip_time_more').click(function() { end_time = end_time + 30000; });
</script>
@endif
