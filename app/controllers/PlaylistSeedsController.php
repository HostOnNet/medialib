<?php

class PlaylistSeedsController extends BaseController {

    public function index()
    {
        $seeds = PlaylistSeed::all();
        $this->layout->title = 'Settings :: Todays';
        $this->layout->nest('content','playlistseeds.index', ['seeds' => $seeds] );
    }

    public function generate($seedId)
    {
        $playListSeed = PlaylistSeed::find($seedId);

        $todays = $playListSeed->seed;
        $timeNow = time();

        $playListName = 'todays';

        $playListId = Playlist::getId($playListName);

        if (! $playListId) {
            $playListId = Playlist::add($playListName);
        }

        if(! $playListId) {
            die('Playlist creation failed');
        }

        Playlist::emptyById($playListId);

        $playListSeedRows = explode("\n", $todays);

        foreach ($playListSeedRows as $playListSeedRow) {
            $playListSeedRow = trim($playListSeedRow);
            if (strpos($playListSeedRow,',') !== false) {
                $playListSeedRowParts = explode(',', $playListSeedRow);
                $tag = trim($playListSeedRowParts[0]);
                $num_medias = (int) $playListSeedRowParts[1];
                $tag_id = Tags::getId($tag);
                if ($tag_id) {
                    //$media_list = DB::select('SELECT * FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id AND MA.view_again < ? ORDER BY TM.likes DESC LIMIT ?', array($tag_id, $timeNow, $num_medias));
                    $media_list = DB::select('SELECT * FROM tag_media AS TM, medias AS MA WHERE TM.tag_id=? AND MA.id=TM.media_id ORDER BY TM.likes_per_tag DESC LIMIT ?', array($tag_id, $num_medias));
                    foreach ($media_list as $media) {
                        $time_start = Tags::getTagTime($media->description, $tag, $media->id);
                        Playlist::addToPlaylist($media->id, $playListId, $time_start);
                    }
                }
            }
        }

        $url = '/playlist/' . $playListId;
        return Redirect::to($url);

    }

} 
