<?php

class Medias
{
	public static function savePlaylist($media)
	{
		$video_folder = Config::get('app.video_folder');
		$time_start_sec = Time::hms2sec($media->time_start_hms);
		$time_end_sec = Time::hms2sec($media->time_end_hms);


		$file_path = $video_folder . $media->file_name;

		$playlist = "#EXTM3U\n#EXTVLCOPT:start-time=$time_start_sec\n#EXTVLCOPT:stop-time=$time_end_sec\n$file_path";

		if (is_dir($video_folder))
		{
			$playlist_file = $video_folder . "../playlists/live.m3u" ;
			$fp = fopen($playlist_file,'w');
			fwrite($fp,$playlist);
			fclose($fp);
		}
		else
		{
			die("Playlist folder not found");
		}
	}

    public static function updateLikes($media_id)
    {
        $r = DB::query('select SUM(likes) as total_likes FROM `media_tag_time` where media_id=?', $media_id);

        $likes = isset($r[0]->total_likes) ? $r[0]->total_likes : 0;

        echo 'Updating likes for media ' . $media_id . ' likes = ' . $likes . '<br>';

        Media::where('id', '=', $media_id)->update(
            array
            (
                'likes' => $likes,
            )
        );

    }
}