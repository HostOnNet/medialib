<?php

class PlaylistSeedsController extends BaseController {

    public function index()
    {
        $seeds = PlaylistSeeds::all();
        $this->layout->title = 'Settings :: Todays';
        $this->layout->nest('content','playlistseeds.index', ['seeds' => $seeds] );
    }

    public function generate()
    {
        if (isset($_POST['submit']))
        {
            $todays = Input::get('todays');
            return Redirect::to('/playlist_make/3');
        }

    }

} 
