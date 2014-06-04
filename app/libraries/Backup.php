<?php

class Backup
{
	public static function db($force = 0)
	{
		if ($force == 0)
		{
			$mysql_backup = Cookie::get('mysql_backup');
			if (!empty($mysql_backup)) return;
		}

		Cookie::queue('mysql_backup', '1', 30); /* expire in 30 minutes */

        $time =  time();
        $db_file = public_path() . DIRECTORY_SEPARATOR . 'dn' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'xyl';

        if (!file_exists($db_file)) {
            die('Database file not found ' . $db_file);
        }

        $backup_folder = public_path() . DIRECTORY_SEPARATOR . 'dn' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'bk' . DIRECTORY_SEPARATOR ;

        $db_file_backup = $backup_folder . time() . '.bak';

        if (!is_dir($backup_folder)) {
            die("Backup folder not found " . $backup_folder);
        }

		copy($db_file, $db_file_backup);

        if (filesize($db_file_backup) != filesize($db_file)) {
            echo '<p>Backkup file size too small</p>';
            echo '<p>Backup file size = ' . filesize($db_file_backup) . '</p>';
            echo '<p>Original file size = ' . filesize($db_file) . '</p>';
            exit;
        }

        /*

        $old_time = $time - (14 * 24 * 60 * 60); // 14 days ago

		$files = scandir($backup_folder);

		foreach($files as $file) {
			if (is_file($backup_folder .$file)) {
				$file_time =  preg_replace("/\.bak/", '', $file);
				if ($file_time < $old_time) {
					unlink($backup_folder . $file);
				}
			}
		}*/

	}
}
