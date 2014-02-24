<?php

class Playlist
{
    public static function getId($playlist_name)
    {
        $playlist_info = DB::table('playlists')->where('name','=',$playlist_name)->first();
        if (!empty($playlist_info))
        {
            return $playlist_info->id;
        } else {
            return false;
        }
    }

    public static function getName($playlist_id)
    {
        $playlist_info = DB::table('playlists')->where('id','=',$playlist_id)->first();
        if (!empty($playlist_info))
        {
            return $playlist_info->name;
        } else {
            return false;
        }
    }

    public static function emptyById($playlist_id)
    {
        DB::table('playlist_media')->where('pm_playlist_id','=',$playlist_id)->delete();
    }

    public static function addToPlaylist($media_id, $playlist_id, $time_start = 0)
    {
        if ($time_start == 0)
        {
            $playlist = DB::select('select * from playlist_media where pm_media_id=? and pm_playlist_id=?',array($media_id, $playlist_id));
        }
        else
        {
            $playlist = DB::select('select * from playlist_media where pm_media_id=? and pm_playlist_id=? AND pm_time_start=?',array($media_id, $playlist_id, $time_start));
        }

        if (empty($playlist))
        {
            DB::table('playlist_media')->insert(array('pm_media_id' => $media_id, 'pm_playlist_id' => $playlist_id, 'pm_time_start' => $time_start));
        }
    }

    public static function deleteFromPlaylist($media_id, $playlist_id, $pm_id = 0)
    {
        if ($pm_id ==0)
        {
            $playlist = DB::delete('delete from playlist_media where pm_media_id=? and pm_playlist_id=?',array($media_id, $playlist_id));
        }
        else
        {
            $playlist = DB::delete('delete from playlist_media where pm_media_id=? AND pm_playlist_id=? AND pm_id=?',array($media_id, $playlist_id, $pm_id));
        }
    }

    public static function getTotalVideos($playlist_id)
    {
        if ($playlist_id == 0)
        {
            return DB::table('medias')->where('view_again','<',time())->count();
        }
        else if ($playlist_id == 1)
        {
            $time_past = time() - (5 * 60  * 60);
            return DB::table('medias')->where('view_time','<',$time_past)->where('likes', '>','0')->count();
        }
        else
        {
            return DB::table('playlist_media')->where('pm_playlist_id','=',$playlist_id)->count();
        }
    }

    public static function add($playlist_name)
    {
        DB::table('playlists')->insert(array('name' => $playlist_name ));
        return self::getId($playlist_name);
    }

    public static function getNumMedia($playlist_id)
    {
        $count =  DB::select('select count(*) as total from playlist_media where pm_playlist_id=?', $playlist_id);
        return $count[0]->total;
    }
}
