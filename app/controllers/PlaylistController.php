<?php

class PlaylistController extends BaseController
{

	public function listPlaylist()
	{
        $playlists = DB::select('select  id,name, count(*) as total  from playlists
                                inner join playlist_media
                                ON playlists.id = playlist_media.pm_playlist_id
                                Group BY playlists.name ORDER BY name ASC');
        $seeds = PlaylistSeed::all();
		return View::make('playlist.index', array('playlists' => $playlists, 'seeds' => $seeds));
	}

    public function emptyPlaylist($playlist_id)
    {
        Playlist::emptyById($playlist_id);
        $url = '/playlists';
        return Redirect::to($url);
    }

    public function browse($playlist_id)
    {
        self::_setAutoTag($playlist_id);
        $medias = DB::select('SELECT * FROM `playlist_media` AS PM,
                            `medias` AS M WHERE
                            PM.pm_media_id=M.id AND
                            PM.pm_playlist_id=?
                            ORDER BY PM.pm_id ASC', [$playlist_id]);
        return View::make('playlist.browse', ['medias' => $medias, 'playlist_id' => $playlist_id]);
    }

    public function browse2()
    {
        $medias = DB::select('SELECT MS.*, MTT.id AS mtt_id from
                        `media_tag_time` AS MTT,
                        `medias` AS MS
                        WHERE MTT.media_id=MS.id AND MTT.likes > 4 ORDER BY MTT.likes DESC');
        return View::make('playlist.browse2', ['medias' => $medias]);
    }

	public function view($playlist_id)
	{
        self::_setAutoTag($playlist_id);
		$url = '/playlist/watch/' . $playlist_id;
        return Redirect::to($url);
	}

    private function _setAutoTag($playlist_id)
    {
        $playlist_name = Playlist::getName($playlist_id);
        $non_tag_playlists = array('search', 'best tags', 'best medias','todays');
        if (in_array($playlist_name, $non_tag_playlists)) {
            $playlist_name = '';
        }
        Settings::put('skip_to_bookmark', $playlist_name);
    }

	public function playlist_watch($playlist_id)
    {
        $time_past = time() - (5 * 60  * 60);
        $pm_id = '';

        if ($playlist_id == 0) {
            $media = DB::select('SELECT id as pm_media_id from medias WHERE
                            view_again > 0 AND view_time < ?
                            ORDER BY view_again ASC LIMIT 1', array($time_past));
        } else if ($playlist_id == 1) {
            $media = DB::select('SELECT id as pm_media_id from medias where
                        view_time < ?
                        ORDER BY likes DESC LIMIT 1', array($time_past));
        } else {
            $media = DB::select('SELECT pm_media_id, pm_id from playlist_media WHERE pm_playlist_id=? ORDER BY pm_id ASC LIMIT 1', array($playlist_id));

            if (isset($media[0]->pm_id))
            {
                $pm_id = 'x' . $media[0]->pm_id ;
                $my_media_id = $media[0]->pm_media_id;

                $my_media_info = Media::find($my_media_id);

                if (empty($my_media_info))
                {
                    DB::delete('delete from playlist_media where pm_media_id=?', array($my_media_id) );
                    return Redirect::to('/playlist/' . $playlist_id);
                }
            }
        }

        if (!empty($media))
        {
            $media_id = $media[0]->pm_media_id;
            $url = '/watch/' . $media_id . '/playlist-' . $playlist_id . $pm_id;
            return Redirect::to($url);
        }

		return View::make('playlist.nomedia', array('playlist_name' => Playlist::getName($playlist_id)));
	}

    public function make($playlist_id)
    {
        if ($playlist_id == 1)
        {
            // best tags (likes)
            $playlist_name = 'best tags';

            $playlist_id = Playlist::getId($playlist_name);

            if (!$playlist_id)
            {
                $playlist_id = Playlist::add($playlist_name);
            }

            if(!$playlist_id)
            {
                die('Playlist creation failed');
            }

            Playlist::emptyById($playlist_id);

            $media_list = DB::table('media_tag_time')
                ->orderBy('likes','DESC')
                ->where('likes','>',2)
                ->get();

            foreach ($media_list as $media)
            {
                Playlist::addToPlaylist($media->media_id, $playlist_id, $media->time_start);
            }

            $url = '/playlist/' . $playlist_id;
            return Redirect::to($url);
        }
        else if ($playlist_id == 2)
        {
            // best tags (likes)
            $playlist_name = 'best medias';

            $playlist_id = Playlist::getId($playlist_name);

            if (!$playlist_id)
            {
                $playlist_id = Playlist::add($playlist_name);
            }

            if(!$playlist_id)
            {
                die('Playlist creation failed');
            }

            Playlist::emptyById($playlist_id);

            $media_list = DB::table('medias')->where('likes','>',1)->orderBy('likes','DESC')->get();

            foreach ($media_list as $media)
            {
                $time_start = '00:00:00';

                $best_time = DB::table('media_tag_time')->where('media_id','=',$media->id)->orderBy('likes','DESC')->first(array('time_start'));

                if (!empty($best_time))
                {
                    $time_start = $best_time->time_start;
                }

                Playlist::addToPlaylist($media->id, $playlist_id, $time_start);
            }

            $url = '/playlist/' . $playlist_id;
            return Redirect::to($url);
        }
    }
}
