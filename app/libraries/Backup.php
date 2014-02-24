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

		$backup_folder = base_path() . '//1//db//';

        if (!is_dir($backup_folder)) {
            die("Backup folder not found " . $backup_folder);
        }

		$time =  time();
        $backup_file = $backup_folder . $time . '.sql';
		$backup_cmd = "G:\\Programs\\mysql_server\\bin\\mysqldump.exe -u root -pflashwebhost playlist > $backup_file";
		system($backup_cmd);

        if (filesize($backup_file) < 500) {
            die("Backkup file size too small " . $backup_cmd);
        }

		$old_time = $time - (14 * 24 * 60 * 60); // 14 days ago

		$files = scandir($backup_folder);

		foreach($files as $file)
		{
			if (is_file($backup_folder .$file))
			{
				$file_time =  preg_replace("/\.sql/", '', $file);

				if ($file_time < $old_time)
				{
					unlink($backup_folder .$file);
				}

			}
		}
	}

}