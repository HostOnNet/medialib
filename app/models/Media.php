<?php

class Media extends Eloquent
{
    public $timestamps = false;

    public static function getViewAgain($views, $likes)
    {
        if ($views < 10) {
            $viewAfterDays = 2;
        } else if ($views < 20) {
            $viewAfterDays = 5;
        } else {
            if ($likes == 0) $likes = 1;
            $likesPerView = ($likes/$views) * 100;
            if ($likesPerView > 90) {
                $viewAfterDays = 2;
            } elseif ($likesPerView > 80) {
                $viewAfterDays = 5;
            } elseif ($likesPerView > 70) {
                $viewAfterDays = 7;
            } elseif ($likesPerView > 60) {
                $viewAfterDays = 12;
            } elseif ($likesPerView > 40) {
                $viewAfterDays = 20;
            } else {
                $viewAfterDays = 40;
            }
        }
        return time() + ($viewAfterDays * 86400);
    }
}
