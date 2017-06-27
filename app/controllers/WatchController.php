<?php

class WatchController extends BaseController
{
    public function show()
    {
        $media_id = Route::input('media_id');
        $ref_page = Route::input('ref_page');

        if (strpos($ref_page, 'tag-') !== false)
        {
            $ref_parts = explode('-', $ref_page);
            $tag_id = $ref_parts[1];
            Settings::put('skip_to_bookmark', Tags::getTagById($tag_id));
        }

		$media = Media::find($media_id);
		Medias::savePlaylist($media);
        $videos_in_playlist = '';

        if (strpos($ref_page, 'playlist-') !== false)
        {
            $playlist_id = str_replace('playlist-', '', $ref_page);
            $videos_in_playlist = Playlist::getTotalVideos($playlist_id);
        }

        $last_viewed = $this->time_range($media->view_time);
		$data = array('media' => $media, 'ref_page' => $ref_page, 'last_viewed' => $last_viewed, 'videos_in_playlist' => $videos_in_playlist);
		return View::make('watch.show', $data);
        //return View::make('watch.show', $data);
    }

    function time_range($view_time)
    {
        $range = '';
        $interval = $_SERVER['REQUEST_TIME'] - $view_time;

        if ($interval > 0)
        {
            $day = (int) $interval / (60 * 60 * 24);

            if ($day >= 2)
            {
                $range = floor($day) . ' days ';
                $interval = $interval - (60 * 60 * 24 * floor($day));
            }

            if ($interval > 0 && $range == '')
            {
                $hour = $interval / (60 * 60);
                if ($hour >= 1)
                {
                    $range = floor($hour) . ' hours ';
                    $interval = $interval - (60 * 60 * floor($hour));
                }
            }
            if ($interval > 0 && $range == '')
            {
                $min = $interval / (60);
                if ($min >= 1)
                {
                    $range = floor($min) . ' minutes ';
                    $interval = $interval - (60 * floor($min));
                }
            }
            if ($interval > 0 && $range == '')
            {
                $scn = $interval;
                if ($scn >= 1)
                {
                    $range = $scn . ' seconds ';
                }
            }
            if ($range != '')
            {
                $range = $range . ' ago';
            }
            else
            {
                $range = 'just now';
            }
            return $range;
        }
        else
        {
            return '1 seconds ago';
        }

    }


}
