<?php

class SettingsController extends BaseController
{

    public function index()
    {
        $this->layout->title = 'Settings';
        $this->layout->nest('content','settings.index');
    }

    public function save()
    {
        $autoForwardDuration = (int) Input::get('autoForwardDuration');
        $skip_to_bookmark = Input::get('skip_to_bookmark');

        Settings::put('autoForwardDuration', $autoForwardDuration);
        Settings::put('skip_to_bookmark', $skip_to_bookmark);
        return Redirect::to('settings');
    }

    public function todays()
    {
        if (isset($_POST['submit']))
        {
            $todays = Input::get('todays');
            Settings::put('todays', $todays);
            return Redirect::to('/playlist_make/3');
        }
        else
        {
            $this->layout->title = 'Settings :: Todays';
            $this->layout->nest('content','settings.todays');
        }
    }

}