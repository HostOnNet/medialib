var player;
var end_time = 0;
var play_next_media= true;
var media_volume_set = false;

function hhmmssms2ms(time_hms_ms)
{
    var a = time_hms_ms.split(':');
    var ssDotms = a[2];
    ssDotms_parts = ssDotms.split('.');
    var hour_2_seconds =  (+a[0]) *3600;
    var minute_2_seconds =  (+a[1]) * 60;
    var total_seconds = hour_2_seconds + minute_2_seconds +  (+ ssDotms_parts[0] ) ;
    var total_milli_seconds = ( total_seconds * 1000 ) + (+ ssDotms_parts[1] );
    return total_milli_seconds;
}

function ms2hhmmssms(milli_seconds)
{
    var totalSec = milli_seconds/1000;
    hours = parseInt( totalSec / 3600 ) % 24;
    minutes = parseInt( totalSec / 60 ) % 60;
    seconds = parseInt(totalSec % 60);
    m_seconds = parseInt(milli_seconds % 1000);
    result = (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds) + "." + m_seconds;
    return result;
}

function ms2hhmmss(milli_seconds)
{
    var totalSec = milli_seconds/1000;
    hours = parseInt( totalSec / 3600 ) % 24;
    minutes = parseInt( totalSec / 60 ) % 60;
    seconds = parseInt(totalSec % 60);
    result = (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
    return result;
}

function mediaTimeUpdate(event)
{
    if (media_volume_set == false)
    {
        player.audio.volume = media_volume;
        media_volume_set = true;
        $('#volume_input').fadeOut(200).fadeIn(200);
    }

    $('#currentTime').html(ms2hhmmssms(event));

    if (end_time > 0 && play_next_media == true)
    {
        if (event > end_time)
        {
            play_next_media = false;
            $('#media_edit').submit();
        }
        else
        {
            $('#skip_time_remaining').html(ms2hhmmss(end_time - event));
        }
    }
}

function trim (str)
{
    return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
}

function vlc_seek(time_to_seek)
{
    if(player) {
        player.input.time = time_to_seek;
    } else {
        alert("can't find player");
    }
}

$(document).ready(function()
{

    player = document.getElementById("vlcp");

    $('#playbutton').click(function(event)
    {
        event.preventDefault();
        if(player)
		{
			if (player.input.state == 0 || player.input.state == 5 || player.input.state == 6)
			{
				player.playlist.play();
				$("#playbutton").html('Stop');
			}
			else if (player.input.state == 3)
			{
				player.playlist.stop();
				$("#playbutton").html('Play');
			}
			else
			{
				alert(player.input.state);
			}
        }
    });

    $('#seekten').click(function(event)
    {
        event.preventDefault();
        if(player)
        {
			vlc_seek(player.input.time + 10000);
        }
    });

    $('#seek_ten').click(function(event)
    {
        event.preventDefault();
        if(player)
        {
			vlc_seek(player.input.time - 10000);
        }
    });

    $('#pausebutton').click(function(event)
    {
        event.preventDefault();
        if(player)
        {
			if (player.input.state == 3)
			{
				$("#pausebutton").html('Resume');
			}
			else if (player.input.state == 4)
			{
				$("#pausebutton").html('Pause&nbsp;');
			}

        	player.playlist.togglePause();
        }
    });

    $('#button_next').click(function(event)
    {
        event.preventDefault();
        if(player)
        {
           player.playlist.next();
        }
    });

    $('#button_prev').click(function(event)
    {
        event.preventDefault();
        if(player)
        {
           player.playlist.prev();
        }
    });

    $('#like_button').click(function(event)
    {
        event.preventDefault();
		media_id = $(this).attr('alt');
		var ajax_url = "/ajax/like/" + media_id;

		 $.ajax({
			 type: "GET",
			 url: ajax_url,
			 dataType: 'html',
			 success: function(html){
				$('#like_button').text(html);
				//$('#like_button').delay(1000).fadeOut();
			 },
			 error: like_error
		 });

		 function like_error()
         {
			 alert('Ajax Error');
		 }

    });

    $('#media_info').click(function(event)
    {
        event.preventDefault();

        if ($('#media_info_display').is(":visible"))
        {
            $('#media_info_display').hide();
            $('#media_info').html("Info");
        }
        else
        {
            media_id = $(this).attr('alt');

            var ajax_url = "/media/info/" + media_id;

             $.ajax({
                 type: "GET",
                 url: ajax_url,
                 dataType: 'html',
                 success: function(html){
                    $('#media_info_display').html(html);
                    $('#media_info_display').show();
                 },
                 error: media_info_error
             });

             $('#media_info').html("Hide");
        }

        function media_info_error()
        {
             alert('Ajax Error');
         }


    });

    $('a.bookmark').click(function(event)
    {
        event.preventDefault();
        play_next_media = false;
        $('#skip_time_remaining').html('auto');
        $('#skip_time_more').hide();
		time_to_seek_hh_mm_ss = $(this).attr('alt');
		var time_to_seek = hhmmssms2ms(time_to_seek_hh_mm_ss);
        var cursor_to_index = $('#txt_description').val().search(time_to_seek_hh_mm_ss);
        highlight_bookmarks(time_to_seek_hh_mm_ss);

        if (cursor_to_index > 1)
        {
            cursor_to_index = cursor_to_index + time_to_seek_hh_mm_ss.length + 3;
            $('#txt_description').caretTo(cursor_to_index);
        }

        if (player.input.state == 0 || player.input.state == 5 || player.input.state == 6)
        {
            player.playlist.play();
            $("#playbutton").html('Stop');
            setTimeout(function() { vlc_seek(time_to_seek); }, 200);
        }
        else if (player.input.state == 4)
        {
            player.playlist.togglePause();
            $("#pausebutton").html('Pause&nbsp;');
            vlc_seek(time_to_seek);
        }
        else
        {
            vlc_seek(time_to_seek);
        }
    });

    $('img.fav').click(function(event)
    {
        event.preventDefault();
        time_start = $(this).attr('alt');
        var media_id = $('span#media_id').text();
        var like;

        if(event.ctrlKey)
        {
            like = -1;
            $(this).attr('src','/img/fav_rm.png');
        }
        else
        {
            like = 1;
            $(this).attr('src','/img/fav_ok.png');
        }

        var sUrl = '/ajax/media_tag_time_like';
        var postData = 'media_id=' + media_id + '&time_start=' + time_start + '&like=' + like;

        $.ajax({
            type: "POST",
            url: sUrl,
            data: postData,
            dataType: 'html',
            success: add_success,
            error: add_error
        });

        function add_success(msg)
        {
           // alert('VOTED');
        }

        function add_error(msg)
        {
            alert("fail");
        }
    });

    $('a#thumb_link').click(function()
    {
		var current_time = $('#currentTime').text();
		var media_id = $('span#media_id').text();
		current_time_ms = hhmmssms2ms(current_time);
		if (current_time == '')
		{
			alert('Result is empty');
			return false;
		}
		else
		{
			var link = '/thumb/' + media_id + '/' + current_time_ms;
			$(this).attr('href', link)
			return true;
		}

    });

	$('#currentTime').click(function()
    {
		var current_position_in_hhmmssms = $(this).text();
		var current_txt_description = $('#txt_description').val();

		if (current_txt_description != '')
		{
			current_txt_description = $.trim(current_txt_description);
			current_txt_description = current_txt_description.replace(/\|$/,'');
			current_txt_description = $.trim(current_txt_description);
			new_description = current_txt_description + " | " + current_position_in_hhmmssms + ' = ';
		}
		else
		{
			new_description =  current_position_in_hhmmssms + '=';
		}
		$('#txt_description').val(new_description);
    });

    /*
    $('#skip_time_remaining').click(function()
    {
        $('#media_edit').submit();
    });
    */

    function highlight_bookmarks(time_to_seek_hh_mm_ss)
    {
        var pattern = time_to_seek_hh_mm_ss + " = ([^\|,]*)";

        var description = $('#txt_description').val();
        var patt1 = new RegExp(pattern);
        var reg_result = patt1.exec(description);
        if (reg_result == null) return;
        var current_keyword = reg_result[1];
        current_keyword = $.trim(current_keyword);

        var bookmarks = description.split("|");
        var bookmark = '';

        var num_bookmakrs = bookmarks.length;

        for (var i = 0; i < num_bookmakrs; i++)
        {
            bookmark = bookmarks[i];
            var bookmark_parts = bookmark.split("=");
            var bookmark_time = $.trim(bookmark_parts[0]);
            var bookmark_keywords = $.trim(bookmark_parts[1]);

            $("a[alt='" + bookmark_time + "']").removeClass("current_keyword current_keyword_more");

            if (bookmark_keywords == current_keyword)
            {
                console.log(bookmark_keywords);

                $("a[alt='" + bookmark_time + "']").addClass("current_keyword_more");

                if (time_to_seek_hh_mm_ss == bookmark_time)
                {
                    $("a[alt='" + bookmark_time + "']").addClass("current_keyword");
                }
            }
        }

    }

	player.addEventListener ('MediaPlayerTimeChanged', mediaTimeUpdate, false);

    $('#volume_input').change(function(e)
    {
        media_volume = parseInt($(this).val());
        media_volume_set = false;
    });

    $('#edit_button').click(function() {
        $("#txt_description").prop('disabled', false);
        $("#txt_description").prop('visibility', "visible");
    });
}
);
