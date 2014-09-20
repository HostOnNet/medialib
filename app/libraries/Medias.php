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
			$playlist_file = $video_folder . '/tmp/live.m3u';
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
        $r = DB::select('select SUM(likes) as total_likes FROM `media_tag_time` where media_id=?', array($media_id));

        $likes = isset($r[0]->total_likes) ? $r[0]->total_likes : 0;

        echo 'Updating likes for media ' . $media_id . ' likes = ' . $likes . '<br>';

        Media::where('id', '=', $media_id)->update(
            array
            (
                'likes' => $likes,
            )
        );

    }

    public static function isAutoPlay($media, $ref_page) {
        if ($ref_page == 'playlist-0') return false;
        $is_playlist = (strpos($ref_page, 'playlist') !== false);
        $is_tag = (strpos($ref_page, 'tag') !== false);
        if ($is_playlist || $is_tag) {
            // This media need to be auto played, find where to start playing.
            $skip_to_bookmark = Settings::get('skip_to_bookmark');
            if (strlen($skip_to_bookmark) > 2)  {
                if ($skip_to_bookmark == 'auto') {
                    $media_tag_time = DB::table('media_tag_time')->where('media_id','=', $media->id)->first();
                    $time_start = $media_tag_time->time_start;
                } else {
                    $time_start = Tags::getTagTime($media->description, $skip_to_bookmark, $media->id);
                }
            } else if (strpos($ref_page, 'x')) {
                $ref_page_parts = explode('x',$ref_page);
                $pm_id = $ref_page_parts[1];
                $media_tag_time_record = DB::table('playlist_media')->where('pm_id','=',$pm_id)->first();
                $time_start = $media_tag_time_record->pm_time_start;
            }
            return $time_start;
        } else {
            return false;
        }
    }
}
