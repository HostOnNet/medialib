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
        $auto_forward_duration = (int) Input::get('auto_forward_duration');
        $skip_to_bookmark = Input::get('skip_to_bookmark');
        Settings::put('auto_forward_duration', $auto_forward_duration);
        Settings::put('skip_to_bookmark', $skip_to_bookmark);
        Settings::put('auto_forward', Input::get('auto_forward'));
        return Redirect::to('settings');
    }

}
