<?php

class AddController extends BaseController
{
    public function index()
    {
        $this->layout->title = "Add";
        $this->layout->nest('content','add.index');
    }

    public function save()
    {
        if (!isset($_POST['submit']))
        {
            die('NO submit');
        }

        $file_name = trim($_POST['file_name']);

        $file_path = Config::get('app.video_folder') . $file_name;

        if (!file_exists($file_path)) die("File not found $file_path");

        if (!is_file($file_path))
        {
            die('Not a valid file: ' . $file_path);
        }

        $msg = '';

        $time_start =  '00:00:00';
        $time_end = $this->find_media_duration($file_name);

        $description = "00:00:00=todo";

        $num_records = Media::where('file_name', '=', $file_name)->where('time_start_hms', '=', $time_start)->count();

        if ($num_records == 0)
        {
            $media_id = Media::insertGetId(array('file_name' => "$file_name", 'time_start_hms' => "$time_start", 'time_end_hms' => "$time_end", 'description' => "$description" ));
            Tags::add($description, $media_id);
            $msg .=  "<p>Added - $file_name ($time_start - $time_end)</p>";
        }
        else
        {
            $msg .= "<p>SKIP - $file_name ($time_start - $time_end)</p>";
        }

        Backup::db(1);

        $this->layout->title = "DONE";
        $this->layout->nest('content','add.done', array('msg' => $msg) );
    }

    function find_media_duration($file_name)
    {
        $file_path =  Config::get('app.video_folder') . $file_name;

        $cmd = Config::get('app.ffmpeg_path') . ' -i "' . $file_path . '"';

        @exec("$cmd 2>&1", $output);
        $output_all = implode("\n", $output);

        if (@preg_match('/Duration: ([0-9][0-9]:[0-9][0-9]:[0-9][0-9]).*, .*/', $output_all, $regs))
        {
            $duration = $regs[1];
        }
        else
        {
            dd($output_all);
            die('Failed to find media duration ' . $file_path);
        }

        return $duration;
    }

    function create_thumb($srcname, $new_image_file, $new_width, $new_height)
    {
        $imagedata = GetImageSize($srcname);
        $imagewidth = $imagedata[0];
        $imageheight = $imagedata[1];

        $src_img = imagecreatefromjpeg($srcname);
        $new_image = imageCreateTrueColor($new_width, $new_height);
        ImageCopyResampled($new_image, $src_img, 0, 0, 0, 0, $new_width, $new_height, $imagewidth, $imageheight);
        imagejpeg($new_image, $new_image_file, 100);
        imagedestroy($src_img);
        imagedestroy($new_image);

    }

    function thumb()
    {
        $media_list = Media::get();


        $msg = '';

        $video_folder = Config::get('app.video_folder');
        $thumb_folder = Config::get('app.thumb_folder');
        $base_folder = public_path();
        $num_thumb_created = 0;

        foreach($media_list as $media)
        {
            $media_id = $media->id;
            $media_file_name = $media->file_name;
            $time_start_sec = Time::hms2sec($media->time_start_hms);
            $time_end_sec = Time::hms2sec($media->time_end_hms);
            $time = rand($time_start_sec, $time_end_sec);

            if ($time < 1)
            {
                $msg .= 'Error processing video with id ' . $media_id . "\n";
                continue;
            }

            $file_path = $video_folder  . $media_file_name;

            if (!file_exists($file_path))
            {
                die('Video Not Found ' . $file_path);
            }

            $thumb_path = $base_folder . '/' . $thumb_folder . $media_id . '.jpg';
            $thumb_path_tmp = $base_folder . '/' . $thumb_folder . 'tmp_' . $media_id. '.jpg';
            $thumb_url =  '/' . $thumb_folder . '/' . $media_id . '.jpg';

            if (file_exists($thumb_path))
            {
                 continue;
            }

            $cmd = Config::get('app.ffmpeg_path') . "  -ss $time  -i \"$file_path\" -f image2 -frames:v 1 $thumb_path_tmp";
            $msg .= "$cmd\n";
            flush();
            exec($cmd);
            $num_thumb_created++;

            if (file_exists("$thumb_path_tmp"))
            {
                $this->create_thumb("$thumb_path_tmp", "$thumb_path", 384, 216);
                unlink("$thumb_path_tmp");
                $msg .= "<a href=/watch/$media_id/media-1><img src=$thumb_url></a> \n \n";
            }
            else
            {
                $msg .= "Failed to create thumbnail for $media_id\n";
            }

            if ($num_thumb_created > 12)
            {
                return "<meta http-equiv=\"refresh\" content=\"10\"><h1>Creating thumbnail in batch. Please wait....</h1>";
                exit;
            }
        }

        $this->layout->title = "Thumb Make";
        $this->layout->nest('content','add.thumb', array('msg' => $msg) );
    }

    public function thumb_single($media_id, $time)
    {
        $msg = '';

        $media = Media::find($media_id);
        $media_file_name = $media->file_name;
        $time = round ($time/1000);

        $video_folder = Config::get('app.video_folder');
        $thumb_folder = Config::get('app.thumb_folder');
        $base_folder = public_path();

        $file_path = $video_folder  . $media_file_name;

        if (!file_exists($file_path))
        {
            die('Video Not Found');
        }

        if (!is_dir($base_folder . '/' . $thumb_folder))
        {
            die('Thumb folder not found ');
        }

        $thumb_path = $base_folder . '/' . $thumb_folder . $media_id . '.jpg';
        $thumb_path_tmp = $base_folder . '/' . $thumb_folder . 'tmp_' . $media_id. '.jpg';
        $thumb_url =  '/' .  $thumb_folder . '/' . $media_id . '.jpg';

        $cmd = Config::get('app.ffmpeg_path') . "  -ss $time  -i \"$file_path\" -f image2 -frames:v 1 $thumb_path_tmp";
        $msg .= "$cmd\n";
        flush();
        exec($cmd);

        if (file_exists("$thumb_path_tmp"))
        {
            if (file_exists ($thumb_path))
            {
                if (!unlink($thumb_path))
                {
                    die("Failed to delete $thumb_path");
                }
            }

            $this->create_thumb("$thumb_path_tmp", "$thumb_path", 384, 216);
            unlink("$thumb_path_tmp");
            $msg .= "<img src=$thumb_url?time=" . time() . ">";
        }
        else
        {
            $msg .= "Failed to create thumbnail for $media_id\n";
        }

        $this->layout->title = "Thumb Make";
        $this->layout->nest('content','add.thumb', array('msg' => $msg) );
    }

    public function rebuild_media_likes()
    {
        $media_list = Media::get();

        foreach ($media_list as $media)
        {
            Medias::updateLikes($media->id);
        }

        return 'Finished';
    }

}