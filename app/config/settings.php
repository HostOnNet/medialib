<?php

// used by config/app.php and config/database.php

$MEDIA_DIR_PRIVATE = "/mnt/tmp/dn/vid/";
$MEDIA_DIR_PUBLIC = '/home/boby/store/cbt/www/';
$ffmpeg_path = '/usr/bin/ffmpeg';

if (is_dir($MEDIA_DIR_PRIVATE)) {
    $video_folder = $MEDIA_DIR_PRIVATE;
    $thumb_folder = 'dn/thumb/';
    $dbName = 'xyl';
} else {
    $video_folder = $MEDIA_DIR_PUBLIC;
    $thumb_folder = '1/thumb/';
    $dbName = 'db2';
}
