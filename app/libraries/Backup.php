<?php

class Backup
{
    public static function db($force = 0)
    {
        if ($force == 0) {
            $mysql_backup = Cookie::get('mysql_backup');
            if (!empty($mysql_backup)) return;
        }

        Cookie::queue('mysql_backup', '1', 30); /* expire in 30 minutes */

        $time =  time();
        $backup_folder = base_path() . DIRECTORY_SEPARATOR . '1' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR;
        $backup_file = $backup_folder . $time . '.sql';

        if (!is_dir($backup_folder)) {
            die("Backup folder not found " . $backup_folder);
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $backup_cmd = "\"C:\\Program Files\\mysql\\bin\\mysqldump.exe\" -u root -pflashwebhost xyl > $backup_file";
        } else {
            $backup_cmd = "/usr/bin/mysqldump -u root -pflashwebhost xyl > $backup_file";
        }

        system($backup_cmd);

        if (filesize($backup_file) < 500) {
            die("Backkup file size too small " . $backup_cmd);
        }

        $old_time = $time - (14 * 24 * 60 * 60); // 14 days ago

        $files = scandir($backup_folder);

        foreach($files as $file) {
            if (is_file($backup_folder .$file)) {
                $file_time =  preg_replace("/\.sql/", '', $file);
                if ($file_time < $old_time) {
                    unlink($backup_folder .$file);
                }
            }
        }
    }
}
