<?php

class ToolsController extends BaseController
{
    public function validate_thumb()
    {
        $thumb_folder =   public_path() . '//' . Config::get('app.thumb_folder');

        if (!is_dir($thumb_folder)) {
            die('Thumb folder not found - ' . $thumb_folder);
        }

        $dh = opendir($thumb_folder);

        $ignore_files = array('.','..');

        $message = '';

        while ($file = readdir($dh)) {
            if (! in_array($file, $ignore_files)) {
                if (preg_match('/(.*)\.jpg/', $file, $match_all)) {
                    $media_id = (int) $match_all[1];
                    $media = Media::find($media_id);
                    if (empty($media)) {
                        $message .= "$file DELETED<br />";
                        $file_path = $thumb_folder . $file;
                        if (is_file($file_path) && file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                }

            }
        }

        closedir($dh);

        if (empty($message)) {
            $message = 'Thumbs are ok!';
        }

        return View::make('tools.validate_thumb', array('message' => $message ));

    }

    public function validate_media_tag_time()
    {
        $media_tag_times = DB::table('media_tag_time')->get();
        $message =  '';

        foreach ($media_tag_times as $media_tag_time)
        {
            $media_id = $media_tag_time->media_id;
            $time = $media_tag_time->time_start;
            $media_info = Media::find($media_id);

            if (empty($media_info))
            {
                $message .= 'DELETE FROM media_tag_time WHERE media_id=' . $media_id . ';<br>';
            }
            else
            {
                $description = $media_info->description;


                if (strpos($description, $time) === false)
                {
                    $message .= 'DELETE FROM media_tag_time WHERE time_start= "' . $time . '" AND media_id = ' . $media_id . ';<br>';
                }
            }
        }

        $message  .= '<p>DONE</p>';

        return View::make('tools.validate_media_tag_time', array('message' => $message ));
    }

    public function join_medias_single()
    {
        return View::make('tools.join_medias_single');
    }

    public function join_medias_single_post()
    {
        $file_name = Input::get('file_name');
        $file_name = trim($file_name);

        $medias = Media::where('file_name','=',$file_name)->get();

        if (empty($medias)) {
            return "No Media found";
        }

        if (count($medias) == 1) {
            return "ALREADY SINGLE";
        }

        $time_min = 0;
        $time_max = 0;
        $description_new = '';
        $media_id_main = 0;

        echo "<textarea cols=100 rows=40>";

        foreach ($medias as $media) {
            $time_start_hms = $media->time_start_hms;
            $time_end_hms = $media->time_end_hms;
            $description = $media->description;
            $media_id = $media->id;

            if (empty($description_new)) {
                $description_new = $description;
            } else {
                $description_new = $description_new . ' | ' . $description;
            }
            $time_start = Time::hms2sec($time_start_hms);
            $time_end = Time::hms2sec($time_end_hms);

            if ($media_id_main == 0) {
                $media_id_main = $media_id;
            }

            if ($time_min == 0) {
                $time_min = $time_start;
            }

            if ($time_max == 0) {
                $time_max = $time_end;
            }

            if ($time_min > $time_start) {
                $time_min = $time_start;
            }

            if ($time_max < $time_end) {
                $time_max = $time_end;
            }

            if ($media_id_main != $media_id) {
                $sql = 'DELETE FROM medias where id=' . $media_id . ';';
                echo "$sql\n";
                $sql = 'UPDATE media_tag_time SET media_id = ' . $media_id_main . ' where media_id=' . $media_id . ';';
                echo "$sql\n";
            }

        }

        $time_min_hms = Time::sec2hms($time_min);
        $time_max_hms = Time::sec2hms($time_max);

        $sql = "UPDATE medias set time_start_hms='$time_min_hms', time_end_hms='$time_max_hms' where id=$media_id_main;";

        echo "$sql\n";

        echo "\n";

        echo $description_new;

        echo "</textarea>";

        $len_max =  2939;
        $len = strlen($description_new);

        echo "<p>Length = " . $len . "</p>";

        if ($len > $len_max) {
            echo '<h1>Too BIG</h1>';
        }


        return "<p>DONE</p>";
    }

}
