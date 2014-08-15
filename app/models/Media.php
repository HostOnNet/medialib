<?php

class Media extends Eloquent
{
    public $timestamps = false;

    public static function getViewAgain($views, $likes)
    {
        if ($views < 10) {
            $viewAfterDays = 10;
        } else if ($views < 20) {
            $viewAfterDays = 20;
        } else {
            if ($likes == 0) $likes = 1;
            $likesPerView = ($likes/$views) * 100;
            if ($likesPerView > 90) {
                $viewAfterDays = 6;
            } elseif ($likesPerView > 80) {
                $viewAfterDays = 15;
            } elseif ($likesPerView > 70) {
                $viewAfterDays = 20;
            } elseif ($likesPerView > 60) {
                $viewAfterDays = 30;
            } elseif ($likesPerView > 40) {
                $viewAfterDays = 50;
            } else {
                $viewAfterDays = 100;
            }
        }
        return time() + ($viewAfterDays * 86400);
    }
}
